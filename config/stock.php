<?php

function transferir_stock($pdo, $producto_id, $destino, $cantidad, $lote = null)
{

    $transaccionPropia = false;

    if (!$pdo->inTransaction()) {
        $pdo->beginTransaction();
        $transaccionPropia = true;
    }

    try {

        $sql = "SELECT id, deposito_id, cantidad
                FROM stock_deposito
                WHERE producto_id = ?
                AND cantidad > 0
                ORDER BY cantidad DESC
                FOR UPDATE";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$producto_id]);

        $stocks = $stmt->fetchAll();

        $restante = $cantidad;

        foreach ($stocks as $s) {

            if ($restante <= 0) break;

            $usar = min($s['cantidad'], $restante);

            $pdo->prepare("
                UPDATE stock_deposito
                SET cantidad = cantidad - ?
                WHERE id = ?
            ")->execute([$usar, $s['id']]);


            $pdo->prepare("
                DELETE FROM stock_deposito
                WHERE id = ?
                AND cantidad <= 0
            ")->execute([$s['id']]);


            $pdo->prepare("
                INSERT INTO stock_deposito
                (producto_id, deposito_id, lote, cantidad)
                VALUES (?, ?, '', ?)
                ON DUPLICATE KEY UPDATE
                cantidad = cantidad + VALUES(cantidad)
            ")->execute([$producto_id, $destino, $usar]);

            $restante -= $usar;
        }

        if ($restante > 0) {
            throw new Exception("Stock insuficiente para producto $producto_id");
        }

        if ($transaccionPropia) {
            $pdo->commit();
        }

    } catch (Exception $e) {

        if ($transaccionPropia && $pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
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