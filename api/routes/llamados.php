<?php
// routes/llamados.php
require_once '../controller/LlamadosController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new LlamadosController();
    echo $controller->getLlamados();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
?>