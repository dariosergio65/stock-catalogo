<?php require_once "../config/auth.php"; ?>
<?php require_once "../config/permisos.php"; ?>
<?php
require_once "../config/db.php"; // conexión PDO

$sql = "
SELECT 
    p.id,
    p.descripcion,
    p.stock,
    p.stock_minimo,
    c.nombre AS categoria
FROM productos p
LEFT JOIN categorias c ON p.categoria_id = c.id
WHERE p.stock <= p.stock_minimo
ORDER BY p.stock ASC
";

$stmt = $pdo->query($sql);
$alertas = $stmt->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Sistema de Stock</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/styles.css">

</head>
<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">Sistema de Stock</span>
    <div class="d-flex align-items-center gap-2">
      <span class="text-white small">
        👤 <?= $_SESSION['nombre'] ?>
      </span>
      <a href="logout.php" class="btn btn-danger btn-sm">Salir</a>
    </div>
  </div>
</nav>

<div class="container mt-4">

<?php if (count($alertas) > 0): ?>
<div class="alert alert-danger">
  <h5 class="mb-2">⚠ Productos con stock bajo</h5>

  <div class="table-responsive">
    <table class="table table-sm table-bordered mb-0">
      <thead class="table-danger">
        <tr>
          <th>Producto</th>
          <th>Categoría</th>
          <th>Stock</th>
          <th>Mínimo</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($alertas as $a): ?>
        <tr>
          <td><?= $a['descripcion'] ?></td>
          <td><?= $a['categoria'] ?></td>
          <td class="fw-bold text-danger"><?= $a['stock'] ?></td>
          <td><?= $a['stock_minimo'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>


  <div class="row g-3">

    <?php if (tienePermiso('productos')): ?>
      <div class="col-md-3 col-sm-6">
        <a href="../modules/productos/index.php" class="btn btn-primary w-100 p-3">
          📦 Productos
        </a>
      </div>
    <?php endif; ?>
    
    <?php if (tienePermiso('categorias')): ?>
      <div class="col-md-3 col-sm-6">
        <a href="../modules/categorias/index.php" class="btn btn-info w-100 p-3">
          🗂 Categorías
        </a>
      </div>
    <?php endif; ?>

    <?php if (tienePermiso('clientes')): ?>
      <div class="col-md-3 col-sm-6">
        <a href="../modules/clientes/index.php" class="btn btn-secondary w-100 p-3">
          👥 Clientes
        </a>
      </div>
    <?php endif; ?>

    <?php if (tienePermiso('proveedores')): ?>
      <div class="col-md-3 col-sm-6">
        <a href="../modules/proveedores/index.php" class="btn btn-secondary w-100 p-3">
          🚚 Proveedores
        </a>
      </div>
    <?php endif; ?>

    <?php if (tienePermiso('movimientos')): ?>
      <div class="col-md-3 col-sm-6">
        <a href="../modules/movimientos/index.php" class="btn btn-warning w-100 p-3">
          🔁 Movimientos de Stock
        </a>
      </div>
    <?php endif; ?>

    <?php if (tienePermiso('reportes')): ?>
      <div class="col-md-3 col-sm-6">
        <a href="../modules/reportes/index.php" class="btn btn-success w-100 p-3">
          📊 Reportes
        </a>
      </div>
    <?php endif; ?>

    <?php if (tienePermiso('usuarios')): ?>
      <div class="col-md-3 col-sm-6">
        <a href="../modules/usuarios/index.php" class="btn btn-success w-100 p-3">
          🔐 Usuarios
        </a>
      </div>
    <?php endif; ?>


      <?php //if ($_SESSION['rol'] === 'admin'): ?>
      <!-- <div class="col-md-3 col-sm-6">
        <a href="../modules/usuarios/index.php" class="btn btn-dark w-100 p-3">
          🔐 Usuarios
        </a>
      </div> -->

    <?php if (tienePermiso('kardex')): ?>
      <div class="col-md-3 col-sm-6">
        <a href="../modules/kardex/index.php" class="btn btn-info w-100 p-3">
        📦 Kardex
        </a>
      </div>
    <?php endif; ?>


    <?php if (tienePermiso('dashboard')): ?>
      <div class="col-md-3 col-sm-6">
        <a href="dashboard.php" class="btn btn-info w-100 p-3">
          📊 Dashboard
        </a>
      </div>
    <?php endif; ?>

    <?php if ($_SESSION['rol'] === 'admin'): ?>
      <div class="col-md-3 col-sm-6">
        <a href="../modules/permisos/index.php" class="btn btn-info w-100 p-3">
        🔐 Permisos
        </a>
      </div>
    <?php endif; ?>


  </div>

</div>

</body>
</html>

