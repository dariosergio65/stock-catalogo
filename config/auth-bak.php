<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /stock/public/login.php");
    exit;
}

function soloAdmin() {
    if ($_SESSION['rol'] !== 'admin') {
        die("Acceso denegado");
    }
}
?>