<?php
$id = $_GET['id'] ?? 0;

header("Location: ../../public/admin/cambiar_estado.php?id=$id&estado=cancelado");
exit;