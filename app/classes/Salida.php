<?php
class Salida {
    private $conn;
    private $table_name = "salidas";

    // Propiedades de la salida
    public $id;
    public $tipo_salida;
    public $monto;
    public $fecha;
    public $ruta_factura;
    public $usuario_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para registrar una nueva salida
    public function create() {
        // La consulta para insertar registro
        $query = "INSERT INTO " . $this->table_name . " (tipo_salida, monto, fecha, ruta_factura, usuario_id) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Sanear los datos
        $this->tipo_salida = htmlspecialchars(strip_tags($this->tipo_salida));
        $this->fecha = htmlspecialchars(strip_tags($this->fecha));

        // Vincular los valores: s=string, d=double/decimal, i=integer
        // El orden de los tipos (sdsis) coincide con el de la clase Entrada.php
        $stmt->bind_param("sdsis", $this->tipo_salida, $this->monto, $this->fecha, $this->ruta_factura, $this->usuario_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para leer todas las salidas de un usuario
    public function readByUserId($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE usuario_id = ? ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>