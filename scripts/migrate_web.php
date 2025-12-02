<?php
$permitir = true;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migración de Contraseñas - HelpDesk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            color: #856404;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #28a745;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            color: #721c24;
        }
        .info {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            color: #004085;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        button:hover {
            background-color: #218838;
        }
        button:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }
        .result {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #007bff;
            max-height: 400px;
            overflow-y: auto;
            font-family: monospace;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .paso {
            margin: 20px 0;
            padding: 15px;
            background-color: #f0f0f0;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Migración de Contraseñas a Hash BCRYPT</h1>
        
        <div class="warning">
            ⚠️ <strong>ADVERTENCIA:</strong> Este proceso convertirá todas las contraseñas en texto plano a hash seguro. Solo debe ejecutarse UNA VEZ.
        </div>

        <div class="paso">
            <h3>📋 ¿Qué hará este script?</h3>
            <ul>
                <li>Conectará a la base de datos HelpDesk</li>
                <li>Buscará todos los usuarios con contraseñas en texto plano</li>
                <li>Convertirá cada contraseña a un hash BCRYPT seguro</li>
                <li>Almacenará el hash en lugar de la contraseña original</li>
                <li>Los usuarios podrán iniciar sesión con sus contraseñas originales</li>
            </ul>
        </div>

        <div class="info">
            ℹ️ <strong>Información:</strong> Después de la migración, las contraseñas se verificarán con password_verify() usando el nuevo código.
        </div>

        <form method="POST">
            <input type="hidden" name="confirmar" value="si">
            <button type="submit">▶️ Iniciar Migración</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
            echo '<div class="result">';
            echo "Iniciando migración...\n\n";
            
            try {
                // Conexión
                echo "1️⃣  Conectando a base de datos...\n";
                $conectar = new PDO(
                    "mysql:host=localhost;dbname=heldesk",
                    "root",
                    ""
                );
                $conectar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "✅ Conexión exitosa\n\n";
                
                // Obtener usuarios
                echo "2️⃣  Obteniendo usuarios...\n";
                $sql = "SELECT usu_id, nombre, apellido, correo, contrasenia FROM tm_usuario";
                $stmt = $conectar->query($sql);
                $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "✅ Se encontraron " . count($usuarios) . " usuarios\n\n";
                
                // Procesar
                echo "3️⃣  Procesando usuarios...\n";
                echo str_repeat("-", 70) . "\n";
                
                $migrados = 0;
                $ya_hasheados = 0;
                $errores = 0;
                
                foreach ($usuarios as $usuario) {
                    $usu_id = $usuario['usu_id'];
                    $nombre = $usuario['nombre'];
                    $apellido = $usuario['apellido'];
                    $correo = $usuario['correo'];
                    $cont_actual = $usuario['contrasenia'];
                    
                    try {
                        // Verificar si ya está hasheado
                        if (substr($cont_actual, 0, 3) === '$2y' || substr($cont_actual, 0, 3) === '$2a' || substr($cont_actual, 0, 3) === '$2b') {
                            echo "⏭️  Usuario $usu_id ($nombre $apellido): Ya está hasheado\n";
                            $ya_hasheados++;
                            continue;
                        }
                        
                        // Crear hash
                        $hash_nuevo = password_hash($cont_actual, PASSWORD_BCRYPT);
                        
                        // Actualizar
                        $update = "UPDATE tm_usuario SET contrasenia = ? WHERE usu_id = ?";
                        $stmt_update = $conectar->prepare($update);
                        $stmt_update->execute([$hash_nuevo, $usu_id]);
                        
                        echo "✅ Usuario $usu_id ($nombre $apellido - $correo): MIGRADO\n";
                        $migrados++;
                        
                    } catch (Exception $e) {
                        echo "❌ Usuario $usu_id: ERROR - " . $e->getMessage() . "\n";
                        $errores++;
                    }
                }
                
                echo str_repeat("-", 70) . "\n";
                echo "\n4️⃣  Resumen de Migración:\n";
                echo "✅ Migrados: $migrados\n";
                echo "⏭️  Ya hasheados: $ya_hasheados\n";
                echo "❌ Errores: $errores\n";
                
                if ($errores === 0) {
                    echo "\n";
                    echo str_repeat("=", 70) . "\n";
                    echo "✅ ¡MIGRACIÓN COMPLETADA EXITOSAMENTE!\n";
                    echo str_repeat("=", 70) . "\n";
                    echo "\n🔒 Cambios realizados:\n";
                    echo "   • Todas las contraseñas están ahora protegidas con BCRYPT\n";
                    echo "   • Se puede iniciar sesión con las contraseñas originales\n";
                    echo "   • Las contraseñas se verifican con password_verify()\n";
                    echo "   • La base de datos está más segura\n";
                } else {
                    echo "\n⚠️  Migración completada con ERRORES\n";
                }
                
            } catch (Exception $e) {
                echo "❌ ERROR CRÍTICO\n";
                echo "Mensaje: " . $e->getMessage() . "\n";
                echo "Archivo: " . $e->getFile() . "\n";
                echo "Línea: " . $e->getLine() . "\n";
            }
            
            echo '</div>';
            
            echo '<div class="success" style="margin-top: 30px;">';
            echo '✅ <strong>Próximos pasos:</strong><br>';
            echo '1. Prueba a iniciar sesión con tus credenciales originales<br>';
            echo '2. Verifica que todo funciona correctamente<br>';
            echo '3. Puedes eliminar esta página (scripts/migrate_web.php)<br>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
