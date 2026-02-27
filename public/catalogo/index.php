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


.mobile-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #ffffff;
    border-top: 1px solid #ddd;
    display: flex;
    justify-content: space-around;
    padding: 8px 0;
    z-index: 1000;
}

.mobile-nav a {
    text-decoration: none;
    color: #333;
    text-align: center;
    font-size: 14px;
}

.mobile-nav small {
    display: block;
    font-size: 11px;
}

body {
    padding-bottom: 65px;
}

</style>

</head>

<body>

<a href="../catalogo/carrito/carrito.php" class="btn btn-dark mb-3 w-100">
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

<a href="carrito/agregar.php?id=<?= $p['id'] ?>" class="btn btn-success w-100">
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

<nav class="mobile-nav d-md-none">
    <a href="index.php">
        🏠
        <small>Catálogo</small>
    </a>

    <a href="./carrito/carrito.php" class="position-relative">
        🛒
        <small>Carrito</small>

        <?php if (!empty($_SESSION['carrito'])): ?>
            <span class="badge bg-danger position-absolute top-0 start-50 translate-middle">
                <?= count($_SESSION['carrito']) ?>
            </span>
        <?php endif; ?>
    </a>

    <a href="#busqueda">
        🔎
        <small>Buscar</small>
    </a>
</nav>

</body>
</html>