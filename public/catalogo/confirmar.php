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

try {

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO pedidos (nombre, telefono, email, direccion, total)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nombre, $telefono, $email, $direccion, $total]);

    $pedido_id = $pdo->lastInsertId();

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

        // reservar stock
        $producto_id = $item['id'];
        $cantidad = $item['cantidad'];

        $deposito_reserva = 8;

        transferir_stock($pdo, $producto_id, $deposito_reserva, $cantidad);
    }

    $pdo->commit();

} catch (Exception $e) {

    $pdo->rollBack();

    $_SESSION['error_stock'] = "❌ No hay stock suficiente para completar el pedido.";

    header("Location: carrito.php");
    exit;
}
unset($_SESSION['carrito']);

header("Location: pedido.php?id=" . $pedido_id);
exit;