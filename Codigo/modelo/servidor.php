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

function obtenerUsuarioID($usuario) {
    global $conn;
    $sql = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    } else {
        http_response_code(404);
        echo json_encode(array("response" => 404, "texto" => "Usuario no encontrado"));
        exit;
    }
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


function addCurriculum($datos)
{
    global $conn;

    // Datos básicos
    $nombre = $datos['nombre'];
    $apellidos = $datos['apellidos'];
    $fecha_nacimiento = $datos['fecha_nacimiento'];
    $datos_interes = $datos['datos_interes'];

    // Manejar imagen
    $imagen_path = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_path = 'uploads/' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_path);
    }

    // Insertar usuario
    $query = "INSERT INTO usuarios (nombre, apellidos, fecha_nacimiento, datos_interes, imagen_path) 
              VALUES ('$nombre', '$apellidos', '$fecha_nacimiento', '$datos_interes', '$imagen_path')";
    mysqli_query($conn, $query);
    $usuario_id = mysqli_insert_id($conn);

    // Insertar contacto
    if (isset($datos['telefono'])) {
        foreach ($datos['telefono'] as $index => $telefono) {
            $correo = $datos['correo_electronico'][$index];
            $paginaweb = $datos['paginaweb'][$index];
            $query = "INSERT INTO contacto (usuario_id, telefono, correo_electronico, paginaweb) 
                      VALUES ($usuario_id, '$telefono', '$correo', '$paginaweb')";
            mysqli_query($conn, $query);
        }
    }

    // Insertar educación
    if (isset($datos['titulo'])) {
        foreach ($datos['titulo'] as $index => $titulo) {
            $institucion = $datos['institucion'][$index];
            $fecha = $datos['fecha'][$index];
            $query = "INSERT INTO educacion (usuario_id, titulo, institucion, fecha) 
                      VALUES ($usuario_id, '$titulo', '$institucion', '$fecha')";
            mysqli_query($conn, $query);
        }
    }

    // Insertar idiomas
    if (isset($datos['Idioma'])) {
        foreach ($datos['Idioma'] as $index => $idioma) {
            $nivel = $datos['nivel'][$index];
            $query = "INSERT INTO idiomas (usuario_id, idioma, nivel) 
                      VALUES ($usuario_id, '$idioma', '$nivel')";
            mysqli_query($conn, $query);
        }
    }

    // Insertar experiencia laboral
    if (isset($datos['puesto'])) {
        foreach ($datos['puesto'] as $index => $puesto) {
            $empresa = $datos['empresa'][$index];
            $fecha_inicio = $datos['fecha_inicio'][$index];
            $fecha_fin = $datos['fecha_fin'][$index];
            $descripcion = $datos['descripcion'][$index];
            $query = "INSERT INTO experiencia_laboral (usuario_id, puesto, empresa, fecha_inicio, fecha_fin, descripcion) 
                      VALUES ($usuario_id, '$puesto', '$empresa', '$fecha_inicio', '$fecha_fin', '$descripcion')";
            mysqli_query($conn, $query);
        }
    }

    // Insertar habilidades
    if (isset($datos['habilidades'])) {
        foreach ($datos['habilidades'] as $habilidad) {
            $query = "INSERT INTO habilidades (usuario_id, habilidad) 
                      VALUES ($usuario_id, '$habilidad')";
            mysqli_query($conn, $query);
        }
    }

    return ['response' => 200, 'texto' => 'Datos guardados con éxito.'];
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
    $hash = '';
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

function procesarCSV($contenido, $usuario) {
    global $conn;
    $lineas = explode(PHP_EOL, $contenido);
    $encabezados = str_getcsv(array_shift($lineas)); // Extraer los encabezados del archivo CSV

    // Variable para controlar la tabla que se está procesando
    $tablaActual = null;

    foreach ($lineas as $linea) {
        if (trim($linea) === "") continue; // Omitir líneas vacías
        $fila = str_getcsv($linea); // Extraer los datos de la línea
        
        // Comprobar si el número de columnas es el mismo que el número de encabezados
        if (count($fila) == count($encabezados)) {
            $datos = array_combine($encabezados, $fila); // Asociar encabezados con los datos

            // Detectar si la fila pertenece a una nueva tabla
            if (isset($datos['tabla'])) {
                $tablaActual = strtolower($datos['tabla']);
            }

            // Procesar los datos según la tabla actual
            if ($tablaActual) {
                switch ($tablaActual) {
                    case "contacto":
                        guardarContactoCSV($datos, $usuario);
                        break;
                    case "educacion":
                        guardarEducacionCSV($datos, $usuario);
                        break;
                    case "experiencia_laboral":
                        guardarExperienciaLaboralCSV($datos, $usuario);
                        break;
                    case "habilidades":
                        // Procesar habilidades, las cuales son solo un listado
                        foreach ($fila as $habilidad) {
                            if ($habilidad !== "tabla") { // Evitar procesar la columna 'tabla'
                                guardarHabilidadCSV($habilidad, $usuario);
                            }
                        }
                        break;
                    case "idiomas":
                        // Procesar idiomas, cada uno con su nivel
                        if (count($fila) == 2) {
                            $datos_idioma = array_combine(['idioma', 'nivel'], $fila);
                            guardarIdiomaCSV($datos_idioma, $usuario);
                        }
                        break;
                    default:
                        // Ignorar tablas desconocidas
                        break;
                }
            }
        }
    }
}



