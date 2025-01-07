<?php
session_start(); // Inicia la sesión
$current_page = basename($_SERVER['PHP_SELF']);

// Verifica si el usuario está autenticado
$is_logged_in = isset($_SESSION['usuario']);
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null; // Obtén el rol del usuario
$usuario_nombre = isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'Usuario'; // Nombre del usuario autenticado
$admin_id = isset($_SESSION['usuario']['id']) ? $_SESSION['usuario']['id'] : null; // ID del usuario autenticado

// Página actual
$nombreArchivo = basename($_SERVER['SCRIPT_NAME']);

if($nombreArchivo == 'index.php') {
  $paginaActual = 'Inicio';
} else if($nombreArchivo == 'loginForm.php') {
  $paginaActual = 'Login';
} else if($nombreArchivo == 'registroForm.php') {
  $paginaActual = 'Registro';
} else if($nombreArchivo == 'vercvForm.php') {
  $paginaActual = 'Mis currículums';
} else if($nombreArchivo == 'subirCVForm.php') {
  $paginaActual = 'Subir currículums';
} else if($nombreArchivo == 'eliminarUserForm.php') {
  $paginaActual = 'Configuración';
} else if($nombreArchivo == 'curriculumView.php') {
  $paginaActual = 'Gestión de Currículums';
} else {
  $paginaActual = '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $paginaActual; ?>Sistema de Gestión de Currículums</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white py-4">
        <div class="container text-center">
            <h1 class="fw-bold">G. I. I. S. I.</h1>
            <h2>Arquitecturas Orientadas a Servicios</h2>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" href="/Trabajo_final_SOA/Codigo/index.php">Inicio</a>
                    </li>
                    <?php if (!$is_logged_in): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page == 'loginForm.php' ? 'active' : ''; ?>" href="/Trabajo_final_SOA/Codigo/vista/loginForm.php">Acceso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page == 'registroForm.php' ? 'active' : ''; ?>" href="/Trabajo_final_SOA/Codigo/vista/registroForm.php">Registro</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page == 'vercvForm.php' ? 'active' : ''; ?>" href="/Trabajo_final_SOA/Codigo/vista/curriculumView.php">Crear currículum</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page == 'vercvForm.php' ? 'active' : ''; ?>" href="/Trabajo_final_SOA/Codigo/vista/vercvForm.php">Ver mis currículums</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page == 'subirCVForm.php' ? 'active' : ''; ?>" href="/Trabajo_final_SOA/Codigo/vista/subirCVForm.php">Subir currículums</a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <?php if ($is_logged_in): ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <p class="card-text">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?> </p>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="/Trabajo_final_SOA/Codigo/vista/configuracionForm.php">Configuración</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/Trabajo_final_SOA/Codigo/vista/logout.php">Cerrar sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
