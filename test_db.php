<?php
require_once(__DIR__ . "/sistema/class/db.php");

$db = new DB();
$pdo = $db->pdo;

try {
    $stmt = $pdo->query("SELECT 1");
    echo "<h1 style='color:green'>✔ Conexión a la BD correcta</h1>";
} catch (Exception $e) {
    echo "<h1 style='color:red'>❌ Error de conexión</h1>";
    echo $e->getMessage();
}
