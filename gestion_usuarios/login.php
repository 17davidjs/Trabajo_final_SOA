<?php

    session_start();

    // Verificar si el formulario ha sido enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Recoger los datos del formulario
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];
        

        // Preparamos los datos en formato array
        $data = array(
            "funcion" => "login",
            "usuario" => $usuario,
            "contrasena" => $contrasena,
        );

        // Enviamos los datos al servidor.php usando cURL
        $handle = curl_init("http://localhost/Trabajo_final_SOA/servidor.php");
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data)); // Convertimos los datos a JSON
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = curl_exec($handle);

        if ($response === false)
        {
            echo "Error en la solicitud cURL: " . curl_error($handle);
            exit;
        }
        else
        {
            //echo "Respuesta del servidor: " . htmlspecialchars($response) . "<br>";
            $responseData = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE)
            {
                echo "Error al decodificar JSON: " . json_last_error_msg();
                exit;
            }

            if(isset($responseData["response"]) && $responseData["response"] == 200)
            {
                echo "Login correcto<br>";
                $_SESSION["usuario"] = $usuario;
                $_SESSION["token"] = $responseData["token"];
                //echo "Token: " . $responseData["token"];


                //
                // REDIRECCIÓN DIRECTA Y SEGURA
                //
                //header("Location: principal.php"); // Redirección segura
                //exit;
            }
            else
            {
                if (isset($responseData["texto"]))
                {
                    echo "Error en el login: " . htmlspecialchars($responseData["texto"]);
                }
                else
                {
                    echo "Error desconocido en la respuesta del servidor.";
                }
                
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
            <title>Formulario de Login</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                form {
                    max-width: 400px;
                    margin: auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                }
                label, input, button {
                    display: block;
                    width: 100%;
                    margin-bottom: 10px;
                }
                button {
                    background-color: #007BFF;
                    color: white;
                    border: none;
                    padding: 10px;
                    cursor: pointer;
                }
                button:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <h1>Formulario de Login</h1>
            <form action="login.php" method="POST">

                <label for="usuario">Usuario:</label>
                <input type="text" name="usuario" id="usuario" required><br>

                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" id="contrasena" required><br>

                <button type="submit">Iniciar sesión</button>
            </form>
        </body>
        </html>
        <?php
    }
?>