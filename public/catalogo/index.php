<?php
require_once "../../config/db.php";

$productos = $pdo->query("
SELECT 
    p.*,
    c.nombre AS categoria
FROM productos p
LEFT JOIN categorias c ON p.categoria_id = c.id
WHERE p.stock > 0
ORDER BY p.descripcion
")->fetchAll();

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nombre")->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Catálogo</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background:#f8f9fa;
}

.card-prod {
    border-radius: 14px;
    overflow: hidden;
    transition: .2s;
}

.card-prod:hover {
    transform: scale(1.01);
}

.card-prod img {
    height: 160px;
    object-fit: cover;
}

@media (min-width: 768px) {
    .card-prod img {
        height: 200px;
    }
}
</style>

</head>

<body>

<a href="../carrito/carrito.php" class="btn btn-dark mb-3 w-100">
   Ver carrito 🛒
</a>

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">🛒 Catálogo</span>
  </div>
</nav>

<div class="container my-3">

<div class="row g-2 mb-3">

<div class="col-6">
<select id="filtroCategoria" class="form-select">
  <option value="">Todas</option>
  <?php foreach ($categorias as $c): ?>
    <option value="<?= strtolower($c['nombre']) ?>">
      <?= $c['nombre'] ?>
    </option>
  <?php endforeach; ?>
</select>
</div>

<div class="col-6">
<input id="filtroTexto" class="form-control" placeholder="Buscar...">
</div>

</div>

<div class="row g-2" id="contenedor">

<?php foreach ($productos as $p): ?>

<div class="col-6 col-md-3 producto"
     data-cat="<?= strtolower($p['categoria']) ?>"
     data-desc="<?= strtolower($p['descripcion']) ?>">

<div class="card card-prod h-100">

<img src="../../uploads/productos/<?= $p['imagen'] ?: 'no-image.png' ?>">

<div class="card-body p-2">

<h6 class="mb-1"><?= $p['descripcion'] ?></h6>

<small class="text-muted d-block">
<?= $p['categoria'] ?: 'Sin categoría' ?>
</small>

<div class="fw-bold text-success mt-1">
$<?= number_format($p['precio_venta'],2,',','.') ?>
</div>

<a href="../carrito/agregar.php?id=<?= $p['id'] ?>" class="btn btn-success w-100">
   Agregar 🛒
</a>

</div>

</div>
</div>

<?php endforeach; ?>

</div>


</div>

<script>
const filtroCat = document.getElementById('filtroCategoria');
const filtroTxt = document.getElementById('filtroTexto');
const productos = document.querySelectorAll('.producto');

function filtrar() {
  const cat = filtroCat.value;
  const txt = filtroTxt.value.toLowerCase();

  productos.forEach(p => {
    const pc = p.dataset.cat || '';
    const pd = p.dataset.desc || '';

    let ver = true;

    if (cat && pc !== cat) ver = false;
    if (txt && !pd.includes(txt)) ver = false;

    p.style.display = ver ? '' : 'none';
  });
}

filtroCat.addEventListener('change', filtrar);
filtroTxt.addEventListener('keyup', filtrar);
</script>

</body>
</html>