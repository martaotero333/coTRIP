<?php
$pagina_publica = true;
include_once("sistema/inc/header.php");
?>

<div class="home-wrapper">

    <div class="home-hero">
        <h1>CoTRIP ✈️</h1>
        <p>Organiza viajes en grupo de forma fácil, moderna y divertida.</p>

        <?php if (!isset($_SESSION["usuario_id"])): ?>
            <a class="home-btn" href="/cotrip/registro.php">Crear cuenta</a>
            <a class="home-btn-sec" href="/cotrip/login.php">Iniciar sesión</a>
        <?php else: ?>
            <a class="home-btn" href="/cotrip/plataforma/vista/mis_viajes.php">Ir a mis viajes</a>
        <?php endif; ?>
    </div>

    <div class="home-section">
        <h2>¿Qué puedes hacer con CoTRIP?</h2>

        <div class="home-grid">

            
            <div class="home-feature">
                <span class="emoji">📌</span>
                <h3>Crea viajes</h3>
                <p>Define destino, fechas, precio y toda la información importante.</p>
            </div>

            
            <div class="home-feature">
                <span class="emoji">👥</span>
                <h3>Invita a tus amigos</h3>
                <p>Envía invitaciones y gestiona fácilmente quién participa.</p>
            </div>

            
            <div class="home-feature">
                <span class="emoji">🗂️</span>
                <h3>Subplanes</h3>
                <p>Organiza actividades dentro del viaje y apunta a los participantes.</p>
            </div>

            
            <div class="home-feature">
                <span class="emoji">💬</span>
                <h3>Foro del viaje</h3>
                <p>Habla con tu grupo, deja comentarios y comparte ideas.</p>
            </div>

            
            <div class="home-feature">
                <span class="emoji">⭐</span>
                <h3>Valora los viajes</h3>
                <p>Deja tu puntuación y reseña cuando el viaje haya comenzado.</p>
            </div>

            
            <div class="home-feature">
                <span class="emoji">💶</span>
                <h3>Gestiona tus gastos</h3>
                <p>Consulta lo que debes pagar y qué gastos ya están liquidados.</p>
            </div>

        </div>
    </div>

</div>

<?php include("sistema/inc/footer.php"); ?>

