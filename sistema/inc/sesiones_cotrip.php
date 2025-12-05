<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["usuario_id"]) && is_array($_SESSION["usuario_id"])) {
    unset($_SESSION["usuario_id"]);
}

/* 
 Páginas públicas:
 index.php
 login.php
 registro.php
 viaje_publico (solo con token válido)
 Para todo lo demás → requiere sesión
*/

// Esta variable la definiremos en cada página pública:
if (!isset($pagina_publica)) {
    $pagina_publica = false;
}

// Si no es pública y no hay sesión → al login
if (!$pagina_publica && !isset($_SESSION["usuario_id"])) {
    header("Location: /cotrip/login.php");
    exit;
}
