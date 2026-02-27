<?php
session_start();
require_once "../../config/db.php";

$pedido_id = $_POST['pedido_id'] ?? 0;

if (!$pedido_id) {
    header("Location: index.php");
    exit;
}

// Verificar estado actual
$stmt = $pdo->prepare("SELECT estado FROM pedidos WHERE id = ?");
$stmt->execute([$pedido_id]);
$pedido = $stmt->fetch();

if (!$pedido) {
    die("Pedido inexistente.");
}

// Solo permitir cancelar si está pendiente
if ($pedido['estado'] != 'pendiente') {
    die("Este pedido ya no puede cancelarse.");
}

// Actualizar estado
$stmt = $pdo->prepare("UPDATE pedidos SET estado='cancelado' WHERE id=?");
$stmt->execute([$pedido_id]);

header("Location: pedido_ver.php?id=".$pedido_id);
exit;