<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexión a la base de datos
    $host = 'localhost';
    $user = 'root'; // Cambiar si es necesario
    $password = ''; // Cambiar si es necesario
    $dbname = 'soa_final';

    $conn = new mysqli($host, $user, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Datos personales
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $info = $_POST['info'];

    // Datos de contacto
    $telefonos = json_encode($_POST['telefono']);
    $correos = json_encode($_POST['correo_electronico']);
    $paginas_web = json_encode($_POST['paginaweb']);

    // Formación académica
    $educacion = json_encode(array_map(null, $_POST['titulo'], $_POST['institucion'], $_POST['fecha']));

    // Experiencia laboral
    $experiencia = json_encode(array_map(null, $_POST['puesto'], $_POST['empresa'], $_POST['fecha_inicio'], $_POST['fecha_fin'], $_POST['descripcion']));

    // Imagen
    $imagen_path = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $imagen_path = 'uploads/' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_path);
    }

    // Insertar datos
    $stmt = $conn->prepare("INSERT INTO cv (nombre, apellidos, fecha_nacimiento, acerca_de, telefonos, correos, paginas_web, educacion, experiencia, imagen_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $nombre, $apellidos, $fecha_nacimiento, $info, $telefonos, $correos, $paginas_web, $educacion, $experiencia, $imagen_path);

    if ($stmt->execute()) {
        echo "Datos guardados correctamente.";
    } else {
        echo "Error al guardar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
