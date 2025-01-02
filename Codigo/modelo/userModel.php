<?php
require_once __DIR__ . '../config/db.php';

class userModel
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM usuarios";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0)
        {
            return $result->fetch_all(MYSQLI_ASSOC); // Devuelve todos los usuarios como un array asociativo
        }

        return [];
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function createUser($data)
    {
        $sql = "INSERT INTO usuarios (nombre, apellidos, fecha_nacimiento, direccion, correo_electronico, telefono, usuario, contrasena, token, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $hashedPassword = password_hash($data['contrasena'], PASSWORD_BCRYPT);
        $stmt->bind_param("ssss", $data['nombre'], $data['correo_electronico'], $hashedPassword, $data['role']);
        return $stmt->execute();
    }

    public function updateUser($id, $data)
    {
        $sql = "UPDATE usuario SET nombre = ?, apellidos = ?, fecha_nacimiento = ?, direccion = ?,, correo_electronico = ?, telefono = ?, usuario = ?, contrasena = ?, token = ?, role = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $data['nombre'], $data['correo_electronico'], $data['role'], $id);
        return $stmt->execute();
    }

    public function deleteUser($id)
    {
        $sql = "DELETE FROM usuario WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
