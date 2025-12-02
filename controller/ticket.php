<?php
    require_once '../config/conexion.php';
    require_once '../models/Ticket.php';

    // Verificar que el usuario esté autenticado
    if (!isset($_SESSION["usu_id"])) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(array("success" => false, "message" => "No autenticado"));
        exit();
    }

    $ticket = new Ticket();

    switch ($_GET["op"]) {
        case "insertar":
            // ✅ VALIDAR CSRF PARA OPERACIONES DE ESCRITURA
            if (!validar_csrf_token($_POST['csrf_token'] ?? '')) {
                echo json_encode(array("success" => false, "message" => "Error de seguridad: Token CSRF inválido"));
                exit();
            }
            $usu_id = $_POST["usu_id"];
            $cat_id = $_POST["cat_id"];
            $tick_asunto = $_POST["tick_asunto"];
            $tick_descrip = $_POST["tick_descrip"];

            $id = $ticket->insertar_ticket($usu_id, $cat_id, $tick_asunto, $tick_descrip);

            // Obtener el siguiente usuario de Soporte para asignación automática (Round-Robin)
            $usu_asig = $ticket->obtener_siguiente_soporte();
            
            // Asignar el ticket al siguiente soporte disponible
            if ($usu_asig) {
                $ticket->asignar_ticket($id, $usu_asig);
            }

            // Procesar archivos si existen
            if (isset($_FILES['fileElem']) && !empty($_FILES['fileElem']['name'][0])) {
                $directorio = '../../public/uploads/tickets/';
                
                // Crear directorio si no existe
                if (!is_dir($directorio)) {
                    mkdir($directorio, 0755, true);
                }

                $total_files = count($_FILES['fileElem']['name']);
                for ($i = 0; $i < $total_files; $i++) {
                    if ($_FILES['fileElem']['error'][$i] == 0) {
                        $nombre_archivo = $_FILES['fileElem']['name'][$i];
                        $archivo_temporal = $_FILES['fileElem']['tmp_name'][$i];
                        $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                        
                        // Sanitizar nombre de archivo
                        $nombre_nuevo = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $nombre_archivo);
                        $ruta_guardado = $directorio . $nombre_nuevo;
                        
                        if (move_uploaded_file($archivo_temporal, $ruta_guardado)) {
                            $ticket->insert_archivo($id, $nombre_archivo, 'public/uploads/tickets/' . $nombre_nuevo);
                        }
                    }
                }
            }

            echo $id;
        break;
        
        // Cerrar ticket
        case "update":
            // Validar que solo Soporte (rol_id = 2) y Super Admin (rol_id = 3) puedan cerrar tickets
            if (!isset($_SESSION["rol_id"]) || ($_SESSION["rol_id"] != 2 && $_SESSION["rol_id"] != 3)) {
                echo json_encode(array("success" => false, "message" => "No tienes permiso para cerrar tickets. Solo el personal de Soporte puede hacerlo."));
                exit();
            }

            $ticket -> update_ticket($_POST["tick_id"]);
            $ticket -> insert_ticketdetalle_cerrar($_POST["tick_id"],$_POST["usu_id"]);

            if(isset($_POST["tick_id"])) {
                $result = $ticket->update_ticket($_POST["tick_id"]);
                if($result) {
                    echo json_encode(array("success" => true, "message" => "Ticket cerrado correctamente"));
                } else {
                    echo json_encode(array("success" => false, "message" => "Error al cerrar ticket"));
                }
            } else {
                echo json_encode(array("success" => false, "message" => "tick_id no recibido"));
            }
        break;

        case "listar_por_usuario":
            $datos = $ticket->listar_ticket_por_usu($_POST["usu_id"]);
            $data = Array();
            
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["tick_id"];
                $sub_array[] = $row["cat_nom"];
                $sub_array[] = $row["tick_asunto"];
                
                // Contar respuestas no leídas
                $no_leidas = $ticket->contar_respuestas_no_leidas($row["tick_id"]);
                if ($no_leidas > 0) {
                    $sub_array[] = '<span class="label label-pill label-danger">' . $no_leidas . '</span>';
                } else {
                    $sub_array[] = '-';
                }
                
                // Estado con etiqueta visual basada en columna 'tick_estado'
                if (isset($row["tick_estado"]) && $row["tick_estado"] == "Cerrado") {
                    $sub_array[] = '<span class="label label-pill label-danger">Cerrado</span>';
                } else if (isset($row["tick_estado"]) && $row["tick_estado"] == "En Revision") {
                    $sub_array[] = '<span class="label label-pill label-warning">En Revision</span>';
                } else {
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }
                $sub_array[] = $row["cerrado_por"];
                $sub_array[] = date("d/m/Y H:i", strtotime($row["fecha_crea"]));
                $sub_array[] = '<button type="button" onClick="ver_ticket('.$row["tick_id"].');" id="'.$row["tick_id"].'" class="btn btn-inline btn-primary btn-sm btn_ver"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                $data[] = $sub_array;

            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
        break;

        case "listar":
            $datos = $ticket->listar_ticket();
            $data = Array();
            
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["tick_id"];
                $sub_array[] = $row["cat_nom"];
                $sub_array[] = $row["tick_asunto"];
                
                // Contar respuestas no leídas
                $no_leidas = $ticket->contar_respuestas_no_leidas($row["tick_id"]);
                if ($no_leidas > 0) {
                    $sub_array[] = '<span class="label label-pill label-danger">' . $no_leidas . '</span>';
                } else {
                    $sub_array[] = '-';
                }
                
                // Estado con etiqueta visual basada en columna 'tick_estado'
                if (isset($row["tick_estado"]) && $row["tick_estado"] == "Cerrado") {
                    $sub_array[] = '<span class="label label-pill label-danger">Cerrado</span>';
                } else if (isset($row["tick_estado"]) && $row["tick_estado"] == "En Revision") {
                    $sub_array[] = '<span class="label label-pill label-warning">En Revision</span>';
                } else {
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }
                $sub_array[] = $row["cerrado_por"];
                $sub_array[] = date("d/m/Y H:i", strtotime($row["fecha_crea"]));
                $sub_array[] = '<button type="button" onClick="ver_ticket('.$row["tick_id"].');" id="'.$row["tick_id"].'" class="btn btn-inline btn-primary btn-sm btn_ver"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                $data[] = $sub_array;

            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
        break;

        case "listar_pendientes":
            $datos = $ticket->listar_tickets_pendientes_asignados($_POST["usu_asig"]);
            $data = Array();
            
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["tick_id"];
                $sub_array[] = $row["cat_nom"];
                $sub_array[] = $row["tick_asunto"];
                
                // Contar respuestas no leídas
                $no_leidas = $ticket->contar_respuestas_no_leidas($row["tick_id"]);
                if ($no_leidas > 0) {
                    $sub_array[] = '<span class="label label-pill label-danger">' . $no_leidas . '</span>';
                } else {
                    $sub_array[] = '-';
                }
                
                // Estado con etiqueta visual basada en columna 'tick_estado'
                if (isset($row["tick_estado"]) && $row["tick_estado"] == "Cerrado") {
                    $sub_array[] = '<span class="label label-pill label-danger">Cerrado</span>';
                } else if (isset($row["tick_estado"]) && $row["tick_estado"] == "En Revision") {
                    $sub_array[] = '<span class="label label-pill label-warning">En Revision</span>';
                } else {
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }
                $sub_array[] = $row["asignado_a"];
                $sub_array[] = date("d/m/Y H:i", strtotime($row["fecha_crea"]));
                $sub_array[] = '<button type="button" onClick="ver_ticket('.$row["tick_id"].');" id="'.$row["tick_id"].'" class="btn btn-inline btn-primary btn-sm btn_ver"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                $data[] = $sub_array;

            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
        break;

        case "listar_revision":
            $datos = $ticket->listar_tickets_revision($_POST["usu_asig"]);
            $data = Array();
            
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["tick_id"];
                $sub_array[] = $row["cat_nom"];
                $sub_array[] = $row["tick_asunto"];
                
                // Contar respuestas no leídas
                $no_leidas = $ticket->contar_respuestas_no_leidas($row["tick_id"]);
                if ($no_leidas > 0) {
                    $sub_array[] = '<span class="label label-pill label-danger">' . $no_leidas . '</span>';
                } else {
                    $sub_array[] = '-';
                }
                
                // Estado con etiqueta visual basada en columna 'tick_estado'
                if (isset($row["tick_estado"]) && $row["tick_estado"] == "Cerrado") {
                    $sub_array[] = '<span class="label label-pill label-danger">Cerrado</span>';
                } else if (isset($row["tick_estado"]) && $row["tick_estado"] == "En Revision") {
                    $sub_array[] = '<span class="label label-pill label-warning">En Revision</span>';
                } else {
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }
                $sub_array[] = $row["cerrado_por"];
                $sub_array[] = date("d/m/Y H:i", strtotime($row["fecha_crea"]));
                $sub_array[] = '<button type="button" onClick="ver_ticket('.$row["tick_id"].');" id="'.$row["tick_id"].'" class="btn btn-inline btn-primary btn-sm btn_ver"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                $data[] = $sub_array;

            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
        break;

        case 'listardetalle':
            $datos = $ticket -> listar_ticket_detalle_por_ticket($_POST["tick_id"]);

            ?>
                <?php
                    foreach($datos as $row){
                        ?>
                            <article class="activity-line-item box-typical">
                                <div class="activity-line-date">
                                    <?php echo date("d/m/Y H:i", strtotime($row["fech_crea"]))?>
                                </div>
                                <header class="activity-line-item-header">
                                    <div class="activity-line-item-user">
                                        <div class="activity-line-item-user-photo">
                                            <a href="#">
                                                <img src="../../public/img/<?php echo ($row['rol_id'] == 1) ? 'usuario.png' : 'soporte.jpg'; ?>" alt="">
                                            </a>
                                        </div>
                                        <div class="activity-line-item-user-name"><?php echo $row['nombre'].' '.$row['apellido']?></div>
                                        <div class="activity-line-item-user-status">
                                            <?php
                                                if ($row['rol_id'] == 1){
                                                        echo 'Usuario';
                                                } else {
                                                        echo 'Soporte';
                                                }
                                            $row['nombre']
                                            ?>

                                        </div>
                                    </div>
                                </header>
                                <div class="activity-line-action-list">
                                    <section class="activity-line-action">
                                        <div class="time"><?php echo date("H:i:s", strtotime($row["fech_crea"]))?></div>
                                        <div class="cont">
                                            <div class="cont-in">
                                                <?php echo $row["tickd_descrip"]?>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </article>
                        <?php
                    }
                ?>
            <?php
        break;

        case 'mostrar':
            $datos = $ticket->listar_ticket_por_id($_POST["tick_id"]);
            if (isset($datos) && is_array($datos) && count($datos) > 0) {
                $row = $datos[0];
                
                // Determinar estado basado en tick_estado con tres opciones
                $tick_estado_texto = (isset($row["tick_estado"]) && $row["tick_estado"] == "Cerrado") ? "Cerrado" : (isset($row["tick_estado"]) && $row["tick_estado"] == "En Revision" ? "En Revision" : "Abierto");
                
                // Obtener nombre de prioridad
                $prioridades_map = array(
                    1 => "Baja",
                    2 => "Media",
                    3 => "Alta",
                    4 => "Urgente"
                );
                $prio_nom = isset($prioridades_map[$row["tick_prioridad"]]) ? $prioridades_map[$row["tick_prioridad"]] : "N/A";
                
                $resultado = array(
                    "tick_id" => $row["tick_id"],
                    "tick_estado" => $tick_estado_texto,
                    "tick_estado_texto" => $tick_estado_texto,
                    "nombre" => $row["nombre"],
                    "apellido" => $row["apellido"],
                    "fech_crea" => $row["fecha_crea"],
                    "fech_cierre" => $row["fech_cierre"],
                    "cat_nom" => $row["cat_nom"],
                    "tick_asunto" => $row["tick_asunto"],
                    "tick_descrip" => $row["tick_descrip"],
                    "prio_nom" => $prio_nom,
                    "tick_estre" => $row["tick_estre"],
                    "tick_coment" => $row["tick_coment"]
                );
                echo json_encode($resultado);
            } else {
                echo json_encode(array("error" => "No encontrado"));
            }
        break;

        case "insertdetalle":
            $ticket->insert_ticketdetalle($_POST["tick_id"], $_POST["usu_id"], $_POST["tickd_descrip"]);
            // Actualizar estado del ticket a 'En Revisión' si está 'Abierto' (primera respuesta)
            // Pasar el usu_id del soporte que está respondiendo para asignación automática
            $ticket->actualizar_estado_respondido($_POST["tick_id"], $_POST["usu_id"]);
            echo json_encode(array("success" => true, "message" => "Detalle agregado correctamente"));
        break;

        case "total":
            $datos = $ticket->get_ticket_total();
            if (is_array($datos) && count($datos) > 0) {
                echo json_encode(array("TOTAL" => $datos[0]["total"]));
            } else {
                echo json_encode(array("TOTAL" => 0));
            }
        break;

        case "totalabierto":
            $datos = $ticket->get_ticket_totalabierto();
            if (is_array($datos) && count($datos) > 0) {
                echo json_encode(array("TOTAL" => $datos[0]["total"]));
            } else {
                echo json_encode(array("TOTAL" => 0));
            }
        break;

        case "totalcerrado":
            $datos = $ticket->get_ticket_totalcerrado();
            if (is_array($datos) && count($datos) > 0) {
                echo json_encode(array("TOTAL" => $datos[0]["total"]));
            } else {
                echo json_encode(array("TOTAL" => 0));
            }
        break;

        case "grafico":
            $datos = $ticket->get_ticket_grafico();
            echo json_encode($datos);
        break;

        case "total_por_usuario":
            $datos = $ticket->get_ticket_total_por_usuario($_POST["usu_id"]);
            if (is_array($datos) && count($datos) > 0) {
                echo json_encode(array("TOTAL" => $datos[0]["total"]));
            } else {
                echo json_encode(array("TOTAL" => 0));
            }
        break;

        case "totalabierto_por_usuario":
            $datos = $ticket->get_ticket_totalabierto_por_usuario($_POST["usu_id"]);
            if (is_array($datos) && count($datos) > 0) {
                echo json_encode(array("TOTAL" => $datos[0]["total"]));
            } else {
                echo json_encode(array("TOTAL" => 0));
            }
        break;

        case "totalcerrado_por_usuario":
            $datos = $ticket->get_ticket_totalcerrado_por_usuario($_POST["usu_id"]);
            if (is_array($datos) && count($datos) > 0) {
                echo json_encode(array("TOTAL" => $datos[0]["total"]));
            } else {
                echo json_encode(array("TOTAL" => 0));
            }
        break;

        case "grafico_por_usuario":
            $datos = $ticket->get_ticket_grafico_por_usuario($_POST["usu_id"]);
            echo json_encode($datos);
        break;

        case "grafico_hoy_por_usuario":
            $datos = $ticket->get_ticket_grafico_hoy_por_usuario($_POST["usu_id"]);
            echo json_encode($datos);
        break;

        case "grafico_total_por_usuario":
            $datos = $ticket->get_ticket_grafico_total_por_usuario($_POST["usu_id"]);
            echo json_encode($datos);
        break;

        case "contar_respuestas_no_leidas":
            $tick_id = $_POST["tick_id"];
            $no_leidas = $ticket->contar_respuestas_no_leidas($tick_id);
            echo json_encode(array("no_leidas" => $no_leidas));
        break;

        case "marcar_respuestas_leidas":
            $tick_id = $_POST["tick_id"];
            $usu_id = $_SESSION["usu_id"];
            $ticket->marcar_respuestas_leidas($tick_id, $usu_id);
            echo json_encode(array("success" => true));
        break;

        case "prioridades":
            $datos = $ticket->get_prioridades();
            $resultado = array();
            foreach($datos as $row) {
                $resultado[] = array(
                    "tick_prioridad" => $row["prio_id"],
                    "prio_nom" => $row["prio_nom"]
                );
            }
            echo json_encode($resultado);
        break;

        case "encuesta":
            $resultado = $ticket->insert_encuesta($_POST["tick_id"], $_POST["tick_estre"], $_POST["tick_coment"]);
            echo json_encode(array("success" => true, "message" => "Encuesta registrada"));
        break;

        case "listar_archivos":
            $datos = $ticket->listar_archivos_ticket($_POST["tick_id"]);
            echo json_encode($datos);
        break;

        // Re-abrir ticket
        case "reabrir":
            // Validar que solo Soporte (rol_id = 2) y Super Admin (rol_id = 3) puedan re-abrir tickets
            if (!isset($_SESSION["rol_id"]) || ($_SESSION["rol_id"] != 2 && $_SESSION["rol_id"] != 3)) {
                echo json_encode(array("success" => false, "message" => "No tienes permiso para re-abrir tickets. Solo el personal de Soporte puede hacerlo."));
                exit();
            }

            if(isset($_POST["tick_id"]) && isset($_POST["usu_id"])) {
                $result = $ticket->reabrir_ticket($_POST["tick_id"], $_POST["usu_id"]);
                if($result) {
                    echo json_encode(array("success" => true, "message" => "Ticket reabierto correctamente"));
                } else {
                    echo json_encode(array("success" => false, "message" => "Error al re-abrir ticket"));
                }
            } else {
                echo json_encode(array("success" => false, "message" => "Datos incompletos"));
            }
        break;

        // Contar respuestas sin leer en tickets pendientes
        case "contar_no_leidos_pendientes":
            $usu_asig = $_POST["usu_asig"];
            $datos = $ticket->listar_tickets_pendientes_asignados($usu_asig);
            $total_no_leidos = 0;
            
            if ($datos && is_array($datos)) {
                foreach($datos as $row) {
                    $no_leidos = $ticket->contar_respuestas_no_leidas($row["tick_id"]);
                    if ($no_leidos > 0) {
                        $total_no_leidos += $no_leidos;
                    }
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode(array("total" => $total_no_leidos));
        break;

        // Contar respuestas sin leer en tickets en revisión
        case "contar_no_leidos_revision":
            $usu_asig = $_POST["usu_asig"];
            $datos = $ticket->listar_tickets_revision($usu_asig);
            $total_no_leidos = 0;
            
            if ($datos && is_array($datos)) {
                foreach($datos as $row) {
                    $no_leidos = $ticket->contar_respuestas_no_leidas($row["tick_id"]);
                    if ($no_leidos > 0) {
                        $total_no_leidos += $no_leidos;
                    }
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode(array("total" => $total_no_leidos));
        break;

        case "desempenio_hoy":
            // Mi desempeño hoy (tickets abiertos, respondidos, cerrados)
            $usu_id = $_POST["usu_id"];
            $result = $ticket->desempenio_hoy($usu_id);
            echo json_encode($result);
        break;

        case "comparativo_periodo":
            // Comparativo: tickets cerrados hoy vs semana pasada
            $usu_id = $_POST["usu_id"];
            $result = $ticket->comparativo_periodo($usu_id);
            echo json_encode($result);
        break;

        case "tasa_respuesta_rapida":
            // Tasa de respuesta rápida (% respondidos en < 4 horas)
            $usu_id = $_POST["usu_id"];
            $result = $ticket->tasa_respuesta_rapida($usu_id);
            echo json_encode($result);
        break;
    }
?>

