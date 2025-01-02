<?php

include '../header.php'; 

// Verificar si la sesión está activa
if (!isset($_SESSION) || empty($_SESSION) || $role != 'admin') {
  session_destroy();
  //$conn->close();
  header('Location: http://localhost/Trabajo_final_SOA/Codigo/index.php');
  exit();
}

?>