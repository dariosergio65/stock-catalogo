<?php
session_start();
require_once "../../config/db.php";
require_once "../../config/stock.php";

if (empty($_SESSION['carrito'])) {
    header("Location: index.php");
    exit;
}

$nombre    = $_POST['nombre'];
$telefono  = $_POST['telefono'];
$email     = $_POST['email'];
$direccion = $_POST['direccion'];

$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

try {

    $pdo->beginTransaction();

    // Crear pedido
    $stmt = $pdo->prepare("
        INSERT INTO pedidos (nombre, telefono, email, direccion, total)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nombre, $telefono, $email, $direccion, $total]);

    $pedido_id = $pdo->lastInsertId();

    $_SESSION['pedido_id'] = $pedido_id;

    $stmtDetalle = $pdo->prepare("
        INSERT INTO pedido_detalle
        (pedido_id, producto_id, descripcion, precio, cantidad, subtotal, deposito_origen)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($_SESSION['carrito'] as $item) {

        $producto_id = $item['id'];
        $cantidad    = $item['cantidad'];
        $subtotal    = $item['precio'] * $cantidad;

        // 🔎 Buscar todos los depósitos con stock
        $stmt = $pdo->prepare("
            SELECT deposito_id, cantidad
            FROM stock_deposito
            WHERE producto_id = ?
            AND cantidad > 0
            ORDER BY cantidad DESC
            FOR UPDATE
        ");
        $stmt->execute([$producto_id]);

        $stocks = $stmt->fetchAll();

        $restante = $cantidad;

        foreach ($stocks as $s) {

            if ($restante <= 0) break;

            $usar = min($s['cantidad'], $restante);

            $deposito_origen = $s['deposito_id'];

            // Guardar detalle del pedido
            $stmtDetalle->execute([
                $pedido_id,
                $producto_id,
                $item['descripcion'],
                $item['precio'],
                $usar,
                $item['precio'] * $usar,
                $deposito_origen
            ]);

            // 🔹 Transferir stock a reservas
            transferir_stock(
                $pdo,
                $producto_id,
                $deposito_origen,
                DEPOSITO_RESERVAS,
                $usar
            );

            // 📊 Kardex: RESERVA (ver error)
            try {
                $pdo->prepare("
                    INSERT INTO movimientos
                    (producto_id, tipo, cantidad, fecha, deposito_origen, deposito_destino, tipo_movimiento, usuario_id)
                    VALUES (?, 'salida', ?, NOW(), ?, ?, 'reserva', ?)
                ")->execute([
                    $producto_id,
                    $usar,
                    $deposito_origen,
                    DEPOSITO_RESERVAS,
                    $_SESSION['usuario_id'] ?? 1
                ]);
            } catch (Exception $e) {

                echo "ERROR KARDEX:<br>";
                echo $e->getMessage();
                exit;
            }

            $restante -= $usar;
        }

        if ($restante > 0) {
            throw new Exception("Stock insuficiente para producto " . $producto_id);
        }
    }

    $pdo->commit();

} catch (Exception $e) {

    $pdo->rollBack();

    $_SESSION['error_stock'] = "❌ No hay stock suficiente para completar el pedido.";
    //ver
    header("Location: carrito/carrito.php");
    exit;
}

unset($_SESSION['carrito']);

header("Location: pedido.php?id=" . $pedido_id);
exit;