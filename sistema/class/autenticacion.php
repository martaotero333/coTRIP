<?php

class Autenticacion {

    private $pdo;

    public function __construct()
    {
        $db = new DB();
        $this->pdo = $db->pdo;
    }

    // Crear autenticación
    public function crearAutenticacion($usuario_id, $email, $password)
    {
        $salt = bin2hex(random_bytes(16));
        $hash_password = hash('sha256', $password . $salt);

        $sql = "INSERT INTO autenticacion (usuario_id, email, hash_password, salt)
                VALUES (:usuario_id, :email, :hash_password, :salt)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':email' => $email,
            ':hash_password' => $hash_password,
            ':salt' => $salt
        ]);
    }

    // Validar credenciales
    public function login($email, $password)
    {
        $sql = "SELECT * FROM autenticacion WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $auth = $stmt->fetch();

        if (!$auth) return false;

        $hash_check = hash('sha256', $password . $auth['salt']);
        if ($hash_check !== $auth['hash_password']) return false;

        // Obtener usuario asociado
        $sql2 = "SELECT * FROM usuarios WHERE id = ?";
        $stmt2 = $this->pdo->prepare($sql2);
        $stmt2->execute([$auth['usuario_id']]);
        return $stmt2->fetch();
    }
}
