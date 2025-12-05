<?php
class Comentario_viaje {

    private $db;

    public function __construct() {
        $db = new DB();
        $this->db = $db->pdo;
    }

    public function crear($viaje_id, $usuario_id, $mensaje) {
        $sql = "INSERT INTO comentarios_viaje (viaje_id, usuario_id, mensaje)
                VALUES (:viaje_id, :usuario_id, :mensaje)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ":viaje_id" => $viaje_id,
            ":usuario_id" => $usuario_id,
            ":mensaje" => $mensaje
        ]);
    }

    public function obtener($viaje_id) {
        $sql = "SELECT c.*, u.nombre 
                FROM comentarios_viaje c
                JOIN usuarios u ON c.usuario_id = u.id
                WHERE c.viaje_id = :viaje_id
                ORDER BY c.fecha ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":viaje_id" => $viaje_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function borrar($id) {
        $sql = "DELETE FROM comentarios_viaje WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM comentarios_viaje WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerUltimo($viaje_id) {
    $sql = "SELECT cv.*, u.nombre 
            FROM comentarios_viaje cv
            JOIN usuarios u ON u.id = cv.usuario_id
            WHERE cv.viaje_id = :id
            ORDER BY cv.fecha DESC
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([":id" => $viaje_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
?>
