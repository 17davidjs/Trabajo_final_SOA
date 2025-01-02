<?php
session_start();
require_once '../config/db.php';

if(isset($_SESSION["usuario"]) && isset($_SESSION["token"]) ){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar si se ha enviado un archivo
        if (isset($_FILES['fichero']) && $_FILES['fichero']['error'] == UPLOAD_ERR_OK) {
            // Obtener información del archivo
            $archivoTmp = $_FILES['fichero']['tmp_name'];
            $nombreArchivo = $_FILES['fichero']['name'];
            $tipoArchivo = $_FILES['fichero']['type'];
    
            // Leer el contenido del archivo
            $contenidoArchivo = file_get_contents($archivoTmp);
    
            // Preparar los datos para enviar al servidor
            $data = array(
                'funcion' => 'subirFichero',
                'nombreArchivo' => $nombreArchivo,
                'tipoArchivo' => $tipoArchivo,
                'fichero' => base64_encode($contenidoArchivo), // Codificar en base64
                'token' => $_SESSION["token"],
                'usuario' => $_SESSION["usuario"]
            );
    
            // Configurar cURL para enviar los datos
            $handle = curl_init("http://localhost/Trabajo_final_SOA/Codigo/modelo/servidor.php");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
            // Ejecutar la solicitud y manejar la respuesta
            $response = curl_exec($handle);
    
            if ($response === false) {
                echo "Error en la solicitud cURL: " . curl_error($handle);
            } else {
                echo "Respuesta del servidor: " . $response . "<br>";
            }
    
            curl_close($handle);
        } else {
            echo "Error al subir el archivo. Por favor, inténtalo de nuevo.";
        }
    } else {
        include '../vista/subirCVForm.php';
    }
} else {
    echo "debe iniciar sesion para subir su CV<br>";
    echo "<a href='../login/login.php'>iniciar sesion</a>";
}
?>