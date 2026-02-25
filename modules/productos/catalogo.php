<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('productos');

$productos = $pdo->query("
SELECT 
    p.*,
    c.nombre AS categoria,
    d.nombre AS deposito
FROM productos p
LEFT JOIN categorias c ON p.categoria_id = c.id
LEFT JOIN depositos d ON p.deposito_id = d.id
ORDER BY p.descripcion
")->fetchAll();

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nombre")->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Catálogo de Productos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.card-prod {
    transition: .2s;
}
.card-prod:hover {
    transform: scale(1.02);
    box-shadow: 0 0 12px rgba(0,0,0,.15);
}
</style>
</head>

<body>

<div class="container mt-4">

<h3 class="mb-3">🛒 Catálogo de Productos</h3>
        <a href="../../public/index.php" class="btn btn-secondary mt-4">⬅ Volver</a>
<br><br>

<div class="row mb-3">

<div class="col-md-4">
<select id="filtroCategoria" class="form-select">
    <option value="">📂 Todas las categorías</option>
    <?php foreach ($categorias as $c): ?>
        <option value="<?= strtolower($c['nombre']) ?>">
            <?= $c['nombre'] ?>
        </option>
    <?php endforeach; ?>
</select>
</div>

<div class="col-md-4">
<input type="text" id="filtroTexto" class="form-control" placeholder="🔍 Buscar producto...">
</div>

</div>

<div class="row g-3" id="contenedorProductos">

<?php foreach ($productos as $p): ?>

<div class="col-md-3 producto"
     data-categoria="<?= strtolower($p['categoria']) ?>"
     data-descripcion="<?= strtolower($p['descripcion']) ?>">

<div class="card card-prod h-100">

<img src="../../uploads/productos/<?= $p['imagen'] ?: 'no-image.png' ?>" 
     class="card-img-top"
     style="height:180px">

<div class="card-body">

<h6><?= $p['descripcion'] ?></h6>

<small class="text-muted">
<?= $p['categoria'] ?: 'Sin categoría' ?>
</small>

<p class="mt-2 mb-1">
<b>$<?= number_format($p['precio_venta'],2,',','.') ?></b>
</p>

<span class="badge bg-secondary"><?= $p['deposito'] ?></span>

</div>

</div>
</div>

<?php endforeach; ?>



<script>
const filtroCategoria = document.getElementById('filtroCategoria');
const filtroTexto = document.getElementById('filtroTexto');
const productos = document.querySelectorAll('.producto');

function filtrar() {

    const cat = filtroCategoria.value;
    const txt = filtroTexto.value.toLowerCase();

    productos.forEach(p => {

        const pc = p.dataset.categoria || '';
        const pd = p.dataset.descripcion || '';

        let visible = true;

        if (cat && pc !== cat) visible = false;
        if (txt && !pd.includes(txt)) visible = false;

        p.style.display = visible ? '' : 'none';
    });
}

filtroCategoria.addEventListener('change', filtrar);
filtroTexto.addEventListener('keyup', filtrar);
</script>

</body>
</html>