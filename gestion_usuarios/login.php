<?php

session_start();

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $usuario = $_POST["usuario"];
    $contraseña = $_POST["contraseña"];
    

    // Preparamos los datos en formato array
    $data = array(
        "funcion" => "login",
        "usuario" => $usuario,
        "contraseña" => $contraseña,
    );

    // Enviamos los datos al servidor.php usando cURL
    $handle = curl_init("../servidor.php");
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data)); // Convertimos los datos a JSON
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $response = curl_exec($handle);

    if ($response === false) {
        echo "Error en la solicitud cURL: " . curl_error($handle);
        exit;
    } else {
        //echo "Respuesta del servidor: " . htmlspecialchars($response) . "<br>";
        $responseData = json_decode($response, true);

        if(isset($responseData["response"]) && $responseData["response"] == 200){
            echo "Login correcto<br>";
            $_SESSION["usuario"] = $usuario;
            $_SESSION["token"] = $responseData["token"];
            //echo "Token: " . $responseData["token"];
        } else {
            echo "Error en el login: " . $responseData["texto"];
        }
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
        <h1>Formulario de Login</h1>
        <form action="login.php" method="POST">

            <label for="usuario">usuario:</label>
            <input type="text" name="usuario" id="usuario" required><br>

            <label for="contraseña">contraseña:</label>
            <input type="password" name="contraseña" id="contraseña" required><br>

            <button type="submit">Enviar</button>
        </form>
    </body>
    </html>
    <?php
}
?>
