<?php
$pedido_id = $_POST['pedido_id'] ?? 0;

if (!$pedido_id) {
    header("Location: index.php");
    exit;
}

header("Location: cambiar_estado.php?id=$pedido_id&estado=cancelado");
exit;