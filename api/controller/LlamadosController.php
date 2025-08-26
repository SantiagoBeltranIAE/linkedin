<?php
require_once '../modelo/LlamadosModel.php';

class LlamadosController {
    public function getLlamados() {
        session_start();
        $usuarioId = $_SESSION['user']['id'] ?? null;

        $model = new LlamadosModel();
        $llamados = $model->traerLlamados($usuarioId);
        return json_encode(['llamados' => $llamados]);
    }
}
?>