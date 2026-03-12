<?php
session_start();
require_once "../../config/db.php";
require_once "../../config/stock.php";

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
    (pedido_id, producto_id, descripcion, precio, cantidad, subtotal, deposito_origen)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

foreach ($_SESSION['carrito'] as $item) {

    $producto_id = $item['id'];
    $cantidad    = $item['cantidad'];
    $subtotal    = $item['precio'] * $cantidad;

    // 🔎 Buscar depósito con stock disponible
    $stmt = $pdo->prepare("
        SELECT deposito_id
        FROM stock_deposito
        WHERE producto_id = ?
        AND cantidad >= ?
        ORDER BY cantidad DESC
        LIMIT 1
    ");
    $stmt->execute([$producto_id, $cantidad]);
    $row = $stmt->fetch();

    if (!$row) {
        throw new Exception("Stock insuficiente para producto " . $producto_id);
    }

    $deposito_origen = $row['deposito_id'];
    $deposito_reserva = 8; // Depósito Reservas Pedidos

    // Guardar detalle del pedido
    $stmtDetalle->execute([
        $pedido_id,
        $producto_id,
        $item['descripcion'],
        $item['precio'],
        $cantidad,
        $subtotal,
        $deposito_origen
    ]);

    // 🔹 Transferir stock a reservas
    transferir_stock($pdo, $producto_id, $deposito_reserva, $cantidad);
}

$pdo->commit();

unset($_SESSION['carrito']);

header("Location: pedido.php?id=" . $pedido_id);
exit;