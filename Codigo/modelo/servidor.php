<?php
header('Content-Type: application/json');

require_once '../config/db.php';

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
    case "cambiarContrasena":
        cambiarContrasena($datos);
        break;
    case "getAllUsers":
        getAllUsers();
        break;
    case "getUserById":
        getUserById($datos);
        break;
    case "obtenerUserById":
        obtenerUserById($datos);
        break;
    case "createUser":
        createUser($datos);
        break;
    case "updateUser":
        updateUser($datos);
        break;
    case "deleteUser":
        deleteUser($datos);
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

    $sql = "INSERT INTO usuarios (nombre, apellidos, fecha_nacimiento, direccion, correo_electronico, telefono, usuario, contrasena, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error en la preparación de la consulta: " . $conn->error));
        exit;
    }

    $stmt->bind_param("sssssssss", $datos["nombre"], $datos["apellidos"], $datos["fecha_nacimiento"], $datos["direccion"], 
                        $datos["correo_electronico"], $datos["telefono"], $datos["usuario"], $contrasena_hash, $datos["role"]);

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


/****************************************************************************************************/
// Función para cambiar contraseña de un usuario autenticado
/****************************************************************************************************/
function cambiarContrasena($datos)
{
    global $conn;

    // Validar que los datos requeridos estén presentes
    if (!isset($datos["usuario"]) || !isset($datos["token"]) || !isset($datos["contrasena-actual"]) || !isset($datos["nueva-contrasena"]))
    {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Datos no válidos para cambiar la contraseña"));
        exit;
    }

    $usuario = $datos["usuario"];
    $token = $datos["token"];
    $contrasena_actual = $datos["contrasena-actual"];
    $nueva_contrasena = $datos["nueva-contrasena"];

    // Verificar token
    $usuarioDB = verificarToken($token);

    if ($usuario !== $usuarioDB)
    {
        http_response_code(401);
        echo json_encode(['response' => 401, 'texto' => 'Usuario no autorizado']);
        return;
    }

    // Verificar contraseña actual
    $stmt = $conn->prepare("SELECT contrasena FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($hash);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($contrasena_actual, $hash))
    {
        http_response_code(400);
        echo json_encode(['response' => 400, 'texto' => 'Contraseña actual incorrecta']);
        return;
    }

    // Validar que la nueva contraseña tenga al menos 8 caracteres
    if (strlen($nueva_contrasena) < 8)
    {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "La nueva contraseña debe tener al menos 8 caracteres"));
        exit;
    }


    // Actualizar nueva contraseña
    $hash_nueva_contrasena = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE usuarios SET contrasena = ? WHERE usuario = ?");
    $stmt->bind_param("ss", $hash_nueva_contrasena, $usuario);
    
    if ($stmt->execute())
    {
        http_response_code(200);
        echo json_encode(array("response" => 200, "texto" => "Contraseña modificada correctamente"));
    }
    else
    {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error al modificar la contraseña: " . $stmt->error));
    }
    
    $stmt->close();
}

