<?php
    session_start();

    class Conectar{
        protected $dbh;

        protected function Conexion(){
            try {
                // Detectar si estamos en Heroku o en desarrollo local
                if (getenv('HEROKU') || getenv('CLEARDB_DATABASE_URL')) {
                    // Configuración para Heroku (ClearDB MySQL)
                    $url = parse_url(getenv('CLEARDB_DATABASE_URL'));
                    $host = $url['host'];
                    $dbname = ltrim($url['path'], '/');
                    $user = $url['user'];
                    $password = $url['pass'];
                } else {
                    // Configuración para desarrollo local
                    $host = getenv('DB_HOST') ?: 'localhost';
                    $dbname = getenv('DB_NAME') ?: 'helpdesk';
                    $user = getenv('DB_USER') ?: 'root';
                    $password = getenv('DB_PASS') ?: '';
                }

                $conectar = $this->dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
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
            // Detectar si estamos en Heroku
            if (getenv('HEROKU') || !empty($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'herokuapp.com') !== false) {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
                return $protocol . $_SERVER['HTTP_HOST'] . '/';
            }
            
            return "http://localhost/HelpDesk/";
		}

    }
?>