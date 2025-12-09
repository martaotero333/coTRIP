<?php
class Gasto {

    private $db;

    public function __construct() {
        
        $db = new DB();
        $this->db = $db->pdo;
    }

    
    public function agregarGasto($viaje_id, $usuario_id, $concepto, $cantidad) {
        $sql = "INSERT INTO viajes_gastos (viaje_id, usuario_id, concepto, cantidad)
                VALUES (:viaje_id, :usuario_id, :concepto, :cantidad)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":viaje_id", $viaje_id);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->bindParam(":concepto", $concepto);
        $stmt->bindParam(":cantidad", $cantidad);
        return $stmt->execute();
    }

    
    public function obtenerGastosUsuario($usuario_id) {
        $sql = "SELECT g.*, v.titulo AS titulo_viaje
                FROM viajes_gastos g
                JOIN viajes v ON g.viaje_id = v.id
                WHERE g.usuario_id = :usuario_id
                ORDER BY g.fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


public function totalUsuario($usuario_id) {
    $sql = "SELECT SUM(cantidad) AS total
            FROM viajes_gastos
            WHERE usuario_id = :usuario_id
              AND pagado = 0";   

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(":usuario_id", $usuario_id);
    $stmt->execute();
    return $stmt->fetchColumn() ?: 0;  
}


    
    public function obtenerGastosViaje($viaje_id) {
        $sql = "SELECT * 
                FROM viajes_gastos
                WHERE viaje_id = :viaje_id
                ORDER BY fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":viaje_id", $viaje_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function getDB() {
    return $this->db;
}
}
?>


