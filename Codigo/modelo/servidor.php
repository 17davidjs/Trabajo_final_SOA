<?php
header('Content-Type: application/json');

require_once 'db.php';

session_start();


// Función para generar un token aleatorio
function generarToken() {
    return bin2hex(random_bytes(16)); // Token aleatorio
}

// Función para almacenar el token en la base de datos
function guardarToken($usuario, $token) {
    global $conn;
    $sql = "UPDATE usuarios SET token = ? WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $usuario);
    $stmt->execute();
    $stmt->close();
}

function verificarToken($token) {
    global $conn;
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
        registro($datos);
        break;
    case "login":
        login($datos);
        break;
    case "eliminarUser":
        eliminarUser($datos);
        break;
    case "subirFichero":
        subir($datos);
        break;
    case "getAllCurriculums":
        getAllCurriculums();
        break;
    case "addCurriculum":
        addCurriculum($datos);
        break;
    case "updateCurriculum":
        updateCurriculum($datos);
        break;
    case "deleteCurriculum":
        deleteCurriculum($datos);
        break;
    default:
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Función no válida"));
        break;
}

/****************************************************************************************************/
// Funciones para la gestión de currículums
/****************************************************************************************************/
function getAllCurriculums() {
    global $conn;
    $sql = "SELECT * FROM curriculums";
    $result = $conn->query($sql);

    $curriculums = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $curriculums[] = $row;
        }
    }

    echo json_encode($curriculums);
}

function addCurriculum($data) {
    global $conn;
    $sql = "INSERT INTO curriculums (usuario_id, nombre, apellidos, fecha_nacimiento, direccion, correo_electronico, telefono, fecha_curriculum) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $data['usuario_id'], $data['nombre'], $data['apellidos'], $data['fecha_nacimiento'], $data['direccion'], $data['correo_electronico'], $data['telefono']);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("response" => 201, "texto" => "Currículum agregado correctamente"));
    } else {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error al agregar currículum: " . $stmt->error));
    }

    $stmt->close();
}

function updateCurriculum($data) {
    global $conn;
    $sql = "UPDATE curriculums SET nombre = ?, apellidos = ?, fecha_nacimiento = ?, direccion = ?, correo_electronico = ?, telefono = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $data['nombre'], $data['apellidos'], $data['fecha_nacimiento'], $data['direccion'], $data['correo_electronico'], $data['telefono'], $data['id']);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("response" => 200, "texto" => "Currículum actualizado correctamente"));
    } else {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error al actualizar currículum: " . $stmt->error));
    }

    $stmt->close();
}

function deleteCurriculum($data) {
    global $conn;
    $sql = "DELETE FROM curriculums WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $data['id']);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("response" => 200, "texto" => "Currículum eliminado correctamente"));
    } else {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error al eliminar currículum: " . $stmt->error));
    }

    $stmt->close();
}
/****************************************************************************************************/


/****************************************************************************************************/
// Funciones para la autenticación de usuarios
/****************************************************************************************************/
function registro($datos) {
    global $conn;
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Error en la decodificación del JSON: " . json_last_error_msg()));
        exit;
    }

    $required_fields = ["nombre", "apellidos", "fecha_nacimiento", "direccion", "correo_electronico", "telefono", "usuario", "contrasena"];
    foreach ($required_fields as $field) {
        if (!isset($datos[$field])) {
            http_response_code(400);
            echo json_encode(array("response" => 400, "texto" => "Campo requerido faltante: " . $field));
            exit;
        }
    }

    if (strlen($datos["contrasena"]) < 8) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "La contraseña debe tener al menos 8 caracteres"));
        exit;
    }

    if (!filter_var($datos["correo_electronico"], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Correo electrónico no válido"));
        exit;
    }

    $contrasena_hash = password_hash($datos["contrasena"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, apellidos, fecha_nacimiento, direccion, correo_electronico, telefono, usuario, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error en la preparación de la consulta: " . $conn->error));
        exit;
    }

    $stmt->bind_param("ssssssss", $datos["nombre"], $datos["apellidos"], $datos["fecha_nacimiento"], $datos["direccion"], $datos["correo_electronico"], $datos["telefono"], $datos["usuario"], $contrasena_hash);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("response" => 200, "texto" => "Datos insertados correctamente"));
    } else {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error interno del servidor: " . $stmt->error));
    }

    $stmt->close();
}