function procesarJSON($contenido, $usuario) {
    $datos = json_decode($contenido, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Error al procesar el archivo JSON: " . json_last_error_msg()));
        exit;
    }

    // Guardar cada sección en su respectiva tabla
    if (isset($datos['contacto'])) {
        guardarContacto($datos['contacto'], $usuario);
    }
    if (isset($datos['educacion'])) {
        foreach ($datos['educacion'] as $educacion) {
            guardarEducacion($educacion, $usuario);
        }
    }
    if (isset($datos['experiencia_laboral'])) {
        foreach ($datos['experiencia_laboral'] as $experiencia) {
            guardarExperienciaLaboral($experiencia, $usuario);
        }
    }
    if (isset($datos['habilidades'])) {
        foreach ($datos['habilidades'] as $habilidad) {
            guardarHabilidad($habilidad, $usuario); 
        }
    }
    if (isset($datos['idiomas'])) {
        foreach ($datos['idiomas'] as $idioma) {
            guardarIdioma($idioma, $usuario);
        }
    }
}


function procesarXML($contenido, $usuario) {
    $xml = simplexml_load_string($contenido);
    if ($xml === false) {
        $errors = libxml_get_errors();
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->message;
        }
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Error al procesar el archivo XML", "errores" => $errorMessages));
        exit;
    }

    $datos = json_decode(json_encode($xml), true);

    // Guardar datos en las tablas correspondientes
    if (isset($datos['contacto'])) {
        guardarContacto($datos['contacto'], $usuario);
    }
    if (isset($datos['educacion']['item'])) {
        // Si hay más de un elemento en 'educacion', se debe recorrer cada 'item'
        foreach ($datos['educacion']['item'] as $educacion) {
            guardarEducacion($educacion, $usuario);
        }
    }
    if (isset($datos['experiencia_laboral']['item'])) {
        // Lo mismo para experiencia_laboral
        foreach ($datos['experiencia_laboral']['item'] as $experiencia) {
            guardarExperienciaLaboral($experiencia, $usuario);
        }
    }
    if (isset($datos['habilidades']['habilidad'])) {
        // Si hay varias habilidades, recorrerlas
        foreach ($datos['habilidades']['habilidad'] as $habilidad) {
            guardarHabilidad($habilidad, $usuario);
        }
    }
    if (isset($datos['idiomas']['idioma'])) {
        // Y lo mismo para los idiomas
        foreach ($datos['idiomas']['idioma'] as $idioma) {
            guardarIdioma($idioma, $usuario);
        }
    }
}


function guardarContacto($datos, $usuario) {
    global $conn;
    $usuario_id = obtenerUsuarioID($usuario);

    $sql = "INSERT INTO contacto (usuario_id, telefono, correo_electronico, paginaweb) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $usuario_id, $datos['telefono'], $datos['correo_electronico'], $datos['peginaweb']);
    $stmt->execute();
}


function guardarEducacion($datos, $usuario) {
    global $conn;
    $usuario_id = obtenerUsuarioID($usuario);

    $sql = "INSERT INTO educacion (usuario_id, titulo, institucion, fecha) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $usuario_id, $datos['titulo'], $datos['institucion'], $datos['fecha']);
    $stmt->execute();
}


function guardarExperienciaLaboral($datos, $usuario) {
    global $conn;
    $usuario_id = obtenerUsuarioID($usuario);

    $sql = "INSERT INTO experiencia_laboral (usuario_id, puesto, empresa, fecha_inicio, fecha_fin, descripcion) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $usuario_id, $datos['puesto'], $datos['empresa'], $datos['fecha_inicio'], $datos['fecha_fin'], $datos['descripcion']);
    $stmt->execute();
}


