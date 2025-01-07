<?php 
include 'header.php';
require_once '../controlador/configuracion.php';

$configurador = new configuracion();


if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (isset($_POST['eliminar']))
    {
        $configurador->delete($_POST['id']);
        exit;
    }
    else if (isset($_POST['cambio-contrasena']))
    {
        $configurador->cambiar_contrasena($_POST);
        exit;
    }
    else
    {
        $configurador->editar($_POST);
        exit;
    }
}


if (isset($_SESSION['id'])){
    $id = $_SESSION['id'];

    if (!$id)
    {
        header("Location: ../index.php");
        exit;
    }
    else $usuario = $configurador->obtenerusuario($id);

    if (!$usuario)
    {
        header("Location: ../index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <body>
        <main class="container my-5">
            <h2>Mi perfil:</h2>
            <br>
            <form method="POST" action="configuracionForm.php">
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
                <br>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>

            <br><hr><br>

            <h2>Cambiar Contraseña:</h2>
            <form method="POST" action="configuracionForm.php">
                <label for="contrasena-actual">Contraseña Actual:</label>
                <input type="password" name="contrasena-actual" class="form-control mb-3" placeholder="Introduce tu contraseña actual">
                
                <label for="nueva-contrasena">Nueva Contraseña:</label>
                <input type="password" name="nueva-contrasena" class="form-control mb-3" placeholder="Introduce tu nueva contraseña">
                
                <label for="confirmar-contrasena">Confirmar Nueva Contraseña:</label>
                <input type="password" name="confirmar-contrasena" class="form-control mb-3" placeholder="Confirma tu nueva contraseña">
                
                <button class="btn btn-primary" type="submit" name="cambio-contrasena">Cambiar Contraseña</button>
            </form>

            <br><hr><br>
             
            <h2>Eliminar Cuenta</h2>
            <p>¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.</p>
            <button onclick="confirmarEliminacion(<?php echo $usuario['id']; ?>)" class="btn btn-sm btn-danger">Eliminar Cuenta</button>

            <script>
            function confirmarEliminacion(id)
            {
                if (confirm('¿Está seguro de que desea eliminar este usuario?'))
                {
                    fetch('configuracionForm.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
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
                            window.location.href = '../index.php';
                        }
                        else
                        {
                            alert(data.mensaje || 'No se pudo eliminar el usuario');
                        }
                    })
                }
            }
        </script>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>