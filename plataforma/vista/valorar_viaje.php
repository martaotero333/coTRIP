<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");

// Validar viaje_id
if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

$viaje_id   = (int) $_GET["id"];
$usuario_id = $_SESSION["usuario_id"];

$viajeClass      = new Viaje();
$valoracionClass = new Valoracion();

$viaje = $viajeClass->obtenerViaje($viaje_id);

// Permisos: usuario debe participar o ser anfitrión
if (!$viaje || !$viajeClass->usuarioPuedeAcceder($usuario_id, $viaje_id)) {
    header("Location: /cotrip/plataforma/vista/error_permisos.php");
    exit;
}

// Validación fecha
$hoy       = date("Y-m-d");
$bloqueado = ($hoy < $viaje["fecha_inicio"]);

$miValoracion = $valoracionClass->obtenerValoracionUsuario($viaje_id, $usuario_id);

include("../../sistema/inc/header.php");
?>

<style>
    .valoracion-container {
        width: 90%;
        max-width: 550px;
        margin: 35px auto 50px auto;
        background: white;
        padding: 28px 32px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        text-align: center;
    }

    .valoracion-title {
        margin-bottom: 10px;
        font-size: 26px;
        font-weight: 600;
    }

    .valoracion-subtitle {
        color: #666;
        margin-bottom: 25px;
        font-size: 17px;
    }

    .alert-bloqueo {
        color: #c00;
        font-size: 15px;
        background: #ffe5e5;
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .stars-wrapper {
        font-size: 45px;
        margin-bottom: 20px;
        cursor: pointer;
        user-select: none;
    }

    .estrella {
        transition: color 0.15s ease-in-out, transform 0.1s ease-out;
    }

    .estrella:hover {
        transform: scale(1.12);
    }

    .btn-guardar {
        padding: 11px 22px;
        background: #0077ff;
        border: none;
        color: white;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        margin-top: 15px;
        transition: background 0.15s, transform 0.05s;
    }

    .btn-guardar:hover {
        background: #005fd1;
        transform: translateY(-1px);
    }
</style>

<div class="valoracion-container">

    <h2 class="valoracion-title">⭐ Valorar el viaje</h2>
    <h3 class="valoracion-subtitle"><?= htmlspecialchars($viaje["titulo"]) ?></h3>

    <?php if ($bloqueado): ?>

        <p class="alert-bloqueo">
            ❌ Aún no puedes valorar este viaje.<br>
            Disponible a partir del <strong><?= $viaje["fecha_inicio"] ?></strong>.
        </p>

    <?php else: ?>

        <?php $valorActual = $miValoracion ? (int)$miValoracion["estrellas"] : 0; ?>

        <!-- FORMULARIO -->
        <form action="/cotrip/plataforma/controlador/valorar_viaje_proc.php" method="POST">

            <div id="estrellas-wrapper" class="stars-wrapper">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="estrella"
                          data-value="<?= $i ?>"
                          style="color: <?= $i <= $valorActual ? '#ffca28' : '#ccc' ?>;">
                        ★
                    </span>
                <?php endfor; ?>
            </div>

            <input type="hidden" name="estrellas" id="estrellas-input" value="<?= $valorActual ?>">
            <input type="hidden" name="viaje_id" value="<?= $viaje_id ?>">

            <button type="submit" class="btn-guardar">
                Guardar valoración →
            </button>

        </form>

    <?php endif; ?>

</div>

<script>
const stars = document.querySelectorAll(".estrella");
const input = document.getElementById("estrellas-input");

stars.forEach(st => {

    st.addEventListener("mouseover", () => {
        const val = st.dataset.value;
        stars.forEach(s => {
            s.style.color = s.dataset.value <= val ? "#ffca28" : "#ccc";
        });
    });

    st.addEventListener("mouseleave", () => {
        const valorReal = input.value;
        stars.forEach(s => {
            s.style.color = s.dataset.value <= valorReal ? "#ffca28" : "#ccc";
        });
    });

    st.addEventListener("click", () => {
        input.value = st.dataset.value;
    });
});
</script>

<?php include("../../sistema/inc/footer.php"); ?>

