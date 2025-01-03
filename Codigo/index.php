<?php include 'vista/header.php'; ?>

<main class="container my-5">
    <section class="text-center">
        <h3 class="fw-bold">Bienvenido al Sistema de Gestión de Currículums</h3>
        <p class="lead">
            Utilice el menú de navegación para acceder a las distintas funcionalidades del sistema, como gestión de
            usuarios, manejo de currículums y generación de reportes.
        </p>
    </section>

    <section class="row mt-5">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Gestión de Usuarios</h5>
                    <p class="card-text">Administre usuarios del sistema: creación, eliminación y autenticación.</p>
                    <a href="vista/usuarios/index_usuarios.php" class="btn btn-primary">Ir a Usuarios</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Gestión de Currículums</h5>
                    <p class="card-text">Cree, edite y valide currículums utilizando nuestras herramientas avanzadas.</p>
                    <a href="curriculums.php" class="btn btn-primary">Ir a Currículums</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Generación de Reportes</h5>
                    <p class="card-text">Genere documentos personalizados en formatos HTML, CSS y PDF.</p>
                    <a href="reportes.php" class="btn btn-primary">Ir a Reportes</a>
                </div>
            </div>
        </div>
    </section>
</main>

    <footer class="bg-dark text-white py-4 text-center">
        <p>© 2024 G.I.I.S.I. - Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>