<?php
class Estadistica extends Conectar {

    // Obtener total de tickets cerrados
    public function get_tickets_cerrados_total() {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT COUNT(*) as total FROM tm_ticket WHERE tick_estado = 'Cerrado'";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener tickets cerrados por soporte
    public function get_tickets_cerrados_por_soporte() {
        $conectar = parent::Conexion();
        parent::set_names();

        // ✅ CORREGIDO: Contar TICKETS ÚNICOS, no comentarios
        $sql = "SELECT 
                    tm_usuario.usu_id,
                    tm_usuario.nombre,
                    tm_usuario.apellido,
                    COUNT(DISTINCT td_ticketdetalle.tick_id) as total_cerrados
                FROM td_ticketdetalle
                INNER JOIN tm_usuario ON td_ticketdetalle.usu_id = tm_usuario.usu_id
                INNER JOIN tm_ticket ON td_ticketdetalle.tick_id = tm_ticket.tick_id
                WHERE tm_ticket.tick_estado = 'Cerrado'
                AND tm_usuario.rol_id = 2
                GROUP BY tm_usuario.usu_id, tm_usuario.nombre, tm_usuario.apellido
                ORDER BY total_cerrados DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener estadísticas generales del equipo de soporte
    public function get_estadisticas_generales() {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT 
                    COUNT(CASE WHEN tm_ticket.tick_estado = 'Abierto' THEN 1 END) as tickets_abiertos,
                    COUNT(CASE WHEN tm_ticket.tick_estado = 'Cerrado' THEN 1 END) as tickets_cerrados,
                    COUNT(CASE WHEN tm_ticket.tick_estado IS NOT NULL AND tm_ticket.tick_estado != '' THEN 1 END) as tickets_totales,
                    (SELECT COUNT(*) FROM tm_usuario WHERE rol_id = 2 AND estado = 1) as total_soporte,
                    ROUND((COUNT(CASE WHEN tm_ticket.tick_estado = 'Cerrado' THEN 1 END) / COUNT(CASE WHEN tm_ticket.tick_estado IS NOT NULL AND tm_ticket.tick_estado != '' THEN 1 END)) * 100, 2) as porcentaje_cerrados
                FROM tm_ticket
                WHERE tm_ticket.tick_estado IS NOT NULL AND tm_ticket.tick_estado != ''";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener tickets cerrados por soporte en el último mes
    public function get_tickets_cerrados_mes() {
        $conectar = parent::Conexion();
        parent::set_names();

        // ✅ CORREGIDO: Contar TICKETS ÚNICOS, no comentarios
        $sql = "SELECT 
                    tm_usuario.usu_id,
                    tm_usuario.nombre,
                    tm_usuario.apellido,
                    COUNT(DISTINCT td_ticketdetalle.tick_id) as total_cerrados
                FROM td_ticketdetalle
                INNER JOIN tm_usuario ON td_ticketdetalle.usu_id = tm_usuario.usu_id
                INNER JOIN tm_ticket ON td_ticketdetalle.tick_id = tm_ticket.tick_id
                WHERE tm_ticket.tick_estado = 'Cerrado'
                AND tm_usuario.rol_id = 2
                AND MONTH(td_ticketdetalle.fech_crea) = MONTH(NOW())
                AND YEAR(td_ticketdetalle.fech_crea) = YEAR(NOW())
                GROUP BY tm_usuario.usu_id, tm_usuario.nombre, tm_usuario.apellido
                ORDER BY total_cerrados DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener tickets cerrados por soporte para gráfico
    public function get_grafico_soporte() {
        $conectar = parent::Conexion();
        parent::set_names();

        // ✅ CORREGIDO: Contar TICKETS ÚNICOS, no comentarios
        $sql = "SELECT 
                    tm_usuario.usu_id,
                    tm_usuario.nombre,
                    tm_usuario.apellido,
                    COUNT(DISTINCT td_ticketdetalle.tick_id) as total_cerrados
                FROM td_ticketdetalle
                INNER JOIN tm_usuario ON td_ticketdetalle.usu_id = tm_usuario.usu_id
                INNER JOIN tm_ticket ON td_ticketdetalle.tick_id = tm_ticket.tick_id
                WHERE tm_ticket.tick_estado = 'Cerrado'
                AND tm_usuario.rol_id = 2
                GROUP BY tm_usuario.usu_id, tm_usuario.nombre, tm_usuario.apellido
                ORDER BY total_cerrados DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener categorías con más tickets cerrados
    public function get_categorias_mas_cerradas() {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT 
                    tm_categoria.cat_nom,
                    COUNT(tm_ticket.tick_id) as total_cerrados
                FROM tm_ticket
                INNER JOIN tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id
                WHERE tm_ticket.tick_estado = 'Cerrado'
                GROUP BY tm_categoria.cat_id, tm_categoria.cat_nom
                ORDER BY total_cerrados DESC
                LIMIT 5";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener distribución de tickets por categoría (Gráfico Pastel)
    public function get_tickets_por_categoria() {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT 
                    tm_categoria.cat_nom as label,
                    COUNT(tm_ticket.tick_id) as value
                FROM tm_ticket
                INNER JOIN tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id
                WHERE tm_ticket.tick_estado IS NOT NULL AND tm_ticket.tick_estado != ''
                GROUP BY tm_categoria.cat_id, tm_categoria.cat_nom
                ORDER BY value DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener tiempo promedio de resolución
    public function get_tiempo_promedio_resolucion() {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT 
                    ROUND(AVG(TIMESTAMPDIFF(HOUR, tm_ticket.fecha_crea, COALESCE(td_ticketdetalle.fech_crea, NOW()))), 2) as horas_promedio,
                    ROUND(AVG(TIMESTAMPDIFF(HOUR, tm_ticket.fecha_crea, COALESCE(td_ticketdetalle.fech_crea, NOW()))) / 24, 2) as dias_promedio
                FROM tm_ticket
                LEFT JOIN td_ticketdetalle ON tm_ticket.tick_id = td_ticketdetalle.tick_id
                WHERE tm_ticket.tick_estado = 'Cerrado'";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener distribución de tickets por estado (Gráfico Pastel)
    public function get_distribucion_estado() {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT 
                    tick_estado as label,
                    COUNT(*) as value
                FROM tm_ticket
                WHERE tick_estado IS NOT NULL AND tick_estado != ''
                GROUP BY tick_estado";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener últimos tickets cerrados
    public function get_ultimos_tickets_cerrados($limite = 5) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT 
                    tm_ticket.tick_id,
                    tm_ticket.tick_asunto,
                    tm_usuario.nombre,
                    tm_usuario.apellido,
                    tm_categoria.cat_nom,
                    tm_ticket.fecha_crea,
                    COALESCE(CONCAT(tm_usuario_soporte.nombre, ' ', tm_usuario_soporte.apellido), '-') as cerrado_por
                FROM tm_ticket
                INNER JOIN tm_usuario ON tm_ticket.usu_id = tm_usuario.usu_id
                INNER JOIN tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id
                LEFT JOIN (
                    SELECT DISTINCT 
                        td_ticketdetalle.tick_id,
                        tm_usuario.nombre,
                        tm_usuario.apellido
                    FROM td_ticketdetalle
                    INNER JOIN tm_usuario ON td_ticketdetalle.usu_id = tm_usuario.usu_id
                    WHERE tm_usuario.rol_id = 2
                    ORDER BY td_ticketdetalle.fech_crea DESC
                ) tm_usuario_soporte ON tm_ticket.tick_id = tm_usuario_soporte.tick_id
                WHERE tm_ticket.tick_estado = 'Cerrado'
                ORDER BY tm_ticket.fecha_crea DESC
                LIMIT " . intval($limite);

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener carga de tickets por día (Últimos 7 días)
    public function get_carga_por_dia() {
        $conectar = parent::Conexion();
        parent::set_names();

        // Morris.js interpreta mejor fechas en formato ISO YYYY-MM-DD
        // O usar un formato personalizado que no sea interpretado como número
        $sql = "SELECT 
                    DATE_FORMAT(tm_ticket.fecha_crea, '%Y-%m-%d') as fecha,
                    CONCAT(DATE_FORMAT(tm_ticket.fecha_crea, '%d'), '/', DATE_FORMAT(tm_ticket.fecha_crea, '%m')) as label,
                    COUNT(*) as value
                FROM tm_ticket
                WHERE tm_ticket.fecha_crea >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                AND tm_ticket.tick_estado IS NOT NULL AND tm_ticket.tick_estado != ''
                GROUP BY DATE(tm_ticket.fecha_crea)
                ORDER BY tm_ticket.fecha_crea ASC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
?>
