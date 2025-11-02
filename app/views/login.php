<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Control de Finanzas POO</title>
</head>
<body>
<div id="login-form">
    <h2>Iniciar Sesión</h2>

    <?php
    // Mostrar mensaje de error si existe (viene del controlador)
    if (isset($login_error)) {
        echo "<p style='color:red;'><strong>" . htmlspecialchars($login_error) . "</strong></p>";
    }
    ?>

    <form action="index.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit" name="login_submit">Entrar</button>
    </form>
</div>
</body>
</html>
