<?php
require_once("../../sistema/inc/include_classes.php");

$id = $_GET["id"];

header("Location: /cotrip/plataforma/vista/viaje_dashboard.php?id=".$id);
exit;
