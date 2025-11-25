<?php
    require_once("../../config/conexion.php");
    // Asegurar que la sesión esté iniciada antes de destruirla
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Limpiar variables de sesión y destruir la sesión
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_unset();
    session_destroy();

    // Redirigir a la página de login dentro de la carpeta view
    header("Location:".Conectar::ruta()."view/index.php");
    exit();
?>