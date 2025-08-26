<?php
// modelo/LlamadosModel.php
require_once 'Conexion.php';

class LlamadosModel {
    private $conn;
    public function __construct() {
        $this->conn = connection();
    }
    public function traerLlamados($usuarioId = null) {
        $sql = "SELECT l.*, 
                       e.nombre AS empresa_nombre, 
                       e.logo, 
                       EXISTS (
                           SELECT 1 
                           FROM postulaciones p 
                           WHERE p.llamado_id = l.id AND p.usuario_id = ?
                       ) AS postulado
                FROM vista_llamados_empresas l
                LEFT JOIN empresas e ON l.empresa_id = e.id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        $llamados = [];
        while ($row = $result->fetch_assoc()) {
            $llamados[] = $row;
        }
        return $llamados;
    }
}
?>
