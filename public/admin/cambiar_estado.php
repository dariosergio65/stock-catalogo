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

    // obtener productos del pedido
    $stmt = $pdo->prepare("
        SELECT producto_id, cantidad
        FROM pedido_detalle
        WHERE pedido_id = ?
    ");
    $stmt->execute([$id]);
    $items = $stmt->fetchAll();

    foreach ($items as $item) {

        $producto_id = $item['producto_id'];
        $cantidad = $item['cantidad'];

        // buscar depósito original del producto
        $stmt = $pdo->prepare("SELECT deposito_id FROM productos WHERE id=?");
        $stmt->execute([$producto_id]);
        $producto = $stmt->fetch();

        $deposito_origen = $producto['deposito_id'];

        // PAGADO → mover de reservas a mostrador
        if ($estado == 'pagado') {

            transferir_stock($pdo, $producto_id, 8, 3, $cantidad);

        }

        // ENVIADO → salida definitiva desde mostrador
        if ($estado == 'enviado') {

            // restar stock del mostrador
            $sql = "UPDATE stock_deposito
                    SET cantidad = cantidad - ?
                    WHERE producto_id = ?
                    AND deposito_id = 3
                    LIMIT 1";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$cantidad, $producto_id]);

            // eliminar fila si queda en 0
            $sql = "DELETE FROM stock_deposito
                    WHERE producto_id = ?
                    AND deposito_id = 3
                    AND cantidad <= 0";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$producto_id]);

        }

        // CANCELADO → devolver al depósito original
        if ($estado == 'cancelado') {

            transferir_stock($pdo, $producto_id, 8, $deposito_origen, $cantidad);

        }
    }

    $stmt = $pdo->prepare("SELECT estado FROM pedidos WHERE id=?");
    $stmt->execute([$id]);
    $pedido = $stmt->fetch();

    $estado_actual = $pedido['estado'];

    $transiciones = [
    'pendiente' => ['pagado','cancelado'],
    'pagado' => ['enviado'],
    'enviado' => ['entregado'],
    'entregado' => [],
    'cancelado' => []
    ];

    if (!in_array($estado, $transiciones[$estado_actual])) {
        die("Cambio de estado no permitido");
    }

    // actualizar estado del pedido
    $stmt = $pdo->prepare("UPDATE pedidos SET estado=? WHERE id=?");
    $stmt->execute([$estado,$id]);

    $pdo->commit();

} catch (Exception $e) {

    $pdo->rollBack();
    die("Error: " . $e->getMessage());

}

header("Location: pedido_ver.php?id=".$id);
exit;