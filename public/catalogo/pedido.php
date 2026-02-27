<?php
require_once "../../config/db.php";

if (!isset($_GET['id'])) {
    die("Pedido inválido");
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
$stmt->execute([$id]);
$pedido = $stmt->fetch();

if (!$pedido) {
    die("Pedido no encontrado");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedido #<?= $pedido['id'] ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">

<div class="card shadow-sm">
<div class="card-body text-center">

<h3>🧾 Pedido #<?= $pedido['id'] ?></h3>

<p class="mt-2"><b>Total:</b> $<?= number_format($pedido['total'], 2, ',', '.') ?></p>

<?php
$estado = $pedido['estado'];

$clase = 'secondary';
$texto = 'Desconocido';

if ($estado == 'pendiente') {
    $clase = 'warning';
    $texto = '⏳ Pendiente de pago';
}
if ($estado == 'pagado') {
    $clase = 'success';
    $texto = '✅ Pago confirmado';
}
if ($estado == 'enviado') {
    $clase = 'primary';
    $texto = '🚚 Enviado';
}
?>

<div class="alert alert-<?= $clase ?> mt-3">
    <b><?= $texto ?></b>
</div>

<?php if ($estado == 'pendiente'): ?>

    <p class="mt-3">Para completar tu compra, realizá la transferencia y envianos el comprobante:</p>

    <a href="pago_transferencia.php?id=<?= $pedido['id'] ?>" class="btn btn-success btn-lg w-100 mb-2">
        💳 Ver datos para pagar
    </a>

    <a href="enviar_whatsapp.php?id=<?= $pedido['id'] ?>" class="btn btn-success btn-lg w-100">
        📲 Enviar comprobante por WhatsApp
    </a>

<?php endif; ?>

<a href="index.php" class="btn btn-outline-secondary w-100 mt-3">
    Seguir comprando
</a>

</div>
</div>

</div>

</body>
</html>