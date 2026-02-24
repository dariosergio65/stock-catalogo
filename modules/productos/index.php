<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('productos');

$productos = $pdo->query("
    SELECT 
        p.*,
        c.nombre AS categoria,
        d.nombre AS deposito
    FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    INNER JOIN depositos d ON p.deposito_id = d.id
    WHERE p.activo=1
    ORDER BY p.descripcion
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

<body>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>📦 Productos</h4>
  <a href="crear.php" class="btn btn-success">➕ Nuevo</a>
  <a href="../../public/index.php" class="btn btn-secondary">⬅ Volver</a>
</div>

<table class="table table-bordered table-striped table-hover align-middle">

<thead class="table-dark">
<tr>
  <th>ID</th>
  <th>Código</th>
  <th>Descripción</th>
  <th>Imagen</th>
  <th>Categoría</th>
  <th>Depósito</th>
  <th class="text-end">Stock</th>
  <th width="210" class="text-end">Acciones</th>
</tr>
</thead>

<tbody>
<?php foreach ($productos as $p): ?>
<tr>
  <td><?= $p['id'] ?></td>
  <td><?= htmlspecialchars($p['codigo']) ?></td>
  <td><?= htmlspecialchars($p['descripcion']) ?></td>
  <td>
    <?php if ($p['imagen']): ?>
        <img src="../../uploads/productos/<?= $p['imagen'] ?>" height="40">
    <?php else: ?>
        —
    <?php endif; ?>
  </td>
  <td><?= htmlspecialchars($p['categoria'] ?? 'Sin categoría') ?></td>
  <td><?= htmlspecialchars($p['deposito']) ?></td>
  <td class="text-end"><?= $p['stock'] ?></td>
  <td class="text-end">
      <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">✏ Editar</a>
      <a href="eliminar.php?id=<?= $p['id'] ?>"
         onclick="return confirm('¿Eliminar producto?')"
         class="btn btn-sm btn-danger">🗑 Eliminar</a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>

</table>


</div>

</body>
</html>
