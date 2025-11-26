<?php
    require_once '../config/conexion.php';
    require_once '../models/Ticket.php';

    $ticket = new Ticket();

    switch ($_GET["op"]) {
        case "insertar":
            $id = $ticket->insertar_ticket(
                $_POST["usu_id"],
                $_POST["cat_id"],
                $_POST["tick_asunto"],
                $_POST["tick_descrip"]
            );

            echo $id;
        break;
        
        // Cerrar ticket
        case "update":
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
                // Estado con etiqueta visual basada en columna 'tick_estado'
                if (isset($row["tick_estado"]) && $row["tick_estado"] == "Cerrado") {
                    $sub_array[] = '<span class="label label-pill label-danger">Cerrado</span>';
                } else {
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }
                $sub_array[] = $row["cerrado_por"];
                $sub_array[] = date("d/m/y H:i", strtotime($row["fecha_crea"]));
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
                // Estado con etiqueta visual basada en columna 'tick_estado'
                if (isset($row["tick_estado"]) && $row["tick_estado"] == "Cerrado") {
                    $sub_array[] = '<span class="label label-pill label-danger">Cerrado</span>';
                } else {
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }
                $sub_array[] = $row["cerrado_por"];
                $sub_array[] = date("d/m/y h:i:s", strtotime($row["fecha_crea"]));
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
                                    <?php echo date("d/m/y H:i", strtotime($row["fech_crea"]))?>
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
                
                // Determinar estado basado en tick_estado
                $tick_estado_texto = (isset($row["tick_estado"]) && $row["tick_estado"] == "Cerrado") ? "Cerrado" : "Abierto";
                
                $resultado = array(
                    "tick_id" => $row["tick_id"],
                    "tick_estado" => $tick_estado_texto,
                    "tick_estado_texto" => $tick_estado_texto,
                    "nombre" => $row["nombre"],
                    "apellido" => $row["apellido"],
                    "fech_crea" => $row["fecha_crea"],
                    "cat_nom" => $row["cat_nom"],
                    "tick_asunto" => $row["tick_asunto"],
                    "tick_descrip" => $row["tick_descrip"]
                );
                echo json_encode($resultado);
            } else {
                echo json_encode(array("error" => "No encontrado"));
            }
        break;

        case "insertdetalle":
            $ticket->insert_ticketdetalle($_POST["tick_id"], $_POST["usu_id"], $_POST["tickd_descrip"]);
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
    }
?>
