<?php
require_once("../config/conexion.php");
require_once("../models/Estadistica.php");

$estadistica = new Estadistica();

switch($_GET["op"]) {
    case "general":
        $datos = $estadistica->get_estadisticas_generales();
        echo json_encode($datos[0]);
    break;

    case "soporte_cerrados":
        $datos = $estadistica->get_tickets_cerrados_por_soporte();
        $data = Array();
        
        foreach($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["nombre"] . " " . $row["apellido"];
            $sub_array[] = $row["total_cerrados"];
            $data[] = $sub_array;
        }
        
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
    break;

    case "soporte_cerrados_mes":
        $datos = $estadistica->get_tickets_cerrados_mes();
        $data = Array();
        
        foreach($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["nombre"] . " " . $row["apellido"];
            $sub_array[] = $row["total_cerrados"];
            $data[] = $sub_array;
        }
        
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
    break;

    case "categorias":
        $datos = $estadistica->get_categorias_mas_cerradas();
        $data = Array();
        
        foreach($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["cat_nom"];
            $sub_array[] = $row["total_cerrados"];
            $data[] = $sub_array;
        }
        
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
    break;

    case "grafico_soporte":
        $datos = $estadistica->get_grafico_soporte();
        $resultado = Array();
        
        foreach($datos as $row) {
            $nombre_completo = $row["nombre"] . " " . $row["apellido"];
            $item = array(
                "name" => $nombre_completo,
                "value" => intval($row["total_cerrados"])
            );
            array_push($resultado, $item);
        }
        
        echo json_encode($resultado);
    break;

    case "tickets_por_categoria":
        $datos = $estadistica->get_tickets_por_categoria();
        echo json_encode($datos);
    break;

    case "tiempo_promedio":
        $datos = $estadistica->get_tiempo_promedio_resolucion();
        echo json_encode($datos[0]);
    break;

    case "distribucion_estado":
        $datos = $estadistica->get_distribucion_estado();
        echo json_encode($datos);
    break;

    case "ultimos_cerrados":
        $datos = $estadistica->get_ultimos_tickets_cerrados(5);
        $data = Array();
        
        foreach($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["tick_id"];
            $sub_array[] = $row["tick_asunto"];
            $sub_array[] = $row["nombre"] . " " . $row["apellido"];
            $sub_array[] = $row["cat_nom"];
            $sub_array[] = $row["cerrado_por"];
            $sub_array[] = date("d/m/Y H:i", strtotime($row["fecha_crea"]));
            $data[] = $sub_array;
        }
        
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
    break;

    case "carga_por_dia":
        $datos = $estadistica->get_carga_por_dia();
        echo json_encode($datos);
    break;
}
?>
