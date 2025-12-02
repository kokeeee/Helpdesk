<?php
/**
 * Script de Migración de Contraseñas
 * 
 * Convierte todas las contraseñas en texto plano a hash BCRYPT
 * IMPORTANTE: Ejecutar SOLO una vez
 * 
 * Uso: php migrar_contrasenas.php
 */

// Conexión a base de datos
try {
    $conectar = new PDO(
        "mysql:host=localhost;dbname=heldesk",
        "root",
        ""
    );
    $conectar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a base de datos exitosa\n\n";
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    exit(1);
}

// Obtener todos los usuarios
try {
    $sql = "SELECT usu_id, nombre, apellido, correo, contrasenia FROM tm_usuario";
    $stmt = $conectar->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Total de usuarios encontrados: " . count($usuarios) . "\n\n";
    
    if (empty($usuarios)) {
        echo "⚠️  No hay usuarios para migrar\n";
        exit(0);
    }
    
} catch (Exception $e) {
    echo "❌ Error al obtener usuarios: " . $e->getMessage() . "\n";
    exit(1);
}

// Procesar cada usuario
$migrados = 0;
$ya_hasheados = 0;
$errores = 0;

foreach ($usuarios as $usuario) {
    $usu_id = $usuario['usu_id'];
    $nombre = $usuario['nombre'];
    $apellido = $usuario['apellido'];
    $correo = $usuario['correo'];
    $contrasenia_actual = $usuario['contrasenia'];
    
    try {
        // Verificar si ya está hasheado (comienza con $2)
        if (substr($contrasenia_actual, 0, 3) === '$2y' || substr($contrasenia_actual, 0, 3) === '$2a' || substr($contrasenia_actual, 0, 3) === '$2b') {
            echo "⏭️  Usuario $usu_id ({$nombre} {$apellido} - {$correo}): Ya está hasheado\n";
            $ya_hasheados++;
            continue;
        }
        
        // Crear hash de la contraseña
        $contrasenia_hash = password_hash($contrasenia_actual, PASSWORD_BCRYPT);
        
        // Actualizar en base de datos
        $update_sql = "UPDATE tm_usuario SET contrasenia = ? WHERE usu_id = ?";
        $update_stmt = $conectar->prepare($update_sql);
        $update_stmt->execute([$contrasenia_hash, $usu_id]);
        
        echo "✅ Usuario $usu_id ({$nombre} {$apellido} - {$correo}): Migrado correctamente\n";
        $migrados++;
        
    } catch (Exception $e) {
        echo "❌ Usuario $usu_id ({$nombre} {$apellido}): ERROR - " . $e->getMessage() . "\n";
        $errores++;
    }
}

// Resumen
echo "\n" . str_repeat("=", 70) . "\n";
echo "📋 RESUMEN DE MIGRACIÓN\n";
echo str_repeat("=", 70) . "\n";
echo "✅ Usuarios migrados: $migrados\n";
echo "⏭️  Ya hasheados: $ya_hasheados\n";
echo "❌ Errores: $errores\n";
echo str_repeat("=", 70) . "\n";

if ($errores === 0) {
    echo "\n✅ ¡Migración completada exitosamente!\n";
    echo "💡 Todos los usuarios ahora están protegidos con hash BCRYPT\n";
    echo "🔒 Las contraseñas originales se han convertido de forma segura\n\n";
    exit(0);
} else {
    echo "\n⚠️  Migración completada con ERRORES\n";
    exit(1);
}
?>
