<?php
//session_start();
require_once __DIR__ . "/EstadoPedidoStrategy.php";
require_once __DIR__ . "/../stock.php";

class CanceladoStrategy implements EstadoPedidoStrategy {

    public function ejecutar($pdo, $pedido_id) {

        // 🔁 Devolver stock a depósitos originales
        devolver_stock_pedido($pdo, $pedido_id);

        // 📊 Registrar movimiento en Kardex
        $sql = "
            SELECT 
                producto_id, 
                cantidad, 
                deposito_origen
            FROM pedido_detalle
            WHERE pedido_id = ?
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$pedido_id]);

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //PRUEBA
        //echo "<pre>";
        //print_r($items);
        //exit;
        //FIN DE PRUEBA

        foreach ($items as $item) {

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
        }
    }
}
