<?php
    require_once ("../config/conexion.php");
    require_once ("../models/Usuario.php");

    $usuario = new Usuario();

    switch ($_GET["op"]) {
        case "guardaryeditar":
            // Verificar que el usuario sea SuperAdmin (rol_id = 3) para gestionar usuarios
            if (!isset($_SESSION["rol_id"]) || $_SESSION["rol_id"] != 3) {
                echo json_encode(array("status" => "error", "mensaje" => "No tienes permiso para acceder a esta sección. Solo SuperAdmin puede gestionar usuarios."));
                exit();
            }
            try {
                // Validar que SuperAdmin solo puede crear/editar usuarios de Soporte (rol_id = 2)
                if ($_POST["rol_id"] != 2) {
                    echo json_encode(array("status" => "error", "mensaje" => "SuperAdmin solo puede gestionar usuarios de Soporte"));
                    exit();
                }

                if (empty($_POST["usu_id"])) {
                    $usuario->insert_usuario($_POST["nombre"], $_POST["apellido"], $_POST["correo"], $_POST["contrasenia"], $_POST["rol_id"]);
                    echo json_encode(array("status" => "success", "mensaje" => "Usuario de Soporte creado correctamente"));
                } else {
                    $usuario->update_usuario($_POST["usu_id"], $_POST["nombre"], $_POST["apellido"], $_POST["correo"], $_POST["contrasenia"], $_POST["rol_id"]);
                    echo json_encode(array("status" => "success", "mensaje" => "Usuario de Soporte actualizado correctamente"));
                }
            } catch (Exception $e) {
                echo json_encode(array("status" => "error", "mensaje" => $e->getMessage()));
            }
        break;

        case "listar":
            // Verificar que el usuario sea SuperAdmin (rol_id = 3) para listar usuarios
            if (!isset($_SESSION["rol_id"]) || $_SESSION["rol_id"] != 3) {
                echo json_encode(array("status" => "error", "mensaje" => "No tienes permiso para acceder a esta sección. Solo SuperAdmin puede gestionar usuarios."));
                exit();
            }
            $datos = $usuario->get_usuario();
            $data = Array();

            foreach($datos as $row){
                // SuperAdmin solo puede gestionar usuarios de Soporte (rol_id = 2)
                if ($row["rol_id"] != 2) {
                    continue;
                }

                $sub_array = array();
                $sub_array[] = $row["nombre"];
                $sub_array[] = $row["apellido"];
                $sub_array[] = $row["correo"];
                $sub_array[] = '<span class="label label-pill label-info">Soporte</span>';
                $sub_array[] = $row["contrasenia"];

                if ($row["estado"] == 1) {
                    $sub_array[] = '<span class="label label-pill label-success">Activo</span>';
                } else {
                    $sub_array[] = '<span class="label label-pill label-danger">Inactivo</span>';
                }

                $sub_array[] = date("d/m/Y H:i", strtotime($row["fecha_crea"]));
                $sub_array[] = '<button type="button" onClick="editar('.$row["usu_id"].');" id="'.$row["usu_id"].'" class="btn btn-inline btn-primary btn-sm btn_editar"><i class="fa fa-edit" aria-hidden="true"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["usu_id"].');" id="'.$row["usu_id"].'" class="btn btn-inline btn-danger btn-sm btn_eliminar"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
    
        break;
    
        case "eliminar":
            // Verificar que el usuario sea SuperAdmin (rol_id = 3) para eliminar usuarios
            if (!isset($_SESSION["rol_id"]) || $_SESSION["rol_id"] != 3) {
                echo json_encode(array("status" => "error", "mensaje" => "No tienes permiso para acceder a esta sección. Solo SuperAdmin puede gestionar usuarios."));
                exit();
            }
            try {
                $resultado = $usuario->delete_usuario($_POST["usu_id"]);
                if ($resultado) {
                    echo json_encode(array("status" => "success", "mensaje" => "Usuario eliminado correctamente"));
                } else {
                    echo json_encode(array("status" => "error", "mensaje" => "Error al eliminar el usuario"));
                }
            } catch (Exception $e) {
                echo json_encode(array("status" => "error", "mensaje" => $e->getMessage()));
            }
        break;

        case "mostrar":
            // Verificar que el usuario sea SuperAdmin (rol_id = 3) para mostrar usuarios
            if (!isset($_SESSION["rol_id"]) || $_SESSION["rol_id"] != 3) {
                echo json_encode(array("status" => "error", "mensaje" => "No tienes permiso para acceder a esta sección. Solo SuperAdmin puede gestionar usuarios."));
                exit();
            }
            $datos = $usuario->get_usuario_por_id($_POST["usu_id"]);
            if (is_array($datos) == true and count($datos) > 0) {
                foreach ($datos as $row) {
                    // SEGURIDAD: SuperAdmin solo puede ver usuarios de Soporte (rol_id = 2)
                    if ($row["rol_id"] != 2) {
                        echo json_encode(array("status" => "error", "mensaje" => "No tienes permiso para ver este usuario"));
                        exit();
                    }
                    
                    $output["usu_id"] = $row["usu_id"];
                    $output["nombre"] = $row["nombre"];
                    $output["apellido"] = $row["apellido"];
                    $output["correo"] = $row["correo"];
                    $output["contrasenia"] = $row["contrasenia"];
                    $output["rol_id"] = $row["rol_id"];
                }
                echo json_encode($output);
            }
        break;

        case "total":
            $datos = $usuario->get_usuario_total_por_id($_POST["usu_id"]);
            if (is_array($datos) && count($datos) > 0) {
                echo json_encode(array("TOTAL" => $datos[0]["total"]));
            } else {
                echo json_encode(array("TOTAL" => 0));
            }
        break;

        case "totalabierto":
            $datos = $usuario->get_usuario_totalabierto_por_id($_POST["usu_id"]);
            if (is_array($datos) && count($datos) > 0) {
                echo json_encode(array("TOTAL" => $datos[0]["total"]));
            } else {
                echo json_encode(array("total" => 0));
            }
        break;

        case "totalcerrado":
            $datos = $usuario->get_usuario_totalcerrado_por_id($_POST["usu_id"]);
            if (is_array($datos) && count($datos) > 0) {
                echo json_encode(array("TOTAL" => $datos[0]["total"]));
            } else {
                echo json_encode(array("TOTAL" => 0));
            }
        break;

        case "grafico":
            $datos = $usuario->get_usuario_grafico_por_id($_POST["usu_id"]);
            echo json_encode($datos);
        break;

        case "cambiar_contrasenia":
            if (!isset($_SESSION["usu_id"])) {
                echo json_encode(array("success" => false, "message" => "Debes estar autenticado"));
                exit();
            }

            $usu_id = $_SESSION["usu_id"];
            $pass_actual = $_POST["pass_actual"];
            $pass_nueva = $_POST["pass_nueva"];
            $pass_confirmar = $_POST["pass_confirmar"];

            $resultado = $usuario->cambiar_contrasenia($usu_id, $pass_actual, $pass_nueva, $pass_confirmar);
            echo json_encode($resultado);
        break;
    }
    
?>