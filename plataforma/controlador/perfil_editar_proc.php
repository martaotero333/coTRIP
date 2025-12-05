<?php
require_once("../../sistema/inc/include_classes.php");
session_start();

$usuario_id = $_SESSION["usuario_id"];

$nombre = $_POST['nombre'];
$pais = $_POST['pais'];
$idiomas = $_POST['idiomas'];
$bio = $_POST['bio'];
$foto = null;

// Manejo de foto
if (isset($_FILES['foto']) && $_FILES['foto']['tmp_name']) {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nombre_archivo = time() . "_" . bin2hex(random_bytes(5)) . "." . $ext;

    $ruta = $_SERVER['DOCUMENT_ROOT'] . "/cotrip/uploads/usuarios/" . $usuario_id . "/";
    if (!is_dir($ruta)) mkdir($ruta, 0777, true);

    move_uploaded_file($_FILES['foto']['tmp_name'], $ruta . $nombre_archivo);

    $foto = "/cotrip/uploads/usuarios/$usuario_id/$nombre_archivo";
}

$usuarioClass = new Usuario();
$usuarioClass->actualizarPerfil($usuario_id, $nombre, $pais, $idiomas, $bio, $foto);

// Redirección al inicio de la plataforma
header("Location: /cotrip/plataforma/controlador/mis_viajes_proc.php");
exit;
