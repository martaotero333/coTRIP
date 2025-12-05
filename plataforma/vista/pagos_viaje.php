<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

// Validar parámetro id
if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$viaje_id   = (int) $_GET["id"];
$usuario_id = $_SESSION["usuario_id"];

$viajeClass   = new Viaje();
$gastoClass   = new Gasto();
$usuarioClass = new Usuario();

$viaje = $viajeClass->obtenerViaje($viaje_id);

// Seguridad: solo anfitrión del viaje y viaje existente
if (!$viaje || $viaje["usuario_id"] != $usuario_id) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

// Obtener todos los gastos del viaje
$gastos = $gastoClass->obtenerGastosViaje($viaje_id);

// Calcular resumen por usuario
$resumen = [];
foreach ($gastos as $g) {
    $uId = $g["usuario_id"];
    if (!isset($resumen[$uId])) {
        $resumen[$uId] = ["total" => 0, "pagado" => 0, "pendiente" => 0];
    }

    $resumen[$uId]["total"] += $g["cantidad"];

    if ($g["pagado"] == 1) {
        $resumen[$uId]["pagado"] += $g["cantidad"];
    } else {
        $resumen[$uId]["pendiente"] += $g["cantidad"];
    }
}

include("../../sistema/inc/header.php");
?>

<style>
    .pagos-container {
        width: 90%;
        max-width: 1000px;
        margin: 25px auto 40px auto;
    }

    .pagos-title {
        margin-top: 10px;
        font-size: 28px;
        color: #333;
        text-align: center;
    }

    .pagos-subtitle {
        margin-top: 6px;
        text-align: center;
        font-weight: 500;
        color: #555;
        margin-bottom: 20px;
    }

    .pagos-block {
        background: white;
        padding: 20px 22px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        margin-bottom: 30px;
    }

    .pagos-block h3 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #333;
        font-size: 20px;
    }

    .pagos-table {
        width: 100%;
        border-collapse: collapse;
    }

    .pagos-table th {
        padding: 12px;
        text-align: left;
        background: #f5f5f5;
        font-weight: 600;
        border-bottom: 1px solid #e3e3e3;
        font-size: 14px;
    }

    .pagos-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }

    .badge-estado-pagado,
    .badge-estado-pendiente {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    .badge-estado-pagado {
        background: #d4ffd4;
        color: #1f7a1f;
    }

    .badge-estado-pendiente {
        background: #ffe0e0;
        color: #c70000;
    }

    .btn-estado {
        display: inline-block;
        padding: 7px 11px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
    }

    .btn-estado-pagar {
        background: #0077ff;
        color: white;
    }

    .btn-estado-pendiente {
        background: #ffb300;
        color: #222;
    }

    .pagos-empty {
        margin-top: 10px;
        color: #777;
        font-size: 14px;
    }
</style>

<div class="pagos-container">

    <h2 class="pagos-title">💶 Gestión de pagos del viaje</h2>
    <h3 class="pagos-subtitle"><?= htmlspecialchars($viaje["titulo"]) ?></h3>

    <!-- RESUMEN POR USUARIO -->
    <div class="pagos-block">
        <h3>📊 Resumen por usuario</h3>

        <?php if (count($resumen) === 0): ?>
            <p class="pagos-empty">Este viaje todavía no tiene gastos registrados.</p>
        <?php else: ?>
            <table class="pagos-table">
                <tr>
                    <th>Usuario</th>
                    <th>Total</th>
                    <th>Pagado</th>
                    <th>Pendiente</th>
                </tr>

                <?php foreach ($resumen as $uid => $data): 
                    $u = $usuarioClass->obtenerUsuario($uid);
                ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($u["nombre"]) ?></strong>
                        </td>
                        <td><?= number_format($data["total"], 2) ?> €</td>
                        <td style="color: green;"><?= number_format($data["pagado"], 2) ?> €</td>
                        <td style="color: #c70000;"><?= number_format($data["pendiente"], 2) ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

    <!-- DETALLE DE GASTOS -->
    <div class="pagos-block">
        <h3>🧾 Detalle de gastos</h3>

        <?php if (count($gastos) === 0): ?>
            <p class="pagos-empty">No hay gastos para este viaje.</p>
        <?php else: ?>
            <table class="pagos-table">
                <tr>
                    <th>Concepto</th>
                    <th>Usuario</th>
                    <th>Cantidad</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>

                <?php foreach ($gastos as $g): 
                    $u = $usuarioClass->obtenerUsuario($g["usuario_id"]);
                ?>
                    <tr>
                        <td><?= htmlspecialchars($g["concepto"]) ?></td>
                        <td><?= htmlspecialchars($u["nombre"]) ?></td>
                        <td><?= number_format($g["cantidad"], 2) ?> €</td>
                        <td>
                            <?php if ($g["pagado"] == 1): ?>
                                <span class="badge-estado-pagado">✔ Pagado</span>
                            <?php else: ?>
                                <span class="badge-estado-pendiente">✖ Pendiente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($g["pagado"] == 0): ?>
                                <a class="btn-estado btn-estado-pagar"
                                   href="/cotrip/plataforma/controlador/gasto_cambiar_estado.php?id=<?= $g['id'] ?>&estado=1&viaje=<?= $viaje_id ?>">
                                    Marcar pagado
                                </a>
                            <?php else: ?>
                                <a class="btn-estado btn-estado-pendiente"
                                   href="/cotrip/plataforma/controlador/gasto_cambiar_estado.php?id=<?= $g['id'] ?>&estado=0&viaje=<?= $viaje_id ?>">
                                    Marcar pendiente
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

</div>

<?php include("../../sistema/inc/footer.php"); ?>


