<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('productos');

require_once "../../config/db.php";

$categorias = $pdo->query("
    SELECT id, nombre 
    FROM categorias 
    ORDER BY nombre
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

<form method="post" action="guardar.php" class="container mt-4">
<input name="codigo" class="form-control mb-2" placeholder="Código">

<select name="categoria_id" class="form-select mb-2" required>
  <option value="">Seleccione categoría</option>
  <?php foreach ($categorias as $c): ?>
    <option value="<?= $c['id'] ?>">
      <?= htmlspecialchars($c['nombre']) ?>
    </option>
  <?php endforeach; ?>
</select>


<input name="descripcion" class="form-control mb-2" placeholder="Descripción">
<input name="stock_minimo" type="number" class="form-control mb-2" placeholder="Stock mínimo">
<input name="precio_compra" class="form-control mb-2" placeholder="Precio compra">
<input name="precio_venta" class="form-control mb-2" placeholder="Precio venta">
<button class="btn btn-success">Guardar</button>
</form>