/****************************************************************************************************/
// Funciones para la gestión de usuarios
/****************************************************************************************************/
    function getAllUsers()
    {
        global $conn;

        $sql = "SELECT * FROM usuarios";
        $result = $conn->query($sql);

        if (!$result)
        {
            http_response_code(500);
            echo json_encode(array("response" => 500, "texto" => "Error en la consulta: " . $conn->error));
            exit;
        }

        $usuarios = [];

        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                $usuarios[] = $row;
            }
        }

        echo json_encode($usuarios);
    }

    function getUserById($datos)
    {
        global $conn;

        $id = $datos['id'];

        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id); // Bindeo el parámetro como entero
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (!$result)
        {
            http_response_code(500);
            echo json_encode(array("response" => 500, "texto" => "Error en la consulta: " . $conn->error));
            exit;
        }

        $ids = [];

        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                $ids[] = $row;
            }
        }

        echo json_encode($ids);
    }

    function obtenerUserById($datos)
    {
        global $conn;

        $id = $datos['id'];

        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id); // Bindeo el parámetro como entero
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (!$result)
        {
            http_response_code(500);
            echo json_encode(array("response" => 500, "texto" => "Error en la consulta: " . $conn->error));
            exit;
        }

        $ids = [];

        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                $ids[] = $row;
            }
        }

        echo json_encode($ids);
    }

    function createUser($datos)
    {
        global $conn;

        $sql = "INSERT INTO usuarios (nombre, apellidos, fecha_nacimiento, direccion, correo_electronico, telefono, usuario, contrasena, token, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $hashedPassword = password_hash($datos['contrasena'], PASSWORD_BCRYPT);

        $datos['correo_electronico'] = filter_var($datos['correo_electronico'], FILTER_VALIDATE_EMAIL);
        
        if (!$datos['correo_electronico'])
        {
            throw new Exception("Correo electrónico inválido.");
        }

        $stmt->bind_param("ssssssssss", $datos['nombre'], $datos['apellidos'], $datos['fecha_nacimiento'], $datos['direccion'], $datos['correo_electronico'], $datos['telefono'], $datos['usuario'], $hashedPassword, $datos['token'], $datos['role']);
        
        if ($stmt->execute())
        {
            http_response_code(201);
            echo json_encode(array("response" => 201, "texto" => "Usuario creado correctamente"));
        } 
        else
        {
            http_response_code(500);
            echo json_encode(array("response" => 500, "texto" => "Error al crear el usuario" . $stmt->error));
        }
        
        $stmt->close();
    }

    function updateUser($datos)
    {
        global $conn;

        $id = $datos['id'];

        $sql = "UPDATE usuarios SET nombre = ?, apellidos = ?, fecha_nacimiento = ?, direccion = ?, correo_electronico = ?, telefono = ?, usuario = ?, token = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssi",  $datos['nombre'], $datos['apellidos'], $datos['fecha_nacimiento'], $datos['direccion'], $datos['correo_electronico'], $datos['telefono'], $datos['usuario'], $datos['token'], $datos['role'], $id);

        if ($stmt->execute())
        {
            http_response_code(200);
            echo json_encode(array("response" => 200, "texto" => "Usuario actualizado correctamente"));
        }
        else
        {
            http_response_code(500);
            echo json_encode(array("response" => 500, "texto" => "Error al actualizar el usuario: " . $stmt->error));
        }

        $stmt->close();
    }

    function deleteUser($datos)
    {
        global $conn;
        $id = $datos['id'];

        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute())
        {
            http_response_code(200);
            echo json_encode(array("response" => 200, "texto" => "Usuario eliminado correctamente"));
        }
        else
        {
            http_response_code(500);
            echo json_encode(array("response" => 500, "texto" => "Error al eliminar el usuario: " . $stmt->error));
        }

        $stmt->close();
    }



/****************************************************************************************************/
function eliminarUser($datos) {
    global $conn;

    if (!isset($datos["token"]) || !isset($datos["usuario"])) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Datos no válidos para eliminar usuario"));
        exit;
    }

    $token = $datos["token"];
    $usuario = $datos["usuario"];

    // Verificar token
    $usuarioValidado = verificarToken($token);

    if ($usuarioValidado !== $usuario) {
        http_response_code(401);
        echo json_encode(array("response" => 401, "texto" => "Token no válido o no coincide con el usuario"));
        exit;
    }

    // Verificar si el usuario existe
    $sqlCheck = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmtCheck = $conn->prepare($sqlCheck);

    if (!$stmtCheck) {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error al preparar la consulta de verificación: " . $conn->error));
        exit;
    }

    $stmtCheck->bind_param("s", $usuario);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(array("response" => 404, "texto" => "Usuario no encontrado"));
        exit;
    }

    $stmtCheck->close();

    // Eliminar usuario
    $sql = "DELETE FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
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

