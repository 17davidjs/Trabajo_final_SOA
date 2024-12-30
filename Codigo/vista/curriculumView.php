<?php include 'header.php'; ?>

<main class="container my-5">
    <h1>Gestión de Currículums</h1>

    <!-- Formulario para agregar un nuevo currículum -->
    <h2>Agregar Nuevo Currículum</h2>
    <form action="../controlador/curriculumController.php" method="POST">
        <input type="hidden" name="action" value="add">
        <div class="mb-3">
            <label for="usuario_id" class="form-label">Usuario ID</label>
            <input type="text" class="form-control" id="usuario_id" name="usuario_id" value="0" >
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" required>
        </div>
        <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" required>
        </div>
        <div class="mb-3">
            <label for="correo_electronico" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Currículum</button>
    </form>

    <!-- Mostrar la lista de currículums -->
    <?php if (!empty($curriculums) && is_array($curriculums)): ?>
        <table class="table table-striped mt-5">
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
                    <th>Acciones</th>
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
                        <td>
                            <form action="../controlador/curriculumController.php" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?php echo $curriculum['id']; ?>">
                                <input type="hidden" name="usuario_id" value="<?php echo $curriculum['usuario_id']; ?>">
                                <button type="submit" class="btn btn-warning btn-sm">Editar</button>
                            </form>
                            <form action="../controlador/curriculumController.php" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $curriculum['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este currículum?');">Eliminar</button>
                            </form>
                        </td>
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
