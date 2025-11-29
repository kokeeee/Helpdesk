<?php 
require_once("../config/conexion.php");

class Usuario extends Conectar{

    public function login(){
        parent::conexion(); // Inicializa $this->dbh
        parent::set_names();
        if (isset($_POST["enviar"])){
            $correo = trim($_POST["correo"]);
            $contrasenia = $_POST["contrasenia"];
            $rol = $_POST["rol_id"];
            
            if(empty($correo) || empty($contrasenia)){
                header("Location:".Conectar::ruta()."view/index.php?m=2");
                exit();
            } else {
                $sql = "SELECT * FROM tm_usuario WHERE correo = ? AND rol_id = ? AND estado = 1";
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindValue(1, $correo);
                $stmt->bindValue(2, $rol);
                $stmt->execute();

                $resultado = $stmt->fetch();

                if (is_array($resultado) && count($resultado)>0){
                    // Verificar contraseña en plaintext (comparación directa)
                    if ($contrasenia == $resultado["contrasenia"]){
                        $_SESSION["usu_id"] = $resultado["usu_id"];
                        $_SESSION["nombre"] = $resultado["nombre"];
                        $_SESSION["apellido"] = $resultado["apellido"];
                        $_SESSION["rol_id"] = $resultado["rol_id"];
                        $_SESSION["correo"] = $resultado["correo"];
                        
                        header("Location:".Conectar::ruta()."view/home/");
                        exit();
                    } else {
                        header("Location:".Conectar::ruta()."view/index.php?m=1");
                        exit();
                    }
                } else {
                    header("Location:".Conectar::ruta()."view/index.php?m=1");
                    exit();
                }
            }
        }
    }

    public function insert_usuario ($nombre, $apellido, $correo, $contrasenia, $rol_id) {
        $conectar = parent::conexion();  
        parent::set_names();
        
        $sql = "INSERT INTO tm_usuario (usu_id, nombre, apellido, correo, contrasenia, rol_id, fecha_crea, fecha_modifi, fecha_elim, estado) VALUES (NULL, ?, ?, ?, ?, ?, NOW(), NOW(), '0000-00-00 00:00:00', 1)";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $nombre);
        $sql->bindValue(2, $apellido);
        $sql->bindValue(3, $correo);
        $sql->bindValue(4, $contrasenia);
        $sql->bindValue(5, $rol_id);
        $sql->execute();
        
        return true;
    }

    public function update_usuario ($usu_id, $nombre, $apellido, $correo, $contrasenia, $rol_id) {
        $conectar = parent::conexion();  
        parent::set_names();

        if (!empty($contrasenia)) {
            $sql = "UPDATE tm_usuario SET nombre = ?, apellido = ?, correo = ?, contrasenia = ?, rol_id = ?, fecha_modifi = NOW() WHERE usu_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $nombre);
            $stmt->bindValue(2, $apellido);
            $stmt->bindValue(3, $correo);
            $stmt->bindValue(4, $contrasenia);
            $stmt->bindValue(5, $rol_id);
            $stmt->bindValue(6, $usu_id);
        } else {
            $sql = "UPDATE tm_usuario SET nombre = ?, apellido = ?, correo = ?, rol_id = ?, fecha_modifi = NOW() WHERE usu_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $nombre);
            $stmt->bindValue(2, $apellido);
            $stmt->bindValue(3, $correo);
            $stmt->bindValue(4, $rol_id);
            $stmt->bindValue(5, $usu_id);
        }
        
        $stmt->execute();
        return true;
    }

    public function delete_usuario ($usu_id) {
        $conectar = parent::conexion();  
        parent::set_names();
        $sql = "UPDATE tm_usuario SET estado = 0, fecha_elim = NOW() WHERE usu_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usu_id);
        $sql->execute();
        registrar_log('USUARIO', "Usuario eliminado", $_SESSION['usu_id'] ?? null);
        return true;
    }

    public function get_usuario(){
        $conectar = parent::conexion();  
            parent::set_names();
            $sql = "SELECT * FROM tm_usuario WHERE estado = 1;";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            return $sql->fetchAll();
    }

    public function get_usuario_por_id ($usu_id){
        $conectar = parent::conexion();  
        parent::set_names();
            $sql = "SELECT * FROM tm_usuario WHERE usu_id = ?;";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $sql->fetchAll();
    }

    public function get_usuario_total_por_id ($usu_id){
        $conectar = parent::conexion();  
        parent::set_names();
            $sql = "SELECT COUNT(*) as total FROM tm_ticket WHERE usu_id = ?;";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $sql->fetchAll();
    }

    public function get_usuario_totalabierto_por_id ($usu_id){
        $conectar = parent::conexion();  
        parent::set_names();
            $sql = "SELECT COUNT(*) as total FROM tm_ticket WHERE usu_id = ? and tick_estado = 'Abierto'";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $sql->fetchAll();
    }

    public function get_usuario_totalcerrado_por_id ($usu_id){
        $conectar = parent::conexion();  
        parent::set_names();
            $sql = "SELECT COUNT(*) as total FROM tm_ticket WHERE usu_id = ? and tick_estado = 'Cerrado'";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $sql->fetchAll();
    }

    public function get_usuario_grafico_por_id ($usu_id){
        $conectar = parent::conexion();  
        parent::set_names();
            $sql = "SELECT tm_categoria.cat_nom as nom, COUNT(tm_ticket.tick_id) as total 
                    FROM tm_ticket 
                    INNER JOIN tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id 
                    WHERE tm_ticket.usu_id = ? 
                    GROUP BY tm_categoria.cat_id, tm_categoria.cat_nom";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $sql->fetchAll();
    }

    public function cambiar_contrasenia($usu_id, $contrasenia_actual, $contrasenia_nueva, $contrasenia_confirmar) {
        $conectar = parent::conexion();  
        parent::set_names();

        $sql = "SELECT contrasenia FROM tm_usuario WHERE usu_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->execute();
        $resultado = $stmt->fetch();

        if ($contrasenia_actual == $resultado["contrasenia"]){
            if ($contrasenia_nueva == $contrasenia_confirmar){
                $sql = "UPDATE tm_usuario SET contrasenia = ?, fecha_modifi = NOW() WHERE usu_id = ?";
                $stmt = $conectar->prepare($sql);
                $stmt->bindValue(1, $contrasenia_nueva);
                $stmt->bindValue(2, $usu_id);
                $stmt->execute();
                
                return array("success" => true, "message" => "Contraseña actualizada correctamente");
            } else {
                return array("success" => false, "message" => "Las contraseñas nuevas no coinciden");
            }
        } else {
            return array("success" => false, "message" => "La contraseña actual es incorrecta");
        }
    }
}
?>