function login($datos) {
    global $conn;
    if (!isset($datos["usuario"]) || !isset($datos["contrasena"])) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Datos no válidos"));
        exit;
    }

    $usuario = $datos["usuario"];
    $contrasena = $datos["contrasena"];

    $sql = "SELECT usuario, contrasena, role FROM usuarios WHERE usuario = ?";
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

    $contrasena_hash = '';
    $role = '';
    $stmt->bind_result($usuario, $contrasena_hash, $role);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($contrasena, $contrasena_hash)) {
        http_response_code(401);
        echo json_encode(array("response" => 401, "texto" => "Usuario o contraseña incorrectos."));
        exit;
    }

    $token = generarToken();
    guardarToken($usuario, $token);

    http_response_code(200);
    echo json_encode(array("response" => 200, "texto" => "Login exitoso", "token" => $token, "role" => $role)); // Aquí se incluye el role
}


function eliminarUser($datos) {
    global $conn;
    if (!isset($datos["token"]) || !isset($datos["usuario"])) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Datos no válidos para eliminar usuario"));
        exit;
    }

    $token = $datos["token"];
    $usuario = $datos["usuario"];

    $usuarioValidado = verificarToken($token);

    if ($usuarioValidado !== $usuario) {
        http_response_code(401);
        echo json_encode(array("response" => 401, "texto" => "Token no válido o no coincide con el usuario"));
        exit;
    }

    $sql = "DELETE FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error al preparar la consulta: " . $conn->error));
        exit;
    }

    $stmt->bind_param("s", $usuario);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("response" => 200, "texto" => "Usuario eliminado correctamente"));
    } else {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error al eliminar el usuario: " . $stmt->error));
    }

    $stmt->close();
}
/****************************************************************************************************/


/// Funciones para subir currículums
function subir($datos) {
    global $conn;
    if (!isset($datos["nombreArchivo"]) || !isset($datos["tipoArchivo"]) || !isset($datos["fichero"]) || !isset($datos["token"]) || !isset($datos["usuario"])) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Datos no válidos para subir el fichero"));
        exit;
    }

    $nombreArchivo = $datos["nombreArchivo"];
    $tipoArchivo = $datos["tipoArchivo"];
    $contenidoArchivo = base64_decode($datos["fichero"]);
    $token = $datos["token"];
    $usuario = $datos["usuario"];

    $usuarioValidado = verificarToken($token);

    if ($usuarioValidado !== $usuario) {
        http_response_code(401);
        echo json_encode(array("response" => 401, "texto" => "Token no válido o no coincide con el usuario"));
        exit;
    }

    $rutaTemporal = sys_get_temp_dir() . "/" . uniqid() . "_" . $nombreArchivo;
    file_put_contents($rutaTemporal, $contenidoArchivo);

    $contenido = file_get_contents($rutaTemporal);
    if ($contenido === false) {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error al leer el contenido del archivo"));
        unlink($rutaTemporal);
        exit;
    }

    switch ($tipoArchivo) {
        case "text/csv":
            procesarCSV($contenido, $usuarioValidado);
            break;
        case "application/json":
            procesarJSON($contenido, $usuarioValidado);
            break;
        case "application/xml":
        case "text/xml":
            procesarXML($contenido, $usuarioValidado);
            break;
        default:
            http_response_code(400);
            echo json_encode(array("response" => 400, "texto" => "Tipo de archivo no soportado"));
            unlink($rutaTemporal);
            exit;
    }

    unlink($rutaTemporal);

    http_response_code(200);
    echo json_encode(array("response" => 200, "texto" => "Currículum procesado y almacenado correctamente"));
}

// Función para procesar un archivo CSV
function procesarCSV($contenido, $usuario) {
    global $conn;
    $lineas = explode("\n", $contenido);
    $cabeceras = str_getcsv(array_shift($lineas));

    foreach ($lineas as $linea) {
        $datos = str_getcsv($linea);
        $datosInsertar = array_combine($cabeceras, $datos);
        guardarEnBaseDatos($datosInsertar, $usuario);
    }
}

// Función para procesar un archivo JSON
function procesarJSON($contenido, $usuario) {
    global $conn;
    $datos = json_decode($contenido, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "JSON no válido"));
        exit;
    }

    guardarEnBaseDatos($datos, $usuario);
}

// Función para procesar un archivo XML
function procesarXML($contenido, $usuario) {
    global $conn;
    $xml = simplexml_load_string($contenido);
    if ($xml === false) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "XML no válido"));
        exit;
    }

    $datos = json_decode(json_encode($xml), true);
    guardarEnBaseDatos($datos, $usuario);
}

// Función para guardar los datos en la base de datos
function guardarEnBaseDatos($datos, $usuario) {
    global $conn;
    $usuario_id = "";
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

    $sql = "INSERT INTO curriculums (usuario_id, nombre, apellidos, fecha_nacimiento, direccion, correo_electronico, telefono, fecha_curriculum, formacion_academica, experiencia_laboral, idiomas, habilidades, datos_interes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $fecha = date("Y-m-d");
    $formacion = json_encode($datos["formacion_academica"]);
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