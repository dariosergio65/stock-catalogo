<?php

function transferir_stock($pdo, $producto_id, $origen, $destino, $cantidad, $lote = null)
{
        $lote = $lote ?? '';

    // buscar stock en origen
    $sql = "SELECT id, cantidad 
            FROM stock_deposito
            WHERE producto_id = ?
            AND deposito_id = ?
            AND lote = ?
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$producto_id, $origen, $lote]);
    $fila = $stmt->fetch();

    if (!$fila || $fila['cantidad'] < $cantidad) {
        throw new Exception("Stock insuficiente para producto $producto_id");
    }

    // restar del origen
    $sql = "UPDATE stock_deposito
            SET cantidad = cantidad - ?
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cantidad, $fila['id']]);

    // eliminar registro si queda en 0
        $sql = "DELETE FROM stock_deposito
        WHERE id = ?
        AND cantidad <= 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$fila['id']]);

    // sumar al destino
    $sql = "INSERT INTO stock_deposito
        (producto_id, deposito_id, lote, cantidad)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        cantidad = cantidad + VALUES(cantidad)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$producto_id, $destino, '', $cantidad]);
}