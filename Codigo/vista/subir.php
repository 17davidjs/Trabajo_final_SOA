<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Datos básicos
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $datos_interes = $_POST['datos_interes'];

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
    if (isset($_POST['telefono'])) {
        foreach ($_POST['telefono'] as $index => $telefono) {
            $correo = $_POST['correo_electronico'][$index];
            $paginaweb = $_POST['paginaweb'][$index];
            $query = "INSERT INTO contacto (usuario_id, telefono, correo_electronico, paginaweb) 
                      VALUES ($usuario_id, '$telefono', '$correo', '$paginaweb')";
            mysqli_query($conn, $query);
        }
    }

    // Insertar educación
    if (isset($_POST['titulo'])) {
        foreach ($_POST['titulo'] as $index => $titulo) {
            $institucion = $_POST['institucion'][$index];
            $fecha = $_POST['fecha'][$index];
            $query = "INSERT INTO educacion (usuario_id, titulo, institucion, fecha) 
                      VALUES ($usuario_id, '$titulo', '$institucion', '$fecha')";
            mysqli_query($conn, $query);
        }
    }

    // Insertar idiomas
    if (isset($_POST['Idioma'])) {
        foreach ($_POST['Idioma'] as $index => $idioma) {
            $nivel = $_POST['nivel'][$index];
            $query = "INSERT INTO idiomas (usuario_id, idioma, nivel) 
                      VALUES ($usuario_id, '$idioma', '$nivel')";
            mysqli_query($conn, $query);
        }
    }

    // Insertar experiencia laboral
    if (isset($_POST['puesto'])) {
        foreach ($_POST['puesto'] as $index => $puesto) {
            $empresa = $_POST['empresa'][$index];
            $fecha_inicio = $_POST['fecha_inicio'][$index];
            $fecha_fin = $_POST['fecha_fin'][$index];
            $descripcion = $_POST['descripcion'][$index];
            $query = "INSERT INTO experiencia_laboral (usuario_id, puesto, empresa, fecha_inicio, fecha_fin, descripcion) 
                      VALUES ($usuario_id, '$puesto', '$empresa', '$fecha_inicio', '$fecha_fin', '$descripcion')";
            mysqli_query($conn, $query);
        }
    }

    // Insertar habilidades
    if (isset($_POST['habilidades'])) {
        foreach ($_POST['habilidades'] as $habilidad) {
            $query = "INSERT INTO habilidades (usuario_id, habilidad) 
                      VALUES ($usuario_id, '$habilidad')";
            mysqli_query($conn, $query);
        }
    }

    echo "Datos guardados con éxito.";
}
?>
