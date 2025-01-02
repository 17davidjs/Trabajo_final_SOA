<?php include '../header.php'; ?>

<main class="container my-5">
    <h1>Gestión de Usuarios</h1>
    <a href="create.php" class="btn btn-primary">Crear Nuevo Usuario</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Fecha de nacimiento</th>
                <th>Direccion</th>
                <th>Correo Electrónico</th>
                <th>Teléfono</th>
                <th>Usuario</th>
                <th>Contraseña</th>
                <th>Token</th>
                <th>Role</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id']; ?></td>
                        <td><?= $user['Nombre']; ?></td>
                        <td><?= $user['apellidos']; ?></td>
                        <td><?= $user['fecha_nacimiento']; ?></td>
                        <td><?= $user['direccion']; ?></td>
                        <td><?= $user['correo_electronico']; ?></td>
                        <td><?= $user['telefono']; ?></td>
                        <td><?= $user['usuario']; ?></td>
                        <td><?= $user['contrasena']; ?></td>
                        <td><?= $user['token']; ?></td>
                        <td><?= $user['role']; ?></td>
                        <td>
                            <a href="edit.php<?= $user['id']; ?>" class="btn btn-primary">Editar</a>
                            <a href="delete.php<?= $user['id']; ?>" class="btn btn-primary">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="12">No hay usuarios registrados.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>