<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('productos');

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch();

if (!$producto) {
    echo "Producto no encontrado";
    exit;
}

$categorias = $pdo->query("SELECT id, nombre FROM categorias ORDER BY nombre")->fetchAll();
$depositos  = $pdo->query("SELECT id, nombre FROM depositos WHERE activo = 1 ORDER BY nombre")->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Editar Producto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body>

<div class="container mt-4">

<h4 class="mb-3">✏ Editar producto</h4>

<form method="post" action="actualizar.php" class="card card-body shadow-sm">

<input type="hidden" name="id" value="<?= $producto['id'] ?>">

<div class="mb-2">
  <label class="form-label">Código</label>
  <input name="codigo" class="form-control" required value="<?= htmlspecialchars($producto['codigo']) ?>">
</div>

<div class="mb-2">
  <label class="form-label">Descripción</label>
  <input name="descripcion" class="form-control" required value="<?= htmlspecialchars($producto['descripcion']) ?>">
</div>

<div class="mb-2">
  <label class="form-label">Categoría</label>
  <select name="categoria_id" class="form-select">
    <option value="">Sin categoría</option>
    <?php foreach ($categorias as $c): ?>
      <option value="<?= $c['id'] ?>" <?= $c['id'] == $producto['categoria_id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['nombre']) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<div class="mb-2">
  <label class="form-label">Depósito</label>
  <select name="deposito_id" class="form-select" required>
    <?php foreach ($depositos as $d): ?>
      <option value="<?= $d['id'] ?>" <?= $d['id'] == $producto['deposito_id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($d['nombre']) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<div class="row">
  <div class="col-md-4 mb-2">
    <label class="form-label">Stock mínimo</label>
    <input type="number" name="stock_minimo" class="form-control" required value="<?= $producto['stock_minimo'] ?>">
  </div>

  <div class="col-md-4 mb-2">
    <label class="form-label">Precio compra</label>
    <input type="number" step="0.01" name="precio_compra" class="form-control" required value="<?= $producto['precio_compra'] ?>">
  </div>

  <div class="col-md-4 mb-2">
    <label class="form-label">Precio venta</label>
    <input type="number" step="0.01" name="precio_venta" class="form-control" required value="<?= $producto['precio_venta'] ?>">
  </div>
</div>

<div class="mt-3">
  <button class="btn btn-primary">Actualizar</button>
  <a href="index.php" class="btn btn-secondary">Volver</a>
</div>

</form>

</div>

</body>
</html>
