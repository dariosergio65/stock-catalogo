<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('depositos');

$stmt = $pdo->query("SELECT * FROM depositos ORDER BY nombre");
$depositos = $stmt->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Depósitos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">
  <h3>🏬 Depósitos</h3>

  <a href="crear.php" class="btn btn-success mb-3">➕ Nuevo depósito</a>
  <a href="../../public/index.php" class="btn btn-secondary">⬅ Volver</a>
  <br><br>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <!-- <th>ID</th> -->
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Estado</th>
        <th width="160">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($depositos as $d): ?>
      <tr>
        <!-- <td><?php // $d['id'] ?></td>  -->
        <td><?= htmlspecialchars($d['nombre']) ?></td>
        <td><?= htmlspecialchars($d['descripcion']) ?></td>
        <td>
          <?= $d['activo'] ? '🟢 Activo' : '🔴 Inactivo' ?>
        </td>
        <td>
          <a href="editar.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-primary">✏</a>
          <a href="eliminar.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('¿Eliminar este depósito?')">🗑</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  

</div>

</body>
</html>
