<?php
require_once "../../config/db.php";

$id = (int)($_GET['id'] ?? 0);
$estado = $_GET['estado'] ?? '';

$validos = ['pendiente','pagado','enviado','cancelado'];

if(!$id || !in_array($estado, $validos)){
    die("Datos inválidos");
}

$stmt = $pdo->prepare("UPDATE pedidos SET estado=? WHERE id=?");
$stmt->execute([$estado,$id]);

header("Location: pedido_ver.php?id=".$id);
exit;