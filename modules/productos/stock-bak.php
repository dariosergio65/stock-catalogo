<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('productos');

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
SELECT 
    d.nombre AS deposito,
    sd.cantidad
FROM stock_deposito sd
INNER JOIN depositos d ON sd.deposito_id = d.id
WHERE sd.producto_id = ?
ORDER BY d.nombre
");

$stmt->execute([$id]);
$stock = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT descripcion FROM productos WHERE id=?");
$stmt->execute([$id]);
$producto = $stmt->fetch();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Stock del producto</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<h4>📊 Stock de: <?= htmlspecialchars($producto['descripcion']) ?></h4>

<table class="table table-bordered mt-3">

<thead class="table-dark">
<tr>
<th>Depósito</th>
<th class="text-end">Cantidad</th>
</tr>
</thead>

<tbody>

<?php foreach ($stock as $s): ?>

<tr>
<td><?= htmlspecialchars($s['deposito']) ?></td>
<td class="text-end"><?= $s['cantidad'] ?></td>
</tr>

<?php endforeach; ?>

</tbody>

</table>

<a href="index.php" class="btn btn-secondary">⬅ Volver</a>

</div>

</body>
</html>