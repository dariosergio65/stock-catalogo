<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('kardex');

require_once "../../config/db.php";

$id = $_GET['producto_id'];

$producto = $pdo->prepare("SELECT * FROM productos WHERE id=?");
$producto->execute([$id]);
$p = $producto->fetch();

$movimientos = $pdo->prepare("
SELECT m.*, 
       c.nombre cliente, 
       pr.nombre proveedor, 
       u.nombre usuario
FROM movimientos m
LEFT JOIN clientes c   ON c.id = m.cliente_id
LEFT JOIN proveedores pr ON pr.id = m.proveedor_id
LEFT JOIN usuarios u   ON u.id = m.usuario_id
WHERE m.producto_id = ?
ORDER BY m.fecha
");
$movimientos->execute([$id]);

$movs = $movimientos->fetchAll();

$stock = 0;
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Kardex</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Kardex: <?= htmlspecialchars($p['descripcion']) ?></h4>
  <a href="index.php" class="btn btn-secondary">⬅ Volver</a>
</div>

<table class="table table-bordered table-sm table-hover">
<tr class="table-dark">
  <th>Fecha</th>
  <th>Tipo</th>
  <th>Entrada</th>
  <th>Salida</th>
  <th>Stock</th>
  <th>Cliente</th>
  <th>Proveedor</th>
  <th>Usuario</th>
</tr>

<?php foreach ($movs as $m): ?>

<?php
if ($m['tipo'] == 'entrada') {
    $stock += $m['cantidad'];
    $ent = $m['cantidad'];
    $sal = '';
} else {
    $stock -= $m['cantidad'];
    $ent = '';
    $sal = $m['cantidad'];
}
?>

<tr>
  <td><?= $m['fecha'] ?></td>
  <td><?= strtoupper($m['tipo']) ?></td>
  <td class="text-success"><?= $ent ?></td>
  <td class="text-danger"><?= $sal ?></td>
  <td><strong><?= $stock ?></strong></td>
  <td><?= $m['cliente'] ?? '-' ?></td>
  <td><?= $m['proveedor'] ?? '-' ?></td>
  <td><?= $m['usuario'] ?></td>
</tr>

<?php endforeach; ?>

</table>

</body>
</html>
