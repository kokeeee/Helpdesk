<?php
    session_start();

    class Conectar{
        protected $dbh;

        protected function Conexion(){
            try {
                // Leer variables de entorno (Railway) o usar valores locales
                $host = getenv('DB_HOST') ?: 'localhost';
                $dbname = getenv('DB_NAME') ?: 'helpdesk';
                $user = getenv('DB_USER') ?: 'root';
                $password = getenv('DB_PASS') ?: '';
                $port = getenv('DB_PORT') ?: '3306';

                $conectar = $this->dbh = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);
                $conectar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                return $conectar;
            } catch (Exception $e) {
                print "¡Error BD!: " . $e->getMessage() . "<br/>";
                die();
            }
        }

        public function set_names(){
            return $this->dbh->query("SET NAMES 'utf8'");
        }

        public static function ruta(){
            // Detectar si estamos en Railway
            if (!empty($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                return $protocol . $_SERVER['HTTP_HOST'] . '/';
            }
            
            // Uso local
            return "http://localhost/HelpDesk/";
        }

    }
?>