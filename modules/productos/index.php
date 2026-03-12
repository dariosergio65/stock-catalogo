<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('productos');

$productos = $pdo->query("
    SELECT 
        p.*,
        c.nombre AS categoria,
        COALESCE(SUM(sd.cantidad),0) AS stock
    FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    LEFT JOIN stock_deposito sd ON p.id = sd.producto_id
    WHERE p.activo = 1
    GROUP BY p.id
    ORDER BY p.descripcion
")->fetchAll();

$cats = $pdo->query("SELECT nombre FROM categorias ORDER BY nombre")->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Productos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">

<style>

.product-card img{
    max-height:80px;
    object-fit:contain;
}

.stock-badge{
    font-size:0.9rem;
}

</style>

</head>

<body>

<div class="container mt-3">

<div class="d-flex justify-content-between align-items-center mb-3">
<h4 class="mb-0">📦 Productos</h4>

<div>
<a href="crear.php" class="btn btn-success btn-sm">➕ Nuevo</a>
<a href="../../public/index.php" class="btn btn-secondary btn-sm">⬅ Volver</a>
</div>
</div>


<!-- FILTROS -->

<div class="row g-2 mb-3">

<div class="col-md-4">
<select id="filtroCategoria" class="form-select">
<option value="">Todas las categorías</option>

<?php foreach ($cats as $c): ?>

<option value="<?= htmlspecialchars($c['nombre']) ?>">
<?= htmlspecialchars($c['nombre']) ?>
</option>

<?php endforeach; ?>

</select>
</div>

<div class="col-md-8">
<input
type="text"
id="buscador"
class="form-control"
placeholder="🔎 Buscar producto por descripción..."
autofocus
>
</div>

</div>


<!-- 📱 MOBILE VIEW -->

<div class="d-md-none">

<?php foreach ($productos as $p): ?>

<div class="card mb-3 product-card shadow-sm producto-item"
data-categoria="<?= htmlspecialchars($p['categoria'] ?? '') ?>"
data-descripcion="<?= strtolower(htmlspecialchars($p['descripcion'])) ?>">

<div class="card-body">

<div class="row align-items-center">

<div class="col-3 text-center">

<?php if ($p['imagen']): ?>

<img src="../../uploads/productos/<?= $p['imagen'] ?>" class="img-fluid">

<?php else: ?>

<span class="text-muted small">Sin imagen</span>

<?php endif; ?>

</div>

<div class="col-9">

<div class="fw-bold">
<?= htmlspecialchars($p['descripcion']) ?>
</div>

<div class="text-muted small">
Código: <?= htmlspecialchars($p['codigo']) ?>
</div>

<div class="small">
<?= htmlspecialchars($p['categoria'] ?? 'Sin categoría') ?>
</div>

<div class="mt-1">
<span class="badge bg-primary stock-badge">
Stock: <?= $p['stock'] ?>
</span>
</div>

</div>

</div>

<div class="mt-3 d-flex gap-2">

<a href="stock.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm flex-fill">
📦 Stock
</a>

<a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm flex-fill">
✏ Editar
</a>

<a href="eliminar.php?id=<?= $p['id'] ?>"
onclick="return confirm('¿Eliminar producto?')"
class="btn btn-danger btn-sm flex-fill">
🗑
</a>

</div>

</div>
</div>

<?php endforeach; ?>

</div>


<!-- 💻 DESKTOP VIEW -->

<div class="d-none d-md-block">

<table class="table table-bordered table-striped table-hover align-middle">

<thead class="table-dark">

<tr>
<th>ID</th>
<th>Código</th>
<th>Descripción</th>
<th>Imagen</th>
<th>Categoría</th>
<th class="text-end">Stock</th>
<th width="260" class="text-end">Acciones</th>
</tr>

</thead>

<tbody>

<?php foreach ($productos as $p): ?>

<tr class="producto-item"
data-categoria="<?= htmlspecialchars($p['categoria'] ?? '') ?>"
data-descripcion="<?= strtolower(htmlspecialchars($p['descripcion'])) ?>">

<td><?= $p['id'] ?></td>

<td><?= htmlspecialchars($p['codigo']) ?></td>

<td><?= htmlspecialchars($p['descripcion']) ?></td>

<td>

<?php if ($p['imagen']): ?>

<img src="../../uploads/productos/<?= $p['imagen'] ?>" height="40">

<?php else: ?>

—

<?php endif; ?>

</td>

<td><?= htmlspecialchars($p['categoria'] ?? 'Sin categoría') ?></td>

<td class="text-end"><?= $p['stock'] ?></td>

<td class="text-end">

<a href="stock.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">
📦 Stock
</a>

<a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">
✏ Editar
</a>

<a href="eliminar.php?id=<?= $p['id'] ?>"
onclick="return confirm('¿Eliminar producto?')"
class="btn btn-sm btn-danger">
🗑 Eliminar
</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>


<script>

const buscador = document.getElementById("buscador");
const filtroCategoria = document.getElementById("filtroCategoria");

function filtrarProductos(){

let texto = buscador.value.toLowerCase();
let categoria = filtroCategoria.value;

document.querySelectorAll(".producto-item").forEach(el => {

let desc = el.dataset.descripcion;
let cat = el.dataset.categoria;

let coincideTexto = desc.includes(texto);
let coincideCategoria = !categoria || cat === categoria;

if(coincideTexto && coincideCategoria){
el.style.display = "";
}else{
el.style.display = "none";
}

});

}

buscador.addEventListener("keyup", filtrarProductos);
filtroCategoria.addEventListener("change", filtrarProductos);

</script>

</body>
</html>