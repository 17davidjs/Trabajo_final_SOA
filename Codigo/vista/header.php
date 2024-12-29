<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G.I.I.S.I. - Sistema de Gestión de Currículums</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <a class="navbar-brand" href="#">Sistema de Gestión</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <?php
                    $current_page = basename($_SERVER['PHP_SELF']);
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" href="/Trabajo_final_SOA/Codigo/index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'loginForm.php' ? 'active' : ''; ?>" href="/Trabajo_final_SOA/Codigo/vista/loginForm.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'vercvForm.php' ? 'active' : ''; ?>" href="/Trabajo_final_SOA/Codigo/vista/vercvForm.php">Gestión de Currículums</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'eliminarUserForm.php' ? 'active' : ''; ?>" href="/Trabajo_final_SOA/Codigo/vista/eliminarUserForm.php">Eliminar cuenta</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>