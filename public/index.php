<?php
// 1. Iniciar la sesión para manejar el estado del usuario
session_start();

// 2. Definir la función de Autoload (Carga Automática de Clases)
// Esto permite que el sistema cargue las clases (Database, User, etc.) cuando se instancien.
spl_autoload_register(function ($class_name) {
    // Busca las clases dentro de la carpeta 'app/classes'
    $file = '../app/classes/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// 3. Inicializar la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// ----------------------------------------------------
// 4. Lógica de Autenticación (Login y Logout)
// ----------------------------------------------------
$is_logged_in = isset($_SESSION['user_id']);

if (isset($_POST['login_submit'])) {
    $user = new User($db);
    $user->email = $_POST['email'];
    $user->password = $_POST['password']; // Contraseña sin hashear (para prueba simple)

    if ($user->login()) {
        // Autenticación exitosa
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->nombre;
        $is_logged_in = true;
        // Redirigir para evitar reenvío de formulario
        header("Location: index.php?route=dashboard");
        exit();
    } else {
        // Fallo en la autenticación
        $login_error = "Email o contraseña incorrectos.";
    }
}

// Lógica de Logout
if (isset($_GET['route']) && $_GET['route'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}


// ----------------------------------------------------
// 5. Lógica para Registrar Entrada
// ----------------------------------------------------
if (isset($_POST['registrar_entrada']) && $is_logged_in) {

    // 1. Configuración de subida
    $target_dir = __DIR__ . "/uploads/facturas_entradas/";
    $ruta_bd_relativa = "uploads/facturas_entradas/";

    // Asegurar que la carpeta de subidas existe
    if (!is_dir($target_dir)) {
        // 0777 da permisos máximos, true permite la creación recursiva
        mkdir($target_dir, 0777, true);
    }

    // 2. Manejo y nombramiento del archivo
    $file_info = pathinfo($_FILES["factura"]["name"]);
    $imageFileType = strtolower($file_info['extension']);

    // Generar un nombre único para el archivo (usando la fecha actual y un ID único)
    $new_file_name = uniqid('entrada_') . "." . $imageFileType;
    $final_target_file = $target_dir . $new_file_name;
    $ruta_bd = $ruta_bd_relativa . $new_file_name; // Ruta a guardar en BD

    // 3. Mover el archivo subido
    if (move_uploaded_file($_FILES["factura"]["tmp_name"], $final_target_file)) {

        // 4. Guardar datos en la BD
        $entrada = new Entrada($db);
        $entrada->tipo_entrada = $_POST['tipo_entrada'];
        $entrada->monto = (float)$_POST['monto']; // Asegurar que el monto sea float
        $entrada->fecha = $_POST['fecha'];
        $entrada->ruta_factura = $ruta_bd; // Ruta relativa para la BD
        $entrada->usuario_id = $_SESSION['user_id'];

        if ($entrada->create()) {
            $mensaje_entrada = "Entrada registrada exitosamente.";
        } else {
            $error_entrada = "Error al guardar los datos en la base de datos.";
            // Si falla la BD, eliminar el archivo subido para limpiar
            unlink($final_target_file);
        }
    } else {
        $error_entrada = "Error al subir el archivo de la factura. El tamaño puede ser demasiado grande o la subida falló.";
    }

    // Forzar la recarga de la vista de registro para mostrar el mensaje
    // Si la ruta no está configurada, el enrutamiento más adelante se encargará
    $_GET['route'] = 'registrar_entrada';
}


// ----------------------------------------------------
// 6. Lógica para Registrar SALIDA
// ----------------------------------------------------
if (isset($_POST['registrar_salida']) && $is_logged_in) {

    // 1. Configuración de subida (Carpeta diferente para salidas)
    $target_dir = __DIR__ . "/uploads/facturas_salidas/";
    $ruta_bd_relativa = "uploads/facturas_salidas/";

    // Asegurar que la carpeta de subidas existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // 2. Manejo y nombramiento del archivo
    $file_info = pathinfo($_FILES["factura"]["name"]);
    $imageFileType = strtolower($file_info['extension']);

    // Generar un nombre único para el archivo
    $new_file_name = uniqid('salida_') . "." . $imageFileType;
    $final_target_file = $target_dir . $new_file_name;
    $ruta_bd = $ruta_bd_relativa . $new_file_name; // Ruta a guardar en BD

    // 3. Mover el archivo subido
    if (move_uploaded_file($_FILES["factura"]["tmp_name"], $final_target_file)) {

        // 4. Guardar datos en la BD
        $salida = new Salida($db);
        $salida->tipo_salida = $_POST['tipo_salida'];
        $salida->monto = (float)$_POST['monto'];
        $salida->fecha = $_POST['fecha'];
        $salida->ruta_factura = $ruta_bd; // Ruta relativa para la BD
        $salida->usuario_id = $_SESSION['user_id'];

        if ($salida->create()) {
            $mensaje_salida = "Salida registrada exitosamente.";
        } else {
            $error_salida = "Error al guardar los datos en la base de datos.";
            // Si falla la BD, eliminar el archivo subido para limpiar
            unlink($final_target_file);
        }
    } else {
        $error_salida = "Error al subir el archivo del comprobante.";
    }

    // Forzar la recarga de la vista de registro para mostrar el mensaje
    $_GET['route'] = 'registrar_salida';
}




// ----------------------------------------------------
// 5. Enrutamiento y Seguridad
// ----------------------------------------------------

$route = isset($_GET['route']) ? $_GET['route'] : '';

if (!$is_logged_in) {
    // Si NO está logueado, forzar la vista de login
    include '../app/views/login.php';
} else {
    // Si SÍ está logueado, mostrar el menú y el contenido

    // Incluir el menú (que crearemos en el siguiente paso)
    // El menú es estático y estará disponible en todas las vistas protegidas
    include '../app/views/menu.php';

    switch ($route) {
        case 'dashboard':
            include __DIR__ . '/../app/views/dashboard.php';
            break;

        case 'registrar_entrada':
            include __DIR__ . '/../app/views/registro_entrada.php';
            break;

        // ¡Añadir esta nueva ruta!
        case 'registrar_salida':
            include __DIR__ . '/../app/views/registro_salida.php';
            break;

        default:
            include __DIR__ . '/../app/views/dashboard.php';
            break;
    }
}
?>