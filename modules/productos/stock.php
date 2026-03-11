<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('productos');

$id = $_GET['id'] ?? 0;

/* PRODUCTO */

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id=?");
$stmt->execute([$id]);
$producto = $stmt->fetch();

/* DEPOSITOS + STOCK */

$stocks = $pdo->prepare("
SELECT 
d.id as deposito_id,
d.nombre as deposito,
COALESCE(sd.cantidad,0) as stock
FROM depositos d
LEFT JOIN stock_deposito sd
ON sd.deposito_id = d.id
AND sd.producto_id = ?
WHERE d.activo=1
ORDER BY d.nombre
");

$stocks->execute([$id]);
$stocks = $stocks->fetchAll();

?>

<!doctype html>
<html lang="es">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Stock por depósito</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f5f6f8;
}

.card-stock{
border-radius:12px;
}

</style>

</head>

<body>

<div class="container py-3">

<h4 class="mb-3">
📦 Stock de: <?= htmlspecialchars($producto['descripcion']) ?>
</h4>

<form method="post" action="guardar_stock.php">

<input type="hidden" name="producto_id" value="<?= $id ?>">

<div class="card card-stock shadow-sm">

<div class="card-body">

<?php foreach ($stocks as $s): ?>

<div class="row align-items-center mb-2">

<div class="col-7">

<label class="form-label mb-0">
<?= htmlspecialchars($s['deposito']) ?>
</label>

</div>

<div class="col-5">

<input 
type="number"
name="stock[<?= $s['deposito_id'] ?>]"
value="<?= $s['stock'] ?>"
class="form-control">

</div>

</div>

<?php endforeach; ?>

</div>

</div>

<div class="mt-3 d-grid gap-2">

<button class="btn btn-success">
Guardar stock
</button>

<a href="index.php" class="btn btn-secondary">
Volver
</a>

</div>

</form>

</div>

</body>
</html>