function procesarCSV($datos,$usuario) {
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


    // Parsear el contenido CSV
    $lineas = explode("\n", $datos);
    $headers = str_getcsv(array_shift($lineas));
    $data = str_getcsv(array_shift($lineas));

    // Asignar los datos a variables
    $nombre = $data[array_search('nombre', $headers)];
    $apellidos = $data[array_search('apellidos', $headers)];
    $fecha_nacimiento = $data[array_search('fecha_nacimiento', $headers)];
    $telefono = $data[array_search('telefonos', $headers)];
    $correo_electronico = $data[array_search('correos', $headers)];
    $web = $data[array_search('paginas_web', $headers)];
    $imagen = $data[array_search('imagen_path', $headers)];
    $formacion_academica = json_decode($data[array_search('formacion_academica', $headers)], true);
    $experiencia_laboral = json_decode($data[array_search('experiencia_laboral', $headers)], true);
    $idiomas = json_decode($data[array_search('idiomas', $headers)], true);
    $habilidades = json_decode($data[array_search('habilidades', $headers)], true);
    $datos_interes = json_decode($data[array_search('datos_interes', $headers)], true);

    // Insertar en la tabla `curriculums`
    $sql = "INSERT INTO cv (usuario_id, nombre, apellidos, fecha_nacimiento, telefonos, correos, paginas_web, imagen_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $usuario_id, $nombre, $apellidos, $fecha_nacimiento, $telefono, $correo_electronico, $web, $imagen); 

    if (!$stmt->execute()) {
        echo json_encode(["response" => 500, "texto" => "Error al insertar currículum: " . $stmt->error]);
        return;
    }

    $curriculumID = $conn->insert_id; // Obtener el ID del currículum recién insertado
    $stmt->close();

    // Insertar en `formacion_academica`
    $sql = "INSERT INTO formacion_academica (curriculum_id, titulo, institucion, fecha_fin) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($formacion_academica as $formacion) {
        $stmt->bind_param(
            "issss",
            $curriculumID,
            $formacion['titulo'],
            $formacion['institucion'],
            $formacion['fecha_fin']
        );
        $stmt->execute();
    }
    $stmt->close();

    // Insertar en `experiencia_laboral`
    $sql = "INSERT INTO experiencia_laboral (curriculum_id, puesto, empresa, fecha_inicio, fecha_fin, responsabilidades) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($experiencia_laboral as $experiencia) {
        $responsabilidades = json_encode($experiencia['responsabilidades']);
        $stmt->bind_param(
            "issss",
            $curriculumID,
            $experiencia['puesto'],
            $experiencia['empresa'],
            $experiencia['fecha_inicio'],
            $experiencia['fecha_fin'],
            $responsabilidades
        );
        $stmt->execute();
    }
    $stmt->close();

    // Insertar en `idiomas`
    $sql = "INSERT INTO idiomas (curriculum_id, idioma, nivel) 
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($idiomas as $idioma) {
        $stmt->bind_param("iss", $curriculumID, $idioma['idioma'], $idioma['nivel']);
        $stmt->execute();
    }
    $stmt->close();

    // Insertar en `habilidades`
    $sql = "INSERT INTO habilidades (curriculum_id, habilidad) 
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($habilidades as $habilidad) {
        $stmt->bind_param("is", $curriculumID, $habilidad);
        $stmt->execute();
    }
    $stmt->close();

    // Insertar en `datos_interes`
    $sql = "INSERT INTO datos_interes (curriculum_id, dato_interes) 
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($datos_interes as $dato) {
        $stmt->bind_param("is", $curriculumID, $dato);
        $stmt->execute();
    }
    $stmt->close();

    echo json_encode(["response" => 200, "texto" => "Currículum procesado y guardado correctamente."]);
}

function procesarJSON($datos,$usuario) {
    global $conn;

    $usuario_id = "";
    $sqlUsuario = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sqlUsuario);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($usuario_id);
    $stmt->fetch();
    $stmt->close();
    // Decodificar el JSON
    $data = json_decode($$datos, true);

    // Asignar los datos a variables
    $nombre = $data['nombre'];
    $apellidos = $data['apellidos'];
    $fecha_nacimiento = $data['fecha_nacimiento'];
    $telefono = $data['telefonos'];
    $correo_electronico = $data['correos'];
    $web = $data['paginas_web'];
    $imagen = $data['imagen_path'];
    $formacion_academica = $data['formacion_academica'];
    $experiencia_laboral = $data['experiencia_laboral'];
    $idiomas = $data['idiomas'];
    $habilidades = $data['habilidades'];
    $datos_interes = $data['datos_interes'];

     // Insertar en la tabla `curriculums`
     $sql = "INSERT INTO cv (usuario_id, nombre, apellidos, fecha_nacimiento, telefonos, correos, paginas_web, imagen_path) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $usuario_id, $nombre, $apellidos, $fecha_nacimiento, $telefono, $correo_electronico, $web, $imagen); 
    if (!$stmt->execute()) {
        echo json_encode(["response" => 500, "texto" => "Error al insertar currículum: " . $stmt->error]);
        return;
    }

    $curriculumID = $conn->insert_id; // Obtener el ID del currículum recién insertado
    $stmt->close();

    // Insertar en `formacion_academica`
    $sql = "INSERT INTO formacion_academica (curriculum_id, titulo, institucion,  fecha_fin) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($formacion_academica as $formacion) {
        $stmt->bind_param(
            "issss",
            $curriculumID,
            $formacion['titulo'],
            $formacion['institucion'],
            $formacion['fecha_fin']
        );
        $stmt->execute();
    }
    $stmt->close();

    // Insertar en `experiencia_laboral`
    $sql = "INSERT INTO experiencia_laboral (curriculum_id, puesto, empresa, fecha_inicio, fecha_fin, responsabilidades) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($experiencia_laboral as $experiencia) {
        $responsabilidades = json_encode($experiencia['responsabilidades']);
        $stmt->bind_param(
            "issss",
            $curriculumID,
            $experiencia['puesto'],
            $experiencia['empresa'],
            $experiencia['fecha_inicio'],
            $experiencia['fecha_fin'],
            $responsabilidades
        );
        $stmt->execute();
    }
    $stmt->close();

    // Insertar en `idiomas`
    $sql = "INSERT INTO idiomas (curriculum_id, idioma, nivel) 
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($idiomas as $idioma) {
        $stmt->bind_param("iss", $curriculumID, $idioma['idioma'], $idioma['nivel']);
        $stmt->execute();
    }
    $stmt->close();

    // Insertar en `habilidades`
    $sql = "INSERT INTO habilidades (curriculum_id, habilidad) 
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($habilidades as $habilidad) {
        $stmt->bind_param("is", $curriculumID, $habilidad);
        $stmt->execute();
    }
    $stmt->close();

    // Insertar en `datos_interes`
    $sql = "INSERT INTO datos_interes (curriculum_id, dato_interes) 
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($datos_interes as $dato) {
        $stmt->bind_param("is", $curriculumID, $dato);
        $stmt->execute();
    }
    $stmt->close();

    echo json_encode(["response" => 200, "texto" => "Currículum procesado y guardado correctamente."]);
}

