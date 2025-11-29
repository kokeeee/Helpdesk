<?php
    if ($_SESSION["rol_id"]==1){
        ?>
            <nav class="side-menu">
                <ul class="side-menu-list">
                    <li class="blue-dirty">
                        <a href="..\Home\">
                            <span class="glyphicon glyphicon-th"></span>
                            <span class="lbl">Inicio</span>
                        </a>
                    </li>

                    <li class="blue-dirty">
                        <a href="..\NuevoTicket\">
                            <span class="glyphicon glyphicon-th"></span>
                            <span class="lbl">Nuevo Ticket</span>
                        </a>
                    </li>

                    <li class="blue-dirty">
                        <a href="..\ConsultarTicket\">
                            <span class="glyphicon glyphicon-th"></span>
                            <span class="lbl">Consultar Ticket</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php
    } elseif ($_SESSION["rol_id"]==2){
        ?>
            <nav class="side-menu">
                <ul class="side-menu-list">
                    <li class="blue-dirty">
                        <a href="..\Home\">
                            <span class="glyphicon glyphicon-th"></span>
                            <span class="lbl">Inicio</span>
                        </a>
                    </li>

                    <li class="blue-dirty menu-expandible" id="consultar-ticket-menu">
                        <a href="javascript:void(0);" class="toggle-submenu" style="display: flex !important; align-items: center !important; justify-content: space-between !important; width: 100% !important;">
                            <span style="display: flex; align-items: center; flex: 1;">
                                <span class="glyphicon glyphicon-th"></span>
                                <span class="lbl" style="margin-left: 10px;">Consultar Ticket</span>
                            </span>
                            <span class="glyphicon glyphicon-chevron-down submenu-icon" style="margin-left: 10px; font-size: 12px;"></span>
                        </a>
                        <ul class="submenu" style="list-style: none !important; margin: 0 !important; padding: 0 !important;">
                            <li style="margin: 0 !important; padding: 0 !important;"><a href="..\ConsultarTicket\?tipo=pendientes" class="submenu-link" data-tipo="pendientes"><span class="lbl">Tickets Pendientes</span><span class="label label-pill label-danger" id="badge-pendientes" style="display:none; margin-left: 10px;"></span></a></li>
                            <li style="margin: 0 !important; padding: 0 !important;"><a href="..\ConsultarTicket\?tipo=todos" class="submenu-link" data-tipo="todos"><span class="lbl">Todos los Tickets</span></a></li>
                        </ul>
                    </li>

                    <li class="blue-dirty">
                        <a href="..\Estadisticas\">
                            <span class="glyphicon glyphicon-th"></span>
                            <span class="lbl">Estadisticas</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php
    } else {
        ?>
            <nav class="side-menu">
                <ul class="side-menu-list">
                    <li class="blue-dirty">
                        <a href="..\Home\">
                            <span class="glyphicon glyphicon-th"></span>
                            <span class="lbl">Inicio</span>
                        </a>
                    </li>

                    <li class="blue-dirty menu-expandible" id="consultar-ticket-menu">
                        <a href="javascript:void(0);" class="toggle-submenu" style="display: flex !important; align-items: center !important; justify-content: space-between !important; width: 100% !important;">
                            <span style="display: flex; align-items: center; flex: 1;">
                                <span class="glyphicon glyphicon-th"></span>
                                <span class="lbl" style="margin-left: 10px;">Consultar Ticket</span>
                            </span>
                            <span class="glyphicon glyphicon-chevron-down submenu-icon" style="margin-left: 10px; font-size: 12px;"></span>
                        </a>
                        <ul class="submenu" style="list-style: none !important; margin: 0 !important; padding: 0 !important;">
                            <li style="margin: 0 !important; padding: 0 !important;"><a href="..\ConsultarTicket\?tipo=pendientes" class="submenu-link" data-tipo="pendientes"><span class="lbl">Tickets Pendientes</span><span class="label label-pill label-danger" id="badge-pendientes-admin" style="display:none; margin-left: 10px;"></span></a></li>
                            <li style="margin: 0 !important; padding: 0 !important;"><a href="..\ConsultarTicket\?tipo=todos" class="submenu-link" data-tipo="todos"><span class="lbl">Todos los Tickets</span></a></li>
                        </ul>
                    </li>

                    <li class="blue-dirty">
                        <a href="..\Estadisticas\">
                            <span class="glyphicon glyphicon-th"></span>
                            <span class="lbl">Estadisticas</span>
                        </a>
                    </li>
                    
                    <li class="blue-dirty">
                        <a href="..\MntUsuario\">
                            <span class="glyphicon glyphicon-th"></span>
                            <span class="lbl">Control de Usuarios</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php
    }
?>
