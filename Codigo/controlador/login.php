<?php
session_start();
require_once '../config/db.php';

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    global $conn;

    $sql = "SELECT id FROM usuarios WHERE usuario = '$usuario'";
    $result = $conn->query($sql);

    if (!$result)
    {
        http_response_code(500);
        echo json_encode(array("response" => 500, "texto" => "Error en la consulta: " . $conn->error));
        exit;
    }

    if ($result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc())
        {
            $ids = (int)$row['id'];
        }
    }

    $_SESSION['id'] = $ids;

    // Preparamos los datos en formato array
    $data = array(
        "funcion" => "login",
        "usuario" => $usuario,
        "contrasena" => $contrasena,
    );

    // Enviamos los datos al servidor.php usando cURL
    $handle = curl_init("http://localhost/Trabajo_final_SOA/Codigo/modelo/servidor.php");
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data)); // Convertimos los datos a JSON
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $response = curl_exec($handle);

    if ($response === false) {
        echo "Error en la solicitud cURL: " . curl_error($handle);
        exit;
    } else {
        $responseData = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error al decodificar JSON: " . json_last_error_msg();
            exit;
        }

        // Verificar si el login fue correcto
        if (isset($responseData["response"]) && $responseData["response"] == 200) {
            $_SESSION["usuario"] = $usuario;
            $_SESSION["token"] = $responseData["token"];
            $_SESSION["role"] = $responseData["role"];  // Guardamos el rol (user/admin)

            // Redirección segura
            header("Location: ../index.php");
            exit;
        } else {
            if (isset($responseData["texto"])) {
                echo "Error en el login: " . htmlspecialchars($responseData["texto"]);
            } else {
                echo "Error desconocido en la respuesta del servidor.";
            }
        }
    }

    curl_close($handle);
} else {
    // Redirigir al formulario de login si no es una solicitud POST
    header("Location: ../vista/loginForm.php");
    exit;
}
