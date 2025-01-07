<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cv_id = $_POST['cv_id'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $datos_interes = $_POST['datos_interes'];

    // Actualizar datos personales
    $query = "UPDATE curriculums SET datos_interes = ? WHERE cv_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nombre, $apellidos, $fecha_nacimiento, $datos_interes, $cv_id);
    $stmt->execute();

    // Actualizar datos de contacto
    $query = "DELETE FROM contacto WHERE cv_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cv_id);
    $stmt->execute();

    foreach ($_POST['telefono'] as $index => $telefono) {
        $correo_electronico = $_POST['correo_electronico'][$index];
        $paginaweb = $_POST['paginaweb'][$index];
        $query = "INSERT INTO contacto (cv_id, telefono, correo_electronico, paginaweb) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $cv_id, $telefono, $correo_electronico, $paginaweb);
        $stmt->execute();
    }

    // Actualizar datos de educación
    $query = "DELETE FROM educacion WHERE cv_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cv_id);
    $stmt->execute();

    foreach ($_POST['titulo'] as $index => $titulo) {
        $institucion = $_POST['institucion'][$index];
        $fecha = $_POST['fecha'][$index];
        $query = "INSERT INTO educacion (cv_id, titulo, institucion, fecha) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $cv_id, $titulo, $institucion, $fecha);
        $stmt->execute();
    }

    // Actualizar datos de idiomas
    $query = "DELETE FROM idiomas WHERE cv_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cv_id);
    $stmt->execute();

    foreach ($_POST['Idioma'] as $index => $idioma) {
        $nivel = $_POST['nivel'][$index];
        $query = "INSERT INTO idiomas (cv_id, idioma, nivel) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $cv_id, $idioma, $nivel);
        $stmt->execute();
    }

    // Actualizar datos de experiencia laboral
    $query = "DELETE FROM experiencia_laboral WHERE cv_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cv_id);
    $stmt->execute();

    foreach ($_POST['puesto'] as $index => $puesto) {
        $empresa = $_POST['empresa'][$index];
        $fecha_inicio = $_POST['fecha_inicio'][$index];
        $fecha_fin = $_POST['fecha_fin'][$index];
        $descripcion = $_POST['descripcion'][$index];
        $query = "INSERT INTO experiencia_laboral (cv_id, puesto, empresa, fecha_inicio, fecha_fin, descripcion) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssss", $cv_id, $puesto, $empresa, $fecha_inicio, $fecha_fin, $descripcion);
        $stmt->execute();
    }

    // Actualizar datos de habilidades
    $query = "DELETE FROM habilidades WHERE cv_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cv_id);
    $stmt->execute();

    foreach ($_POST['habilidades'] as $habilidad) {
        $query = "INSERT INTO habilidades (cv_id, habilidad) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $cv_id, $habilidad);
        $stmt->execute();
    }

    header("Location: ../vista/vercvForm.php");
    exit;
}
?>