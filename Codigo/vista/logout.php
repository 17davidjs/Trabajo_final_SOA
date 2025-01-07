<?php
session_start();
$_SESSION = [];
session_destroy();
header("Location: ../vista/loginForm.php");
exit;