function procesarXML($datos, $usuario) {
    global $conn;

    $usuario_id = "";
    $sqlUsuario = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sqlUsuario);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($usuario_id);
    $stmt->fetch();
    $stmt->close();
    // Cargar el XML y convertirlo en un objeto SimpleXMLElement
    $xml = simplexml_load_string($datos);

    // Asignar los datos a variables
    $nombre = (string)$xml->nombre;
    $apellidos = (string)$xml->apellidos;
    $fecha_nacimiento = (string)$xml->fecha_nacimiento;
    $telefono = (string)$xml->telefonos;
    $correo_electronico = (string)$xml->correos;
    $web = (string)$xml->paginas_web;
    $imagen = (string)$xml->imagen_path;
    $formacion_academica = $xml->formacion_academica->estudio;
    $experiencia_laboral = $xml->experiencia_laboral->trabajo;
    $idiomas = $xml->idiomas->idioma;
    $habilidades = $xml->habilidades->habilidad;
    $datos_interes = $xml->datos_interes->dato;

   // Insertar en la tabla `curriculums`
   $sql = "INSERT INTO cv (usuario_id, nombre, apellidos, fecha_nacimiento, telefonos, correos, paginas_web, imagen_path) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $usuario_id, $nombre, $apellidos, $fecha_nacimiento, $telefono, $correo_electronico, $web, $imagen); 

    if (!$stmt->execute()) {
        echo json_encode(["response" => 500, "texto" => "Error al insertar currículum: " . $stmt->error]);
        return;
    }

    $curriculumID = $conn->insert_id; // Obtener el ID del currículum recién insertado
    $stmt->close();

    // Insertar en `formacion_academica`
    $sql = "INSERT INTO formacion_academica (curriculum_id, titulo, institucion, fecha_fin) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($formacion_academica as $formacion) {
        $stmt->bind_param(
            "issss",
            $curriculumID,
            (string)$formacion->titulo,
            (string)$formacion->institucion,
            (string)$formacion->fecha_fin
        );
        $stmt->execute();
    }
    $stmt->close();

    // Insertar en `experiencia_laboral`
    $sql = "INSERT INTO experiencia_laboral (curriculum_id, puesto, empresa, fecha_inicio, fecha_fin, responsabilidades) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($experiencia_laboral as $experiencia) {
        $responsabilidades = [];
        foreach ($experiencia->responsabilidades->responsabilidad as $responsabilidad) {
            $responsabilidades[] = (string)$responsabilidad;
        }
        $responsabilidadesJson = json_encode($responsabilidades);

        $stmt->bind_param(
            "issss",
            $curriculumID,
            (string)$experiencia->puesto,
            (string)$experiencia->empresa,
            (string)$experiencia->fecha_inicio,
            (string)$experiencia->fecha_fin,
            $responsabilidadesJson
        );
        $stmt->execute();
    }
    $stmt->close();

    // Insertar en `idiomas`
    $sql = "INSERT INTO idiomas (curriculum_id, idioma, nivel) 
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($idiomas as $idioma) {
        $idioma_nombre = (string)$idioma->nombre;
        $idioma_nivel = (string)$idioma->nivel;
        $stmt->bind_param("iss", $curriculumID, $idioma_nombre, $idioma_nivel);
        $stmt->execute();
    }
    $stmt->close();

    // Insertar en `habilidades`
    $sql = "INSERT INTO habilidades (curriculum_id, habilidad) 
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($habilidades as $habilidad) {
        $habilidad_str = (string)$habilidad;
        $stmt->bind_param("is", $curriculumID, $habilidad_str);
        $stmt->execute();
    }
    $stmt->close();

    // Insertar en `datos_interes`
    $sql = "INSERT INTO datos_interes (curriculum_id, dato_interes) 
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($datos_interes as $dato) {
        $dato_interes = (string)$dato;
        $stmt->bind_param("is", $curriculumID, $dato_interes);
        $stmt->execute();
    }
    $stmt->close();

    echo json_encode(["response" => 200, "texto" => "Currículum procesado y guardado correctamente."]);
}

$conn->close();
?>
