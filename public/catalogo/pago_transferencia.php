<?php
session_start();

if (!isset($_SESSION['pedido_id'])) {
    header("Location: index.php");
    exit;
}

$pedido_id = $_SESSION['pedido_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pago por transferencia</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4 text-center">

    <h3 class="mb-3">💳 Pago por transferencia</h3>

    <p class="text-muted">Pedido <b>#<?= $pedido_id ?></b></p>

    <div class="card shadow-sm p-3 mb-3">
        <h5 class="mb-2">Escaneá el QR</h5>
        <img src="../img/qr-mp.png" class="img-fluid rounded mx-auto d-block" style="max-width:280px">
    </div>

    <div class="card shadow-sm p-3 mb-3 text-start">
        <h5 class="text-center mb-3">Datos para transferir</h5>

        <p><b>Banco:</b> Mercado Pago</p>
        <p><b>Titular:</b> Darío Moreda</p>
        <p><b>Alias:</b> <span class="fs-5">bueno.tero.sean.mp</span></p>
        <p><b>CBU:</b> 0000003100061558394821</p>
        <p><b>CUIT:</b> 20-17611227-2</p>
    </div>

    <a href="enviar_whatsapp.php" class="btn btn-success btn-lg w-100 py-3">
        📲 Ya pagué — Enviar comprobante por WhatsApp
    </a>

</div>

</body>
</html>