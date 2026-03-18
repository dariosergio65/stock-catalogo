<?php
// En realidad cancela pedidos en: public/admin/cambiar_estado
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

        // 📊 Kardex: CANCELACIÓN
        try {
            $pdo->prepare("
                INSERT INTO movimientos
                (producto_id, tipo, cantidad, fecha, deposito_origen, deposito_destino, tipo_movimiento, usuario_id)
                VALUES (?, 'entrada', ?, NOW(), ?, ?, 'cancelacion', ?)
            ")->execute([
                $item['producto_id'],
                $item['cantidad'],
                DEPOSITO_RESERVAS,
                $item['deposito_origen'],
                $_SESSION['usuario_id'] ?? 1
            ]);
        } catch (Exception $e) {
            echo "ERROR CANCELACION KARDEX:<br>";
            echo $e->getMessage();
            exit;
        }

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