<?php require_once "../config/auth.php"; ?>
<?php require_once "../config/permisos.php"; ?>
<?php require_once "../config/db.php"; ?>

<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');

$sql = "
SELECT 
    p.id,
    p.descripcion,
    p.stock_minimo,
    c.nombre AS categoria,
    COALESCE(SUM(sd.cantidad),0) AS stock_total
FROM productos p
LEFT JOIN categorias c 
    ON p.categoria_id = c.id
LEFT JOIN stock_deposito sd
    ON sd.producto_id = p.id
WHERE p.activo = 1
GROUP BY p.id
HAVING stock_total <= p.stock_minimo
ORDER BY stock_total ASC
";

$stmt = $pdo->query($sql);
$alertas = $stmt->fetchAll();

?>

<!doctype html>
<html lang="es">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Sistema de Stock</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/styles.css">

</head>

<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">Sistema de Stock</span>

    <div class="dropdown">
      <button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
        👤 <?= $_SESSION['nombre'] ?>
      </button>

      <ul class="dropdown-menu dropdown-menu-end">

        <li>
          <a class="dropdown-item" href="../modules/usuarios/cambiar_clave.php">
            🔐 Cambiar contraseña
          </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        <li>
          <a class="dropdown-item text-danger" href="logout.php">
            🚪 Cerrar sesión
          </a>
        </li>

      </ul>
    </div>

  </div>
</nav>


<div class="container mt-4">

<div class="row g-3">


<?php if (tienePermiso('productos')): ?>
<div class="col-6 col-md-3">
<a href="../modules/productos/index.php" class="btn btn-primary w-100 p-3">
📦 Productos
</a>
</div>
<?php endif; ?>


<?php if (tienePermiso('catalogos')): ?>
<div class="col-6 col-md-3">
<a href="../modules/productos/catalogo.php" class="btn btn-primary w-100 p-3">
🛒 Catálogos
</a>
</div>
<?php endif; ?>


<?php if (tienePermiso('catalogos')): ?>
<div class="col-6 col-md-3">
<a href="../public/admin/pedidos.php" class="btn btn-primary w-100 p-3">
🛒 Pedidos
</a>
</div>
<?php endif; ?>


<?php if (tienePermiso('categorias')): ?>
<div class="col-6 col-md-3">
<a href="../modules/categorias/index.php" class="btn btn-info w-100 p-3">
🗂 Categorías
</a>
</div>
<?php endif; ?>


<?php if (tienePermiso('depositos')): ?>
<div class="col-6 col-md-3">
<a href="../modules/depositos/index.php" class="btn btn-info w-100 p-3">
🏬 Depósitos
</a>
</div>
<?php endif; ?>


<?php if (tienePermiso('clientes')): ?>
<div class="col-6 col-md-3">
<a href="../modules/clientes/index.php" class="btn btn-secondary w-100 p-3">
👥 Clientes
</a>
</div>
<?php endif; ?>


<?php if (tienePermiso('proveedores')): ?>
<div class="col-6 col-md-3">
<a href="../modules/proveedores/index.php" class="btn btn-secondary w-100 p-3">
🚚 Proveedores
</a>
</div>
<?php endif; ?>


<?php if (tienePermiso('movimientos')): ?>
<div class="col-6 col-md-3">
<a href="../modules/movimientos/index.php" class="btn btn-warning w-100 p-3">
🔁 Movimientos
</a>
</div>
<?php endif; ?>


<?php if (tienePermiso('reportes')): ?>
<div class="col-6 col-md-3">
<a href="../modules/reportes/index.php" class="btn btn-success w-100 p-3">
📊 Reportes
</a>
</div>
<?php endif; ?>


<?php if (tienePermiso('usuarios')): ?>
<div class="col-6 col-md-3">
<a href="../modules/usuarios/index.php" class="btn btn-success w-100 p-3">
🔐 Usuarios
</a>
</div>
<?php endif; ?>


<?php if (tienePermiso('kardex')): ?>
<div class="col-6 col-md-3">
<a href="../modules/kardex/index.php" class="btn btn-info w-100 p-3">
📦 Kardex
</a>
</div>
<?php endif; ?>


<?php if (tienePermiso('dashboard')): ?>
<div class="col-6 col-md-3">
<a href="dashboard.php" class="btn btn-info w-100 p-3">
📊 Dashboard
</a>
</div>
<?php endif; ?>


<?php if ($_SESSION['rol'] === 'admin'): ?>
<div class="col-6 col-md-3">
<a href="../modules/permisos/index.php" class="btn btn-info w-100 p-3">
🔐 Permisos
</a>
</div>
<?php endif; ?>


<?php if ($_SESSION['rol'] === 'admin'): ?>
<div class="col-6 col-md-3">
<a href="../modules/auditoria/index.php" class="btn btn-outline-dark w-100 p-3">
📜 Auditoría
</a>
</div>
<?php endif; ?>


</div>



<?php if (count($alertas) > 0): ?>

<div class="mt-5">

<h5 class="mb-3">⚠ Productos con stock bajo</h5>

<div class="row g-2">

<?php foreach ($alertas as $a): ?>

<?php
$clase = ($a['stock_total'] == 0) ? 'btn-danger' : 'btn-outline-danger';
?>

<div class="col-12 col-sm-6 col-md-4 col-lg-3">

<a href="../modules/productos/editar.php?id=<?= $a['id'] ?>"
class="btn <?= $clase ?> w-100 text-start">

<strong><?= htmlspecialchars($a['descripcion']) ?></strong><br>

<small>
<?= $a['categoria'] ?? 'Sin categoría' ?>
</small>

<div class="mt-1">

Stock:
<strong><?= $a['stock_total'] ?></strong>

<span class="text-muted">
/ Min: <?= $a['stock_minimo'] ?>
</span>

</div>

</a>

</div>

<?php endforeach; ?>

</div>

</div>

<?php endif; ?>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>