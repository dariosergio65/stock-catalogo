<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('productos');

$depositos = $pdo->query("SELECT id, nombre FROM depositos WHERE activo = 1 ORDER BY nombre")->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Nuevo Producto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body>

<div class="container mt-4">

<h4 class="mb-3">📦 Nuevo producto</h4>

<form method="post" action="guardar.php" class="card card-body shadow-sm">

  <div class="mb-2">
    <label class="form-label">Código</label>
    <input name="codigo" class="form-control" required>
  </div>

  <div class="mb-2">
    <label class="form-label">Descripción</label>
    <input name="descripcion" class="form-control" required>
  </div>

  <div class="mb-2">
    <label class="form-label">Depósito</label>
    <select name="deposito_id" class="form-select" required>
      <option value="">Seleccione depósito...</option>
      <?php foreach ($depositos as $d): ?>
        <option value="<?= $d['id'] ?>">
            <?= htmlspecialchars($d['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="row">
    <div class="col-md-4 mb-2">
      <label class="form-label">Stock mínimo</label>
      <input type="number" name="stock_minimo" class="form-control" required>
    </div>

    <div class="col-md-4 mb-2">
      <label class="form-label">Precio compra</label>
      <input type="number" step="0.01" name="precio_compra" class="form-control" required>
    </div>

    <div class="col-md-4 mb-2">
      <label class="form-label">Precio venta</label>
      <input type="number" step="0.01" name="precio_venta" class="form-control" required>
    </div>
  </div>

  <div class="mt-3">
    <button class="btn btn-success">Guardar</button>
    <a href="index.php" class="btn btn-secondary">Volver</a>
  </div>

</form>

</div>

</body>
</html>
