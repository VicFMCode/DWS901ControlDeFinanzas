<?php
class Entrada {
    private $conn;
    private $table_name = "entradas";

    // Propiedades de la entrada
    public $id;
    public $tipo_entrada;
    public $monto;
    public $fecha;
    public $ruta_factura;
    public $usuario_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para registrar una nueva entrada
    public function create() {
        // La consulta usa marcadores de posición (?)
        $query = "INSERT INTO " . $this->table_name . " (tipo_entrada, monto, fecha, ruta_factura, usuario_id) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Sanear los datos (limpiar de posibles inyecciones SQL o XSS)
        $this->tipo_entrada = htmlspecialchars(strip_tags($this->tipo_entrada));
        $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        // El monto ya fue validado como número y la ruta es interna

        // Vincular los valores: s=string, d=double/decimal, i=integer
        // El orden de los tipos debe coincidir con el orden de las columnas en el INSERT
        $stmt->bind_param("sdsis", $this->tipo_entrada, $this->monto, $this->fecha, $this->ruta_factura, $this->usuario_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para leer todas las entradas de un usuario (para el dashboard)
    public function readByUserId($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE usuario_id = ? ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>