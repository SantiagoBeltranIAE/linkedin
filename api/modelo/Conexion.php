<?php
// modelo/Conexion.php
function connection() {
    try {
        $host = "localhost";
        $bd = "linkedin";
        $usuario = "root";
        $password = "";
        $puerto = "3306";
        $mysqli = new mysqli($host, $usuario, $password, $bd, $puerto);

        if ($mysqli->connect_error) {
            throw new Exception("Error de conexión: " . $mysqli->connect_error);
        }

        return $mysqli;
    } catch (Exception $e) {
        die("Error al conectar a la base de datos: " . $e->getMessage());
    }
}
?>