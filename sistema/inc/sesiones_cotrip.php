<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["usuario_id"]) && is_array($_SESSION["usuario_id"])) {
    unset($_SESSION["usuario_id"]);
}



if (!isset($pagina_publica)) {
    $pagina_publica = false;
}


if (!$pagina_publica && !isset($_SESSION["usuario_id"])) {
    header("Location: /cotrip/login.php");
    exit;
}
