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

    $required_fields = ["nombre", "apellidos", "fecha_nacimiento", "direccion", "correo_electronico", "telefono", "usuario", "contraseña"];
    foreach ($required_fields as $field) {
        if (!isset($datos[$field])) {
            http_response_code(400);
            echo json_encode(array("response" => 400, "texto" => "Campo requerido faltante: " . $field));
            exit;
        }
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

    $sql = "INSERT INTO usuarios (nombre, apellidos, fecha_nacimiento, direccion, correo_electronico, telefono, usuario, contraseña) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error en la preparación de la consulta: " . $conn->error));
        exit;
    }

    $stmt->bind_param("ssssssss", $datos["nombre"], $datos["apellidos"], $datos["fecha_nacimiento"], $datos["direccion"], $datos["correo_electronico"], $datos["telefono"], $datos["usuario"], $contraseña_hash);

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
    if (!isset($datos["usuario"]) || !isset($datos["contraseña"])) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Datos no válidos"));
        exit;
    }

    $usuario = $datos["usuario"];
    $contraseña = $datos["contraseña"];

    $sql = "SELECT usuario, contraseña, role FROM usuarios WHERE usuario = ?";
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
    $role = '';
    $stmt->bind_result($usuario, $contraseña_hash, $role);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($contraseña, $contraseña_hash)) {
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


$conn->close();
?>