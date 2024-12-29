<?php include 'header.php'; ?>

    <main class="container my-5">
        <h1>Subir Fichero</h1>
        <form action="../controlador/subirCV.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="fichero" class="form-label">Selecciona un fichero (CSV, XML, JSON):</label>
                <input type="file" name="fichero" id="fichero" class="form-control" accept=".csv,.xml,.json" required>
            </div>
            <button type="submit" class="btn btn-primary">Subir Fichero</button>
        </form>
    </main>
    
</body>
</html>