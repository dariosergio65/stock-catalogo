<?php
require_once "../config/auth.php";
require_once "../config/permisos.php";
verificarPermiso('dashboard');

require_once "../config/db.php";

// Métricas principales
$totalProductos   = $pdo->query("SELECT COUNT(*) FROM productos")->fetchColumn();
$totalClientes    = $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
$totalProveedores = $pdo->query("SELECT COUNT(*) FROM proveedores")->fetchColumn();

$stockTotal = $pdo->query("SELECT SUM(stock) FROM productos")->fetchColumn();

$stockBajo = $pdo->query("
  SELECT COUNT(*) 
  FROM productos 
  WHERE stock <= stock_minimo
")->fetchColumn();

$movHoy = $pdo->query("
  SELECT COUNT(*) 
  FROM movimientos 
  WHERE DATE(fecha) = CURDATE()
")->fetchColumn();

$ultimos = $pdo->query("
  SELECT m.*, p.descripcion 
  FROM movimientos m
  JOIN productos p ON p.id = m.producto_id
  ORDER BY m.id DESC
  LIMIT 8
")->fetchAll();

// Movimientos últimos 7 días
$movDias = $pdo->query("
SELECT DATE(fecha) as dia, COUNT(*) total
FROM movimientos
WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY dia
ORDER BY dia
")->fetchAll();

$labelsDias = [];
$valoresDias = [];

foreach ($movDias as $d) {
    $labelsDias[] = $d['dia'];
    $valoresDias[] = $d['total'];
}

// Entradas vs salidas
$io = $pdo->query("
SELECT tipo, SUM(cantidad) total
FROM movimientos
GROUP BY tipo
")->fetchAll();

$labelsIO = [];
$valoresIO = [];

foreach ($io as $r) {
    $labelsIO[] = strtoupper($r['tipo']);
    $valoresIO[] = $r['total'];
}

// Top 5 productos más movidos
$top = $pdo->query("
SELECT p.descripcion, SUM(m.cantidad) total
FROM movimientos m
JOIN productos p ON p.id = m.producto_id
GROUP BY p.id
ORDER BY total DESC
LIMIT 5
")->fetchAll();

$labelsTop = [];
$valoresTop = [];

foreach ($top as $t) {
    $labelsTop[] = $t['descripcion'];
    $valoresTop[] = $t['total'];
}


?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/styles.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">📊 Dashboard</span>
    <a href="index.php" class="btn btn-secondary btn-sm">Menú</a>
  </div>
</nav>

<div class="container mt-4">

<div class="row g-3 text-center">

  <div class="col-md-2">
    <div class="card p-3 bg-primary text-white">
      <h5><?= $totalProductos ?></h5>
      Productos
    </div>
  </div>

  <div class="col-md-2">
    <div class="card p-3 bg-success text-white">
      <h5><?= $stockTotal ?></h5>
      Stock total
    </div>
  </div>

  <div class="col-md-2">
    <div class="card p-3 bg-danger text-white">
      <h5><?= $stockBajo ?></h5>
      Stock bajo
    </div>
  </div>

  <div class="col-md-2">
    <div class="card p-3 bg-secondary text-white">
      <h5><?= $totalClientes ?></h5>
      Clientes
    </div>
  </div>

  <div class="col-md-2">
    <div class="card p-3 bg-warning text-dark">
      <h5><?= $totalProveedores ?></h5>
      Proveedores
    </div>
  </div>

  <div class="col-md-2">
    <div class="card p-3 bg-dark text-white">
      <h5><?= $movHoy ?></h5>
      Movimientos hoy
    </div>
  </div>

  <div class="row mt-4">

  <div class="col-md-6">
    <div class="card p-3">
      <h6>Movimientos últimos 7 días</h6>
      <canvas id="grafDias"></canvas>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card p-3">
      <h6>Entradas vs Salidas</h6>
      <canvas id="grafIO"></canvas>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card p-3">
      <h6>Top productos</h6>
      <canvas id="grafTop"></canvas>
    </div>
  </div>

</div>


</div>

<hr>

<h5>Últimos movimientos</h5>

<table class="table table-bordered table-sm table-hover">
<tr class="table-dark">
  <th>Fecha</th>
  <th>Producto</th>
  <th>Tipo</th>
  <th>Cantidad</th>
</tr>

<?php foreach ($ultimos as $m): ?>
<tr>
  <td><?= $m['fecha'] ?></td>
  <td><?= htmlspecialchars($m['descripcion']) ?></td>
  <td><?= strtoupper($m['tipo']) ?></td>
  <td><?= $m['cantidad'] ?></td>
</tr>
<?php endforeach; ?>

</table>

</div>

<script>
const diasLabels = <?= json_encode($labelsDias) ?>;
const diasData   = <?= json_encode($valoresDias) ?>;

new Chart(document.getElementById('grafDias'), {
  type: 'line',
  data: {
    labels: diasLabels,
    datasets: [{
      label: 'Movimientos',
      data: diasData,
      tension: 0.3,
      fill: true
    }]
  }
});

const ioLabels = <?= json_encode($labelsIO) ?>;
const ioData   = <?= json_encode($valoresIO) ?>;

new Chart(document.getElementById('grafIO'), {
  type: 'doughnut',
  data: {
    labels: ioLabels,
    datasets: [{
      data: ioData
    }]
  }
});

const topLabels = <?= json_encode($labelsTop) ?>;
const topData   = <?= json_encode($valoresTop) ?>;

new Chart(document.getElementById('grafTop'), {
  type: 'bar',
  data: {
    labels: topLabels,
    datasets: [{
      label: 'Cantidad',
      data: topData
    }]
  },
  options: {
    indexAxis: 'y'
  }
});
</script>

</body>
</html>
