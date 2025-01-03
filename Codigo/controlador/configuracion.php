<?php
session_start();

// Depuración inicial
//var_dump($_SESSION);
//var_dump($_POST);


if (isset($_SESSION["usuario"]) && isset($_SESSION["token"]) && isset($_POST["eliminarUser"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = array(
            "funcion" => "eliminarUser",
            "usuario" => $_SESSION["usuario"],
            "token" => $_SESSION["token"],
        );

        // Enviar los datos al servidor.php usando cURL
        $handle = curl_init("http://localhost/Trabajo_final_SOA/Codigo/modelo/servidor.php");
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data)); // Convertir los datos a JSON
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($handle);

        if ($response === false) {
            echo "Error en la solicitud cURL: " . curl_error($handle);
            exit;
        } else {
            $responseData = json_decode($response, true);

            if (isset($responseData["response"]) && $responseData["response"] == 200) {
                echo "Usuario eliminado correctamente.<br>";
                session_unset(); 
                session_destroy();
                header("Location: ../vista/loginForm.php"); // Redirige al inicio de sesión
                exit;
            } else if (isset($responseData["texto"])) {
                echo "Error en el eliminado: " . htmlspecialchars($responseData["texto"]) . "<br>";
                echo "<a href='../vista/eliminarUserForm.php'>Intentar de nuevo</a>";
            } else {
                echo "Error desconocido. Por favor, intenta nuevamente.<br>";
            }
        }

        curl_close($handle);
    } else {
        include '../vista/configuracion.php';
    }
} 




if (isset($_SESSION["usuario"]) && isset($_SESSION["token"]) && isset($_POST['cambio-contraseña'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $contraseña = $_POST["contrasena-actual"];
        $contraseña1 = $_POST["nueva-contrasena"];
        $contraseña2 = $_POST["confirmar-contrasena"];

        if ($contraseña1 !== $contraseña2) {
            echo "Error: Las contraseñas no coinciden.";
            exit;
        }

        $data = array(
            "funcion" => "cambiarContraseña",
            "usuario" => $_SESSION["usuario"],
            "token" => $_SESSION["token"],
            "contraseña-actual" => $contraseña,
            "nueva-contraseña" => $contraseña1,
        );

        // Enviar los datos al servidor.php usando cURL
        $handle = curl_init("http://localhost/Trabajo_final_SOA/Codigo/modelo/servidor.php");
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data)); // Convertir los datos a JSON
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($handle);

        if ($response === false) {
            echo "Error en la solicitud cURL: " . curl_error($handle);
            exit;
        } else {
            $responseData = json_decode($response, true);

            if (isset($responseData["response"]) && $responseData["response"] == 200) {
                echo "Contraseña midificada correctamente.<br>";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = '../index.php';
                        }, 5000); // Redirige después de 5 segundos
                    </script>";
            } else if (isset($responseData["texto"])) {
                echo "Error en la modificación: " . htmlspecialchars($responseData["texto"]) . "<br>";
                echo "<a href='../vista/configuracion.php'>Intentar de nuevo</a>";
            } else {
                echo "Error desconocido. Por favor, intenta nuevamente.<br>";
            }
            
        }

        curl_close($handle);
    } else {
        include '../vista/configuracion.php';
    }
} 
?>