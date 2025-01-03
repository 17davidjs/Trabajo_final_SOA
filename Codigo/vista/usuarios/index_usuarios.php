<?php include '../header.php'; ?>

<main class="container my-5">
    <h1>Gestión de Usuarios</h1>
    <a href="create.php" class="btn btn-primary">Crear Nuevo Usuario</a>
    <!-- Depuración: verifica que `$users` tiene datos -->
    <pre><?php print_r($users); ?></pre>
    <?php if (!empty($users)): ?>
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
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']); ?></td>
                        <td><?= htmlspecialchars($user['nombre']); ?></td>
                        <td><?= htmlspecialchars($user['apellidos']); ?></td>
                        <td><?= htmlspecialchars($user['fecha_nacimiento']); ?></td>
                        <td><?= htmlspecialchars($user['direccion']); ?></td>
                        <td><?= htmlspecialchars($user['correo_electronico']); ?></td>
                        <td><?= htmlspecialchars($user['telefono']); ?></td>
                        <td><?= htmlspecialchars($user['usuario']); ?></td>
                        <td><?= htmlspecialchars($user['contrasena']); ?></td>
                        <td><?= htmlspecialchars($user['token']); ?></td>
                        <td><?= htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a href="edit.php<?= htmlspecialchars($user['id']); ?>" class="btn btn-primary">Editar</a>
                            <a href="delete.php<?= htmlspecialchars($user['id']); ?>" class="btn btn-primary">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <br>
                <br>
                <td colspan="12">No hay usuarios registrados.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>