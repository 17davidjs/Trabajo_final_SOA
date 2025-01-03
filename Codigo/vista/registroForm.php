<?php 
include 'header.php'; 
?>

    <main class="container my-5">
        <h1>Formulario de Registro</h1>
        <form action="../controlador/registro.php" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos:</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento:</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" name="direccion" id="direccion" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="correo_electronico" class="form-label">Correo electrónico:</label>
                <input type="email" name="correo_electronico" id="correo_electronico" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="tel" name="telefono" id="telefono" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario:</label>
                <input type="text" name="usuario" id="usuario" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña:</label>
                <input type="password" name="contrasena" id="contrasena" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contrasena2" class="form-label">Repetir contraseña:</label>
                <input type="password" name="contrasena2" id="contrasena2" class="form-control" required>
            </div>
            <p>¿Ya tienes una cuenta? <a href="/Trabajo_final_SOA/Codigo/vista/loginForm.php">Inicia sesión</a></p>
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
    </main>


    </body>
</html>