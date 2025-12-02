<?php
class Ticket extends Conectar {

    public function insertar_ticket($usu_id,$cat_id,$tick_asunto,$tick_descrip) {
        $conectar = parent::Conexion();
        parent::set_names();

        // Obtener el nombre de la categoría para asignar prioridad
        $sql_cat = "SELECT cat_nom FROM tm_categoria WHERE cat_id = ?";
        $stmt_cat = $conectar->prepare($sql_cat);
        $stmt_cat->bindValue(1, $cat_id);
        $stmt_cat->execute();
        $categoria = $stmt_cat->fetch();
        $cat_nom = $categoria['cat_nom'] ?? '';

        // Asignar prioridad según la categoría (devuelve número)
        $prioridad = $this->obtener_id_prioridad_por_categoria($cat_nom);

        $sql = "INSERT INTO tm_ticket
                (usu_id, cat_id, tick_asunto, tick_descrip, fecha_crea, est, tick_estado, tick_prioridad)
                VALUES (?, ?, ?, ?, NOW(), 1, 'Abierto', ?)";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->bindValue(2, $cat_id);
        $stmt->bindValue(3, $tick_asunto);
        $stmt->bindValue(4, $tick_descrip);
        $stmt->bindValue(5, $prioridad);
        $stmt->execute();

        return $conectar->lastInsertId();
    }

    public function obtener_id_prioridad_por_categoria($categoria) {
        // Mapeo de categorías a ID de prioridades
        // 1 = Baja, 2 = Media, 3 = Alta, 4 = Urgente
        $categorias_prioridad = array(
            'Cotizacion' => 4,      // Urgente
            'Consulta Stock' => 3,  // Alta
            'Personal' => 2,        // Media
            'Otros' => 1            // Baja
        );

        return isset($categorias_prioridad[$categoria]) ? $categorias_prioridad[$categoria] : 2; // Por defecto Media
    }

    public function obtener_prioridad_por_categoria($categoria) {
        // Mapeo de categorías a prioridades (DEPRECATED - usar obtener_id_prioridad_por_categoria)
        $categorias_prioridad = array(
            'Cotizacion' => 'Urgente',
            'Consulta Stock' => 'Alta',
            'Personal' => 'Media',
            'Otros' => 'Baja'
        );

        return isset($categorias_prioridad[$categoria]) ? $categorias_prioridad[$categoria] : 'Media';
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
                    tm_ticket.tick_prioridad,
                    tm_ticket.usu_asig,
                    tm_usuario.nombre,
                    tm_usuario.apellido,
                    tm_categoria.cat_nom,
                    COALESCE(CONCAT(tm_usuario_asignado.nombre, ' ', tm_usuario_asignado.apellido), '-') as asignado_a,
                    COALESCE(CONCAT(tm_usuario_soporte.nombre, ' ', tm_usuario_soporte.apellido), '-') as cerrado_por
                FROM tm_ticket
                INNER JOIN tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id
                INNER JOIN tm_usuario ON tm_ticket.usu_id = tm_usuario.usu_id
                LEFT JOIN tm_usuario as tm_usuario_asignado ON tm_ticket.usu_asig = tm_usuario_asignado.usu_id
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
                    tm_ticket.tick_prioridad,
                    tm_ticket.usu_asig,
                    tm_usuario.nombre,
                    tm_usuario.apellido,
                    tm_categoria.cat_nom,
                    COALESCE(CONCAT(tm_usuario_asignado.nombre, ' ', tm_usuario_asignado.apellido), '-') as asignado_a,
                    COALESCE(CONCAT(tm_usuario_soporte.nombre, ' ', tm_usuario_soporte.apellido), '-') as cerrado_por
                FROM tm_ticket
                INNER JOIN tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id
                INNER JOIN tm_usuario ON tm_ticket.usu_id = tm_usuario.usu_id
                LEFT JOIN tm_usuario as tm_usuario_asignado ON tm_ticket.usu_asig = tm_usuario_asignado.usu_id
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
            td_ticketdetalle.leido,
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

    // Contar respuestas no leídas de un ticket
    public function contar_respuestas_no_leidas($tick_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT COUNT(*) as no_leidas FROM td_ticketdetalle 
                WHERE tick_id = ? AND leido = 0 AND usu_id != ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $tick_id);
        $stmt->bindValue(2, $_SESSION['usu_id']);
        $stmt->execute();
        $result = $stmt->fetch();

        return isset($result['no_leidas']) ? $result['no_leidas'] : 0;
    }

