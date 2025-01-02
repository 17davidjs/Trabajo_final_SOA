<?php 
include 'header.php'; 
?>

    
<main class="container my-5">
    <h1>Formulario de Login</h1>
    <form action="../controlador/login.php" method="POST">
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuario:</label>
            <input type="text" name="usuario" id="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="contraseña" class="form-label">Contraseña:</label>
            <input type="password" name="contraseña" id="contraseña" class="form-control" required>
        </div>
        <p>¿No tienes una cuenta? <a href="/Trabajo_final_SOA/Codigo/vista/registroForm.php">Regístrate aquí</a></p>
        <button type="submit" class="btn btn-primary">Iniciar sesión</button>
    </form>
</main>


</body>
</html>