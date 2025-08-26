<?php
require_once '../modelo/Conexion.php';

class LoginController {
    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $conn = connection();
        $stmt = $conn->prepare("SELECT id, nombre, email, password FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_start();
                unset($user['password']); 
                $_SESSION['user'] = $user;
                return json_encode(['success' => true, 'user' => $user]);
            } else {
                http_response_code(401);
                return json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
            }
        } else {
            http_response_code(401);
            return json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
        }
    }
}
?>