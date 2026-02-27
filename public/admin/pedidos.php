<?php
require_once "../../config/db.php";

$stmt = $pdo->query("SELECT * FROM pedidos ORDER BY id DESC");
$pedidos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel - Pedidos</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">

<h3 class="mb-3">📦 Pedidos</h3>

<table class="table table-bordered table-striped table-hover">
<thead class="table-dark">
<tr>
<th>#</th>
<th>Cliente</th>
<th>Total</th>
<th>Estado</th>
<th>Fecha</th>
<th></th>
</tr>
</thead>
<tbody>

<?php foreach ($pedidos as $p): ?>

<tr>
<td><?= $p['id'] ?></td>
<td><?= htmlspecialchars($p['nombre']) ?></td>
<td>$<?= number_format($p['total'], 2, ',', '.') ?></td>
<td>
    <span class="badge bg-<?php
        echo $p['estado']=='pendiente'?'warning':
             ($p['estado']=='pagado'?'success':'primary');
    ?>">
        <?= strtoupper($p['estado']) ?>
    </span>
</td>
<td><?= $p['fecha'] ?></td>
<td>
    <a href="pedido_ver.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">
        Ver
    </a>
</td>
</tr>

<?php endforeach; ?>

</tbody>
</table>

</div>

</body>
</html>