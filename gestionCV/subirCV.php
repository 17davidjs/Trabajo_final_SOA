<?php
session_start();
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
            $handle = curl_init("../servidor.php");
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
        echo $_SESSION["token"] ."<br>";
        // Mostrar el formulario HTML
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Subir Fichero</title>
        </head>
        <body>
            <h1>Subir Fichero</h1>
            <form action="subirCV.php" method="POST" enctype="multipart/form-data">
                <label for="fichero">Selecciona un fichero (CSV, XML, JSON):</label>
                <input type="file" name="fichero" id="fichero" accept=".csv,.xml,.json" required><br><br>
                <button type="submit">Subir Fichero</button>
            </form>
        </body>
        </html>
        <?php
    }

}else{
    echo "debe iniciar sesion para subir su CV<br>";
    echo "<a href='../login/login.php'>iniciar sesion</a>";
}
?>
