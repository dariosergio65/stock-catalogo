<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('categorias');

require_once "../../config/db.php";

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nombre")->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Categorías</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Categorías</h3>
  <div>
    <a href="crear.php" class="btn btn-success">+ Nueva</a>
    <a href="../../public/index.php" class="btn btn-secondary">Menú</a>
  </div>
</div>

<table class="table table-bordered table-hover">
<tr>
  <th>ID</th>
  <th>Nombre</th>
  <th width="160">Acciones</th>
</tr>

<?php foreach ($categorias as $c): ?>
<tr>
  <td><?= $c['id'] ?></td>
  <td><?= htmlspecialchars($c['nombre']) ?></td>
  <td>
    <a href="editar.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
    <a href="eliminar.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger"
       onclick="return confirm('¿Eliminar esta categoría?')">Eliminar</a>
  </td>
</tr>
<?php endforeach; ?>

</table>
</body>
</html>
