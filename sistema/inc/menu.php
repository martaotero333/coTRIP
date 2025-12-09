<?php
$usuarioHeader = null;
if (isset($_SESSION["usuario_id"])) {
    $uClass = new Usuario();
    $usuarioHeader = $uClass->obtenerUsuario($_SESSION["usuario_id"]);
}
?>

<nav class="nav-bar">

    <div class="nav-left">
        <a href="/cotrip/index.php" class="logo">CoTRIP</a>
    </div>

    <div class="nav-right">

        <?php if(isset($_SESSION["usuario_id"])): ?>

            <a href="/cotrip/plataforma/vista/mis_viajes.php">Mis Viajes</a>
            <a href="/cotrip/plataforma/vista/calendario.php">Calendario</a>
            <a href="/cotrip/plataforma/vista/mis_gastos.php">Mis gastos</a>
            <a href="/cotrip/plataforma/vista/viaje_crear.php">Crear viaje</a>

            <div class="perfil-dropdown">

                <div class="perfil-trigger">
                    <span class="perfil-avatar">
                        <?= strtoupper(substr($usuarioHeader["nombre"] ?? "?", 0, 1)) ?>
                    </span>
                    <span class="perfil-nombre">
                        <?= htmlspecialchars($usuarioHeader["nombre"] ?? "Perfil") ?>
                    </span>
                    <span class="perfil-arrow">▾</span>
                </div>

                <div class="perfil-menu">
                    <a href="/cotrip/plataforma/vista/perfil_editar.php">Mi perfil</a>
                    <a href="/cotrip/logout.php" class="logout-op">Cerrar sesión</a>
                </div>

            </div>

        <?php else: ?>

            <a href="/cotrip/login.php">Entrar</a>
            <a href="/cotrip/registro.php" class="btn-primary-small">Registrarme</a>

        <?php endif; ?>

    </div>

</nav>

<style>


.nav-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 5%;
    background: #ffffff;
    border-bottom: none;
}

.logo {
    font-size: 32px;            
    font-weight: 800;
    color: #ff6f6f;             
    letter-spacing: -0.5px;
    text-decoration: none;
}

.nav-right a {
    margin-left: 20px;
    text-decoration: none;
    color: #333;
    font-size: 15px;
}

.nav-right a:hover {
    color: #0077ff;
}


.perfil-dropdown {
    position: relative;
    display: inline-block;
    margin-left: 18px;
}

.perfil-trigger {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    font-size: 14px;
    color: #333;
}

.perfil-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #5b2245;   
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

.perfil-nombre {
    font-size: 14px;
}

.perfil-arrow {
    font-size: 11px;
    color: #555;
}

.perfil-menu {
    position: absolute;
    top: 36px;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 6px 0;
    width: 150px;
    display: none;
}

.perfil-menu a {
    display: block;
    padding: 8px 12px;
    font-size: 14px;
    color: #333;
    text-decoration: none;
}

.perfil-menu a:hover {
    background: #f2f2f2;
}

.logout-op {
    color: #b10000 !important;
}
</style>

<script>

document.addEventListener("DOMContentLoaded", () => {

    const trigger = document.querySelector(".perfil-trigger");
    const menu = document.querySelector(".perfil-menu");

    if (!trigger || !menu) return;

    trigger.addEventListener("click", () => {
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", (e) => {
        if (!trigger.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = "none";
        }
    });
});
</script>




