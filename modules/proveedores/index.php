<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('proveedores');

require_once "../../config/db.php";

$proveedor = $pdo->query("SELECT * FROM proveedores")->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Proveedores</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="container mt-4">

<div class="d-flex justify-content-between mb-3">
  <h3>Proveedores</h3>
  <a href="../../public/index.php" class="btn btn-secondary">⬅ Menú</a>
</div>

<a href="crear.php" class="btn btn-primary mb-3">➕ Nuevo proveedor</a>

<table class="table table-bordered table-sm table-hover">
<thead class="table-dark">
<tr>
  <th>Nombre</th>
  <th>Teléfono</th>
  <th>Email</th>
  <th>Acciones</th>
</tr>
</thead>
<tbody>
<?php foreach ($proveedor as $c): ?>
<tr>
  <td><?= htmlspecialchars($c['nombre']) ?></td>
  <td><?= htmlspecialchars($c['telefono']) ?></td>
  <td><?= htmlspecialchars($c['email']) ?></td>
  <td>
    <a href="editar.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
    <a href="eliminar.php?id=<?= $c['id'] ?>"
       class="btn btn-danger btn-sm"
       onclick="return confirm('¿Eliminar Proveedor?')">🗑</a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</body>
</html>
