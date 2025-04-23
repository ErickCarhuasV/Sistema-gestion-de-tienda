<?php
class ConexionBD {
    const SERVIDOR = "localhost"; 
    const PUERTO = "1521"; 
    const SID = "XE"; 
    const USUARIO = "C##LEZAMA"; 
    const PASSWORD = "lezama"; 

    private $cn = null;

    public function getConexionBD() {
        if ($this->cn === null) { 
            try {
                putenv('NLS_LANG=.AL32UTF8');
                $dsn = "oci:dbname=//" . self::SERVIDOR . ":" . self::PUERTO . "/" . self::SID;
                $this->cn = new PDO($dsn, self::USUARIO, self::PASSWORD, array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_CASE => PDO::CASE_UPPER
                ));
            } catch (PDOException $e) {
                error_log("Error en la conexión: " . $e->getMessage());
                die("Error de conexión a la base de datos.");
            }
        }
        return $this->cn;
    }    
}
?>
