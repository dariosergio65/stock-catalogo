<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('productos');

$id = $_GET['id'];

$producto = $pdo->prepare("SELECT * FROM productos WHERE id=?");
$producto->execute([$id]);
$p = $producto->fetch();

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nombre")->fetchAll();
$depositos  = $pdo->query("SELECT * FROM depositos ORDER BY nombre")->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Editar Producto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

<h4 class="mb-3">✏ Editar producto</h4>

<form method="post" action="actualizar.php" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $p['id'] ?>">

<div class="row">

<div class="col-md-8">

<input name="codigo" value="<?= $p['codigo'] ?>" class="form-control mb-2" placeholder="Código">

<input name="descripcion" value="<?= $p['descripcion'] ?>" class="form-control mb-2" placeholder="Descripción">

<select name="categoria_id" class="form-control mb-2">
  <option value="">-- Categoría --</option>
  <?php foreach ($categorias as $c): ?>
    <option value="<?= $c['id'] ?>" <?= $p['categoria_id']==$c['id']?'selected':'' ?>>
        <?= $c['nombre'] ?>
    </option>
  <?php endforeach; ?>
</select>

<select name="deposito_id" class="form-control mb-2">
  <option value="">-- Depósito --</option>
  <?php foreach ($depositos as $d): ?>
    <option value="<?= $d['id'] ?>" <?= $p['deposito_id']==$d['id']?'selected':'' ?>>
        <?= $d['nombre'] ?>
    </option>
  <?php endforeach; ?>
</select>

<input name="stock_minimo" type="number" value="<?= $p['stock_minimo'] ?>" class="form-control mb-2">

<input name="precio_compra" value="<?= $p['precio_compra'] ?>" class="form-control mb-2">

<input name="precio_venta" value="<?= $p['precio_venta'] ?>" class="form-control mb-2">

<input type="file" name="imagen" class="form-control mb-2">

</div>

<div class="col-md-4 text-center">

<?php if ($p['imagen']): ?>
    <img src="../../uploads/productos/<?= $p['imagen'] ?>" class="img-fluid rounded border">
<?php else: ?>
    <div class="border p-4 text-muted">Sin imagen</div>
<?php endif; ?>

</div>

</div>

<button class="btn btn-success">Guardar cambios</button>
<a href="index.php" class="btn btn-secondary">Cancelar</a>

</form>

</div>

</body>
</html>