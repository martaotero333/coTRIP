<?php


function esAnfitrion($usuario_id, $viaje)
{
    return isset($viaje['usuario_id']) && $viaje['usuario_id'] == $usuario_id;
}


function checkUserCanAccessViaje($puedeAcceder)
{
    if (!$puedeAcceder) {
        header("Location: /cotrip/plataforma/vista/error_permisos.php");
        exit;
    }
}


function checkUserCanAccessSubplan($puedeAcceder)
{
    if (!$puedeAcceder) {
        header("Location: /cotrip/plataforma/vista/error_permisos.php");
        exit;
    }
}

