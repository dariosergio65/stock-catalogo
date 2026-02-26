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

/* ============================
   MENSAJE AUTOMÁTICO WHATSAPP
   ============================ */

$mensaje  = "🛒 *Nuevo Pedido*\n\n";
$mensaje .= "Pedido Nº: #$pedido_id\n\n";
$mensaje .= "Cliente: $nombre\n";
$mensaje .= "Tel: $telefono\n";
$mensaje .= "Dirección: $direccion\n\n";
$mensaje .= "Productos:\n";

foreach ($_SESSION['carrito'] as $item) {
    $mensaje .= "- {$item['descripcion']} x{$item['cantidad']} = $"
             . number_format($item['precio'] * $item['cantidad'], 0, ',', '.')
             . "\n";
}

$mensaje .= "\nTotal: $"
          . number_format($total, 0, ',', '.');

$telefono_whatsapp = "5491130348609";

$link_whatsapp = "https://wa.me/$telefono_whatsapp?text=" . urlencode($mensaje);

/* Limpio carrito */
unset($_SESSION['carrito']);

/* Redirijo automáticamente */
header("Location: $link_whatsapp");
exit;