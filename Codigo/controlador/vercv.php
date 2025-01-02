<?php
session_start();
require_once '../config/db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION["usuario"]) || !isset($_SESSION["token"])) {
    echo "Debe iniciar sesión para ver los currículums<br>";
    echo "<a href='../vista/loginForm.php'>Iniciar sesión</a>";
    exit;
}

// Obtener los datos de la tabla curriculums
$sql = "SELECT id, usuario_id, nombre, apellidos, fecha_nacimiento, direccion, correo_electronico, telefono, fecha_curriculum FROM curriculums";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $curriculums = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $curriculums = [];
}

$conn->close();

// Incluir la vista para mostrar los datos
include '../vista/vercv.php';
?>