    // Marcar respuestas como leídas
    public function marcar_respuestas_leidas($tick_id, $usu_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "UPDATE td_ticketdetalle SET leido = 1, leido_por = ?, fecha_leido = NOW() 
                WHERE tick_id = ? AND leido = 0 AND usu_id != ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->bindValue(2, $tick_id);
        $stmt->bindValue(3, $usu_id);
        $stmt->execute();

        return true;
    }

    // Listar tickets pendientes (abiertos asignados a un soporte SIN respuestas)
    public function listar_tickets_pendientes_asignados($usu_asig) {
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
                    tm_ticket.tick_prioridad,
                    tm_ticket.usu_asig,
                    tm_usuario.nombre,
                    tm_usuario.apellido,
                    tm_categoria.cat_nom,
                    COALESCE(CONCAT(tm_usuario_soporte.nombre, ' ', tm_usuario_soporte.apellido), '-') as asignado_a,
                    '-' as cerrado_por
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
                WHERE tm_ticket.tick_estado IN ('Abierto', 'En Revision')
                ORDER BY tm_ticket.fecha_crea DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Listar tickets en revisión (tickets donde el soporte respondió al menos una vez)
    public function listar_tickets_revision($usu_asig) {
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
                    tm_ticket.tick_prioridad,
                    tm_ticket.usu_asig,
                    tm_usuario.nombre,
                    tm_usuario.apellido,
                    tm_categoria.cat_nom,
                    COALESCE(CONCAT(tm_usuario_asignado.nombre, ' ', tm_usuario_asignado.apellido), '-') as asignado_a,
                    '-' as cerrado_por
                FROM tm_ticket
                INNER JOIN tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id
                INNER JOIN tm_usuario ON tm_ticket.usu_id = tm_usuario.usu_id
                LEFT JOIN tm_usuario as tm_usuario_asignado ON tm_ticket.usu_asig = tm_usuario_asignado.usu_id
                WHERE tm_ticket.usu_asig = ? 
                AND tm_ticket.tick_estado = 'En Revision'
                ORDER BY tm_ticket.fecha_crea DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_asig);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Listar tickets por usuario filtrando por estado (para usuarios normales)
    public function listar_ticket_por_usu_estado($usu_id, $estado) {
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
                    tm_ticket.tick_prioridad,
                    tm_ticket.usu_asig,
                    tm_usuario.nombre,
                    tm_usuario.apellido,
                    tm_categoria.cat_nom,
                    COALESCE(CONCAT(tm_usuario_asignado.nombre, ' ', tm_usuario_asignado.apellido), '-') as asignado_a,
                    COALESCE(CONCAT(tm_usuario_soporte.nombre, ' ', tm_usuario_soporte.apellido), '-') as cerrado_por
                FROM tm_ticket
                INNER JOIN tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id
                INNER JOIN tm_usuario ON tm_ticket.usu_id = tm_usuario.usu_id
                LEFT JOIN tm_usuario as tm_usuario_asignado ON tm_ticket.usu_asig = tm_usuario_asignado.usu_id
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
                AND tm_ticket.tick_estado = ?
                ORDER BY tm_ticket.fecha_crea DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->bindValue(2, $estado);
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
                    tm_ticket.tick_prioridad,
                    tm_ticket.fech_cierre,
                    tm_ticket.tick_estre,
                    tm_ticket.tick_coment,
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

        // Cambiar estado del ticket a 'En Revisión' si está 'Abierto' (primera respuesta)
        $this->actualizar_estado_respondido($tick_id);

        return $conectar->lastInsertId();
    }

    // Función que actualiza el estado del ticket
    public function update_ticket($tick_id) {
        try {
            $conectar = parent::Conexion();
            parent::set_names();

            $sql = "UPDATE tm_ticket SET tick_estado = 'Cerrado', fech_cierre = NOW() WHERE tick_id = ?";

            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $tick_id, PDO::PARAM_INT);
            $result = $stmt->execute();

            return $result;
        } catch (Exception $e) {
            error_log("Error en update_ticket: " . $e->getMessage());
            return false;
        }
    }

