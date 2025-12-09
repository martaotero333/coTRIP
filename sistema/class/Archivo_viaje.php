<?php
class Archivo_viaje {

    private $db;

    public function __construct() {
        $db = new DB();
        $this->db = $db->pdo;
    }

 
    public function agregarArchivo($viaje_id, $usuario_id, $ruta, $tipo = "foto") {
        $sql = "INSERT INTO archivos_viaje (viaje_id, usuario_id, ruta, tipo)
                VALUES (:viaje, :usuario, :ruta, :tipo)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ":viaje"   => $viaje_id,
            ":usuario" => $usuario_id,
            ":ruta"    => $ruta,
            ":tipo"    => $tipo
        ]);
    }

  
    public function obtenerFotosViaje($viaje_id) {
        $sql = "SELECT * FROM archivos_viaje
                WHERE viaje_id = :viaje AND tipo = 'foto'
                ORDER BY fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":viaje" => $viaje_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function obtenerArchivo($id) {
        $sql = "SELECT * FROM archivos_viaje WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function borrarArchivo($id) {
        $sql = "DELETE FROM archivos_viaje WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
?>
