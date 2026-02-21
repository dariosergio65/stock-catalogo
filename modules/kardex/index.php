<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('kardex');

require_once "../../config/db.php";

$productos = $pdo->query("
  SELECT id, descripcion 
  FROM productos 
  ORDER BY descripcion
")->fetchAll();
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
  <h3>📦 Kardex por Producto</h3>
  <a href="../../public/index.php" class="btn btn-secondary">⬅ Menú</a>
</div>

<div class="card p-4">

<form method="get" action="ver.php">

  <label class="form-label">Seleccione producto:</label>
  <select name="producto_id" class="form-select mb-3" required>
    <option value="">-- Seleccione --</option>
    <?php foreach ($productos as $p): ?>
      <option value="<?= $p['id'] ?>">
        <?= htmlspecialchars($p['descripcion']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <button class="btn btn-primary">
    🔍 Ver Kardex
  </button>

</form>

</div>

</body>
</html>