    public function insert_ticketdetalle_cerrar($tick_id, $usu_id) {
        try {
            $conectar = parent::Conexion();
            parent::set_names();

            $sql = "INSERT INTO td_ticketdetalle (tick_id, usu_id, tickd_descrip, fech_crea, est) VALUES (?, ?, 'Ticket cerrado', NOW(), 1)";

            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $tick_id);
            $stmt->bindValue(2, $usu_id);
            $result = $stmt->execute();

            return $result;
        } catch (Exception $e) {
            error_log("Error en insert_ticketdetalle_cerrar: " . $e->getMessage());
            return false;
        }
    }

    public function insert_ticketdetalle_cierre($tick_id, $usu_id, $comentarios_cierre) {
        try {
            $conectar = parent::Conexion();
            parent::set_names();

            // Insertar comentarios de cierre con un marcador especial
            $comentario_formateado = "[CIERRE] " . $comentarios_cierre;
            
            $sql = "INSERT INTO td_ticketdetalle (tick_id, usu_id, tickd_descrip, fech_crea, est) VALUES (?, ?, ?, NOW(), 1)";

            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $tick_id);
            $stmt->bindValue(2, $usu_id);
            $stmt->bindValue(3, $comentario_formateado);
            $result = $stmt->execute();

            return $result;
        } catch (Exception $e) {
            error_log("Error en insert_ticketdetalle_cierre: " . $e->getMessage());
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

    // Obtener estadísticas por usuario de soporte
    public function get_ticket_totalcerrado_por_usuario($usu_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT COUNT(DISTINCT tm_ticket.tick_id) as total 
                FROM tm_ticket 
                INNER JOIN td_ticketdetalle ON tm_ticket.tick_id = td_ticketdetalle.tick_id
                WHERE tm_ticket.tick_estado = 'Cerrado' 
                AND td_ticketdetalle.usu_id = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function get_ticket_total_por_usuario($usu_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT COUNT(DISTINCT tm_ticket.tick_id) as total 
                FROM tm_ticket 
                INNER JOIN td_ticketdetalle ON tm_ticket.tick_id = td_ticketdetalle.tick_id
                WHERE td_ticketdetalle.usu_id = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function get_ticket_totalabierto_por_usuario($usu_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT COUNT(DISTINCT tm_ticket.tick_id) as total 
                FROM tm_ticket 
                INNER JOIN td_ticketdetalle ON tm_ticket.tick_id = td_ticketdetalle.tick_id
                WHERE tm_ticket.tick_estado = 'Abierto' 
                AND td_ticketdetalle.usu_id = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function get_ticket_grafico_por_usuario($usu_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT tm_categoria.cat_nom as nom, COUNT(DISTINCT tm_ticket.tick_id) as total 
                FROM tm_categoria
                LEFT JOIN tm_ticket ON tm_categoria.cat_id = tm_ticket.cat_id AND tm_ticket.tick_estado = 'Cerrado'
                LEFT JOIN td_ticketdetalle ON tm_ticket.tick_id = td_ticketdetalle.tick_id AND td_ticketdetalle.usu_id = ?
                GROUP BY tm_categoria.cat_id, tm_categoria.cat_nom
                ORDER BY tm_categoria.cat_nom";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener tickets cerrados HOY por usuario por categoría
    public function get_ticket_grafico_hoy_por_usuario($usu_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT tm_categoria.cat_nom as nom, COUNT(DISTINCT tm_ticket.tick_id) as total 
                FROM tm_categoria
                LEFT JOIN tm_ticket ON tm_categoria.cat_id = tm_ticket.cat_id 
                    AND tm_ticket.tick_estado = 'Cerrado'
                    AND DATE(tm_ticket.fecha_crea) = DATE(NOW())
                LEFT JOIN td_ticketdetalle ON tm_ticket.tick_id = td_ticketdetalle.tick_id 
                    AND td_ticketdetalle.usu_id = ?
                GROUP BY tm_categoria.cat_id, tm_categoria.cat_nom
                HAVING total > 0
                ORDER BY tm_categoria.cat_nom";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener TOTAL de tickets cerrados HISTÓRICOS por usuario por categoría
    public function get_ticket_grafico_total_por_usuario($usu_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT tm_categoria.cat_nom as nom, COUNT(DISTINCT tm_ticket.tick_id) as total 
                FROM tm_categoria
                LEFT JOIN tm_ticket ON tm_categoria.cat_id = tm_ticket.cat_id AND tm_ticket.tick_estado = 'Cerrado'
                LEFT JOIN td_ticketdetalle ON tm_ticket.tick_id = td_ticketdetalle.tick_id AND td_ticketdetalle.usu_id = ?
                GROUP BY tm_categoria.cat_id, tm_categoria.cat_nom
                ORDER BY tm_categoria.cat_nom";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Insertar encuesta de satisfacción
    public function insert_encuesta($tick_id, $tick_estre, $tick_coment) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "UPDATE tm_ticket SET tick_estre = ?, tick_coment = ? WHERE tick_id = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $tick_estre);
        $stmt->bindValue(2, $tick_coment);
        $stmt->bindValue(3, $tick_id);
        $stmt->execute();

        return true;
    }

    // Insertar archivo adjunto
    public function insert_archivo($tick_id, $arch_nombre, $arch_ruta) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "INSERT INTO td_archivo (tick_id, arch_nombre, arch_ruta, fech_crea, est) 
                VALUES (?, ?, ?, NOW(), 1)";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $tick_id);
        $stmt->bindValue(2, $arch_nombre);
        $stmt->bindValue(3, $arch_ruta);
        $stmt->execute();

        return $conectar->lastInsertId();
    }

    // Listar archivos de un ticket
    public function listar_archivos_ticket($tick_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT * FROM td_archivo WHERE tick_id = ? AND est = 1 ORDER BY fech_crea DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $tick_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener el siguiente usuario de Soporte para distribución Round-Robin
    public function obtener_siguiente_soporte() {
        $conectar = parent::Conexion();
        parent::set_names();

        // 1. Obtener todos los usuarios de Soporte (rol_id = 2)
        $sql_soporte = "SELECT usu_id FROM tm_usuario WHERE rol_id = 2 AND estado = 1 ORDER BY usu_id";
        $stmt_soporte = $conectar->prepare($sql_soporte);
        $stmt_soporte->execute();
        $soportes = $stmt_soporte->fetchAll(PDO::FETCH_COLUMN);

        if (empty($soportes)) {
            return null; // No hay soporte disponible
        }

        // 2. Obtener el último usuario de soporte que recibió un ticket
        $sql_ultimo = "SELECT usu_asig FROM tm_ticket WHERE usu_asig IS NOT NULL ORDER BY tick_id DESC LIMIT 1";
        $stmt_ultimo = $conectar->prepare($sql_ultimo);
        $stmt_ultimo->execute();
        $ultimo_asignado = $stmt_ultimo->fetch(PDO::FETCH_COLUMN);

        // 3. Si no hay último asignado, devolver el primero
        if (!$ultimo_asignado) {
            return $soportes[0];
        }

        // 4. Buscar la posición del último asignado
        $posicion = array_search($ultimo_asignado, $soportes);

        // 5. Si está en la lista, devolver el siguiente; si no, devolver el primero
        if ($posicion !== false && $posicion < count($soportes) - 1) {
            return $soportes[$posicion + 1];
        } else {
            return $soportes[0]; // Volver al primero
        }
    }

    // Asignar un ticket a un usuario de Soporte
    public function asignar_ticket($tick_id, $usu_asig) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "UPDATE tm_ticket SET usu_asig = ?, fech_asig = NOW() WHERE tick_id = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_asig);
        $stmt->bindValue(2, $tick_id);
        $stmt->execute();

        return true;
    }

        // Actualizar estado del ticket a 'En Revision' cuando hay primera respuesta
        public function actualizar_estado_respondido($tick_id, $usu_id = null){
            $conectar = parent::Conexion();
            parent::set_names();
            try {
                // Solo cambiar a 'En Revision' si el ticket está en estado 'Abierto'
                // También asignar el ticket al soporte que responde
                if ($usu_id) {
                    $sql = "UPDATE tm_ticket SET tick_estado='En Revision', usu_asig=? WHERE tick_id=? AND tick_estado='Abierto';";
                    $stmt = $conectar->prepare($sql);
                    $stmt->bindValue(1, $usu_id);
                    $stmt->bindValue(2, $tick_id);
                } else {
                    $sql = "UPDATE tm_ticket SET tick_estado='En Revision' WHERE tick_id=? AND tick_estado='Abierto';";
                    $stmt = $conectar->prepare($sql);
                    $stmt->bindValue(1, $tick_id);
                }
                $stmt->execute();
            } catch (Exception $e) {
                error_log("Error en actualizar_estado_respondido: " . $e->getMessage());
            }
        }    // Re-abrir un ticket (cambiar de 'Cerrado' a 'Abierto')
    public function reabrir_ticket($tick_id, $usu_id) {
        try {
            $conectar = parent::Conexion();
            parent::set_names();

            // Cambiar el estado a 'Abierto' y eliminar fecha de cierre
            $sql = "UPDATE tm_ticket SET tick_estado = 'Abierto', fech_cierre = NULL WHERE tick_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $tick_id);
            $result = $stmt->execute();

            if ($result) {
                // Registrar la acción en el detalle del ticket
                $sql_detalle = "INSERT INTO td_ticketdetalle (tick_id, usu_id, tickd_descrip, fech_crea, est) VALUES (?, ?, 'Ticket reabierto', NOW(), 1)";
                $stmt_detalle = $conectar->prepare($sql_detalle);
                $stmt_detalle->bindValue(1, $tick_id);
                $stmt_detalle->bindValue(2, $usu_id);
                $stmt_detalle->execute();
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error en reabrir_ticket: " . $e->getMessage());
            return false;
        }
    }

    // Mi Desempeño Hoy
    public function desempenio_hoy($usu_asig) {
        $conectar = parent::Conexion();
        parent::set_names();

        $hoy = date("Y-m-d");
        
        // Tickets abiertos asignados a este soporte
        $sql_abiertos = "SELECT COUNT(*) as total FROM tm_ticket WHERE usu_asig = ? AND tick_estado = 'Abierto'";
        $stmt = $conectar->prepare($sql_abiertos);
        $stmt->bindValue(1, $usu_asig);
        $stmt->execute();
        $abiertos = $stmt->fetch()["total"];

        // Tickets que han recibido respuesta del soporte hoy
        $sql_respondidos = "SELECT COUNT(DISTINCT tm_ticket.tick_id) as total 
                           FROM tm_ticket
                           INNER JOIN td_ticketdetalle ON tm_ticket.tick_id = td_ticketdetalle.tick_id
                           WHERE tm_ticket.usu_asig = ? 
                           AND td_ticketdetalle.usu_id = ?
                           AND DATE(td_ticketdetalle.fech_crea) = ?";
        $stmt = $conectar->prepare($sql_respondidos);
        $stmt->bindValue(1, $usu_asig);
        $stmt->bindValue(2, $usu_asig);
        $stmt->bindValue(3, $hoy);
        $stmt->execute();
        $respondidos = $stmt->fetch()["total"];

        // Tickets cerrados hoy
        $sql_cerrados = "SELECT COUNT(*) as total FROM tm_ticket 
                        WHERE usu_asig = ? AND tick_estado = 'Cerrado' AND DATE(fech_cierre) = ?";
        $stmt = $conectar->prepare($sql_cerrados);
        $stmt->bindValue(1, $usu_asig);
        $stmt->bindValue(2, $hoy);
        $stmt->execute();
        $cerrados = $stmt->fetch()["total"];

        return array(
            "abiertos" => $abiertos,
            "respondidos" => $respondidos,
            "cerrados" => $cerrados
        );
    }

    // Comparativo: Tickets cerrados hoy vs semana pasada
    public function comparativo_periodo($usu_asig) {
        $conectar = parent::Conexion();
        parent::set_names();

        $hoy = date("Y-m-d");
        $semana_pasada = date("Y-m-d", strtotime("-7 days"));

        // Cerrados hoy
        $sql_hoy = "SELECT COUNT(*) as total FROM tm_ticket 
                   WHERE usu_asig = ? AND tick_estado = 'Cerrado' AND DATE(fech_cierre) = ?";
        $stmt = $conectar->prepare($sql_hoy);
        $stmt->bindValue(1, $usu_asig);
        $stmt->bindValue(2, $hoy);
        $stmt->execute();
        $cerrados_hoy = $stmt->fetch()["total"];

        // Cerrados hace 7 días (mismo día semana pasada)
        $fecha_semana_pasada = date("Y-m-d", strtotime("-7 days"));
        $sql_semana = "SELECT COUNT(*) as total FROM tm_ticket 
                      WHERE usu_asig = ? AND tick_estado = 'Cerrado' AND DATE(fech_cierre) = ?";
        $stmt = $conectar->prepare($sql_semana);
        $stmt->bindValue(1, $usu_asig);
        $stmt->bindValue(2, $fecha_semana_pasada);
        $stmt->execute();
        $cerrados_semana_pasada = $stmt->fetch()["total"];

        // Calcular variación
        $variacion = $cerrados_hoy - $cerrados_semana_pasada;
        if ($cerrados_semana_pasada > 0) {
            $pct_cambio = round(($variacion / $cerrados_semana_pasada) * 100, 1);
        } else {
            $pct_cambio = $cerrados_hoy > 0 ? 100 : 0;
        }

        return array(
            "hoy" => $cerrados_hoy,
            "semana_pasada" => $cerrados_semana_pasada,
            "variacion" => $variacion,
            "pct_cambio" => $pct_cambio
        );
    }

    // Tasa de Respuesta Rápida (respondidos en < 4 horas)
    public function tasa_respuesta_rapida($usu_asig) {
        $conectar = parent::Conexion();
        parent::set_names();

        // Tickets que el soporte ha respondido
        $sql_total = "SELECT COUNT(DISTINCT tm_ticket.tick_id) as total 
                     FROM tm_ticket
                     INNER JOIN td_ticketdetalle ON tm_ticket.tick_id = td_ticketdetalle.tick_id
                     WHERE tm_ticket.usu_asig = ? AND td_ticketdetalle.usu_id = ?";
        $stmt = $conectar->prepare($sql_total);
        $stmt->bindValue(1, $usu_asig);
        $stmt->bindValue(2, $usu_asig);
        $stmt->execute();
        $total_respondidos = $stmt->fetch()["total"];

        // Tickets respondidos en < 4 horas
        $sql_rapidos = "SELECT COUNT(DISTINCT tm_ticket.tick_id) as total 
                       FROM tm_ticket
                       INNER JOIN td_ticketdetalle ON tm_ticket.tick_id = td_ticketdetalle.tick_id
                       WHERE tm_ticket.usu_asig = ? 
                       AND td_ticketdetalle.usu_id = ?
                       AND TIMESTAMPDIFF(HOUR, tm_ticket.fecha_crea, td_ticketdetalle.fech_crea) < 4";
        $stmt = $conectar->prepare($sql_rapidos);
        $stmt->bindValue(1, $usu_asig);
        $stmt->bindValue(2, $usu_asig);
        $stmt->execute();
        $rapidos = $stmt->fetch()["total"];

        // Calcular porcentaje
        if ($total_respondidos > 0) {
            $pct = round(($rapidos / $total_respondidos) * 100, 1);
        } else {
            $pct = 0;
        }

        return array(
            "rapidos" => $rapidos,
            "total" => $total_respondidos,
            "pct" => $pct
        );
    }
}
?>

