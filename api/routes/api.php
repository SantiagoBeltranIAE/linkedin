<?php
require_once '../controller/LlamadosController.php';
require_once '../controller/login.php';
require_once '../controller/register.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? null;

switch ($action) {
    case 'getLlamados':
        $controller = new LlamadosController();
        echo $controller->getLlamados();
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginController = new LoginController();
            echo $loginController->login();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
        }
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $registerController = new RegisterController();
            echo $registerController->register();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
        }
        break;

    case 'postular':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user'])) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para postularte']);
                exit();
            }

            $data = json_decode(file_get_contents('php://input'), true);
            $usuarioId = $_SESSION['user']['id'];
            $llamadoId = $data['llamado_id'] ?? null;

            $stmt = $conn->prepare("INSERT INTO postulaciones (usuario_id, llamado_id, fecha_postulacion) VALUES (?, ?, NOW())");
            $stmt->bind_param("ii", $usuarioId, $llamadoId);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Postulación registrada']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al registrar la postulación']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
        }
        break;

    case 'getPostulaciones':
        session_start();
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
            exit();
        }

        $usuarioId = $_SESSION['user']['id'];
        $sql = "SELECT p.id, l.titulo, l.descripcion, p.fecha_postulacion 
                FROM postulaciones p
                INNER JOIN llamados l ON p.llamado_id = l.id
                WHERE p.usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        $postulaciones = [];
        while ($row = $result->fetch_assoc()) {
            $postulaciones[] = $row;
        }

        echo json_encode(['success' => true, 'postulaciones' => $postulaciones]);
        break;

    case 'logout':
        session_start();
        session_destroy();
        header('Location: ../../index.html'); // Redirigir al usuario a la página principal
        exit();

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint no encontrado']);
        break;
}
?>