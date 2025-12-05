<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

$subplan_id = $_GET["id"];
$usuario_id = $_SESSION["usuario_id"];

$subplanClass     = new Subplan();
$comentarioClass  = new Comentario_subplan();
$viajeClass       = new Viaje();
$usuarioClass     = new Usuario();

$subplan = $subplanClass->obtenerSubplan($subplan_id);
$viaje_id = $subplan["viaje_id"];

if (!$viajeClass->usuarioPuedeAcceder($usuario_id, $viaje_id)) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$comentarios = $comentarioClass->obtener($subplan_id);
$viaje = $viajeClass->obtenerViaje($viaje_id);

$esAnfitrion = ($viaje["usuario_id"] == $usuario_id);

include("../../sistema/inc/header.php");
?>

<div style="width:90%; max-width:900px; margin:auto;">

    <h2 style="margin-top:20px; color:#333;">💬 Comentarios de la actividad</h2>
    <h3 style="color:#666; margin-bottom:25px;">
        <?= htmlspecialchars($subplan["titulo"]) ?>
    </h3>

    <!-- ====================== LISTA COMENTARIOS ====================== -->
    <div style="margin-bottom:30px;">

        <?php if (count($comentarios) == 0): ?>
            <p style="color:#777; font-style:italic;">Aún no hay comentarios en este subplan.</p>
        <?php else: ?>

            <?php foreach ($comentarios as $c): 
                $nombre = htmlspecialchars($c["nombre"]);
                $inicial = strtoupper(substr($nombre, 0, 1));

                $puedeBorrar = ($c["usuario_id"] == $usuario_id || $esAnfitrion);
            ?>

                <div style="display:flex; gap:12px; margin-bottom:18px;">
                    
                    <!-- Avatar -->
                    <div style="
                        width:42px;
                        height:42px;
                        border-radius:50%;
                        background:#0077ff;
                        color:white;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-weight:bold;
                        font-size:18px;
                    ">
                        <?= $inicial ?>
                    </div>

                    <!-- Burbuja -->
                    <div style="
                        background:white;
                        padding:12px 15px;
                        border-radius:10px;
                        box-shadow:0 2px 6px rgba(0,0,0,0.1);
                        width:100%;
                    ">
                        <div style="font-weight:bold; color:#333;">
                            <?= $nombre ?>
                        </div>

                        <div style="font-size:12px; color:#777; margin-bottom:8px;">
                            <?= $c["fecha"] ?>
                        </div>

                        <div style="color:#444; margin-bottom:8px;">
                            <?= nl2br(htmlspecialchars($c["mensaje"])) ?>
                        </div>

                        <?php if ($puedeBorrar): ?>
                            <a href="/cotrip/plataforma/controlador/comentario_subplan_borrar_proc.php?id=<?= $c['id'] ?>&subplan=<?= $subplan_id ?>"
                               style="font-size:12px; color:#d00;"
                               onclick="return confirm('¿Seguro que quieres eliminar este comentario?');">
                                Eliminar
                            </a>
                        <?php endif; ?>

                    </div>
                </div>

            <?php endforeach; ?>

        <?php endif; ?>
    </div>


    <!-- ====================== FORMULARIO ====================== -->
    <form action="/cotrip/plataforma/controlador/comentario_subplan_crear_proc.php" method="POST"
          style="
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 2px 6px rgba(0,0,0,0.1);
          ">

        <h3 style="margin-top:0; color:#333;">✍️ Escribe un comentario</h3>

        <textarea name="mensaje"
                  placeholder="Escribe aquí..."
                  required
                  style="
                    width:100%;
                    height:90px;
                    padding:10px;
                    border-radius:6px;
                    border:1px solid #ccc;
                    resize:none;
                    font-size:15px;
                  "></textarea>

        <input type="hidden" name="subplan_id" value="<?= $subplan_id ?>">

        <button type="submit"
                style="
                    margin-top:10px;
                    padding:10px 18px;
                    background:#0077ff;
                    border:none;
                    color:white;
                    border-radius:6px;
                    font-size:15px;
                    cursor:pointer;
                ">
            Enviar comentario →
        </button>
    </form>

</div>

<?php include("../../sistema/inc/footer.php"); ?>
