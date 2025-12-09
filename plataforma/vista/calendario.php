<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$usuario_id = $_SESSION["usuario_id"];
$viajeClass = new Viaje();


$misViajes = $viajeClass->obtenerViajesCreados($usuario_id);


$viajesAceptados = $viajeClass->obtenerViajesAceptados($usuario_id);


$viajes = array_merge($misViajes, $viajesAceptados);


function colorPorViaje($viaje_id) {
    $colores = [
        "#ffadad", "#ffd6a5", "#fdffb6", "#caffbf",
        "#9bf6ff", "#a0c4ff", "#bdb2ff", "#ffc6ff"
    ];
    return $colores[$viaje_id % count($colores)];
}

function fondoMultiple($colores) {
    $grad = "repeating-linear-gradient(45deg, ";
    $px = 12;
    $offset = 0;

    foreach ($colores as $c) {
        $grad .= "$c $offset"."px ".($offset + $px)."px, ";
        $offset += $px;
    }

    return rtrim($grad, ", ") . ")";
}


$mes = isset($_GET["mes"]) ? intval($_GET["mes"]) : date("n");
$anio = isset($_GET["anio"]) ? intval($_GET["anio"]) : date("Y");


$primerDia = mktime(0, 0, 0, $mes, 1, $anio);
$nombreMes = strftime("%B", $primerDia);
$diasMes = date("t", $primerDia);
$diaSemana = date("w", $primerDia);
if ($diaSemana == 0) $diaSemana = 7;


$diasMarcados = [];

foreach ($viajes as $v) {
    $color = colorPorViaje($v["id"]);
    $inicio = strtotime($v["fecha_inicio"]);
    $fin = strtotime($v["fecha_fin"]);

    for ($ts = $inicio; $ts <= $fin; $ts += 86400) {
        if (date("n", $ts) == $mes && date("Y", $ts) == $anio) {
            $fecha = date("Y-m-d", $ts);
            $diasMarcados[$fecha][] = $color; 
        }
    }
}

include("../../sistema/inc/header.php");
?>

<div style="width:90%; max-width:800px; margin:auto; text-align:center;">

    <h2 style="margin-bottom:20px;">Calendario de viajes</h2>

   
    <div style="margin-bottom:20px;">
        <a href="?mes=<?= ($mes == 1 ? 12 : $mes-1) ?>&anio=<?= ($mes == 1 ? $anio-1 : $anio) ?>"
           style="margin-right:20px;">⬅ Mes anterior</a>

        <strong style="font-size:20px;">
            <?= ucfirst($nombreMes) ?> <?= $anio ?>
        </strong>

        <a href="?mes=<?= ($mes == 12 ? 1 : $mes+1) ?>&anio=<?= ($mes == 12 ? $anio+1 : $anio) ?>"
           style="margin-left:20px;">Mes siguiente ➡</a>
    </div>

    
    <table style="
        width:100%;
        border-collapse:collapse;
        background:white;
        border-radius:10px;
        overflow:hidden;
        box-shadow:0 2px 6px rgba(0,0,0,0.1);">

        <tr style="background:#f0f0f0;">
            <th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th>
            <th>Vie</th><th>Sáb</th><th>Dom</th>
        </tr>

        <?php
        echo "<tr>";

        for ($i = 1; $i < $diaSemana; $i++) {
            echo "<td></td>";
        }

        for ($dia = 1; $dia <= $diasMes; $dia++) {

            $fechaActual = "$anio-".str_pad($mes,2,"0",STR_PAD_LEFT)."-".str_pad($dia,2,"0",STR_PAD_LEFT);

            echo "<td style='padding:15px; border:1px solid #eee; height:70px; ";

            if (isset($diasMarcados[$fechaActual])) {

                $colores = $diasMarcados[$fechaActual];

                if (count($colores) == 1) {
                    
                    echo "background:".$colores[0]."; font-weight:bold;";
                } else {
                    
                    echo "background:".fondoMultiple($colores)."; font-weight:bold;";
                }
            }

            echo "'>$dia</td>";

            if (($dia + $diaSemana - 1) % 7 == 0) echo "</tr><tr>";
        }

        echo "</tr>";
        ?>
    </table>

    
    <h3 style="margin-top:30px;">Leyenda</h3>

    <?php foreach ($viajes as $v): ?>
        <div style="display:flex; align-items:center; gap:10px; justify-content:center; margin-bottom:8px;">
            <div style="
                width:20px;
                height:20px;
                border-radius:4px;
                background: <?= colorPorViaje($v['id']) ?>;
                border:1px solid #ccc;
            "></div>
            <span><?= htmlspecialchars($v["titulo"]) ?></span>
        </div>
    <?php endforeach; ?>

</div>

<?php include("../../sistema/inc/footer.php"); ?>

