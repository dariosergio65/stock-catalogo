<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";

$producto_id  = (int) $_POST['producto_id'];
$tipo         = $_POST['tipo'];          // 'entrada' o 'salida'
$cantidad     = (int) $_POST['cantidad'];
$lote         = $_POST['lote'] ?? null;
$cliente_id   = $_POST['cliente_id'] ?: null;
$proveedor_id = $_POST['proveedor_id'] ?: null;
$usuario_id   = $_SESSION['usuario_id'];

// Validación básica
if ($cantidad <= 0 || !in_array($tipo, ['entrada','salida'])) {
    die("Movimiento inválido");
}

try {
    $pdo->beginTransaction();

    // 1️⃣ Registrar movimiento
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

    // 2️⃣ Actualizar stock
    if ($tipo === 'entrada') {
        $stmt = $pdo->prepare("
            UPDATE productos 
            SET stock = stock + ?
            WHERE id = ?
        ");
    } else {
        $stmt = $pdo->prepare("
            UPDATE productos 
            SET stock = stock - ?
            WHERE id = ?
        ");
    }

    $stmt->execute([$cantidad, $producto_id]);

    $pdo->commit();
    header("Location: ../productos/index.php");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("Error al mover stock");
}
?>