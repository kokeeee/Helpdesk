<?php
    session_start();
    
    // Incluir funciones CSRF
    require_once(__DIR__ . '/csrf.php');

    class Conectar{
        protected $dbh;

        protected function Conexion(){
            try {
				$conectar = $this->dbh = new PDO("mysql:host=localhost;dbname=heldesk","root","");
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
			return "http://localhost/HelpDesk/";
		}

    }
?>