<nav>
    <ul>
        <li><a href="index.php?route=dashboard">Dashboard</a></li>
        <li><a href="index.php?route=registrar_entrada">1. Registrar entrada</a></li>
        <li><a href="index.php?route=registrar_salida">2. Registrar salida</a></li>
        <li><a href="index.php?route=logout">Cerrar Sesión (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)</a></li>
    </ul>
</nav>
<hr>