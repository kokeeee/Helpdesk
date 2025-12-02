<?php
/**
 * Archivo de verificación de autenticación
 * Incluir este archivo al inicio de cualquier página que requiera autenticación
 * 
 * Uso: require_once '../config/check_auth.php';
 */

if (!isset($_SESSION["usu_id"])) {
    // Redirigir a página de error 404 si no está autenticado
    header("Location: ../error404.php?reason=not_logged_in");
    exit();
}

// Función para verificar permisos por rol
function check_role($required_role) {
    if (!isset($_SESSION["rol_id"]) || $_SESSION["rol_id"] != $required_role) {
        header("Location: ../error404.php?reason=no_permission");
        exit();
    }
}

// Función para verificar múltiples roles
function check_roles_multiple($allowed_roles) {
    if (!isset($_SESSION["rol_id"]) || !in_array($_SESSION["rol_id"], $allowed_roles)) {
        header("Location: ../error404.php?reason=no_permission");
        exit();
    }
}
?>
