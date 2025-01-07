<?php include 'vista/header.php'; ?>

<head>
    <link rel="stylesheet" href="estilos/style.css">
</head>

<main class="container my-5">
    <section class="text-center">
        <h3 class="fw-bold">Bienvenido al Sistema de Gestión de Currículums</h3>
        <p class="lead">
            Descubra todo lo que nuestro sistema tiene para ofrecer. Una herramienta completa para la gestión de currículums, usuarios y reportes.
        </p>
    </section>

    <section class="row mt-5">
    <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Gestión de Currículums</h5>
                    <p class="card-text">
                        Una herramienta avanzada para crear, editar y validar currículums de forma eficiente y profesional. 
                        ¡Empiece hoy mismo y optimice su gestión de currículums!
                        <?php //echo "Id de la sesión: " . $_SESSION['id']; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">¡Regístrate y Descubre el Poder de los Curriculums!</h5>
                    <p class="card-text">
                        Aprovecha nuestra poderosa herramienta para generar curriculums personalizados en formatos como WEB y PDF.
                        Regístrate ahora y lleva tu documentación al siguiente nivel.
                    </p>
                    <a href="vista/registroForm.php" class="btn btn-warning text-white fw-bold">Regístrate Ahora</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Sobre Nosotros</h5>
                        <p class="card-text">
                            Este sistema fue creado por un grupo de estudiantes de la Universidad de Salamanca con el objetivo de revolucionar la gestión de currículums.
                            Nuestra visión es proporcionar herramientas modernas, accesibles y efectivas que faciliten la vida de profesionales y empresas. 
                            Inspirados por la innovación y respaldados por una institución histórica, hemos diseñado una solución que combina simplicidad y potencia.
                        </p>
                </div>
            </div>
        </div>
        
    </section>
</main>

   
</body>
 <footer class="bg-dark text-white py-4 text-center">
        <p>© 2024 G.I.I.S.I. - Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>