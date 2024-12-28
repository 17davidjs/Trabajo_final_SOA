<?php
header('Content-Type: application/json');

// Configuración de conexión a la base de datos
$servidor = "localhost"; // Cambia según tu configuración
$user = "root"; // Cambia según tu configuración
$pass = ""; // Cambia según tu configuración
$bd = "soa_final"; // Cambia según tu configuración

session_start();
//conexion a la base de datos
$conn = new mysqli($servidor, $user, $pass, $bd);

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500); // Respuesta 500 Internal Server Error
    echo json_encode(array("response" => 500, "texto" => "Error de conexión a la base de datos: " . $conn->connect_error));
    exit;
}

// Función para generar un token aleatorio
function generarToken() {
    return bin2hex(random_bytes(16)); // Token aleatorio
}

// Función para almacenar el token en la base de datos
function guardarToken($conn, $usuario, $token) {
    $sql = "UPDATE usuarios SET token = ? WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $usuario);
    $stmt->execute();
    $stmt->close();
}

function verificarToken($conn, $token){
    // Buscar el token en la base de datos y devuelve el nombre de usuario asociado
    $sql = "SELECT usuario FROM usuarios WHERE token = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error en la preparación de la consulta: " . $conn->error));
        exit;
    }

    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        http_response_code(401);
        echo json_encode(array("response" => 401, "texto" => "Token no válido"));
        exit;
    }

    $usuario = '';
    $stmt->bind_result($usuario);
    $stmt->fetch();
    $stmt->close();

    return $usuario; // Retorna el nombre de usuario si el token es válido
}


$inputvacio = file_get_contents('php://input');
$datos = json_decode($inputvacio, true);

// Verificar si se especificó una función
if (!isset($datos["funcion"])) {
    http_response_code(400);
    echo json_encode(array("response" => 400, "texto" => "No se especificó una función a ejecutar"));
    exit;
}

// Seleccionar la función a ejecutar
switch ($datos["funcion"]) {
    case "registro":
        registro($conn, $datos);
        break;
    case "login":
        login($conn, $datos);
        break;
    case "eliminarUser":
        eliminarUser($conn, $datos);
    case "subirFichero":
        subir($conn, $datos);
        break;
    default:
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Función no válida"));
        break;
}


function registro($conn,$datos){
    //datos de cliente php
    
    // Verificar si la decodificación fue exitosa y si se recibieron las claves esperadas
    if (json_last_error() !== JSON_ERROR_NONE || !isset($datos["nombre"]) || !isset($datos["apellidos"]) || !isset($datos["fecha_nacimiento"]) || !isset($datos["direccion"]) || !isset($datos["correo_electronico"]) || !isset($datos["telefono"]) || !isset($datos["usuario"]) || !isset($datos["contraseña"]) || !isset($datos["contraseña2"])) {
        http_response_code(400); // Respuesta 400 Bad Request
        echo json_encode(array("response" => 400, "texto" => "Datos no validos"));
        exit;
    }

    // Verificar si las contraseñas coinciden
    if ($datos["contraseña"] !== $datos["contraseña2"]) {
        http_response_code(400); // Respuesta 400 Bad Request
        echo json_encode(array("response" => 400, "texto" => "Las contraseñas no coinciden"));
        exit;
    }

    if (strlen($datos["contraseña"]) < 8) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "La contraseña debe tener al menos 8 caracteres"));
        exit;
    }

    if (!filter_var($datos["correo_electronico"], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Correo electrónico no válido"));
        exit;
    }

    $contraseña_hash = password_hash($datos["contraseña"], PASSWORD_DEFAULT);



    // Preparar la consulta SQL para insertar datos
    $sql = "INSERT INTO usuarios (nombre, apellidos, fecha_nacimiento, direccion, correo_electronico, telefono, usuario, contraseña) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Verificar si la preparación fue exitosa
    if ($stmt === false) {
        http_response_code(500); // Respuesta 500 Internal Server Error
        echo json_encode(array("response" => 500, "texto" => "Error en la preparación de la consulta: " . $conn->error));
        exit;
    }

    // Vincular parámetros
    $stmt->bind_param("ssssssss", $datos["nombre"], $datos["apellidos"], $datos["fecha_nacimiento"], $datos["direccion"], $datos["correo_electronico"], $datos["telefono"], $datos["usuario"], $contraseña_hash);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Se envía un OK 200
        http_response_code(200); // Respuesta 200 OK
        echo json_encode(array("response" => 200, "texto" => "Datos insertados correctamente"));
    } else {
        // Se envía un KO 500 Internal Server Error
        http_response_code(500); // Respuesta 500 Internal Server Error
        error_log("Error al insertar datos: " . $stmt->error);
        echo json_encode(array("response" => 500, "texto" => "Error interno del servidor"));
    }

    // Cerrar la declaración 
    $stmt->close();
}

