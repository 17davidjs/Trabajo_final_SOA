<?php
    // CONEXIÓN CON LA BASE DE DATOS
    // ARCHIVO AISLADO PARA REQUERIRLO SIEMPRE EN LOS DEMÁS ARCHIVOS Y NO TENER QUE REPETIRLO TODO EL TIEMPO

    $host = "localhost";
    $db = "soa_final";           // nombre de la base de datos
    $user = "root";         // usuario de MySQL
    $pass = "";         // contraseña de MySQL

    $conn = new mysqli($host, $user, $pass, $db);

        // Comprobar la conexión
        if ($conn->connect_error)
        {
            die("Conexión fallida: " . $conn->connect_error);
        }
?>