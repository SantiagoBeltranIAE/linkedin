<?php
require_once '../modelo/Conexion.php';

class RegisterController {
    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);
        $nombre = $data['nombre'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        // Encriptar la contraseña
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $conn = connection();
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $hashedPassword);

        if ($stmt->execute()) {
            return json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente']);
        } else {
            http_response_code(500);
            return json_encode(['success' => false, 'message' => 'Error al registrar el usuario']);
        }
    }
}
?>