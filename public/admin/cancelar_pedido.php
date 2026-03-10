<?php
session_start();
require_once "../../config/db.php";
require_once "../../config/stock.php";

$pedido_id = $_POST['pedido_id'] ?? 0;

if (!$pedido_id) {
    header("Location: index.php");
    exit;
}

// Verificar estado actual
$stmt = $pdo->prepare("SELECT estado FROM pedidos WHERE id = ?");
$stmt->execute([$pedido_id]);
$pedido = $stmt->fetch();

if (!$pedido) {
    die("Pedido inexistente.");
}

// Solo permitir cancelar si está pendiente
if ($pedido['estado'] != 'pendiente') {
    die("Este pedido ya no puede cancelarse.");
}

try {

    $pdo->beginTransaction();

    // Obtener productos del pedido
    $stmt = $pdo->prepare("
        SELECT producto_id, cantidad
        FROM pedido_detalle
        WHERE pedido_id = ?
    ");
    $stmt->execute([$pedido_id]);
    $items = $stmt->fetchAll();

    foreach ($items as $item) {

        // Buscar depósito original del producto
        $stmt = $pdo->prepare("
            SELECT deposito_id
            FROM productos
            WHERE id = ?
        ");
        $stmt->execute([$item['producto_id']]);
        $producto = $stmt->fetch();

        $deposito_original = $producto['deposito_id'];

        // Devolver stock desde reservas
        transferir_stock(
            $pdo,
            $item['producto_id'],
            8, // depósito reservas
            $deposito_original,
            $item['cantidad']
        );

    }

    // Actualizar estado del pedido
    $stmt = $pdo->prepare("UPDATE pedidos SET estado='cancelado' WHERE id=?");
    $stmt->execute([$pedido_id]);

    $pdo->commit();

} catch (Exception $e) {

    $pdo->rollBack();
    die("Error: " . $e->getMessage());

}

header("Location: pedido_ver.php?id=".$pedido_id);
exit;