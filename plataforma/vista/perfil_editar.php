<?php
include_once("../../sistema/inc/header.php");

$usuarioClass = new Usuario();
$usuario = $usuarioClass->obtenerUsuario($_SESSION["usuario_id"]);
?>

<style>
    .perfil-container {
        width: 90%;
        max-width: 600px;
        margin: 30px auto 50px auto;
        background: white;
        padding: 28px 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    }

    .perfil-container h2 {
        margin-top: 0;
        text-align: center;
        font-size: 26px;
        margin-bottom: 20px;
    }

    .perfil-form label {
        display: block;
        font-weight: 600;
        color: #444;
        margin-bottom: 6px;
        margin-top: 16px;
    }

    .perfil-form input[type="text"],
    .perfil-form input[type="file"],
    .perfil-form textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 15px;
        box-sizing: border-box;
    }

    .perfil-form textarea {
        min-height: 100px;
        resize: vertical;
    }

    .perfil-btn {
        display: inline-block;
        margin-top: 22px;
        padding: 11px 22px;
        background: #0077ff;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        transition: background 0.15s, transform 0.05s;
    }

    .perfil-btn:hover {
        background: #005fd1;
        transform: translateY(-1px);
    }

    .perfil-foto-actual {
        margin-top: 8px;
        padding: 10px 0;
    }

    .perfil-foto-actual img {
        max-width: 100%;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
</style>

<div class="perfil-container">

    <h2>Completar Perfil</h2>

    <form action="/cotrip/plataforma/controlador/perfil_editar_proc.php"
          method="POST"
          enctype="multipart/form-data"
          class="perfil-form">

        <label>Nombre</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

        <label>País</label>
        <input type="text" name="pais" value="<?= htmlspecialchars($usuario['pais'] ?? '') ?>" placeholder="Ej: España">

        <label>Idiomas</label>
        <input type="text" name="idiomas" value="<?= htmlspecialchars($usuario['idiomas'] ?? '') ?>" placeholder="Ej: Español, Inglés...">

        <label>Biografía</label>
        <textarea name="bio" placeholder="Cuéntanos algo sobre ti..."><?= htmlspecialchars($usuario['bio'] ?? '') ?></textarea>

        <label>Foto de Perfil</label>
        <input type="file" name="foto" accept="image/*">

        <?php if (!empty($usuario['foto'])): ?>
            <div class="perfil-foto-actual">
                <p style="color:#555; font-size:14px; margin-bottom:6px;">Foto actual:</p>
                <img src="<?= htmlspecialchars($usuario['foto']) ?>">
            </div>
        <?php endif; ?>

        <button type="submit" class="perfil-btn">Guardar cambios</button>

    </form>

</div>

<?php include("../../sistema/inc/footer.php"); ?>

