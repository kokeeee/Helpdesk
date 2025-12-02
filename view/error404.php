<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página no encontrada</title>
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .error-container {
            width: 100%;
            max-width: 1200px;
            padding: 40px 20px;
        }
        
        .error-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }
        
        .error-left {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .error-code {
            font-size: 120px;
            font-weight: 800;
            color: #667eea;
            line-height: 1;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(102, 126, 234, 0.1);
        }
        
        .error-title {
            font-size: 36px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
        }
        
        .error-message {
            font-size: 16px;
            color: #718096;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        
        .error-reason {
            font-size: 14px;
            color: #a0aec0;
            margin-bottom: 40px;
            padding: 15px;
            background: #edf2f7;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .btn-home {
            display: inline-block;
            padding: 14px 35px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            background: #667eea;
            color: white;
            transition: all 0.3s ease;
            width: fit-content;
            border: none;
            cursor: pointer;
        }
        
        .btn-home:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .error-right {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        lottie-player {
            max-width: 100%;
            height: auto;
        }
        
        @media (max-width: 768px) {
            .error-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            
            .error-code {
                font-size: 80px;
            }
            
            .error-title {
                font-size: 28px;
            }
            
            .error-right {
                order: -1;
            }
        }
        
        /* Estilos para animación de entrada */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .error-left {
            animation: slideInLeft 0.6s ease;
        }
        
        .error-right {
            animation: slideInRight 0.6s ease;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-content">
            <div class="error-left">
                <div class="error-code">404</div>
                <h1 class="error-title">¡Página No Encontrada!</h1>
                <p class="error-message">
                    La página que intentas acceder no existe, fue removida o requiere autenticación.
                </p>
                
                <?php 
                    $reason = isset($_GET['reason']) ? $_GET['reason'] : '';
                    if($reason) {
                        echo '<div class="error-reason">';
                        if($reason == 'not_logged_in') {
                            echo '📌 Por favor inicia sesión para continuar.';
                        } elseif($reason == 'no_permission') {
                            echo '🔒 No tienes permiso para acceder a esta página.';
                        }
                        echo '</div>';
                    }
                ?>
                
                <a class="btn-home" href="http://localhost/HelpDesk/view/index.php">
                    ← Volver al Inicio
                </a>
            </div>
            
            <div class="error-right">
                <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_kcsr6fcp.json" background="transparent" speed="1" loop="" autoplay=""></lottie-player>
            </div>
        </div>
    </div>

    <script src="../public/js/lib/bootstrap/bootstrap.min.js"></script>
</body>
</html>
