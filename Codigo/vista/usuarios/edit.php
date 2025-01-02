<?php include '../header.php'; ?>

<main class="container my-5">
    <h1>Editar Usuario</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre" value="<?= $user['nombre']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellidos:</label>
            <input type="text" class="form-control" name="apellidos" value="<?= $user['apellidos']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de nacimiento:</label>
            <input type="date" class="form-control" name="fecha_nacimiento" value="<?= $user['fecha_nacimiento']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Dirección:</label>
            <input type="text" class="form-control" name="direccion" value="<?= $user['direccion']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo electrónico:</label>
            <input type="email"class="form-control" name="correo_electronico" value="<?= $user['correo_electronico']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Teléfono:</label>
            <input type="tel"class="form-control" name="telefono" value="<?= $user['telefono']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Usuario:</label>
            <input type="text"class="form-control" name="usuario" value="<?= $user['usuario']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña:</label>
            <input type="password"class="form-control" name="contrasena" value="<?= $user['contrasena']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role:</label>
            <input type="text"class="form-control" name="role" value="<?= $user['role']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </form>
</main>
</body>
</html>