<header class="site-header">
    <a href="#" class="site-logo">
        <img class="hidden-md-down" src="../../public/img/logo_navbar.png" alt="">
        <img class="hidden-lg-up" src="../../public/img/logo_navbar.png" alt="">
    </a>

    <button id="show-hide-sidebar-toggle" class="show-hide-sidebar">
        <span>toggle menu</span>
    </button>

    <button class="hamburger hamburger--htla">
        <span>toggle menu</span>
    </button>
    
    <div class="site-header-content">
        <div class="site-header-content-in">
            <div class="site-header-shown">
                <div style="display: flex; align-items: center; gap: 10px; justify-content: flex-end; margin-top : -35px;">
                    <div class="dropdown dropdown-typical" style="display: flex; align-items: center; gap: 5px;">
                        <a href="#" class="dropdown-toggle no-arr" style="display: flex; align-items: center; gap: 5px; white-space: nowrap;">
                            <span class="font-icon font-icon-user"></span>
                            <span class="lbl-user"><?php echo $_SESSION["nombre"] ?> <?php echo $_SESSION["apellido"] ?></span>
                        </a>
                    </div>

                    <div class="dropdown user-menu">
                        <button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center; padding: 5px;">
                            <img src="../../public/img/avatar-sign.png" alt="" style="width: 30px; height: 30px;">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
                            <a class="dropdown-item" href="../MntPerfil/"><span class="font-icon glyphicon glyphicon-user"></span>Perfil</a>
                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-question-sign"></span>Ayuda</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../Logout/logout.php"><span class="font-icon glyphicon glyphicon-log-out"></span>Cerrar Sesion</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mobile-menu-right-overlay"></div>
            
            <!--Roles de Usuario -->
            <input type="hidden" id = "user_idx" value="<?php echo isset($_SESSION["usu_id"]) ? $_SESSION["usu_id"] : '' ?>">
            <input type="hidden" id = "rol_idx" value="<?php echo isset($_SESSION["rol_id"]) ? $_SESSION["rol_id"] : '' ?>">
        </div>
    </div>
</header>