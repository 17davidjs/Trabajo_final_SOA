<?php 
include '../header.php';
require_once '../../controlador/userController.php';

$controladorUsuarios = new userController();

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $controladorUsuarios->edit($_POST);
}
else
{
    $id = $_GET['id'] ?? null;

    if (!$id)
    {
        header("Location: index_usuarios.php");
        exit;
    }

    $usuario = $controladorUsuarios->obtenerUsuario($id);

    if (!$usuario)
    {
        header("Location: index_usuarios.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <body>
        <main>
            <div class="container mt-4">
                <h2>Editar Usuario</h2>
                <form method="POST" action="edit.php">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario['id']); ?>">
                    
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Apellidos</label>
                        <input type="text" class="form-control" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" class="form-control" name="fecha_nacimiento" value="<?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" class="form-control" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" class="form-control" name="correo_electronico" value="<?php echo htmlspecialchars($usuario['correo_electronico']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="tel" class="form-control" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" class="form-control" name="usuario" value="<?php echo htmlspecialchars($usuario['usuario']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Rol</label>
                        <select class="form-control" name="role" required>
                            <option value="admin" <?php echo $usuario['role'] === 'admin' ? 'selected' : ''; ?>>
                                Administrador
                            </option>
                            <option value="user" <?php echo $usuario['role'] === 'user' ? 'selected' : ''; ?>>
                                Usuario
                            </option>
                        </select>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="index_usuarios.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </main>
    </body>
</html>