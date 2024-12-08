<?php
// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $direccion = $_POST["direccion"];
    $correo_electronico = $_POST["correo_electronico"];
    $telefono = $_POST["telefono"];
    $usuario = $_POST["usuario"];
    $contraseña = $_POST["contraseña"];
    $contraseña2 = $_POST["contraseña2"];
    

    // Preparamos los datos en formato array
    $data = array(
        "funcion" => "registro",
        "nombre" => $nombre,
        "apellidos" => $apellidos,
        "fecha_nacimiento" => $fecha_nacimiento,
        "direccion" => $direccion,
        "correo_electronico" => $correo_electronico,
        "telefono" => $telefono,
        "usuario" => $usuario,
        "contraseña" => $contraseña,
        "contraseña2" => $contraseña2
    );

    // Enviamos los datos al servidor.php usando cURL
    $handle = curl_init("http://localhost/SOA/servidor.php");
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data)); // Convertimos los datos a JSON
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $response = curl_exec($handle);

    if ($response === false) {
        echo "Error en la solicitud cURL: " . curl_error($handle);
    } else {
        echo "Respuesta del servidor: " . $response . "<br>";
    }

    curl_close($handle);
} else {
    // Mostrar el formulario HTML
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulario de Registro</title>
    </head>
    <body>
        <h1>Formulario de Registro</h1>
        <form action="clienteReg.php" method="POST">
            <label for="nombre">nombre:</label>
            <input type="text" name="nombre" id="nombre" required><br>

            <label for="apellidos">apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" required><br>

            <label for="fecha_nacimiento">fecha de nacimiento:</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required><br>

            <label for="direccion">Direccion:</label>
            <input type="text" name="direccion" id="direccion" required><br>

            <label for="correo_electronico">email:</label>
            <input type="email" name="correo_electronico" id="correo_electronico" required><br>

            <label for="telefono">telefono:</label>
            <input type="tel" name="telefono" id="telefono" required><br>

            <label for="usuario">usuario:</label>
            <input type="text" name="usuario" id="usuario" required><br>

            <label for="contraseña">contraseña:</label>
            <input type="password" name="contraseña" id="contraseña" required><br>

            <label for="contraseña2">Repetir contraseña:</label>
            <input type="password" name="contraseña2" id="contraseña2" required><br>

            <button type="submit">Enviar</button>
        </form>
    </body>
    </html>
    <?php
}
?>
