<?php
session_start();
require_once '../modelo/db.php';

    // Verificar si el formulario ha sido enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["admin"]))
    {
        // Recoger los datos del formulario
        $nombre = htmlspecialchars(trim($_POST["nombre"]));
        $apellidos = htmlspecialchars(trim($_POST["apellidos"]));
        $fecha_nacimiento = htmlspecialchars(trim($_POST["fecha_nacimiento"]));
        $direccion = htmlspecialchars(trim($_POST["direccion"]));
        $correo_electronico = filter_var($_POST["correo_electronico"], FILTER_SANITIZE_EMAIL);
        $telefono = htmlspecialchars(trim($_POST["telefono"]));
        $usuario = htmlspecialchars(trim($_POST["usuario"]));
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
            "role" => "admin",
        );

         // Mostrar el array por pantalla
    echo '<pre>';
    print_r($data);
    echo '</pre>';

        // Enviamos los datos al servidor.php usando cURL
        $handle = curl_init("http://localhost/Trabajo_final_SOA/Codigo/modelo/servidor.php");
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
                echo "<script>
                        setTimeout(function() {
                            window.location.href = '../index.php';
                        }, 5000); // Redirige después de 5 segundos
                    </script>";
                exit;
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
        include '../vista/admin/registroAdminForm.php';
    }
?>