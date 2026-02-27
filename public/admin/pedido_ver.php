<?php
require_once "../../config/db.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id=?");
$stmt->execute([$id]);
$pedido = $stmt->fetch();

if (!$pedido) die("Pedido no encontrado");

$stmt = $pdo->prepare("SELECT * FROM pedido_detalle WHERE pedido_id=?");
$stmt->execute([$id]);
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedido #<?= $id ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">

<h3>🧾 Pedido #<?= $id ?></h3>

<div class="card mb-3">
<div class="card-body">

<p><b>Cliente:</b> <?= htmlspecialchars($pedido['nombre']) ?></p>
<p><b>Tel:</b> <?= htmlspecialchars($pedido['telefono']) ?></p>
<p><b>Email:</b> <?= htmlspecialchars($pedido['email']) ?></p>
<p><b>Dirección:</b> <?= htmlspecialchars($pedido['direccion']) ?></p>
<p><b>Total:</b> $<?= number_format($pedido['total'], 2, ',', '.') ?></p>
<p><b>Estado:</b> <?= strtoupper($pedido['estado']) ?></p>

</div>
</div>

<table class="table table-bordered">
<thead class="table-dark">
<tr>
<th>Producto</th>
<th>Precio</th>
<th>Cant</th>
<th>Subtotal</th>
</tr>
</thead>
<tbody>

<?php foreach($items as $i): ?>

<tr>
<td><?= htmlspecialchars($i['descripcion']) ?></td>
<td>$<?= number_format($i['precio'],2,',','.') ?></td>
<td><?= $i['cantidad'] ?></td>
<td>$<?= number_format($i['subtotal'],2,',','.') ?></td>
</tr>

<?php endforeach ?>

</tbody>
</table>

<div class="d-grid gap-2">

<?php if($pedido['estado']=='pendiente'): ?>

<a href="cambiar_estado.php?id=<?= $id ?>&estado=pagado" 
   class="btn btn-success btn-lg">
   Marcar como PAGADO
</a>

<?php if ($pedido['estado'] == 'pendiente'): ?>
    <form action="cancelar_pedido.php" method="post">
        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
        <button class="btn btn-danger w-100 mt-3">
            ❌ Cancelar pedido
        </button>
    </form>
<?php endif; ?>
<!--
<a href="cambiar_estado.php?id=<?= $id ?>&estado=cancelado" 
   class="btn btn-success btn-lg">
   Marcar como CANCELADO

</a> -->
<?php endif; ?>

<?php if($pedido['estado']=='pagado'): ?>

<a href="cambiar_estado.php?id=<?= $id ?>&estado=enviado" 
   class="btn btn-primary btn-lg">
   Marcar como ENVIADO
</a>

<?php endif; ?>

</div>

<a href="pedidos.php" class="btn btn-secondary w-100 mt-3">
Volver
</a>

</div>

</body>
</html>