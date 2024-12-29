<?php include 'header.php'; ?>

<main class="container my-5">
    <h1>Eliminar Cuenta</h1>
    <p>¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.</p>
    <form id="eliminarCuentaForm" action="../controlador/eliminarUser.php" method="POST" onsubmit="return confirmarEliminacion();">
        <button type="submit" class="btn btn-danger">Eliminar Cuenta</button>
        <a href="../index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</main>
</body>
</html>
<script>
function confirmarEliminacion() {
    return confirm("¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.");
}
</script>
