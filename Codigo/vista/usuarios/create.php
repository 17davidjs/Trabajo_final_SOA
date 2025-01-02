<?php include '../header.php'; ?>

<main class="container my-5">
    <h1>Crear Usuario</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" class="form-control" name="nombre" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellidos:</label>
            <input type="text" class="form-control" name="apellidos" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de nacimiento:</label>
            <input type="date" class="form-control" name="fecha_nacimiento" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Dirección:</label>
            <input type="text" class="form-control" name="direccion" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo electrónico:</label>
            <input type="email" class="form-control" name="correo_electronico" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Teléfono:</label>
            <input type="tel" class="form-control" name="telefono" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Usuario:</label>
            <input type="text" class="form-control" name="usuario" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña:</label>
            <input type="password" class="form-control" name="contrasena" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role:</label>
            <input type="text" class="form-control" name="role" required>
        </div>
        <button type="submit" class="btn btn-primary">Crear</button>
    </form>
</main>
</body>
</html>