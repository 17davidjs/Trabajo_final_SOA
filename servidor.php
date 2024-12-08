<?php
header('Content-Type: application/json');

// Configuración de conexión a la base de datos
$servidor = "localhost"; // Cambia según tu configuración
$user = "root"; // Cambia según tu configuración
$pass = ""; // Cambia según tu configuración
$bd = "soa_final"; // Cambia según tu configuración


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


$conn->close();
?>























?>