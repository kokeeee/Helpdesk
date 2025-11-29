<?php
class Ticket extends Conectar {

    public function insertar_ticket($usu_id,$cat_id,$tick_asunto,$tick_descrip) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "INSERT INTO tm_ticket
                (usu_id, cat_id, tick_asunto, tick_descrip, fecha_crea, est, tick_estado)
                VALUES (?, ?, ?, ?, NOW(), 1, 'Abierto')";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->bindValue(2, $cat_id);
        $stmt->bindValue(3, $tick_asunto);
        $stmt->bindValue(4, $tick_descrip);
        $stmt->execute();

        return $conectar->lastInsertId();
    }

    public function listar_ticket_por_usu($usu_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT
                    tm_ticket.tick_id,
                    tm_ticket.usu_id,
                    tm_ticket.cat_id,
                    tm_ticket.tick_asunto,
                    tm_ticket.tick_descrip,
                    tm_ticket.fecha_crea,
                    tm_ticket.est,
                    tm_ticket.tick_estado,
                    tm_usuario.nombre,
                    tm_usuario.apellido,
                    tm_categoria.cat_nom,
                    COALESCE(CONCAT(tm_usuario_soporte.nombre, ' ', tm_usuario_soporte.apellido), '-') as cerrado_por
                FROM tm_ticket
                INNER JOIN tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id
                INNER JOIN tm_usuario ON tm_ticket.usu_id = tm_usuario.usu_id
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
                WHERE tm_ticket.usu_id = ?
                ORDER BY tm_ticket.fecha_crea DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function listar_ticket() {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT
                    tm_ticket.tick_id,
                    tm_ticket.usu_id,
                    tm_ticket.cat_id,
                    tm_ticket.tick_asunto,
                    tm_ticket.tick_descrip,
                    tm_ticket.fecha_crea,
                    tm_ticket.est,
                    tm_ticket.tick_estado,
                    tm_usuario.nombre,
                    tm_usuario.apellido,
                    tm_categoria.cat_nom,
                    COALESCE(CONCAT(tm_usuario_soporte.nombre, ' ', tm_usuario_soporte.apellido), '-') as cerrado_por
                FROM tm_ticket
                INNER JOIN tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id
                INNER JOIN tm_usuario ON tm_ticket.usu_id = tm_usuario.usu_id
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
                ORDER BY tm_ticket.fecha_crea DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    
    public function listar_ticket_detalle_por_ticket($tick_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT
            td_ticketdetalle.tickd_id,
            td_ticketdetalle.tickd_descrip,
            td_ticketdetalle.fech_crea,
            tm_usuario.nombre,
            tm_usuario.apellido,
            tm_usuario.rol_id
            FROM
            td_ticketdetalle
            INNER JOIN tm_usuario ON td_ticketdetalle.usu_id = tm_usuario.usu_id
            WHERE tick_id = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $tick_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function listar_ticket_por_id($tick_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT
                    tm_ticket.tick_id,
                    tm_ticket.usu_id,
                    tm_ticket.cat_id,
                    tm_ticket.tick_asunto,
                    tm_ticket.tick_descrip,
                    tm_ticket.fecha_crea,
                    tm_ticket.est,
                    tm_ticket.tick_estado,
                    tm_usuario.nombre,
                    tm_usuario.apellido,
                    tm_categoria.cat_nom
                FROM tm_ticket
                INNER JOIN tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id
                INNER JOIN tm_usuario ON tm_ticket.usu_id = tm_usuario.usu_id
                WHERE tm_ticket.tick_id = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $tick_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function insert_ticketdetalle($tick_id, $usu_id,$tickd_descrip) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "INSERT INTO td_ticketdetalle (tick_id, usu_id, tickd_descrip, fech_crea, est) VALUES (?, ?, ?, NOW(), 1)";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $tick_id);
        $stmt->bindValue(2, $usu_id);
        $stmt->bindValue(3, $tickd_descrip);
        $stmt->execute();

        return $conectar->lastInsertId();
    }

    #funcion que actualiza el estado del ticket
    public function update_ticket($tick_id) {
        try {
            $conectar = parent::Conexion();
            parent::set_names();

            $sql = "UPDATE tm_ticket SET tick_estado = 'Cerrado' WHERE tick_id = ?";

            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $tick_id, PDO::PARAM_INT);
            $result = $stmt->execute();

            return $result;
        } catch (Exception $e) {
            error_log("Error en update_ticket: " . $e->getMessage());
            return false;
        }
    }
    
    public function insert_ticketdetalle_cerrar($tick_id, $usu_id,) {
            try {
                $conectar = parent::Conexion();
                parent::set_names();

                $sql = "UPDATE td_ticket_detalle (tick_id, usu_id, tickd_descrip, fech_crea, est) VALUES (?, ?, 'Ticket cerrado', NOW(), 1)";

                $stmt = $conectar->prepare($sql);
                $stmt->bindValue(1, $tick_id);
                $stmt->bindValue(1, $usu_id);
                $result = $stmt->execute();

                return $result;
            } catch (Exception $e) {
                error_log("Error en update_ticket: " . $e->getMessage());
                return false;
            }
        }

    public function get_ticket_total() {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT COUNT(*) as total FROM tm_ticket";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function get_ticket_totalabierto() {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT COUNT(*) as total FROM tm_ticket WHERE tick_estado = 'Abierto' OR est = 1";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function get_ticket_totalcerrado() {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT COUNT(*) as total FROM tm_ticket WHERE tick_estado = 'Cerrado' OR est = 0";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function get_ticket_grafico() {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT tm_categoria.cat_nom as nom, COUNT(tm_ticket.tick_id) as total 
                FROM tm_ticket 
                INNER JOIN tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id 
                GROUP BY tm_categoria.cat_id, tm_categoria.cat_nom";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}
?>
