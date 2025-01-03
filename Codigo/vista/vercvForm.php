<?php
require_once 'header.php';
// Configuración de la base de datos
$host = 'localhost';
$user = 'root'; // Cambiar si es necesario
$password = ''; // Cambiar si es necesario
$dbname = 'soa_final';

// Conexión a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recuperar el ID del currículum desde la URL (por ejemplo, ?id=1)
$cvId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar los datos del currículum
$sql = "SELECT `id`, `nombre`, `apellidos`, `fecha_nacimiento`, `acerca_de`, `telefonos`, `correos`, `paginas_web`, `educacion`, `experiencia`, `imagen_path` FROM `cv` WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cvId);
$stmt->execute();
$result = $stmt->get_result();
$cv = $result->fetch_assoc();

// Verificar si se encontraron datos
if (!$cv) {
    die("No se encontraron datos para el ID proporcionado.");
}

// Procesar los datos que están en formato JSON o texto separado
$telefonos = !empty($cv['telefonos']) ? json_decode($cv['telefonos'], true) : [];
$correos = !empty($cv['correos']) ? json_decode($cv['correos'], true) : [];
$paginasWeb = !empty($cv['paginas_web']) ? json_decode($cv['paginas_web'], true) : [];
$educacion = !empty($cv['educacion']) ? json_decode($cv['educacion'], true) : [];
$experiencia = !empty($cv['experiencia']) ? json_decode($cv['experiencia'], true) : [];

?>

<main class="container my-5">
    <h1 class="text-center">Currículum Vitae</h1>

    <!-- Datos personales -->
    <div class="row mb-4">
        <div class="col-md-3 text-center">
            <div class="mb-3">
                <?php if (!empty($cv['imagen_path'])): ?>
                    <img src="<?php echo htmlspecialchars($cv['imagen_path']); ?>" class="img-fluid rounded" alt="Imagen de perfil">
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-9">
            <h2><?php echo htmlspecialchars($cv['nombre']) . ' ' . htmlspecialchars($cv['apellidos']); ?></h2>
            <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($cv['fecha_nacimiento']); ?></p>
            <p><strong>Acerca de mí:</strong> <?php echo nl2br(htmlspecialchars($cv['acerca_de'])); ?></p>
        </div>
    </div>

    <!-- Datos de contacto -->
    <h2>Datos de contacto</h2>
    <?php if (!empty($telefonos)): ?>
        <p><strong>Teléfonos:</strong></p>
        <ul>
            <?php foreach ($telefonos as $telefono): ?>
                <li><?php echo htmlspecialchars(trim($telefono)); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($correos)): ?>
        <p><strong>Correos Electrónicos:</strong></p>
        <ul>
            <?php foreach ($correos as $correo): ?>
                <li><?php echo htmlspecialchars(trim($correo)); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($paginasWeb)): ?>
        <p><strong>Páginas WEB:</strong></p>
        <ul>
            <?php foreach ($paginasWeb as $paginaWeb): ?>
                <li><?php echo htmlspecialchars(trim($paginaWeb)); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Formación académica -->
    <h2>Formación académica</h2>
    <?php if (!empty($educacion)): ?>
        <ul>
            <?php foreach ($educacion as $edu): ?>
                <li>
                    <p><strong>Título:</strong> <?php echo htmlspecialchars($edu[0]); ?></p>
                    <p><strong>Institución:</strong> <?php echo htmlspecialchars($edu[1]); ?></p>
                    <p><strong>Fecha:</strong> <?php echo htmlspecialchars($edu[2]); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Experiencia laboral -->
    <h2>Experiencia laboral</h2>
    <?php if (!empty($experiencia)): ?>
        <ul>
            <?php foreach ($experiencia as $exp): ?>
                <li>
                    <p><strong>Puesto:</strong> <?php echo htmlspecialchars($exp[0]); ?></p>
                    <p><strong>Empresa:</strong> <?php echo htmlspecialchars($exp[1]); ?></p>
                    <p><strong>Fecha de Inicio:</strong> <?php echo htmlspecialchars($exp[2]); ?></p>
                    <p><strong>Fecha de Fin:</strong> <?php echo htmlspecialchars($exp[3]); ?></p>
                    <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($exp[4])); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Botón para generar PDF -->
    <form action="/Trabajo_final_SOA/Codigo/controlador/generate_pdf.php" method="post" target="_blank">
        <input type="hidden" name="id" value="<?php echo $cvId; ?>">
        <button type="submit" class="btn btn-success">Generar PDF</button>
    </form>
</main>
</body>
</html>