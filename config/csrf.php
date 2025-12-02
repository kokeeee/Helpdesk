<?php
/**
 * CSRF Token Handler
 * 
 * Funciones para generar y validar tokens CSRF
 * Protege contra ataques Cross-Site Request Forgery
 */

/**
 * Generar un nuevo token CSRF
 * 
 * @return string Token CSRF seguro (64 caracteres hexadecimales)
 */
function generar_csrf_token() {
    // random_bytes(32) genera 32 bytes aleatorios
    // bin2hex() convierte a string hexadecimal (64 caracteres)
    // Cada visitante obtiene un token único
    $token = bin2hex(random_bytes(32));
    
    // Guardar en sesión
    $_SESSION['csrf_token'] = $token;
    
    return $token;
}

/**
 * Obtener el token CSRF actual
 * 
 * Si no existe, generar uno nuevo
 * 
 * @return string Token CSRF
 */
function obtener_csrf_token() {
    // Si no existe token en sesión, generar uno
    if (!isset($_SESSION['csrf_token'])) {
        generar_csrf_token();
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Verificar que el token CSRF es válido
 * 
 * Se usa cuando se recibe un POST/PUT/DELETE
 * 
 * @param string $token_recibido El token que viene en el formulario
 * @return bool true si es válido, false si no
 */
function validar_csrf_token($token_recibido) {
    // Verificar que el token existe en la solicitud
    if (empty($token_recibido)) {
        return false;
    }
    
    // Verificar que existe un token en la sesión
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    // Comparar tokens de forma segura
    // hash_equals() previene ataques de timing
    return hash_equals($_SESSION['csrf_token'], $token_recibido);
}

/**
 * Campo oculto HTML con el token CSRF
 * 
 * Usa esta función en formularios para incluir el token
 * 
 * @return string HTML con input oculto
 */
function campo_csrf() {
    $token = obtener_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Validar CSRF y mostrar error si falla
 * 
 * Usa en controladores para proteger acciones
 * 
 * @param string $origen Identificador de donde viene la solicitud (opcional)
 * @return bool true si válido, sale con error si no
 */
function validar_csrf_o_morir($origen = 'formulario') {
    // Obtener token de POST/GET
    $token_recibido = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
    
    // Validar
    if (!validar_csrf_token($token_recibido)) {
        // Token inválido, rechazar solicitud
        header('HTTP/1.1 403 Forbidden');
        die(json_encode([
            'success' => false,
            'message' => 'Error de seguridad: Token CSRF inválido. La solicitud fue rechazada.',
            'origen' => $origen
        ]));
    }
    
    return true;
}

?>
