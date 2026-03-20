<?php

class EntregadoStrategy
{
    public function ejecutar($pdo, $pedido_id)
    {
        $sql = "
            SELECT 
                producto_id, 
                cantidad
            FROM pedido_detalle
            WHERE pedido_id = ?
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$pedido_id]);

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $item) {

            $producto_id = $item['producto_id'];
            $cantidad = $item['cantidad'];

            // 🔻 descontar de reservas
            $stmt = $pdo->prepare("
                UPDATE stock_deposito
                SET cantidad = cantidad - ?
                WHERE producto_id = ?
                AND deposito_id = ?
            ");
            $stmt->execute([
                $cantidad,
                $producto_id,
                DEPOSITO_RESERVAS
            ]);

            // 🧹 limpiar si queda en 0
            $stmt = $pdo->prepare("
                DELETE FROM stock_deposito
                WHERE producto_id = ?
                AND deposito_id = ?
                AND cantidad <= 0
            ");
            $stmt->execute([
                $producto_id,
                DEPOSITO_RESERVAS
            ]);

            // 📊 registrar salida en kardex
            $pdo->prepare("
                INSERT INTO movimientos
                (producto_id, tipo, cantidad, fecha, deposito_origen, deposito_destino, tipo_movimiento, usuario_id)
                VALUES (?, 'salida', ?, NOW(), ?, NULL, 'venta', ?)
            ")->execute([
                $producto_id,
                $cantidad,
                DEPOSITO_RESERVAS,
                $_SESSION['usuario_id'] ?? 1
            ]);
        }
    }
}