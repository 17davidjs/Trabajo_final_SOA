<?php
require_once '../config/db.php';
include 'header.php'; 

?>
<body>
    <div class="container my-5">
        <h1 class="text-center">Currículums Guardados</h1>

        <?php
        $usuario = $_SESSION['id'];
        // Consulta para obtener todos los usuarios
        $query = "SELECT * FROM usuarios where id = $usuario";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $usuario_id = $row['id'];
                $nombre = $row['nombre'];
                $apellidos = $row['apellidos'];
                $fecha_nacimiento = $row['fecha_nacimiento'];
                $datos_interes = $row['datos_interes'];
                $imagen_path = $row['imagen_path'];
        ?>

        <div class="card my-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <!-- Imagen del usuario -->
                        <?php if ($imagen_path): ?>
                            <img src="<?php echo $imagen_path; ?>" class="img-fluid rounded" alt="Foto de <?php echo $nombre; ?>">
                        <?php else: ?>
                            <img src="default-avatar.png" class="img-fluid rounded" alt="Sin imagen">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9">
                        <h3><?php echo $nombre . ' ' . $apellidos; ?></h3>
                        <p><strong>Fecha de Nacimiento:</strong> <?php echo $fecha_nacimiento; ?></p>
                        <p><strong>Datos de Interés:</strong> <?php echo nl2br($datos_interes); ?></p>
                        
                        <!-- Mostrar datos dinámicos -->
                        <h4>Contacto</h4>
                        <ul>
                            <?php
                            $contacto_query = "SELECT * FROM contacto WHERE usuario_id = $usuario_id";
                            $contacto_result = mysqli_query($conn, $contacto_query);
                            while ($contacto = mysqli_fetch_assoc($contacto_result)) {
                                echo "<li>Teléfono: {$contacto['telefono']}, Correo: {$contacto['correo_electronico']}, Página web: {$contacto['paginaweb']}</li>";
                            }
                            ?>
                        </ul>

                        <h4>Formación Académica</h4>
                        <ul>
                            <?php
                            $educacion_query = "SELECT * FROM educacion WHERE usuario_id = $usuario_id";
                            $educacion_result = mysqli_query($conn, $educacion_query);
                            while ($educacion = mysqli_fetch_assoc($educacion_result)) {
                                echo "<li>Título: {$educacion['titulo']}, Institución: {$educacion['institucion']}, Fecha: {$educacion['fecha']}</li>";
                            }
                            ?>
                        </ul>

                        <h4>Idiomas</h4>
                        <ul>
                            <?php
                            $idiomas_query = "SELECT * FROM idiomas WHERE usuario_id = $usuario_id";
                            $idiomas_result = mysqli_query($conn, $idiomas_query);
                            while ($idioma = mysqli_fetch_assoc($idiomas_result)) {
                                echo "<li>{$idioma['idioma']} (Nivel: {$idioma['nivel']})</li>";
                            }
                            ?>
                        </ul>

                        <h4>Experiencia Laboral</h4>
                        <ul>
                            <?php
                            $experiencia_query = "SELECT * FROM experiencia_laboral WHERE usuario_id = $usuario_id";
                            $experiencia_result = mysqli_query($conn, $experiencia_query);
                            while ($experiencia = mysqli_fetch_assoc($experiencia_result)) {
                                echo "<li>Puesto: {$experiencia['puesto']}, Empresa: {$experiencia['empresa']}, Desde: {$experiencia['fecha_inicio']} hasta {$experiencia['fecha_fin']}. Descripción: {$experiencia['descripcion']}</li>";
                            }
                            ?>
                        </ul>

                        <h4>Habilidades</h4>
                        <ul>
                            <?php
                            $habilidades_query = "SELECT * FROM habilidades WHERE usuario_id = $usuario_id";
                            $habilidades_result = mysqli_query($conn, $habilidades_query);
                            while ($habilidad = mysqli_fetch_assoc($habilidades_result)) {
                                echo "<li>{$habilidad['habilidad']}</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?php
            }
        } else {
            echo "<p class='text-center'>No hay currículums guardados.</p>";
        }
        ?>
    </div>
</body>
</html>
