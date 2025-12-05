<?php
class Valoracion {

    private $db;

    public function __construct() {
        $db = new DB();
        $this->db = $db->pdo;
    }

    public function obtenerValoracionUsuario($viaje_id, $usuario_id) {
        $sql = "SELECT * FROM viajes_valoraciones 
                WHERE viaje_id = :viaje AND usuario_id = :usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ":viaje" => $viaje_id,
            ":usuario" => $usuario_id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardarValoracion($viaje_id, $usuario_id, $estrellas) {

        $sql = "INSERT INTO viajes_valoraciones (viaje_id, usuario_id, estrellas)
                VALUES (:viaje, :usuario, :estrellas)
                ON DUPLICATE KEY UPDATE estrellas = :estrellas2";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ":viaje"      => $viaje_id,
            ":usuario"    => $usuario_id,
            ":estrellas"  => $estrellas,
            ":estrellas2" => $estrellas
        ]);
    }

    public function mediaViaje($viaje_id) {
        $sql = "SELECT AVG(estrellas) AS media FROM viajes_valoraciones WHERE viaje_id = :viaje";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":viaje" => $viaje_id]);
        return round($stmt->fetchColumn(), 2);
    }

    public function totalVotos($viaje_id) {
        $sql = "SELECT COUNT(*) FROM viajes_valoraciones WHERE viaje_id = :viaje";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":viaje" => $viaje_id]);
        return $stmt->fetchColumn();
    }

    public function obtenerTodas($viaje_id) {
        $sql = "SELECT v.*, u.nombre 
                FROM viajes_valoraciones v
                JOIN usuarios u ON v.usuario_id = u.id
                WHERE viaje_id = :viaje";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":viaje" => $viaje_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
