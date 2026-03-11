<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('productos');

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
SELECT 
    p.*,
    sd.cantidad as stock_actual
FROM productos p
LEFT JOIN stock_deposito sd 
    ON sd.producto_id = p.id 
    AND sd.deposito_id = p.deposito_id
WHERE p.id=?
");

$stmt->execute([$id]);
$p = $stmt->fetch();

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nombre")->fetchAll();
$depositos  = $pdo->query("SELECT * FROM depositos WHERE activo=1 ORDER BY nombre")->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Editar Producto</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f6f7f9;
}

.preview{
max-width:180px;
border-radius:10px;
}

</style>

</head>

<body>

<div class="container py-3">

<h4 class="mb-3">✏ Editar producto</h4>

<form method="post" action="actualizar.php" enctype="multipart/form-data" class="card shadow-sm p-3">

<input type="hidden" name="id" value="<?= $p['id'] ?>">

<div class="row g-2">

<div class="col-12">

<label class="form-label">Código</label>
<input name="codigo" value="<?= htmlspecialchars($p['codigo']) ?>" class="form-control">

</div>

<div class="col-12">

<label class="form-label">Descripción</label>
<input name="descripcion" value="<?= htmlspecialchars($p['descripcion']) ?>" class="form-control">

</div>

<div class="col-12">

<label class="form-label">Categoría</label>
<select name="categoria_id" class="form-select">

<option value="">-- Categoría --</option>

<?php foreach ($categorias as $c): ?>

<option value="<?= $c['id'] ?>" <?= $p['categoria_id']==$c['id']?'selected':'' ?>>

<?= htmlspecialchars($c['nombre']) ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-12">

<label class="form-label">Depósito</label>

<select name="deposito_id" class="form-select">

<?php foreach ($depositos as $d): ?>

<option value="<?= $d['id'] ?>" <?= $p['deposito_id']==$d['id']?'selected':'' ?>>

<?= htmlspecialchars($d['nombre']) ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-6">

<label class="form-label">Stock actual</label>

<input type="number" 
name="stock_actual" 
value="<?= $p['stock_actual'] ?? 0 ?>" 
class="form-control">

</div>

<div class="col-6">

<label class="form-label">Stock mínimo</label>

<input type="number" name="stock_minimo" value="<?= $p['stock_minimo'] ?>" class="form-control">

</div>

<div class="col-6">

<label class="form-label">Precio compra</label>

<input name="precio_compra" value="<?= $p['precio_compra'] ?>" class="form-control">

</div>

<div class="col-6">

<label class="form-label">Precio venta</label>

<input name="precio_venta" value="<?= $p['precio_venta'] ?>" class="form-control">

</div>

<div class="col-12">

<label class="form-label">Imagen</label>

<input 
type="file" 
name="imagen" 
id="imagen"
class="form-control"
accept="image/*"
capture="environment">

</div>

<div class="col-12 text-center">

<?php if ($p['imagen']): ?>

<img 
src="../../uploads/productos/<?= $p['imagen'] ?>" 
id="preview"
class="img-fluid preview mt-2">

<?php else: ?>

<img 
id="preview"
style="display:none"
class="preview mt-2">

<?php endif; ?>

</div>

</div>

<div class="mt-3 d-grid gap-2">

<button class="btn btn-success">

Guardar cambios

</button>

<a href="index.php" class="btn btn-secondary">

Cancelar

</a>

</div>

</form>

</div>

<script>

const input = document.getElementById("imagen");
const preview = document.getElementById("preview");

input.addEventListener("change", function(e){

const file = e.target.files[0];

if(!file) return;

const reader = new FileReader();

reader.onload = function(event){

preview.src = event.target.result;
preview.style.display="block";

}

reader.readAsDataURL(file);

});

</script>

</body>
</html>