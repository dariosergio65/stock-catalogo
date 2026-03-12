<?php
session_start();
require_once "../../../config/db.php";

$id = intval($_GET['id'] ?? 0);
$cantidad = intval($_GET['cantidad'] ?? 1);

if ($id <= 0 || $cantidad <= 0) {
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$stmt = $pdo->prepare("
    SELECT 
        p.id,
        p.descripcion,
        p.precio_venta,
        p.imagen,
        COALESCE(SUM(sd.cantidad),0) AS stock
    FROM productos p
    LEFT JOIN stock_deposito sd ON p.id = sd.producto_id
    WHERE p.id = ?
    GROUP BY p.id
");

$stmt->execute([$id]);
$producto = $stmt->fetch();

if (!$producto) {
    header("Location: ../index.php");
    exit;
}

$stock = $producto['stock'];

$cantidad_en_carrito = $_SESSION['carrito'][$id]['cantidad'] ?? 0;

if (($cantidad_en_carrito + $cantidad) > $stock) {

    $_SESSION['error_stock'] = "Stock insuficiente para {$producto['descripcion']}";
    header("Location: carrito.php");
    exit;
}

if (isset($_SESSION['carrito'][$id])) {

    $_SESSION['carrito'][$id]['cantidad'] += $cantidad;

} else {

    $_SESSION['carrito'][$id] = [
        'id' => $producto['id'],
        'descripcion' => $producto['descripcion'],
        'precio' => $producto['precio_venta'],
        'cantidad' => $cantidad,
        'imagen' => $producto['imagen']
    ];
}

header("Location: carrito.php");
exit;