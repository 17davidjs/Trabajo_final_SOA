<?php
include '../header.php';
require_once '../../controlador/userController.php';

$controladorUsuarios = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $controladorUsuarios->create($_POST);
}
?>

<!DOCTYPE html>
<html lang="es">
    <body>
        <main class="container my-5">
            <h1>Crear Usuario</h1>
            <form method="POST" action="create.php">
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" required title="Introduzca su nombre">
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellidos:</label>
                    <input type="text" class="form-control" name="apellidos" required title="Introduzca sus apellidos">
                </div>
                <div class="mb-3">
                    <label class="form-label">Fecha de nacimiento:</label>
                    <input type="date" class="form-control" name="fecha_nacimiento" required title="Introduzca su fecha de nacimiento o pulse en el calendario a la derecha">
                </div>
                <div class="mb-3">
                    <label class="form-label">Dirección:</label>
                    <input type="text" class="form-control" name="direccion" required title="Introduzca su dirección de resicencia completa">
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo electrónico:</label>
                    <input type="email" class="form-control" name="correo_electronico" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required title="Introduzca su correo electrónico personal">
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono:</label>
                    <input type="tel" class="form-control" name="telefono" required title="Introduzca su número de teléfono">
                </div>
                <div class="mb-3">
                    <label class="form-label">Usuario:</label>
                    <input type="text" class="form-control" name="usuario" required title="Introduzca su nombre de usuario">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" name="contrasena" required title="Introduzca su contraseña">
                </div>
                <div class="mb-3">
                    <label class="form-label">Rol:</label>
                    <select class="form-control" name="role" required>
                        <option value="user">Usuario</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Crear</button>
            </form>
        </main>
    </body>
</html>