function login($conn, $datos){
    if(!isset($datos["usuario"]) || !isset($datos["contraseña"])){
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Datos no válidos"));
        exit;
    }

    $usuario = $datos["usuario"];
    $contraseña = $datos["contraseña"];

    $sql = "SELECT usuario, contraseña FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error en la preparación de la consulta: " . $conn->error));
        exit;
    }

    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        http_response_code(401);
        echo json_encode(array("response" => 401, "texto" => "Usuario no encontrado"));
        exit;
    }
    $contraseña_hash = '';
    $stmt->bind_result($usuario, $contraseña_hash); // Se obtiene la contraseña hasheada del usuario
    $stmt->fetch();
    $stmt->close();

    //verificamos si la contraseña es correcta
    if (!password_verify($contraseña, $contraseña_hash)) {
        http_response_code(401); // Respuesta 401 Unauthorized
        echo json_encode(array("response" => 401, "texto" => "Usuario o contraseña incorrectos."));
        exit;
    }

    if(isset($_SESSION["token"])){
        $token = $_SESSION["token"];
    }else{
        $token=generarToken();   
    }
    guardarToken($conn, $usuario, $token);
    http_response_code(200);
    echo json_encode(array("response" => 200, "texto" => "Login exitoso", "token" => $token));

}

function eliminarUser($conn, $datos){
    // Verificar si los datos requeridos están presentes
    if (!isset($datos["token"]) || !isset($datos["usuario"])) {
        http_response_code(400); // Bad Request
        echo json_encode(array("response" => 400, "texto" => "Datos no válidos para eliminar usuario"));
        exit;
    }

    $token = $datos["token"];
    $usuario = $datos["usuario"];

    // Verificar si el token es válido y obtener el nombre de usuario asociado
    $usuarioValidado = verificarToken($conn, $token);

    // Si el token no es válido o el usuario no coincide
    if ($usuarioValidado !== $usuario) {
        http_response_code(401); // Unauthorized
        echo json_encode(array("response" => 401, "texto" => "Token no válido o no coincide con el usuario"));
        exit;
    }

    // Proceder a eliminar el usuario de la base de datos
    $sql = "DELETE FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        http_response_code(500); // error interno 
        echo json_encode(array("response" => 500, "texto" => "Error al preparar la consulta: " . $conn->error));
        exit;
    }

    // Vincular parámetros y ejecutar la consulta
    $stmt->bind_param("s", $usuario);

    // Ejecutar la consulta y verificar si se eliminó correctamente
    if ($stmt->execute()) {
        // Si la eliminación fue exitosa
        http_response_code(200); // OK
        echo json_encode(array("response" => 200, "texto" => "Usuario eliminado correctamente"));
        echo "Ir al registro";
        echo"<a href='../registro/registro.php'>Registro</a>";
    } else {
        // Si hubo un error al eliminar el usuario
        http_response_code(500); // Internal Server Error
        echo json_encode(array("response" => 500, "texto" => "Error al eliminar el usuario: " . $stmt->error));
    }

    // Cerrar la declaración de la consulta
    $stmt->close();
}

