<?php
session_start();
require_once "../../config/db.php";

if (empty($_SESSION['carrito'])) {
    header("Location: index.php");
    exit;
}

$nombre    = $_POST['nombre'];
$telefono  = $_POST['telefono'];
$email     = $_POST['email'];
$direccion = $_POST['direccion'];

$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

$pdo->beginTransaction();

$stmt = $pdo->prepare("
    INSERT INTO pedidos (nombre, telefono, email, direccion, total)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->execute([$nombre, $telefono, $email, $direccion, $total]);

$pedido_id = $pdo->lastInsertId();

$stmtDetalle = $pdo->prepare("
    INSERT INTO pedido_detalle
    (pedido_id, producto_id, descripcion, precio, cantidad, subtotal)
    VALUES (?, ?, ?, ?, ?, ?)
");

foreach ($_SESSION['carrito'] as $item) {
    $subtotal = $item['precio'] * $item['cantidad'];

    $stmtDetalle->execute([
        $pedido_id,
        $item['id'],
        $item['descripcion'],
        $item['precio'],
        $item['cantidad'],
        $subtotal
    ]);
}

$pdo->commit();

unset($_SESSION['carrito']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedido confirmado</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5 text-center">

<h2 class="text-success">✅ Pedido confirmado</h2>

<p class="mt-3">Gracias por tu compra, <b><?= htmlspecialchars($nombre) ?></b></p>

<p>Número de pedido: <b>#<?= $pedido_id ?></b></p>

<a href="index.php" class="btn btn-primary mt-3">
    Volver al catálogo
</a>

</div>

</body>
</html>