<?php
$pagina_publica = $pagina_publica ?? false;
include_once(__DIR__ . "/sesiones_cotrip.php");
include_once(__DIR__ . "/include_classes.php");
include_once(__DIR__ . "/helpers.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CoTRIP</title>
    <link rel="stylesheet" href="/cotrip/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body>
<header class="main-header">
    <?php include(__DIR__ . "/menu.php"); ?>
</header>

<main class="main-container">
