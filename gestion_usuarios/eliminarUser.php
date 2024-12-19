
<?php

session_start();
if(isset($_SESSION["usuario"]) && isset($_SESSION["token"]) ){
    $data =array( //preparamos los datos en formato array
        "funcion" => "eliminarUser",
        "usuario" => $_SESSION["usuario"],
        "token" => $_SESSION["token"],
    );
    
    // Enviar los datos al servidor.php usando cURL
    $handle = curl_init("../servidor.php");
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data)); // Convertir los datos a JSON
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
    $response = curl_exec($handle);
    
    if ($response === false) {
        echo "Error en la solicitud cURL: " . curl_error($handle);
        exit;
    } else {
        echo "Respuesta del servidor: " . htmlspecialchars($response) . "<br>"; // Mostrar la respuesta del servidor
        $responseData = json_decode($response, true);

        if(isset($responseData["response"]) && $responseData["response"] == 200){
            echo "usuario eliminado<br>";
            session_unset();// Eliminar todas las variables de sesión
            session_destroy();// Destruir la sesión

            
        } else {
            echo "Error en el eliminado " ;
        }
    }
}else{
    echo "debe iniciar sesion para eliminar su cuenta<br>";
    echo "<a href='login.php'>iniciar sesion</a>";
}


?>
