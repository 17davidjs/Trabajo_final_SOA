<?php 
include '../header.php';
require_once '../../controlador/userController.php';

$controladorUsuarios = new UserController();

// Si es una petición POST para eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar']))
{
    $controladorUsuarios->delete($_POST['id']);
    exit;
}

// Mostrar lista de usuarios
$controladorUsuarios->index();
?>

<html lang="es">
<main class="container my-5">
    <h1>Gestión de Usuarios</h1>


    <a href="create.php" class="btn btn-primary">Crear Nuevo Usuario</a> 
    <br>
    <br>

    <?php if (!empty($usuarios)): ?>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Fecha de nacimiento</th>
                    <th>Direccion</th>
                    <th>Correo Electrónico</th>
                    <th>Teléfono</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['direccion']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['correo_electronico']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['role']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-info">Editar</a>
                            <button onclick="eliminarUsuario(<?php echo $usuario['id']; ?>)" class="btn btn-sm btn-danger">Eliminar</button>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <script>
            function eliminarUsuario(id) 
            {
                if (confirm('¿Está seguro de que desea eliminar este usuario?'))
                {
                    fetch('index_usuarios.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'eliminar=1&id=' + encodeURIComponent(id),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.exito)
                        {
                            location.reload();
                        }
                        else
                        {
                            alert(data.mensaje || 'No se pudo eliminar el usuario');
                        }
                    })
                }
            }
        </script>
    <?php else: ?>
        <tr>
            <br>
            <br>
            <td colspan="12">No hay usuarios registrados.</td>
        </tr>
    <?php endif; ?>
</main>
</body>
</html>