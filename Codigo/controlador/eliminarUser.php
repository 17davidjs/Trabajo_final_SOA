<?php
session_start();
if (isset($_SESSION["usuario"]) && isset($_SESSION["token"])) {
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
                echo "Usuario eliminado<br>";
                session_unset(); // Eliminar todas las variables de sesión
                session_destroy(); // Destruir la sesión
                echo "<a href='../vista/loginForm.php'>Volver al inicio</a>";
            } else {
                echo "Error en el eliminado: " . htmlspecialchars($responseData["texto"]);
            }
        }

        curl_close($handle);
    } else {
        include '../vista/eliminarUserForm.php';
    }
} else {
    echo "Debe iniciar sesión para eliminar su cuenta<br>";
    echo "<a href='../vista/loginForm.php'>Iniciar sesión</a>";
}
?>