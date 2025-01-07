<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$apiUrl = 'http://localhost/Trabajo_final_SOA/Codigo/modelo/servidor.php';


// Función para hacer solicitudes a la API REST usando cURL en formato simplificado
function callAPI($data) {
    $handle = curl_init($GLOBALS['apiUrl']);
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
// Obtener todos los currículums
$data = ['funcion' => 'getAllCurriculums'];
$curriculums = callAPI($data);

// Manejar las solicitudes del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $data = [];
        switch ($_POST['action']) {
            case 'add':
                $data = [
                    'funcion' => 'addCurriculum',
                    'usuario_id' => $_POST['usuario_id'],
                    'nombre' => $_POST['nombre'],
                    'apellidos' => $_POST['apellidos'],
                    'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                    'direccion' => $_POST['direccion'],
                    'correo_electronico' => $_POST['correo_electronico'],
                    'telefono' => $_POST['telefono']
                ];
                break;
            case 'update':
                $data = [
                    'funcion' => 'updateCurriculum',
                    'id' => $_POST['id'],
                    'nombre' => $_POST['nombre'],
                    'apellidos' => $_POST['apellidos'],
                    'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                    'direccion' => $_POST['direccion'],
                    'correo_electronico' => $_POST['correo_electronico'],
                    'telefono' => $_POST['telefono']
                ];
                break;
            case 'delete':
                $data = [
                    'funcion' => 'deleteCurriculum',
                    'id' => $_POST['id']
                ];
                break;
        }

        

        $response = callAPI($data);

    }
}

// Obtener todos los currículums
$data = ['funcion' => 'getAllCurriculums'];
$curriculums = callAPI($data);



// Incluir la vista y pasar los currículums
include '../vista/curriculumForm.php';
?>
