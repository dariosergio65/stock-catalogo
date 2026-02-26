<?php
session_start();
$carrito = $_SESSION['carrito'] ?? [];
$total = 0;
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Carrito</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">

<h3 class="mb-4">🛒 Mi carrito</h3>

<?php if (!$carrito): ?>
    <div class="alert alert-info">El carrito está vacío</div>
<?php else: ?>

<table class="table table-bordered bg-white">
<thead class="table-dark">
<tr>
  <th>Producto</th>
  <th>Cant.</th>
  <th>Precio</th>
  <th>Subtotal</th>
</tr>
</thead>

<tbody>
<?php foreach ($carrito as $p): 
    $sub = $p['precio'] * $p['cantidad'];
    $total += $sub;
?>
<tr>
  <td><?= htmlspecialchars($p['descripcion']) ?></td>
  <td><?= $p['cantidad'] ?></td>
  <td>$<?= number_format($p['precio'],2,',','.') ?></td>
  <td>$<?= number_format($sub,2,',','.') ?></td>
</tr>
<?php endforeach; ?>
</tbody>

<tfoot class="table-secondary">
<tr>
  <th colspan="3">Total</th>
  <th>$<?= number_format($total,2,',','.') ?></th>
</tr>
</tfoot>
</table>

<a href="vaciar.php" class="btn btn-danger">Vaciar carrito</a>
<a href="../catalogo/index.php" class="btn btn-secondary">Seguir comprando</a>

<?php endif; ?>

</div>
</body>
</html>