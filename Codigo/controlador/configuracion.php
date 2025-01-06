<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class configuracion
{
    private $conn;
    private $apiUrl = 'http://localhost/Trabajo_final_SOA/Codigo/modelo/servidor.php';

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // Función para hacer solicitudes a la API REST usando cURL en formato simplificado
    private function callAPI($data)
    {
        $handle = curl_init($this->apiUrl);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data)); // Convertimos los datos a JSON
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($handle);

        if ($response === false) {
            echo "Error en la solicitud cURL: " . curl_error($handle);
            exit;
        }

        curl_close($handle);

        $responseData = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error al decodificar JSON: " . json_last_error_msg();
            exit;
        }

        return $responseData;
    }


    // Obtener usuario por ID (utiliza obtenerUserById del servidor.php)
    public function obtenerusuario($id)
    {
        try
        {
            $response = $this->callAPI(['funcion' => 'obtenerUserById', 'id' => $id]);

            if (isset($response[0]))
            {
                return $response[0];
            }
            else
            {
                throw new Exception("Usuario no encontrado");
            }
        }
        catch (Exception $e)
        {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    // Actualizar usuario (utiliza updateUser del servidor.php)
    public function editar($datos)
    {
        try
        {
            if (!filter_var($datos['correo_electronico'], FILTER_VALIDATE_EMAIL))
            {
                throw new Exception("Correo electrónico inválido");
            }

            $datos['funcion'] = 'updateUser';

            $response = $this->callAPI($datos);

            if ($response['response'] === 200)
            {
                header("Location: ../vista/configuracionForm.php");
            }
            else
            {
                throw new Exception($response['texto']);
            }
        }
        catch (Exception $e)
        {
            echo "Error: " . $e->getMessage();
        }
    }

    // Eliminar usuario (utiliza deleteUser del server.php)
    public function delete($id)
    {
        try
        {
            $response = $this->callAPI(['funcion' => 'deleteUser','id' => $id]);

            if ($response['response'] === 200)
            {
                header("Location: ../index.php");
            }
            else
            {
                throw new Exception($response['texto']);
            }
        }
        catch (Exception $e)
        {
            echo "Error: " . $e->getMessage();
        }
    }
}






































// if (isset($_SESSION["usuario"]) && isset($_SESSION["token"]) && isset($_POST["eliminarUser"])) {
//     if ($_SERVER["REQUEST_METHOD"] == "POST") {
//         $data = array(
//             "funcion" => "eliminarUser",
//             "usuario" => $_SESSION["usuario"],
//             "token" => $_SESSION["token"],
//         );

//         // Enviar los datos al servidor.php usando cURL
//         $handle = curl_init("http://localhost/Trabajo_final_SOA/Codigo/modelo/servidor.php");
//         curl_setopt($handle, CURLOPT_POST, true);
//         curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data)); // Convertir los datos a JSON
//         curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

//         $response = curl_exec($handle);

//         if ($response === false) {
//             echo "Error en la solicitud cURL: " . curl_error($handle);
//             exit;
//         } else {
//             $responseData = json_decode($response, true);

//             if (isset($responseData["response"]) && $responseData["response"] == 200) {
//                 echo "Usuario eliminado correctamente.<br>";
//                 session_unset(); 
//                 session_destroy();
//                 header("Location: ../vista/loginForm.php"); // Redirige al inicio de sesión
//                 exit;
//             } else if (isset($responseData["texto"])) {
//                 echo "Error en el eliminado: " . htmlspecialchars($responseData["texto"]) . "<br>";
//                 echo "<a href='../vista/eliminarUserForm.php'>Intentar de nuevo</a>";
//             } else {
//                 echo "Error desconocido. Por favor, intenta nuevamente.<br>";
//             }
//         }

//         curl_close($handle);
//     } else {
//         include '../vista/configuracion.php';
//     }
// } 

// if (isset($_SESSION["usuario"]) && isset($_SESSION["token"]) && isset($_POST['cambio-contrasena'])) {
//     if ($_SERVER["REQUEST_METHOD"] == "POST") {

//         $contrasena = $_POST["contrasena-actual"];
//         $contrasena1 = $_POST["nueva-contrasena"];
//         $contrasena2 = $_POST["confirmar-contrasena"];

//         if ($contrasena1 !== $contrasena2) {
//             echo "Error: Las contraseñas no coinciden.";
//             exit;
//         }

//         $data = array(
//             "funcion" => "cambiarcontrasena",
//             "usuario" => $_SESSION["usuario"],
//             "token" => $_SESSION["token"],
//             "contrasena-actual" => $contrasena,
//             "nueva-contrasena" => $contrasena1,
//         );

//         // Enviar los datos al servidor.php usando cURL
//         $handle = curl_init("http://localhost/Trabajo_final_SOA/Codigo/modelo/servidor.php");
//         curl_setopt($handle, CURLOPT_POST, true);
//         curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data)); // Convertir los datos a JSON
//         curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

//         $response = curl_exec($handle);

//         if ($response === false) {
//             echo "Error en la solicitud cURL: " . curl_error($handle);
//             exit;
//         } else {
//             $responseData = json_decode($response, true);

//             if (isset($responseData["response"]) && $responseData["response"] == 200) {
//                 echo "Contraseña midificada correctamente.<br>";
//                 echo "<script>
//                         setTimeout(function() {
//                             window.location.href = '../index.php';
//                         }, 5000); // Redirige después de 5 segundos
//                     </script>";
//             } else if (isset($responseData["texto"])) {
//                 echo "Error en la modificación: " . htmlspecialchars($responseData["texto"]) . "<br>";
//                 echo "<a href='../vista/configuracion.php'>Intentar de nuevo</a>";
//             } else {
//                 echo "Error desconocido. Por favor, intenta nuevamente.<br>";
//             }
            
//         }

//         curl_close($handle);
//     } else {
//         include '../vista/configuracion.php';
//     }
// } 

?>