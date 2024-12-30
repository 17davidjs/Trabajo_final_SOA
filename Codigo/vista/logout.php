<?php
session_start();
session_destroy();
header("Location: /Trabajo_final_SOA/Codigo/vista/loginForm.php");
exit;
