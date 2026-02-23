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
</head>
<body class="container mt-4">
  <h3>🗂 Categorías</h3>

  <a href="crear.php" class="btn btn-success mb-3">➕ Nueva Categoría</a>
  <a href="../../public/index.php" class="btn btn-secondary">⬅ Volver</a>
  <br><br>

<table class="table table-bordered table-hover">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th width="160">Acciones</th>
    </tr>
  </thead>

<?php foreach ($categorias as $c): ?>
<tr>
  <td><?= $c['id'] ?></td>
  <td><?= htmlspecialchars($c['nombre']) ?></td>
  <td>
          <a href="editar.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-primary">✏</a>
          <a href="eliminar.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('¿Eliminar esta categoría?')">🗑</a>
        </td>
</tr>
<?php endforeach; ?>

</table>
</body>
</html>
