<?php

class Invitacion {

    private $pdo;

    public function __construct()
    {
        $db = new DB();
        $this->pdo = $db->pdo;
    }

    /**
     * Crear una invitación nueva
     * usa: viaje_id, email_invitado, token
     * estado = 'pendiente' por defecto (según la tabla)
     */
    public function crearInvitacion($viaje_id, $email_invitado, $token)
    {
        $sql = "INSERT INTO invitaciones (viaje_id, email_invitado, token)
                VALUES (?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$viaje_id, $email_invitado, $token]);

        return $this->pdo->lastInsertId();
    }

    /**
     * Obtener una invitación por token (para ver/aceptar/rechazar)
     */
    public function obtenerPorToken($token)
    {
        $sql = "SELECT * FROM invitaciones WHERE token = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    /**
     * Marcar invitación como aceptada y vincularla a un usuario
     */
    public function aceptar($id_invitacion, $usuario_id)
    {
        $sql = "UPDATE invitaciones 
                SET estado = 'aceptada', usuario_id = ?
                WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$usuario_id, $id_invitacion]);
    }

    /**
     * Marcar invitación como rechazada
     */
    public function rechazar($id_invitacion)
    {
        $sql = "UPDATE invitaciones 
                SET estado = 'rechazada'
                WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_invitacion]);
    }

    /**
     * Listar invitaciones de un viaje (para que el anfitrión las vea)
     */
    public function obtenerInvitacionesViaje($viaje_id)
    {
        $sql = "SELECT * FROM invitaciones
                WHERE viaje_id = ?
                ORDER BY fecha DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$viaje_id]);
        return $stmt->fetchAll();
    }


    public function obtenerInvitacionesPorViaje($viaje_id)
{
    $sql = "SELECT * FROM invitaciones WHERE viaje_id = ? ORDER BY fecha DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$viaje_id]);
    return $stmt->fetchAll();
}

}
