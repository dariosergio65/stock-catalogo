<?php
define('DEPOSITO_RESERVAS', 8);
define('DEPOSITO_MOSTRADOR', 3);

function transferir_stock($pdo, $producto_id, $origen, $destino, $cantidad)
{

    $stmt = $pdo->prepare("
        SELECT id, cantidad
        FROM stock_deposito
        WHERE producto_id = ?
        AND deposito_id = ?
        FOR UPDATE
    ");

    $stmt->execute([$producto_id, $origen]);
    $stock = $stmt->fetch();

    if (!$stock || $stock['cantidad'] < $cantidad) {
        throw new Exception("Stock insuficiente en depósito $origen");
    }

    // restar origen
    $pdo->prepare("
        UPDATE stock_deposito
        SET cantidad = cantidad - ?
        WHERE id = ?
    ")->execute([$cantidad, $stock['id']]);

    // eliminar si queda en 0
    $pdo->prepare("
        DELETE FROM stock_deposito
        WHERE id = ?
        AND cantidad <= 0
    ")->execute([$stock['id']]);


    // sumar destino
    $pdo->prepare("
        INSERT INTO stock_deposito
        (producto_id, deposito_id, lote, cantidad)
        VALUES (?, ?, '', ?)
        ON DUPLICATE KEY UPDATE
        cantidad = cantidad + VALUES(cantidad)
    ")->execute([$producto_id, $destino, $cantidad]);

}

function obtener_stock_total($pdo, $producto_id)
{
    $sql = "SELECT SUM(cantidad) as stock
            FROM stock_deposito
            WHERE producto_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$producto_id]);

    $fila = $stmt->fetch();

    return (int)($fila['stock'] ?? 0);
}

function devolver_stock_pedido($pdo, $pedido_id)
{
    $stmt = $pdo->prepare("
        SELECT producto_id, cantidad, deposito_origen
        FROM pedido_detalle
        WHERE pedido_id = ?
    ");

    $stmt->execute([$pedido_id]);
    $items = $stmt->fetchAll();

    foreach ($items as $item) {

        if (!$item['deposito_origen']) {
            throw new Exception(
                "El pedido tiene productos sin depósito de origen."
            );
        }

        transferir_stock(
            $pdo,
            $item['producto_id'],
            DEPOSITO_RESERVAS,          // origen
            $item['deposito_origen'],   // destino
            $item['cantidad']           // cantidad
        );
    }
}