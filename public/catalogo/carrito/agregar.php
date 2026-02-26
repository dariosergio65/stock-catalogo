<?php
session_start();
require_once "../../config/db.php";

$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT id, descripcion, precio_venta, imagen 
    FROM productos 
    WHERE id = ? AND stock > 0
");
$stmt->execute([$id]);
$producto = $stmt->fetch();

if (!$producto) {
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if (isset($_SESSION['carrito'][$id])) {
    $_SESSION['carrito'][$id]['cantidad']++;
} else {
    $_SESSION['carrito'][$id] = [
        'id' => $producto['id'],
        'descripcion' => $producto['descripcion'],
        'precio' => $producto['precio_venta'],
        'cantidad' => 1,
        'imagen' => $producto['imagen']
    ];
}

header("Location: carrito.php");
exit;