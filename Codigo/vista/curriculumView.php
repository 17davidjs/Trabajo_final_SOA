<?php 
include 'header.php'; 
?>


<main class="container my-5">
    <h1 class="text-center">Formulario de Currículum</h1>

    <form id="curriculumForm" action="/Trabajo_final_SOA/Codigo/controlador/save_data.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="action" value="add">

        <!-- Datos personales -->
        <div class="row mb-4">
            <div class="col-md-3 text-center">
                <div class="mb-3">
                    <label for="imagen" class="form-label">Subir Imagen</label>
                    <input type="file" class="form-control" id="imagen" name="imagen" onchange="previewImage(event)">
                </div>
                <!-- Contenedor para la vista previa de la imagen -->
                <div class="mb-3">
                    <img id="preview-img" class="img-fluid rounded" src="" alt="Vista previa de la imagen" style="display: none;">
                </div>
            </div>
            <div class="col-md-9">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre">
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos">
                </div>
                <div class="mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                </div>
                <div class="mb-3">
                    <label for="info" class="form-label">Acerca de mí</label>
                    <textarea class="form-control" id="info" name="info" rows="5"></textarea>
                </div>
            </div>
        </div>

        <!-- Contenido dividido en columnas -->
        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <h2>Datos de contacto</h2>
                <div id="contacto-container">
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono[]">
                    </div>
                    <div class="mb-3">
                        <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo_electronico" name="correo_electronico[]">
                    </div>
                    <div class="mb-3">
                        <label for="paginaweb" class="form-label">Página WEB</label>
                        <input type="text" class="form-control" id="paginaweb" name="paginaweb[]">
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addContacto1()">Añadir otro teléfono</button>
                <button type="button" class="btn btn-secondary" onclick="addContacto2()">Añadir otro correo electrónico</button>

                <h2>Formación académica</h2>
                <div id="educacion-container">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo[]">
                    </div>
                    <div class="mb-3">
                        <label for="institucion" class="form-label">Institución</label>
                        <input type="text" class="form-control" id="institucion" name="institucion[]">
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="text" class="form-control" id="fecha" name="fecha[]">
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addEducacion()">Añadir más educación</button>
            </div>

            <!-- Columna derecha -->
            <div class="col-md-6">
                <h2>Experiencia laboral</h2>
                <div id="experiencia-container">
                    <div class="mb-3">
                        <label for="puesto" class="form-label">Puesto</label>
                        <input type="text" class="form-control" id="puesto" name="puesto[]">
                    </div>
                    <div class="mb-3">
                        <label for="empresa" class="form-label">Empresa</label>
                        <input type="text" class="form-control" id="empresa" name="empresa[]">
                    </div>
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio[]">
                    </div>
                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin[]">
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion[]" rows="3"></textarea>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addExperiencia()">Añadir más experiencia laboral</button>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-primary">Crear</button>
            <button type="submit" formaction="/Trabajo_final_SOA/Codigo/controlador/generate_pdf.php" class="btn btn-success">Generar PDF</button>
            <button type="submit" formaction="../vercvForm.php" class="btn btn-success">Ver</button>

        </div>
    </form>
</main>
</body>
</html>

<script>
function previewImage(event) {
    const file = event.target.files[0]; // Obtener el archivo seleccionado
    const previewImg = document.getElementById('preview-img');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result; // Establecer la imagen de vista previa
            previewImg.style.display = 'block'; // Mostrar la imagen
        }
        reader.readAsDataURL(file); // Leer el archivo y cargar la vista previa
    }
}

function addContacto1() {
    const container = document.getElementById('contacto-container');
    const newContacto = document.createElement('div');
    newContacto.className = 'mb-3';
    newContacto.innerHTML = `
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" name="telefono[]">
        <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeField(this)">Eliminar</button>
    `;
    container.appendChild(newContacto);
}

function addContacto2() {
    const container = document.getElementById('contacto-container');
    const newContacto = document.createElement('div');
    newContacto.className = 'mb-3';
    newContacto.innerHTML = `
        <label for="correo_electronico" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" name="correo_electronico[]">
        <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeField(this)">Eliminar</button>
    `;
    container.appendChild(newContacto);
}

function addExperiencia() {
    const container = document.getElementById('experiencia-container');
    const newExperiencia = document.createElement('div');
    newExperiencia.className = 'mb-3';
    newExperiencia.innerHTML = `
        <label for="puesto" class="form-label">Puesto</label>
        <input type="text" class="form-control" name="puesto[]">
        <label for="empresa" class="form-label">Empresa</label>
        <input type="text" class="form-control" name="empresa[]">
        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
        <input type="date" class="form-control" name="fecha_inicio[]">
        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
        <input type="date" class="form-control" name="fecha_fin[]">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" name="descripcion[]" rows="3"></textarea>
        <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeField(this)">Eliminar</button>
    `;
    container.appendChild(newExperiencia);
}

function addEducacion() {
    const container = document.getElementById('educacion-container');
    const newEducacion = document.createElement('div');
    newEducacion.className = 'mb-3';
    newEducacion.innerHTML = `
        <label for="titulo" class="form-label">Título</label>
        <input type="text" class="form-control" name="titulo[]">
        <label for="institucion" class="form-label">Institución</label>
        <input type="text" class="form-control" name="institucion[]">
        <label for="fecha" class="form-label">Fecha</label>
        <input type="text" class="form-control" name="fecha[]">
        <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeField(this)">Eliminar</button>
    `;
    container.appendChild(newEducacion);
}

function removeField(button) {
    button.parentElement.remove();
}
</script>
