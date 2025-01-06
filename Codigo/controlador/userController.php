<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class userController
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

    // Mostrar listado de usuarios (utiliza getAllUsers del server.php)
    public function index()
    {
        try
        {
            // Llamar a la API para obtener todos los usuarios
            $usuarioss = $this->callAPI(['funcion' => 'getAllUsers']);

            if (empty($usuarioss))
            {
                throw new Exception("No se encontraron usuarios.");
            }

            global $usuarios;

            $usuarios = $usuarioss;

            require_once __DIR__ . '/../vista/usuarios/index_usuarios.php';

            
        }
        catch (Exception $e)
        {
            echo "Error: " . $e->getMessage();
        }
    }

    // Obtener usuario por ID (utiliza getUserById del server.php)
    public function obtenerUsuario($id)
    {
        try
        {
            $response = $this->callAPI(['funcion' => 'getUserById', 'id' => $id]);

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

    // Crear nuevo usuario (utiliza createUser del server.php)
    public function create($datos)
    {
        try
        {
            // Validar correo electrónico
            if (!filter_var($datos['correo_electronico'], FILTER_VALIDATE_EMAIL))
            {
                throw new Exception("Correo electrónico inválido");
            }

            $datos['funcion'] = 'createUser';
            $response = $this->callAPI($datos);

            if ($response['response'] === 201)
            {
                header("Location: ../usuarios/index_usuarios.php");
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

    // Actualizar usuario (utiliza updateUser del server.php)
    public function edit($datos)
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
                header("Location: ../usuarios/index_usuarios.php");
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
?>