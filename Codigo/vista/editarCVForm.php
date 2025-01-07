<?php 
include 'header.php'; 
require_once '../config/db.php';

$cv_id = $_GET['cv_id'];
$query = "SELECT * FROM curriculums WHERE cv_id = $cv_id";
$result = mysqli_query($conn, $query);
$cv = mysqli_fetch_assoc($result);
$id_usuario = $_SESSION['id'];

?>

<main class="container my-5">
    <h1 class="text-center">Editar Currículum</h1>

    <form id="curriculumForm" action="../controlador/editarCV.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="cv_id" value="<?php echo $cv_id; ?>">

        <!-- Datos personales -->
        <div class="row mb-4">
        <h2>Datos personales</h2>
        <div id="contacto-container">
            <!-- Cargar datos de contacto -->
            <?php
            // Asegúrate de que $cv_id esté definido correctamente
            $contacto_query = "SELECT * FROM contacto WHERE cv_id = '$cv_id'";
            $contacto_result = mysqli_query($conn, $contacto_query);

            // Obtén los datos del usuario
            $usuario_query = "SELECT * FROM usuarios WHERE id = '$id_usuario'";
            $usuario_result = mysqli_query($conn, $usuario_query);

            // Verifica que ambos resultados existan
            if ($contacto_result && $usuario_result) {
                $contacto = mysqli_fetch_assoc($contacto_result);
                $usuario = mysqli_fetch_assoc($usuario_result);
                
                echo '<div class="col-md-3 text-center mb-3">';
                echo '<label for="imagen" class="form-label">Subir Imagen</label>';
                echo '<input type="file" class="form-control" id="imagen" name="imagen" onchange="previewImage(event)">';
                echo '<div class="mb-3">';
                echo '<img id="preview-img" class="img-fluid rounded" src="' . $contacto['imagen_path'] . '" alt="Vista previa de la imagen" style="display: block;">';
                echo '</div>';
                echo '</div>';

                echo '<div class="col-md-9">';
                echo '<div class="mb-3">';
                echo '<label for="nombre" class="form-label">Nombre</label>';
                echo '<input type="text" class="form-control" id="nombre" name="nombre" value="' . htmlspecialchars($usuario['nombre']) . '">';
                echo '</div>';

                echo '<div class="mb-3">';
                echo '<label for="apellidos" class="form-label">Apellidos</label>';
                echo '<input type="text" class="form-control" id="apellidos" name="apellidos" value="' . htmlspecialchars($usuario['apellidos']) . '">';
                echo '</div>';

                echo '<div class="mb-3">';
                echo '<label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>';
                echo '<input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="' . $usuario['fecha_nacimiento'] . '">';
                echo '</div>';

                echo '<div class="mb-3">';
                echo '<label for="datos_interes" class="form-label">Datos de interés</label>';
                echo '<textarea class="form-control" id="datos_interes" name="datos_interes" rows="5">' . htmlspecialchars($contacto['datos_interes']) . '</textarea>';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<p>Error al cargar los datos del contacto o usuario.</p>';
            }
            ?>
        </div>
    </div>


        <!-- Contenido dividido en columnas -->
        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <h2>Datos de contacto</h2>
                <div id="contacto-container">
                    <!-- Cargar datos de contacto -->
                    <?php
                    $contacto_query = "SELECT * FROM contacto WHERE cv_id = $cv_id";
                    $contacto_result = mysqli_query($conn, $contacto_query);
                    while ($contacto = mysqli_fetch_assoc($contacto_result)) {
                        echo '<div class="mb-3">';
                        echo '<label for="telefono" class="form-label">Teléfono</label>';
                        echo '<input type="text" class="form-control" name="telefono[]" value="' . $contacto['telefono'] . '">';
                        echo '<label for="correo_electronico" class="form-label">Correo Electrónico</label>';
                        echo '<input type="email" class="form-control" name="correo_electronico[]" value="' . $contacto['correo_electronico'] . '">';
                        echo '<label for="paginaweb" class="form-label">Página WEB</label>';
                        echo '<input type="text" class="form-control" name="paginaweb[]" value="' . $contacto['paginaweb'] . '">';
                        echo '</div>';
                    }
                    ?>
                </div>

                <h2>Formación académica</h2>
                <div id="educacion-container">
                    <!-- Cargar datos de educación -->
                    <?php
                    $educacion_query = "SELECT * FROM educacion WHERE cv_id = $cv_id";
                    $educacion_result = mysqli_query($conn, $educacion_query);
                    while ($educacion = mysqli_fetch_assoc($educacion_result)) {
                        echo '<div class="mb-3">';
                        echo '<label for="titulo" class="form-label">Título</label>';
                        echo '<input type="text" class="form-control" name="titulo[]" value="' . $educacion['titulo'] . '">';
                        echo '<label for="institucion" class="form-label">Institución</label>';
                        echo '<input type="text" class="form-control" name="institucion[]" value="' . $educacion['institucion'] . '">';
                        echo '<label for="fecha" class="form-label">Fecha</label>';
                        echo '<input type="text" class="form-control" name="fecha[]" value="' . $educacion['fecha'] . '">';
                        echo '</div>';
                    }
                    ?>
                </div>

                <h2>Idiomas</h2>
                <div id="idiomas-container">
                    <!-- Cargar datos de idiomas -->
                    <?php
                    $idiomas_query = "SELECT * FROM idiomas WHERE cv_id = $cv_id";
                    $idiomas_result = mysqli_query($conn, $idiomas_query);
                    while ($idioma = mysqli_fetch_assoc($idiomas_result)) {
                        echo '<div class="mb-3">';
                        echo '<label for="Idioma" class="form-label">Idioma</label>';
                        echo '<input type="text" class="form-control" name="Idioma[]" value="' . $idioma['idioma'] . '">';
                        echo '<label for="nivel" class="form-label">Nivel</label>';
                        echo '<input type="text" class="form-control" name="nivel[]" value="' . $idioma['nivel'] . '">';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-md-6">
                <h2>Experiencia laboral</h2>
                <div id="experiencia-container">
                    <!-- Cargar datos de experiencia laboral -->
                    <?php
                    $experiencia_query = "SELECT * FROM experiencia_laboral WHERE cv_id = $cv_id";
                    $experiencia_result = mysqli_query($conn, $experiencia_query);
                    while ($experiencia = mysqli_fetch_assoc($experiencia_result)) {
                        echo '<div class="mb-3">';
                        echo '<label for="puesto" class="form-label">Puesto</label>';
                        echo '<input type="text" class="form-control" name="puesto[]" value="' . $experiencia['puesto'] . '">';
                        echo '<label for="empresa" class="form-label">Empresa</label>';
                        echo '<input type="text" class="form-control" name="empresa[]" value="' . $experiencia['empresa'] . '">';
                        echo '<label for="fecha_inicio" class="form-label">Fecha de Inicio</label>';
                        echo '<input type="date" class="form-control" name="fecha_inicio[]" value="' . $experiencia['fecha_inicio'] . '">';
                        echo '<label for="fecha_fin" class="form-label">Fecha de Fin</label>';
                        echo '<input type="date" class="form-control" name="fecha_fin[]" value="' . $experiencia['fecha_fin'] . '">';
                        echo '<label for="descripcion" class="form-label">Descripción</label>';
                        echo '<textarea class="form-control" name="descripcion[]" rows="3">' . $experiencia['descripcion'] . '</textarea>';
                        echo '</div>';
                    }
                    ?>
                </div>

                <h2>Habilidades</h2>
                <div id="habilidades-container">
                    <!-- Cargar datos de habilidades -->
                    <?php
                    $habilidades_query = "SELECT * FROM habilidades WHERE cv_id = $cv_id";
                    $habilidades_result = mysqli_query($conn, $habilidades_query);
                    while ($habilidad = mysqli_fetch_assoc($habilidades_result)) {
                        echo '<div class="mb-3">';
                        echo '<label for="habilidades" class="form-label">Habilidades</label>';
                        echo '<input type="text" class="form-control" name="habilidades[]" value="' . $habilidad['habilidad'] . '">';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="mt-4 text-center">
            <!--<button type="submit" class="btn btn-primary">Guardar Cambios</button>-->
            <a href="vercvForm.php" class="btn btn-primary">Volver</a>
        </div>
    </form>
</main>
</body>
</html>