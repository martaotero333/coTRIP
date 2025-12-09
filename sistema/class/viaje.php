<?php

class Viaje {

    private $pdo;

    public function __construct()
    {
        $db = new DB();
        $this->pdo = $db->pdo;
    }

 
    public function crearViaje($usuario_id, $titulo, $descripcion, $destino, 
                               $fecha_inicio, $fecha_fin, $precio_base, $imagen=null)
    {
        $sql = "INSERT INTO viajes 
                (usuario_id, titulo, descripcion, destino, fecha_inicio, fecha_fin, precio_base, imagen)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $usuario_id, $titulo, $descripcion, $destino,
            $fecha_inicio, $fecha_fin, $precio_base, $imagen
        ]);

        return $this->pdo->lastInsertId();
    }

 
    public function obtenerViaje($id)
    {
        $sql = "SELECT * FROM viajes WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    
    public function obtenerViajesCreados($usuario_id)
    {
        $sql = "SELECT * FROM viajes WHERE usuario_id = ? ORDER BY fecha_creacion DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$usuario_id]);

        return $stmt->fetchAll();
    }

    
    public function obtenerViajesAceptados($usuario_id)
    {
        $sql = "SELECT v.* 
                FROM viajes v
                JOIN invitaciones i ON v.id = i.viaje_id
                WHERE i.usuario_id = ? AND i.estado = 'aceptada'
                ORDER BY v.fecha_inicio ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$usuario_id]);

        return $stmt->fetchAll();
    }

    
    public function estadoViaje($viaje)
    {
        $hoy = date("Y-m-d");

        if ($hoy < $viaje['fecha_inicio']) return "pendiente";
        if ($hoy > $viaje['fecha_fin']) return "finalizado";
        return "en curso";
    }

    
    public function usuarioPuedeAcceder($usuario_id, $viaje_id)
    {
        
        $v = $this->obtenerViaje($viaje_id);
        if ($v['usuario_id'] == $usuario_id) return true;

        
        $sql = "SELECT id FROM invitaciones WHERE viaje_id = ? AND usuario_id = ? AND estado = 'aceptada'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$viaje_id, $usuario_id]);
        if ($stmt->fetch()) return true;

        return false;
    }

    public function yaEsParticipante($viaje_id, $usuario_id)
    {
        $sql = "SELECT id FROM viajes_participantes 
                WHERE viaje_id = ? AND usuario_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$viaje_id, $usuario_id]);

        return $stmt->fetch() ? true : false;
    }

    public function agregarParticipante($viaje_id, $usuario_id)
{
    $sql = "INSERT INTO viajes_participantes (viaje_id, usuario_id)
            VALUES (?, ?)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$viaje_id, $usuario_id]);
}

public function obtenerParticipantes($viaje_id)
{
    $sql = "SELECT u.*
            FROM viajes_participantes vp
            JOIN usuarios u ON u.id = vp.usuario_id
            WHERE vp.viaje_id = ?";
            
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$viaje_id]);
    return $stmt->fetchAll();
}

}
