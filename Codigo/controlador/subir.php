<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Usar el usuario que ya está logueado
    $id = $_SESSION['id']; // El ID del usuario que está logueado

    // Manejar imagen
    $imagen_path = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_dir = '../Imagenes/';
        if (!is_dir($imagen_dir)) {
            mkdir($imagen_dir, 0777, true);
        }
        $imagen_path = $imagen_dir . basename($_FILES['imagen']['name']);
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_path)) {
            echo "Error al mover el archivo subido.";
            exit;
        }
    }
    
    // Insertar un nuevo currículum asociado al usuario
    $query = "INSERT INTO curriculums (usuario_id) VALUES ('$id')";
    mysqli_query($conn, $query);
    $cv_id = mysqli_insert_id($conn);  // Obtener el cv_id generado

    // Insertar contacto
    if (isset($_POST['telefono'])) {
        foreach ($_POST['telefono'] as $index => $telefono) {
            $correo = $_POST['correo_electronico'][$index];
            $paginaweb = $_POST['paginaweb'][$index];
            $datos_interes = $_POST['datos_interes'];
            $query = "INSERT INTO contacto (cv_id, telefono, correo_electronico, paginaweb, datos_interes, imagen_path) 
                      VALUES ($cv_id, '$telefono', '$correo', '$paginaweb', '$datos_interes', '$imagen_path')";
            mysqli_query($conn, $query);
        }
    }

    // Insertar educación
    if (isset($_POST['titulo'])) {
        foreach ($_POST['titulo'] as $index => $titulo) {
            $institucion = $_POST['institucion'][$index];
            $fecha = $_POST['fecha'][$index];
            $query = "INSERT INTO educacion (cv_id, titulo, institucion, fecha) 
                      VALUES ($cv_id, '$titulo', '$institucion', '$fecha')";
            mysqli_query($conn, $query);
        }
    }

    // Insertar idiomas
    if (isset($_POST['Idioma'])) {
        foreach ($_POST['Idioma'] as $index => $idioma) {
            $nivel = $_POST['nivel'][$index];
            $query = "INSERT INTO idiomas (cv_id, idioma, nivel) 
                      VALUES ($cv_id, '$idioma', '$nivel')";
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
            $query = "INSERT INTO experiencia_laboral (cv_id, puesto, empresa, fecha_inicio, fecha_fin, descripcion) 
                      VALUES ($cv_id, '$puesto', '$empresa', '$fecha_inicio', '$fecha_fin', '$descripcion')";
            mysqli_query($conn, $query);
        }
    }

    // Insertar habilidades
    if (isset($_POST['habilidades'])) {
        foreach ($_POST['habilidades'] as $habilidad) {
            $query = "INSERT INTO habilidades (cv_id, habilidad) 
                      VALUES ($cv_id, '$habilidad')";
            mysqli_query($conn, $query);
        }
    }

    echo "<script>
            alert('Datos guardados con éxito.');
            setTimeout(function() {
                window.location.href = '../vista/vercvForm.php';
            }, 2000); // Redirige después de 2 segundos
        </script>";
}
?>