function subir($conn, $datos) {
    // Verificar si los datos requeridos están presentes
    if (!isset($datos["nombreArchivo"]) || !isset($datos["tipoArchivo"]) || !isset($datos["fichero"]) || !isset($datos["token"]) || !isset($datos["usuario"])) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Datos no válidos para subir el fichero"));
        exit;
    }

    $nombreArchivo = $datos["nombreArchivo"];
    $tipoArchivo = $datos["tipoArchivo"];
    $contenidoArchivo = base64_decode($datos["fichero"]); // Decodificar el contenido base64
    $token = $datos["token"];
    $usuario = $datos["usuario"];

    // Verificar el token
    $usuarioValidado = verificarToken($conn, $token);

    if ($usuarioValidado !== $usuario) {
        http_response_code(401);
        echo json_encode(array("response" => 401, "texto" => "Token no válido o no coincide con el usuario"));
        exit;
    }

    // Guardar el archivo temporalmente para leerlo
    $rutaTemporal = sys_get_temp_dir() . "/" . uniqid() . "_" . $nombreArchivo;
    file_put_contents($rutaTemporal, $contenidoArchivo);

    // Leer el contenido del archivo
    $contenido = file_get_contents($rutaTemporal);
    if ($contenido === false) {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error al leer el contenido del archivo"));
        unlink($rutaTemporal); // Eliminar el archivo temporal
        exit;
    }

    // Procesar el contenido del archivo según el tipo (CSV, XML, JSON, etc.)
    switch ($tipoArchivo) {
        case "text/csv":
            procesarCSV($conn, $contenido, $usuarioValidado);
            break;
        case "application/json":
            procesarJSON($conn, $contenido, $usuarioValidado);
            break;
        case "application/xml":
        case "text/xml":
            procesarXML($conn, $contenido, $usuarioValidado);
            break;
        default:
            http_response_code(400);
            echo json_encode(array("response" => 400, "texto" => "Tipo de archivo no soportado"));
            unlink($rutaTemporal); // Eliminar el archivo temporal
            exit;
    }

    // Eliminar el archivo temporal
    unlink($rutaTemporal);

    http_response_code(200);
    echo json_encode(array("response" => 200, "texto" => "Currículum procesado y almacenado correctamente"));
}

function procesarCSV($conn, $contenido, $usuario) {
    $lineas = explode("\n", $contenido);
    $cabeceras = str_getcsv(array_shift($lineas)); // Extraer las cabeceras del archivo

    foreach ($lineas as $linea) {
        $datos = str_getcsv($linea);

        // Mapear los datos al esquema de la tabla curriculums
        $datosInsertar = array_combine($cabeceras, $datos);
        guardarEnBaseDatos($conn, $usuario, $datosInsertar);
    }
}

function procesarJSON($conn, $contenido, $usuario) {
    $datos = json_decode($contenido, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "JSON no válido"));
        exit;
    }

    guardarEnBaseDatos($conn, $usuario, $datos);
}

function procesarXML($conn, $contenido, $usuario) {
    $xml = simplexml_load_string($contenido);
    if ($xml === false) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "XML no válido"));
        exit;
    }

    $datos = json_decode(json_encode($xml), true); // Convertir XML a array
    guardarEnBaseDatos($conn, $usuario, $datos);
}

function guardarEnBaseDatos($conn, $usuario, $datos) {
    $usuario_id= "";
    // Obtener el ID del usuario
    $sqlUsuario = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sqlUsuario);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($usuario_id);
    $stmt->fetch();
    $stmt->close();

    if (!$usuario_id) {
        http_response_code(404);
        echo json_encode(array("response" => 404, "texto" => "Usuario no encontrado"));
        exit;
    }

    // Insertar los datos en la tabla curriculums
    $sql = "INSERT INTO curriculums (usuario_id, nombre, apellidos, fecha_nacimiento, direccion, correo_electronico, telefono, fecha_curriculum, formacion_academica, experiencia_laboral, idiomas, habilidades, datos_interes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,? , ?, ?)";

    $stmt = $conn->prepare($sql);
    $fecha= date("Y-m-d");
    $formacion =json_encode($datos["formacion_academica"]); 
    $experiencia_laboral = json_encode($datos["experiencia_laboral"]);
    $idiomas = json_encode($datos["idiomas"]);
    $habilidades = json_encode($datos["habilidades"]);
    $datos_interes = json_encode($datos["datos_interes"]);
    $stmt->bind_param(
        "issssssssssss",
        $usuario_id,
        $datos["nombre"],
        $datos["apellidos"],
        $datos["fecha_nacimiento"],
        $datos["direccion"],
        $datos["correo_electronico"],
        $datos["telefono"],
        $fecha,
        $formacion,
        $experiencia_laboral,
        $idiomas,
        $habilidades,
        $datos_interes
    );

    if ($stmt->execute()) {
        http_response_code(200); // OK
        echo json_encode(array("response" => 200, "texto" => "CV guardado correctamente"));
    } else {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error al guardar en la base de datos: " . $stmt->error));
        exit;
    }

    $stmt->close();
}


$conn->close();
?>

