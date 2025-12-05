<?php
class Comentario_subplan {

    private $db;

    public function __construct() {
        $db = new DB();
        $this->db = $db->pdo;
    }

    public function crear($subplan_id, $usuario_id, $mensaje) {
        $sql = "INSERT INTO comentarios_subplan (subplan_id, usuario_id, mensaje)
                VALUES (:subplan_id, :usuario_id, :mensaje)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ":subplan_id" => $subplan_id,
            ":usuario_id" => $usuario_id,
            ":mensaje" => $mensaje
        ]);
    }

    public function obtener($subplan_id) {
        $sql = "SELECT c.*, u.nombre 
                FROM comentarios_subplan c
                JOIN usuarios u ON c.usuario_id = u.id
                WHERE c.subplan_id = :subplan_id
                ORDER BY c.fecha ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":subplan_id" => $subplan_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function borrar($id) {
        $sql = "DELETE FROM comentarios_subplan WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM comentarios_subplan WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
