<?php

class Subplan {

    private $pdo;

    public function __construct()
    {
        $db = new DB();
        $this->pdo = $db->pdo;
    }

    
    public function crearSubplan($viaje_id, $titulo, $descripcion, $fecha, $precio, $lugar, $imagen)
    {
        $sql = "INSERT INTO subplanes (viaje_id, titulo, descripcion, fecha, precio, lugar, imagen)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $viaje_id,
            $titulo,
            $descripcion,
            $fecha,
            $precio,
            $lugar,
            $imagen
        ]);

        return $this->pdo->lastInsertId();
    }

   
    public function obtenerSubplanes($viaje_id)
    {
        $sql = "SELECT * FROM subplanes 
                WHERE viaje_id = ?
                ORDER BY fecha ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$viaje_id]);

        return $stmt->fetchAll();
    }

   
    public function obtenerSubplan($id)
    {
        $sql = "SELECT * FROM subplanes WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

        
    public function usuarioApuntado($subplan_id, $usuario_id)
    {
        $sql = "SELECT id FROM subplanes_apuntados
                WHERE subplan_id = ? AND usuario_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$subplan_id, $usuario_id]);
        return $stmt->fetch();
    }

   
    public function apuntar($subplan_id, $usuario_id)
    {
        $sql = "INSERT INTO subplanes_apuntados (subplan_id, usuario_id)
                VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$subplan_id, $usuario_id]);
    }


    public function desapuntar($subplan_id, $usuario_id)
    {
        $sql = "DELETE FROM subplanes_apuntados
                WHERE subplan_id = ? AND usuario_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$subplan_id, $usuario_id]);
    }

 
    public function obtenerApuntados($subplan_id)
    {
        $sql = "SELECT u.*
                FROM subplanes_apuntados sa
                JOIN usuarios u ON u.id = sa.usuario_id
                WHERE sa.subplan_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$subplan_id]);
        return $stmt->fetchAll();
    }

}

