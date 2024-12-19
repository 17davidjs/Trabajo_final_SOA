<?php

    require 'db.php';

    // Verificar si el formulario ha sido enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Recoger los datos del formulario
        $nombre = $_POST["nombre"];
        $apellidos = $_POST["apellidos"];
        $fecha_nacimiento = $_POST["fecha_nacimiento"];
        $direccion = $_POST["direccion"];
        $correo_electronico = $_POST["correo_electronico"];
        $telefono = $_POST["telefono"];
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];
        $contrasena2 = $_POST["contrasena2"];

        if ($contrasena !== $contrasena2)
        {
            echo "Error: Las contraseñas no coinciden.";
            exit;
        }

        if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL))
        {
            echo "Error: El formato del correo electrónico no es válido.";
            exit;
        }
        

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
            "contrasena" => $contrasena,
        );

        // Enviamos los datos al servidor.php usando cURL
        $handle = curl_init("../servidor.php");
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data)); // Convertimos los datos a JSON
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = curl_exec($handle);

        if ($response === false)
        {
            echo "Error en la solicitud cURL: " . curl_error($handle);
        }
        else
        {
            $decodedResponse = json_decode($response, true);
           
            if (json_last_error() === JSON_ERROR_NONE)
            {
                // Si la respuesta es JSON válida
                echo "Respuesta del servidor: " . print_r($decodedResponse, true);
            }
            else
            {
                // Si no es JSON válido
                echo "Respuesta del servidor: " . $response;
            }
        }

        curl_close($handle);
    }
    else
    {
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
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required><br>

            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" required><br>

            <label for="fecha_nacimiento">Fecha de nacimiento:</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required><br>

            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" id="direccion" required><br>

            <label for="correo_electronico">Correo electrónico:</label>
            <input type="email" name="correo_electronico" id="correo_electronico" required><br>

            <label for="telefono">Teléfono:</label>
            <input type="tel" name="telefono" id="telefono" required><br>

            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" required><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena" required><br>

            <label for="contrasena2">Repetir contraseña:</label>
            <input type="password" name="contrasena2" id="contrasena2" required><br>

            <button type="submit">Registrarse</button>
        </form>
    </body>
    </html>

<?php
    }
?>