<div id="contenido">
    <h2>Registro de Nueva Salida (Egreso)</h2>
    <?php
    // Mensajes de éxito o error se mostrarán aquí
    if (isset($mensaje_salida)) {
        echo "<p style='color:green;'>" . htmlspecialchars($mensaje_salida) . "</p>";
    }
    if (isset($error_salida)) {
        echo "<p style='color:red;'><strong>" . htmlspecialchars($error_salida) . "</strong></p>";
    }
    ?>

    <form action="index.php?route=registrar_salida" method="POST" enctype="multipart/form-data">

        <label for="tipo_salida">Tipo de salida (Gasto):</label>
        <input type="text" id="tipo_salida" name="tipo_salida" required><br><br>

        <label for="monto">Monto ($):</label>
        <input type="number" id="monto" name="monto" step="0.01" min="0" required><br><br>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br><br>

        <label for="factura">Factura/Comprobante (Foto):</label>
        <input type="file" id="factura" name="factura" accept="image/*" required><br><br>

        <button type="submit" name="registrar_salida">Registrar Salida</button>
    </form>

    <p><a href="index.php?route=dashboard">Volver al Dashboard</a></p>
</div>