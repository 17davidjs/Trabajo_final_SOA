<?php
session_start();
session_destroy();
header("Location: ../vista/loginForm.php");
exit;
