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

// 👉 Guardamos el ID del pedido en sesión
$_SESSION['pedido_id'] = $pedido_id;

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

// 👉 Redirigir directamente al pago
header("Location: pago_transferencia.php");
exit;