function guardarHabilidad($habilidad, $usuario) {
    global $conn;
    $usuario_id = obtenerUsuarioID($usuario);

    // Validar que la habilidad no esté vacía
    if (empty($habilidad)) {
        http_response_code(400);
        echo json_encode(array("response" => 400, "texto" => "Habilidad no válida"));
        exit;
    }

    $sql = "INSERT INTO habilidades (usuario_id, habilidad) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $usuario_id, $habilidad);
    $stmt->execute();
}


function guardarIdioma($datos, $usuario) {
    global $conn;
    $usuario_id = obtenerUsuarioID($usuario);

    $sql = "INSERT INTO idiomas (usuario_id, idioma, nivel) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $usuario_id, $datos['idioma'], $datos['nivel']);
    $stmt->execute();
}


/****************************************************************************************************/
// Funciones para guardar currículums en la base de datos en CSV
/****************************************************************************************************/

function guardarContactoCSV($datos, $usuario) {
    global $conn;
    $usuario_id = obtenerUsuarioID($usuario);

    // Verificar que las claves existen y asignar valores
    $telefono = isset($datos['telefono']) ? $datos['telefono'] : '';
    $correo_electronico = isset($datos['correo_electronico']) ? $datos['correo_electronico'] : '';
    $paginaweb = isset($datos['paginaweb']) ? $datos['paginaweb'] : '';

    // Verificar que los valores no estén vacíos antes de proceder con la inserción
    if (!empty($telefono) && !empty($correo_electronico) && !empty($paginaweb)) {
        $sql = "INSERT INTO contacto (usuario_id, telefono, correo_electronico, paginaweb) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $usuario_id, $telefono, $correo_electronico, $paginaweb);
        $stmt->execute();
    }
}


function guardarEducacionCSV($datos, $usuario) {
    global $conn;
    $usuario_id = obtenerUsuarioID($usuario);

    // Verificar que las claves existen y asignar valores
    $titulo = isset($datos['titulo']) ? $datos['titulo'] : '';
    $institucion = isset($datos['institucion']) ? $datos['institucion'] : '';
    $fecha = isset($datos['fecha']) ? $datos['fecha'] : '';

    // Verificar que los valores no estén vacíos antes de proceder con la inserción
    if (!empty($titulo) && !empty($institucion) && !empty($fecha)) {
        $sql = "INSERT INTO educacion (usuario_id, titulo, institucion, fecha) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $usuario_id, $titulo, $institucion, $fecha);
        $stmt->execute();
    }
}


function guardarExperienciaLaboralCSV($datos, $usuario) {
    global $conn;
    $usuario_id = obtenerUsuarioID($usuario);

    // Verificar que las claves existen y asignar valores
    $puesto = isset($datos['puesto']) ? $datos['puesto'] : '';
    $empresa = isset($datos['empresa']) ? $datos['empresa'] : '';
    $fecha_inicio = isset($datos['fecha_inicio']) ? $datos['fecha_inicio'] : '';
    $fecha_fin = isset($datos['fecha_fin']) ? $datos['fecha_fin'] : '';
    $descripcion = isset($datos['descripcion']) ? $datos['descripcion'] : '';

    // Verificar que los valores no estén vacíos antes de proceder con la inserción
    if (!empty($puesto) && !empty($empresa) && !empty($fecha_inicio)) {
        $sql = "INSERT INTO experiencia_laboral (usuario_id, puesto, empresa, fecha_inicio, fecha_fin, descripcion) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $usuario_id, $puesto, $empresa, $fecha_inicio, $fecha_fin, $descripcion);
        $stmt->execute();
    }
}


function guardarHabilidadCSV($habilidad, $usuario) {
    global $conn;
    $usuario_id = obtenerUsuarioID($usuario);

    // Validar que la habilidad no esté vacía
    if (!empty($habilidad)) {
        $sql = "INSERT INTO habilidades (usuario_id, habilidad) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $usuario_id, $habilidad);
        $stmt->execute();
    }
}


function guardarIdiomaCSV($datos, $usuario) {
    global $conn;
    $usuario_id = obtenerUsuarioID($usuario);

    // Verificar que las claves existen y asignar valores
    $idioma = isset($datos['idioma']) ? $datos['idioma'] : '';
    $nivel = isset($datos['nivel']) ? $datos['nivel'] : '';

    // Verificar que los valores no estén vacíos antes de proceder con la inserción
    if (!empty($idioma) && !empty($nivel)) {
        $sql = "INSERT INTO idiomas (usuario_id, idioma, nivel) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $usuario_id, $idioma, $nivel);
        $stmt->execute();
    }
}


$conn->close();
?>
