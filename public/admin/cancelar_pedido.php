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

    // 🔹 DEVOLVER STOCK A DEPÓSITOS ORIGINALES
    devolver_stock_pedido($pdo, $pedido_id);

    // 🔹 ACTUALIZAR ESTADO DEL PEDIDO
    $stmt = $pdo->prepare("
        UPDATE pedidos 
        SET estado = 'cancelado'
        WHERE id = ?
    ");
    $stmt->execute([$pedido_id]);

    $pdo->commit();

} catch (Exception $e) {

    $pdo->rollBack();
    die("Error: " . $e->getMessage());

}

header("Location: pedido_ver.php?id=".$pedido_id);
exit;