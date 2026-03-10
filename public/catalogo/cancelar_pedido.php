<?php
require_once "../../config/db.php";
require_once "../../config/stock.php";

$pedido_id = (int)($_POST['pedido_id'] ?? 0);

if (!$pedido_id) {
    die("Pedido inválido");
}

try {

    $pdo->beginTransaction();

    // Verificar estado actual
    $stmt = $pdo->prepare("SELECT estado FROM pedidos WHERE id=?");
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch();

    if (!$pedido) {
        throw new Exception("Pedido no encontrado");
    }

    if ($pedido['estado'] == 'cancelado') {
        throw new Exception("El pedido ya está cancelado");
    }

    // Obtener productos del pedido
    $stmt = $pdo->prepare("
        SELECT producto_id, cantidad
        FROM pedido_detalle
        WHERE pedido_id = ?
    ");
    $stmt->execute([$pedido_id]);
    $items = $stmt->fetchAll();

    foreach ($items as $item) {

        transferir_stock(
            $pdo,
            $item['producto_id'],
            8, // desde reservas
            1, // vuelve al depósito central
            $item['cantidad'],
            null
        );

    }

    // Cambiar estado del pedido
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

header("Location: pedido_ver.php?id=" . $pedido_id);
exit;