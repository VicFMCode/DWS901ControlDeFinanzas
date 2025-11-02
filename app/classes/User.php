<?php
class User {
    private $conn;
    private $table_name = "usuarios";

    // Propiedades del usuario
    public $id;
    public $nombre;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para iniciar sesión
    public function login() {
        // Consulta para leer un solo registro por email
        $query = "SELECT id, nombre, email, password FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Sanear y vincular el email
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bind_param("s", $this->email);

        // Ejecutar la consulta
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Asignar valores a las propiedades del objeto (excepto password, por seguridad)
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $hashed_password = $row['password'];

            // === VERIFICACIÓN DE CONTRASEÑA (IMPORTANTE) ===
            // Como usamos un hash simple o texto plano ('12345') en la BD para la prueba,
            // haremos una verificación simple por ahora.

            // SI HUBIÉRAMOS USADO password_hash() en la BD, la línea sería:
            // if (password_verify($this->password, $hashed_password)) { return true; }

            // Para la prueba simple (asumiendo '12345' en la BD):
            if ($this->password == $hashed_password) {
                return true;
            }
        }
        return false;
    }
}
?>