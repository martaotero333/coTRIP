<?php
$pagina_publica = true;

include("sistema/inc/header.php");
require_once("sistema/inc/include_classes.php");

?>

<div class="form-container">

    <h2>Crear tu cuenta 👤</h2>

<form action="/cotrip/plataforma/controlador/registro_proc.php" method="POST" autocomplete="off">

    <?php
    if (isset($_SESSION["token_invitacion"])): ?>
        <input type="hidden" name="token_invitacion" value="<?= $_SESSION["token_invitacion"] ?>">
    <?php endif; ?>

    <label>Tu nombre</label>
    <input type="text" name="reg_nombre" required>

    <label>Correo electrónico</label>
    <input type="email" name="reg_email" required
           value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>">

    <label>Contraseña</label>
    <input type="password" name="reg_password" required>

    <button type="submit">Crear cuenta</button>
</form>

</div>

<?php include("sistema/inc/footer.php"); ?>
