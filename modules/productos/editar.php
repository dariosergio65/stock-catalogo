<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('productos');

require_once "../../config/db.php";

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id=?");
$stmt->execute([$_GET['id']]);
$p = $stmt->fetch();
?>
<head>
<meta charset="utf-8">
<title>Productos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<form method="post" action="actualizar.php" class="container mt-4">
<input type="hidden" name="id" value="<?= $p['id'] ?>">

<input name="codigo" value="<?= $p['codigo'] ?>" class="form-control mb-2">
<input name="descripcion" value="<?= $p['descripcion'] ?>" class="form-control mb-2">
<input type="number" name="stock_minimo" value="<?= $p['stock_minimo'] ?>" class="form-control mb-2">
<input name="precio_compra" value="<?= $p['precio_compra'] ?>" class="form-control mb-2">
<input name="precio_venta" value="<?= $p['precio_venta'] ?>" class="form-control mb-2">

<button class="btn btn-success">Actualizar</button>
<a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>
