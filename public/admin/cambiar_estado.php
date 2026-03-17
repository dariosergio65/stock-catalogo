<?php
require_once "../../config/db.php";
require_once "../../config/stock.php";

$id = (int)($_GET['id'] ?? 0);
$estado = $_GET['estado'] ?? '';

$validos = ['pendiente','pagado','enviado','cancelado','entregado'];

if(!$id || !in_array($estado, $validos)){
    die("Datos inválidos");
}

try {

    $pdo->beginTransaction();

    // Obtener pedido
    $stmt = $pdo->prepare("SELECT estado, fecha FROM pedidos WHERE id=?");
    $stmt->execute([$id]);
    $pedido = $stmt->fetch();

    if(!$pedido){
        throw new Exception("Pedido inexistente");
    }

    $estado_actual = $pedido['estado'];

    // 🔒 Control de transición
    $transiciones = [
        'pendiente' => ['pagado','cancelado'],
        'pagado' => ['enviado','cancelado'],
        'enviado' => ['entregado','cancelado'],
        'entregado' => ['cancelado'],
        'cancelado' => []
    ];

    if (!in_array($estado, $transiciones[$estado_actual])) {
        throw new Exception("Cambio de estado no permitido");
    }

    // 🔒 Regla de 10 días para cancelación
    if ($estado == 'cancelado' && $estado_actual == 'entregado') {

        $fechaPedido = new DateTime($pedido['fecha']);
        $hoy = new DateTime();

        $diff = $fechaPedido->diff($hoy)->days;

        if ($diff > 10) {
            throw new Exception("No se puede cancelar: pasaron más de 10 días");
        }
    }

    // Obtener items
    $stmt = $pdo->prepare("
        SELECT producto_id, cantidad, deposito_origen
        FROM pedido_detalle
        WHERE pedido_id = ?
    ");
    $stmt->execute([$id]);
    $items = $stmt->fetchAll();

    foreach ($items as $item) {

        $producto_id = $item['producto_id'];
        $cantidad = $item['cantidad'];
        $deposito_origen = $item['deposito_origen'];

        // ✅ CANCELADO → devolver a origen
        if ($estado == 'cancelado') {

            transferir_stock(
                $pdo,
                $producto_id,
                DEPOSITO_RESERVAS,
                $deposito_origen,
                $cantidad
            );
        }

        // ✅ ENTREGADO → salida definitiva (se elimina de reservas)
        if ($estado == 'entregado') {

            $stmt = $pdo->prepare("
                UPDATE stock_deposito
                SET cantidad = cantidad - ?
                WHERE producto_id = ?
                AND deposito_id = ?
            ");
            $stmt->execute([$cantidad, $producto_id, DEPOSITO_RESERVAS]);

            $stmt = $pdo->prepare("
                DELETE FROM stock_deposito
                WHERE producto_id = ?
                AND deposito_id = ?
                AND cantidad <= 0
            ");
            $stmt->execute([$producto_id, DEPOSITO_RESERVAS]);
        }
    }

    // actualizar estado
    $stmt = $pdo->prepare("UPDATE pedidos SET estado=? WHERE id=?");
    $stmt->execute([$estado,$id]);

    $pdo->commit();

} catch (Exception $e) {

    $pdo->rollBack();
    die("Error: " . $e->getMessage());

}

header("Location: pedido_ver.php?id=".$id);
exit;