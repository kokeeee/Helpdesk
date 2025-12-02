<?php
echo "Iniciando script de migracion...\n";

try {
    echo "Intentando conectar a BD...\n";
    $conectar = new PDO(
        "mysql:host=localhost;dbname=heldesk",
        "root",
        ""
    );
    $conectar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexion exitosa\n\n";
    
    // Obtener usuarios
    $sql = "SELECT usu_id, nombre, apellido, correo, contrasenia FROM tm_usuario";
    $stmt = $conectar->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total de usuarios: " . count($usuarios) . "\n\n";
    
    // Procesar
    foreach ($usuarios as $usuario) {
        $usu_id = $usuario['usu_id'];
        $nombre = $usuario['nombre'];
        $correo = $usuario['correo'];
        $cont_actual = $usuario['contrasenia'];
        
        // Verificar si ya está hasheado
        if (substr($cont_actual, 0, 3) === '$2y' || substr($cont_actual, 0, 3) === '$2a') {
            echo "⏭️  Usuario $usu_id: Ya hasheado\n";
            continue;
        }
        
        // Crear hash
        $hash_nuevo = password_hash($cont_actual, PASSWORD_BCRYPT);
        
        // Actualizar
        $update = "UPDATE tm_usuario SET contrasenia = ? WHERE usu_id = ?";
        $stmt_update = $conectar->prepare($update);
        $stmt_update->execute([$hash_nuevo, $usu_id]);
        
        echo "✅ Usuario $usu_id ($nombre - $correo): MIGRADO\n";
    }
    
    echo "\n✅ Migracion completada!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Linea: " . $e->getLine() . "\n";
}
?>
