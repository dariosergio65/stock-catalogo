<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";

$pdo->prepare("
UPDATE productos
SET codigo=?, descripcion=?, stock_minimo=?, precio_compra=?, precio_venta=?
WHERE id=?
")->execute([
$_POST['codigo'],
$_POST['descripcion'],
$_POST['stock_minimo'],
$_POST['precio_compra'],
$_POST['precio_venta'],
$_POST['id']
]);

header("Location: index.php");
?>
<head>
<meta charset="utf-8">
<title>Productos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>