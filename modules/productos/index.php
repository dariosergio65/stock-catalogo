<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('productos');

require_once "../../config/db.php";

$productos = $pdo->query("
    SELECT p.*, c.nombre AS categoria
    FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    WHERE p.activo = 1
")->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Productos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Productos</h3>
  <a href="../../public/index.php" class="btn btn-secondary">⬅ Menú</a>
</div>

<a href="crear.php" class="btn btn-primary mb-3">➕ Nuevo producto</a>

<table class="table table-bordered table-hover table-sm">
<thead class="table-dark">
<tr>
  <th>Código</th>
  <th>Descripción</th>
  <th>Categoría</th>
  <th>Stock</th>
  <th>Stock mín.</th>
  <th>Precio</th>
  <th>Acciones</th>
</tr>
</thead>

<tbody>
<?php foreach($productos as $p): ?>
<tr class="<?= $p['stock'] <= $p['stock_minimo'] ? 'table-danger' : '' ?>">
  <td><?= htmlspecialchars($p['codigo']) ?></td>
  <td><?= htmlspecialchars($p['descripcion']) ?></td>
  <td><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
  <td><?= $p['stock'] ?></td>
  <td><?= $p['stock_minimo'] ?></td>
  <td>$<?= number_format($p['precio_venta'],2) ?></td>
  <td>
    <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
    <a href="eliminar.php?id=<?= $p['id'] ?>"
       class="btn btn-danger btn-sm"
       onclick="return confirm('¿Eliminar producto?')">🗑</a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</body>
</html>
