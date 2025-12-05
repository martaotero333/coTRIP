<?php

// Helpers de permisos SIN SQL directo. 
// Usarán luego métodos de las clases Viaje y Subplan.

// Comprueba si el usuario es anfitrión del viaje (usando datos del viaje ya cargados)
function esAnfitrion($usuario_id, $viaje)
{
    return isset($viaje['usuario_id']) && $viaje['usuario_id'] == $usuario_id;
}

// Comprobación genérica de acceso a viaje.
// Aquí NO hacemos SQL: asumimos que desde el controlador ya tenemos el viaje
// y que hemos consultado en la clase correspondiente si el usuario puede acceder.
function checkUserCanAccessViaje($puedeAcceder)
{
    if (!$puedeAcceder) {
        header("Location: /cotrip/plataforma/vista/error_permisos.php");
        exit;
    }
}

// Comprobación genérica de acceso a subplan
function checkUserCanAccessSubplan($puedeAcceder)
{
    if (!$puedeAcceder) {
        header("Location: /cotrip/plataforma/vista/error_permisos.php");
        exit;
    }
}

