<?php include 'header.php'; ?>

<main class="container my-5">
    <h1>Lista de Currículums</h1>
    <?php if (!empty($curriculums)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Dirección</th>
                    <th>Correo Electrónico</th>
                    <th>Teléfono</th>
                    <th>Fecha del Currículum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($curriculums as $curriculum): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($curriculum['id']); ?></td>
                        <td><?php echo htmlspecialchars($curriculum['usuario_id']); ?></td>
                        <td><?php echo htmlspecialchars($curriculum['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($curriculum['apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($curriculum['fecha_nacimiento']); ?></td>
                        <td><?php echo htmlspecialchars($curriculum['direccion']); ?></td>
                        <td><?php echo htmlspecialchars($curriculum['correo_electronico']); ?></td>
                        <td><?php echo htmlspecialchars($curriculum['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($curriculum['fecha_curriculum']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron currículums.</p>
    <?php endif; ?>
</main>

    
</body>
</html>