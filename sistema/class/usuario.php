<?php

class Usuario {

    private $pdo;

    public function __construct()
    {
        $db = new DB();
        $this->pdo = $db->pdo;
    }

    // Crear usuario solo con nombre
    public function crearUsuario($nombre)
    {
        $sql = "INSERT INTO usuarios (nombre) VALUES (?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nombre]);

        return $this->pdo->lastInsertId();
    }

    // Alias directo para compatibilidad con tu flujo anterior
    public function crearUsuarioSimple($nombre)
    {
        return $this->crearUsuario($nombre);
    }

    public function obtenerUsuario($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function actualizarPerfil($id, $nombre, $pais, $idiomas, $bio, $foto=null)
    {
        $sql = "UPDATE usuarios SET nombre=?, pais=?, idiomas=?, bio=?, foto=? WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $pais, $idiomas, $bio, $foto, $id]);
    }
}


