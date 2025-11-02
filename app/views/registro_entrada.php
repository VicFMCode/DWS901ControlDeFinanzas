<div id="contenido">
    <h2>Registro de Nueva Entrada</h2>
    <?php
    // Mensajes de éxito o error se mostrarán aquí
    if (isset($mensaje_entrada)) {
        echo "<p style='color:green;'>" . htmlspecialchars($mensaje_entrada) . "</p>";
    }
    if (isset($error_entrada)) {
        echo "<p style='color:red;'><strong>" . htmlspecialchars($error_entrada) . "</strong></p>";
    }
    ?>

    <form action="index.php?route=registrar_entrada" method="POST" enctype="multipart/form-data">

        <label for="tipo_entrada">Tipo de entrada:</label>
        <input type="text" id="tipo_entrada" name="tipo_entrada" required><br><br>

        <label for="monto">Monto ($):</label>
        <input type="number" id="monto" name="monto" step="0.01" min="0" required><br><br>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br><br>

        <label for="factura">Factura (Foto):</label>
        <input type="file" id="factura" name="factura" accept="image/*" required><br><br>

        <button type="submit" name="registrar_entrada">Registrar Entrada</button>
    </form>

    <p><a href="index.php?route=dashboard">Volver al Dashboard</a></p>
</div>