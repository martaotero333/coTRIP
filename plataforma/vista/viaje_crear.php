<?php
require_once("../../sistema/inc/include_classes.php");
require_once("../../sistema/inc/sesiones_cotrip.php");
include("../../sistema/inc/header.php");
?>

<style>
    .form-container {
        width: 90%;
        max-width: 650px;
        margin: 35px auto 50px auto;
        background: white;
        padding: 30px 32px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    }

    .form-container h2 {
        margin-top: 0;
        text-align: center;
        font-size: 26px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .form-container p {
        text-align: center;
        color: #666;
    }

    .viaje-form label {
        display: block;
        font-weight: 600;
        color: #444;
        margin-bottom: 6px;
        margin-top: 16px;
    }

    .viaje-form input[type="text"],
    .viaje-form input[type="date"],
    .viaje-form input[type="number"],
    .viaje-form input[type="file"],
    .viaje-form textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 15px;
        box-sizing: border-box;
    }

    .viaje-form textarea {
        min-height: 110px;
        resize: vertical;
    }

    .btn-crear-viaje {
        width: 100%;
        margin-top: 25px;
        padding: 12px 18px;
        background: #0077ff;
        border: none;
        color: white;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.15s, transform 0.05s;
    }

    .btn-crear-viaje:hover {
        background: #005fd1;
        transform: translateY(-1px);
    }
</style>

<div class="form-container">

    <h2>Crear nuevo viaje ✈️</h2>
    <p>Define la información principal del viaje que vas a organizar.</p>

    <form action="/cotrip/plataforma/controlador/viaje_guardar_proc.php"
          method="POST"
          enctype="multipart/form-data"
          class="viaje-form">

        <label for="titulo">Título del viaje</label>
        <input type="text" id="titulo" name="titulo" required placeholder="Ej: Escapada a Oporto">

        <label for="destino">Destino</label>
        <input type="text" id="destino" name="destino" required placeholder="Ciudad o país">

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" required placeholder="Describe brevemente el viaje..."></textarea>

        <label for="fecha_inicio">Fecha inicio</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" required>

        <label for="fecha_fin">Fecha fin</label>
        <input type="date" id="fecha_fin" name="fecha_fin" required>

        <label for="precio_base">Precio base aproximado (€)</label>
        <input type="number" id="precio_base" name="precio_base" step="0.01" required>

        <label for="imagen">Imagen portada del viaje</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">

        <button type="submit" class="btn-crear-viaje">Crear viaje 🚀</button>
    </form>

</div>

<?php include("../../sistema/inc/footer.php"); ?>

