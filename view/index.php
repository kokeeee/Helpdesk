<?php
  require_once("../config/conexion.php");
  if (isset($_POST["enviar"]) and $_POST["enviar"]=="si"){
      require_once("../models/Usuario.php");
      $usuario = new Usuario();
      $usuario->login();
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
  <div class="container">
      <div class="row">
        <div class="col-md-6 offset-md-3">
          <h2 class="text-center text-dark mt-5">Iniciar Sesión</h2>
          <div class="card my-5">

            <form class="sign-box" action = "<?php echo Conectar::ruta(); ?>view/index.php" method = "POST" id = "login_form">
                <?php
                  if (isset($_GET["m"])){
                      switch($_GET["m"]){
                          case "1":
                              ?>
                                <button type= button class = "close" data-dismiss = "alert" aria-label = "Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                
                                <div class="alert alert-warning" role="alert">
                                    El usuario y/o contraseña son incorrectos.
                                </div>
                              <?php
                          break;

                          case "2":
                              ?>
                                <button type= button class = "close" data-dismiss = "alert" aria-label = "Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                
                                <div class="alert alert-warning" role="alert">
                                  Por favor, complete los campos vacios.
                                </div>
                              <?php
                          break;
                      }
                  }
                
                ?>

                <div class="text-center">
                    <img src="../public/img/usuario.png" class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3" id = "imgtipo" width="200px" alt="profile">
                    <h3 id="signTitle" class="sign-title mt-2 mb-3">Acceso Usuario</h3>
                </div>
                
                <style>
                  .login-input-half { width:60%; max-width:320px; margin-left:auto; margin-right:auto; }
                  @media (max-width:575.98px){ .login-input-half { width:90%; max-width:none; } }
                </style>

                <div class = "form-group mb-3 login-input-half">
                  <input type="text" id = "correo" name = "correo" class = "form-control" placeholder="Correo Electrónico">
                </div>

                <div class = "form-group login-input-half">
                    <input type="password" id = "contrasenia" name = "contrasenia" class = "form-control" placeholder="Contraseña">
                </div>

                <div class="mx-auto login-input-half mt-3 mb-3">
                  <div class="d-flex justify-content-between">
                    <a href="#" id="btnsoporte" class="text-dark">Acceso Soporte</a>
                    <a href="reset-password.html" id="cambiar_contrasena" class="text-dark">Cambiar Contraseña</a>
                  </div>
                </div>

                <input type="hidden" name = "enviar" class = "form-control" value = "si">
                  <input type="hidden" id="login_mode" name="login_mode" value="user">
                  <input type="hidden" id="rol_id" name="rol_id" value="">
                <div class="text-center mt-3 mb-3">
                  <button type = "submit" class = "btn" style="background:#5bc0de;color:#fff;border:2px solid #4ac0da;min-width:220px;border-radius:6px;padding:8px 22px;">Iniciar Sesión</button>
                </div>
                  <script>
                    (function(){
                      var soporteLink = document.getElementById('btnsoporte');
                      var signTitle = document.getElementById('signTitle');
                      var loginMode = document.getElementById('login_mode');
                      var imgTipo = document.getElementById('imgtipo');
                      if(!soporteLink || !signTitle || !loginMode || !imgTipo) return;

                      // Función para activar modo soporte
                      function activateSupport(){
                        signTitle.textContent = 'Acceso Soporte';
                        loginMode.value = 'support';
                        var rolInput = document.getElementById('rol_id');
                        if(rolInput) rolInput.value = '2'; // rol_id 2 = Soporte
                        imgTipo.src = '../public/img/soporte.jpg'; // Cambiar imagen a soporte
                        soporteLink.textContent = 'Acceso Usuario';
                        soporteLink.classList.add('fw-bold');
                      }

                      // Función para volver a modo usuario
                      function activateUser(){
                        signTitle.textContent = 'Acceso Usuario';
                        loginMode.value = 'user';
                        var rolInput = document.getElementById('rol_id');
                        if(rolInput) rolInput.value = '1'; // rol_id 1 = Usuario
                        imgTipo.src = '../public/img/usuario.png'; // Cambiar imagen a usuario
                        soporteLink.textContent = 'Acceso Soporte';
                        soporteLink.classList.remove('fw-bold');
                      }

                      // Estado inicial: Modo usuario (rol_id = 1)
                      activateUser();

                      soporteLink.addEventListener('click', function(e){
                        e.preventDefault();
                        if(loginMode.value === 'user'){
                          activateSupport();
                        } else {
                          activateUser();
                        }
                      });
                    })();
                  </script>
            </form>
        </div>
      </div>
    </div>
  </div>

<script src="public/js/lib/jquery/jquery.min.js"></script>
<script src="public/js/lib/tether/tether.min.js"></script>
<script src="public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="public/js/plugins.js"></script>
<script type="text/javascript" src="public/js/lib/match-height/jquery.matchHeight.min.js"></script>
<script>
    $(function() {
        $('.page-center').matchHeight({
            target: $('html')
        });

        $(window).resize(function(){
            setTimeout(function(){
                $('.page-center').matchHeight({ remove: true });
                $('.page-center').matchHeight({
                    target: $('html')
                });
            },100);
        });
    });
</script>
<script src="public/js/app.js"></script>

<script type="text/javascript" src="datos.js"></script>

</body>
</html>