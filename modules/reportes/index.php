<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('reportes');
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Reportes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-4">
  <h3>📊 Reportes del Sistema</h3>
  <a href="../../public/index.php" class="btn btn-secondary">⬅ Menú</a>
</div>

<div class="row">

  <div class="col-md-6">
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <h5 class="card-title">📦 Reporte General de Stock</h5>
        <p class="card-text">
          Lista completa de productos con:
          <ul>
            <li>Código</li>
            <li>Descripción</li>
            <li>Categoría</li>
            <li>Stock actual</li>
            <li>Precio de compra</li>
            <li>Precio de venta</li>
          </ul>
        </p>

        <div class="d-flex gap-2">
          <a href="exportar_excel.php" class="btn btn-success">
            📗 Descargar Excel
          </a>

          <a href="exportar_pdf.php" class="btn btn-danger">
            📕 Descargar PDF
          </a>
        </div>
      </div>
    </div>
  </div>

</div>

<hr>

<div class="alert alert-info">
  Próximamente:
  <ul class="mb-0">
    <li>📈 Reportes de ventas mensuales</li>
    <li>🔁 Historial completo de movimientos</li>
    <li>📦 Ranking de productos más vendidos</li>
    <li>💰 Rentabilidad por producto</li>
  </ul>
</div>

</body>
</html>
