<?php
session_start();
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";
require_once "../../config/auditoria.php";

verificarPermiso('movimientos');

$producto_id = $_POST['producto_id'];
$tipo         = $_POST['tipo'];
$cantidad     = $_POST['cantidad'];
$lote = !empty($_POST['lote']) ? $_POST['lote'] : 'SIN-LOTE';
$cliente_id   = $_POST['cliente_id'] ?: null;
$proveedor_id = $_POST['proveedor_id'] ?: null;
$usuario_id   = $_SESSION['usuario_id'];

$pdo->beginTransaction();

try {

    /* Obtener stock actual */
    $stmt = $pdo->prepare("SELECT stock FROM productos WHERE id = ?");
    $stmt->execute([$producto_id]);
    $stock_actual = $stmt->fetchColumn();

    /* ENTRADA */
    if ($tipo === 'entrada') {

        $nuevo_stock = $stock_actual + $cantidad;

        $stmt = $pdo->prepare("UPDATE productos SET stock = ? WHERE id = ?");
        $stmt->execute([$nuevo_stock, $producto_id]);

        $stmt = $pdo->prepare("
            INSERT INTO stock_lotes (producto_id, lote, cantidad)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)
        ");
        $stmt->execute([$producto_id, $lote, $cantidad]);
    }

    /* SALIDA FIFO */
    if ($tipo === 'salida') {

        if ($cantidad > $stock_actual) {
            throw new Exception("Stock insuficiente");
        }

        $restante = $cantidad;

        $stmt = $pdo->prepare("
            SELECT * FROM stock_lotes
            WHERE producto_id = ? AND cantidad > 0
            ORDER BY fecha_ingreso ASC
        ");
        $stmt->execute([$producto_id]);
        $lotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($lotes as $l) {

            if ($restante <= 0) break;

            if ($l['cantidad'] <= $restante) {
                $restante -= $l['cantidad'];
                $stmt = $pdo->prepare("UPDATE stock_lotes SET cantidad = 0 WHERE id = ?");
                $stmt->execute([$l['id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE stock_lotes SET cantidad = cantidad - ? WHERE id = ?");
                $stmt->execute([$restante, $l['id']]);
                $restante = 0;
            }
        }

        $nuevo_stock = $stock_actual - $cantidad;
        $stmt = $pdo->prepare("UPDATE productos SET stock = ? WHERE id = ?");
        $stmt->execute([$nuevo_stock, $producto_id]);
    }

    /* Registrar movimiento */
    $stmt = $pdo->prepare("
        INSERT INTO movimientos
        (producto_id, tipo, cantidad, lote, cliente_id, proveedor_id, usuario_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $producto_id,
        $tipo,
        $cantidad,
        $lote,
        $cliente_id,
        $proveedor_id,
        $usuario_id
    ]);

    $pdo->commit();

    registrarAuditoria(
    'movimiento',
    'movimientos',
    "Movimiento: {$tipo} - Producto ID {$producto_id} - Cantidad {$cantidad}"
    );

    header("Location: index.php");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("❌ Error: " . $e->getMessage());
}
