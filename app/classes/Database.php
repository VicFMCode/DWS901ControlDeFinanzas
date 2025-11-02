<?php
class Database {

    private $host = 'localhost';
    private $db_name = 'db_finanzas';
    private $username = 'root'; // Usuario por defecto de XAMPP
    private $password = '';     // Contraseña por defecto de XAMPP (vacía)
    public $conn;

    //Método para obtener la conexión
    public function getConnection() {
        $this->conn = null;

        try {

            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

            if ($this->conn->connect_error) {
                // Si falla la conexión, muestra el error y detiene el script
                die("Error de conexión a la base de datos: " . $this->conn->connect_error);
            }
        } catch (Exception $exception) {
            echo "Error al intentar conectar: " . $exception->getMessage();
        }

        // Retorna el objeto de conexión
        return $this->conn;
    }
}
?>