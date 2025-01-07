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

    // Eliminar usuario (utiliza deleteUser del servidor.php)
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

    // Cambiar la contraseña del usuario (utiliza cambiarContrasena del servidor.php)
    public function cambiar_contrasena($datos)
    {
        if ($datos['nueva-contrasena'] !== $datos['confirmar-contrasena'])
        {
            echo "Error: Las contraseñas no coinciden.";
            return;
        }

        $data = [
            'funcion' => 'cambiarContrasena',
            'usuario' => $_SESSION['usuario'],
            'token' => $_SESSION['token'],
            'contrasena-actual' => $datos['contrasena-actual'],
            'nueva-contrasena' => $datos['nueva-contrasena']
        ];

        $response = $this->callAPI($data);

        if ($response['response'] === 200)
        {
            header("Location: ../index.php");
        }
        else
        {
            echo "Error: " . $response['texto'];
        }
    }
}