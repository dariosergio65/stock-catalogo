<?php

class EnviadoStrategy
{
    public function ejecutar($pdo, $pedido_id)
    {
        $stmt = $pdo->prepare("
            SELECT producto_id, cantidad
            FROM pedido_detalle
            WHERE pedido_id = ?
        ");
        $stmt->execute([$pedido_id]);

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $item) {

            $producto_id = $item['producto_id'];
            $cantidad = $item['cantidad'];

            // 📊 Registrar movimiento (sin afectar stock)
            $pdo->prepare("
                INSERT INTO movimientos
                (producto_id, tipo, cantidad, fecha, deposito_origen, deposito_destino, tipo_movimiento, usuario_id)
                VALUES (?, 'salida', ?, NOW(), ?, NULL, 'envio', ?)
            ")->execute([
                $producto_id,
                $cantidad,
                DEPOSITO_RESERVAS,
                $_SESSION['usuario_id'] ?? 1
            ]);
        }
    }
}