<?php 
include 'header.php'; 


// Simulación de datos del usuario para el ejemplo
$nombre = isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'Juan';
$apellidos = isset($_SESSION['usuario']['apellidos']) ? $_SESSION['usuario']['apellidos'] : 'Pérez';
$fecha_nacimiento = isset($_SESSION['usuario']['fecha_nacimiento']) ? $_SESSION['usuario']['fecha_nacimiento'] : '1990-01-01';
$direccion = isset($_SESSION['usuario']['direccion']) ? $_SESSION['usuario']['direccion'] : 'Calle Falsa 123';
$correo_electronico = isset($_SESSION['usuario']['correo_electronico']) ? $_SESSION['usuario']['correo_electronico'] : 'juan.perez@example.com';
$telefono = isset($_SESSION['usuario']['telefono']) ? $_SESSION['usuario']['telefono'] : '123456789';
$usuario = isset($_SESSION['usuario']['usuario']) ? $_SESSION['usuario']['usuario'] : 'juan123';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Cuenta y Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container my-5">
        <h3>Mi perfil:</h3>
        <br>
        <form class="perfil-formulario" method="post" action="../controlador/actualizarPerfil.php">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control mb-3" value="<?php echo htmlspecialchars($nombre); ?>" required>

            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" class="form-control mb-3" value="<?php echo htmlspecialchars($apellidos); ?>" required>

            <label for="usuario">Nombre Usuario:</label>
            <input type="text" id="usuario" name="usuario" class="form-control mb-3" value="<?php echo htmlspecialchars($usuario); ?>" readonly>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control mb-3" value="<?php echo htmlspecialchars($fecha_nacimiento); ?>" required>

            <label for="correo_electronico">Correo Electrónico:</label>
            <input type="email" id="correo_electronico" name="correo_electronico" class="form-control mb-3" value="<?php echo htmlspecialchars($correo_electronico); ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" class="form-control mb-3" value="<?php echo htmlspecialchars($telefono); ?>" required>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" class="form-control mb-3" value="<?php echo htmlspecialchars($direccion); ?>" required>
            

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>

        <br><hr><br>

        <h4>Cambiar Contraseña:</h4>
        <form method="post">
            <label for="contrasena-actual">Contraseña Actual:</label>
            <input type="password" name="contrasena-actual" class="form-control mb-3" placeholder="Introduce tu contraseña actual">
            
            <label for="nueva-contrasena">Nueva Contraseña:</label>
            <input type="password" name="nueva-contrasena" class="form-control mb-3" placeholder="Introduce tu nueva contraseña">
            
            <label for="confirmar-contrasena">Confirmar Nueva Contraseña:</label>
            <input type="password" name="confirmar-contrasena" class="form-control mb-3" placeholder="Confirma tu nueva contraseña">
            
            <button class="btn btn-primary" type="submit">Cambiar Contraseña</button>
        </form>

        <br><hr><br>
        <h1>Eliminar Cuenta</h1>
        <p>¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.</p>
        <form id="eliminarCuentaForm" action="../controlador/eliminarUser.php" method="POST" onsubmit="return confirmarEliminacion();">
            <button type="submit" class="btn btn-danger">Eliminar Cuenta</button>
            <a href="../../index.php" class="btn btn-secondary">Cancelar</a>
        </form>


    </main>

    <script>
    function confirmarEliminacion() {
        return confirm("¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.");
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
