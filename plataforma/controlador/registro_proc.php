<?php
$pagina_publica = true;
require_once($_SERVER['DOCUMENT_ROOT']."/cotrip/sistema/inc/include_classes.php");
require_once($_SERVER['DOCUMENT_ROOT']."/cotrip/sistema/inc/sesiones_cotrip.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /cotrip/registro.php");
    exit;
}

/* ============================================================
   CAPTURAR TOKEN SI LLEGA POR POST
============================================================ */
if (isset($_POST["token_invitacion"])) {
    $_SESSION["token_invitacion"] = $_POST["token_invitacion"];
}

/* ============================================================
   1. RECOGER DATOS DEL FORMULARIO
============================================================ */
$nombre  = trim($_POST['reg_nombre']);
$email   = trim($_POST['reg_email']);
$pass    = trim($_POST['reg_password']);

$auth = new Autenticacion();
$usuarioClass = new Usuario();

/* ============================================================
   2. COMPROBAR EMAIL DUPLICADO
============================================================ */
$db = new DB();
$pdo = $db->pdo;

$stmt = $pdo->prepare("SELECT id FROM autenticacion WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    header("Location: /cotrip/registro.php?error=email_usado");
    exit;
}

/* ============================================================
   3. CREAR USUARIO
============================================================ */
$usuario_id = $usuarioClass->crearUsuarioSimple($nombre);

/* ============================================================
   4. CREAR AUTENTICACIÓN
============================================================ */
$auth->crearAutenticacion($usuario_id, $email, $pass);

/* ============================================================
   5. INICIAR SESIÓN
============================================================ */
$_SESSION['usuario_id'] = $usuario_id;

/* ============================================================
   6. PROCESAR INVITACIÓN (si existía)
============================================================ */
if (isset($_SESSION['token_invitacion'])) {

    $token = $_SESSION['token_invitacion'];
    unset($_SESSION['token_invitacion']);

    $invClass = new Invitacion();
    $viajeClass = new Viaje();

    $inv = $invClass->obtenerPorToken($token);

    if ($inv && $inv['estado'] === 'pendiente') {

        $invClass->aceptar($inv['id'], $usuario_id);

        if (!$viajeClass->yaEsParticipante($inv['viaje_id'], $usuario_id)) {
            $viajeClass->agregarParticipante($inv['viaje_id'], $usuario_id);
        }

        /* ============================================================
           INSERTAR GASTO PRECIO BASE
        ============================================================= */
        $gastoClass = new Gasto();
        $viaje = $viajeClass->obtenerViaje($inv['viaje_id']);
        $gastoClass->agregarGasto($inv['viaje_id'], $usuario_id, "Precio base del viaje", $viaje["precio_base"]);
        /* ============================================================ */
    }
}

/* ============================================================
   7. REDIRIGIR A EDITAR PERFIL
============================================================ */
header("Location: /cotrip/plataforma/vista/perfil_editar.php?msg=complete_tu_perfil");
exit;





