<?php
$pagina_publica = true;
include("sistema/inc/header.php");

$redirect = $_GET["redirect"] ?? null;
$token    = $_GET["token"] ?? null;
?>

<div class="form-container">

    <h2>Iniciar sesión 🔐</h2>

    <form action="/cotrip/plataforma/controlador/login_proc.php" method="POST">

        <input type="hidden" name="redirect" value="<?= $redirect ?>">
        <input type="hidden" name="token" value="<?= $token ?>">

        <label>Email</label>
        <input type="email" name="email"
               value="<?= isset($_GET['email_prellenado']) ? htmlspecialchars($_GET['email_prellenado']) : '' ?>"
               required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <button type="submit">Entrar</button>

    </form>

</div>

<?php include("sistema/inc/footer.php"); ?>

