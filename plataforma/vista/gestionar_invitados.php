<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

// Validación del parámetro viaje_id
if (!isset($_GET["viaje_id"]) || !ctype_digit($_GET["viaje_id"])) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$viaje_id   = (int) $_GET["viaje_id"];
$usuario_id = $_SESSION["usuario_id"];

$viajeClass = new Viaje();
$invClass   = new Invitacion();

$viaje = $viajeClass->obtenerViaje($viaje_id);

// Solo anfitrión
if (!$viaje || $viaje["usuario_id"] != $usuario_id) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$invitaciones = $invClass->obtenerInvitacionesViaje($viaje_id);
$hoy = date("Y-m-d");

include("../../sistema/inc/header.php");
?>

<style>
    .main-container {
        margin-top: 30px;
    }

    .invites-header {
        margin-bottom: 22px;
    }

    .invites-header h2 {
        margin-bottom: 10px;
        font-size: 28px;
    }

    .invites-header p {
        margin-top: 4px;
        margin-bottom: 0;
        color: #555;
        font-size: 15px;
    }

    .dashboard-block {
        background: white;
        padding: 22px 24px;
        border-radius: 12px;
        box-shadow: 0 2px 7px rgba(0,0,0,0.06);
        margin-bottom: 28px;
    }

    .dashboard-block h3 {
        margin-top: 0;
        margin-bottom: 16px;
        font-size: 20px;
    }

    /* FORMULARIO */
    .invites-form {
        display: flex;
        flex-direction: column;
        gap: 14px;
        max-width: 430px;
        margin-top: 6px;
    }

    .invites-form label {
        font-size: 15px;
        font-weight: 500;
    }

    .invites-form input[type="email"] {
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 15px;
        width: 100%;
        box-sizing: border-box;
    }

    .btn-primary-small {
        margin-top: 4px;
        padding: 9px 18px;
        background: #0077ff;
        border: none;
        border-radius: 6px;
        color: white;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.15s;
        width: fit-content;
    }

    .btn-primary-small:hover {
        background: #005fd1;
    }

    /* LISTADO DE INVITACIONES */
    .invites-list {
        list-style: none;
        padding: 0;
        margin-top: 14px;
    }

    .invites-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        padding: 12px 18px 12px 26px;
        border-radius: 10px;
        background: #fafafa;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 10px;
        font-size: 15px;
    }

    .invites-email {
        font-weight: 600;
        font-size: 15px;
    }

    .invite-meta {
        display: flex;
        gap: 8px;
        align-items: center;
        font-size: 13px;
    }

    .badge-estado {
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 12px;
        text-transform: capitalize;
        background: #e0e0e0;
        color: #333;
    }

    .badge-pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .badge-aceptada {
        background: #d4edda;
        color: #155724;
    }

    .badge-rechazada {
        background: #f8d7da;
        color: #721c24;
    }

    .invite-fecha {
        font-size: 12px;
        color: #888;
    }

    .invites-alert {
        margin-top: 6px;
        font-size: 14px;
        color: #a15c00;
        background: #fff4d6;
        padding: 10px 12px;
        border-radius: 8px;
    }
</style>

<div class="main-container">

    <div class="invites-header">
        <h2>Gestionar invitados 👥</h2>
        <p><strong>Viaje:</strong> <?= htmlspecialchars($viaje["titulo"]) ?></p>
    </div>

    <div class="dashboard-block">
        <h3>Enviar nueva invitación</h3>

        <?php if ($hoy < $viaje["fecha_inicio"]): ?>

            <form action="/cotrip/plataforma/controlador/invitacion_enviar_proc.php"
                  method="POST"
                  class="invites-form">

                <input type="hidden" name="viaje_id" value="<?= $viaje_id ?>">

                <label for="email_invitado">Email del invitado</label>
                <input
                    id="email_invitado"
                    type="email"
                    name="email_invitado"
                    placeholder="ejemplo@correo.com"
                    required
                >

                <button type="submit" class="btn-primary-small">
                    Enviar invitación ✉️
                </button>

            </form>

        <?php else: ?>

            <p class="invites-alert">
                ⚠ Este viaje ya ha empezado (<?= htmlspecialchars($viaje["fecha_inicio"]) ?>).  
                No se pueden enviar nuevas invitaciones.
            </p>

        <?php endif; ?>
    </div>

    <div class="dashboard-block">
        <h3>Invitaciones enviadas</h3>

        <?php if (count($invitaciones) === 0): ?>
            <p style="margin-top: 12px;">No hay invitaciones enviadas todavía.</p>
        <?php else: ?>
            <ul class="invites-list">
                <?php foreach ($invitaciones as $i): ?>
                    <?php
                        $estado = $i["estado"];
                        $claseEstado = "badge-estado";
                        if ($estado === "pendiente") $claseEstado .= " badge-pendiente";
                        elseif ($estado === "aceptada") $claseEstado .= " badge-aceptada";
                        elseif ($estado === "rechazada") $claseEstado .= " badge-rechazada";
                    ?>
                    <li class="invites-item">
                        <span class="invites-email">
                            <?= htmlspecialchars($i["email_invitado"]) ?>
                        </span>

                        <span class="invite-meta">
                            <span class="<?= $claseEstado ?>">
                                <?= htmlspecialchars($estado) ?>
                            </span>
                            <span class="invite-fecha">
                                <?= htmlspecialchars($i["fecha"]) ?>
                            </span>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

</div>

<?php include("../../sistema/inc/footer.php"); ?>






