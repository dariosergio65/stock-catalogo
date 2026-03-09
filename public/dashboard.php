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
    $labelsDias[] = date('d/m', strtotime($d['dia']));
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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    
    <!-- Bootstrap 5 + Íconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* ============================================
           ESTILOS MOBILE-FIRST
           ============================================ */
        
        /* Header con navegación */
        .dashboard-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: white;
            border-radius: 0 0 25px 25px;
            padding: 1.5rem 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .header-content h1 {
            font-size: 1.5rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-menu {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.9rem;
            backdrop-filter: blur(5px);
            transition: all 0.2s;
        }
        
        .btn-menu:hover,
        .btn-menu:active {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        .fecha-actual {
            font-size: 0.9rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Tarjetas de métricas */
        .metricas-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.8rem;
            margin-bottom: 1.5rem;
        }
        
        .metrica-card {
            background: white;
            border-radius: 18px;
            padding: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        
        .metrica-card:active {
            transform: scale(0.98);
        }
        
        .metrica-icono {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.8rem;
            font-size: 1.3rem;
        }
        
        .bg-soft-primary { background: #e7f1ff; color: #0d6efd; }
        .bg-soft-success { background: #d1e7dd; color: #198754; }
        .bg-soft-danger { background: #f8dddd; color: #dc3545; }
        .bg-soft-warning { background: #fff3cd; color: #ffc107; }
        .bg-soft-secondary { background: #e2e3e5; color: #6c757d; }
        .bg-soft-dark { background: #d3d3d4; color: #212529; }
        
        .metrica-valor {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 0.2rem;
        }
        
        .metrica-label {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .metrica-trend {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            background: #f8f9fa;
        }
        
        /* Sección de gráficos */
        .graficos-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .grafico-card {
            background: white;
            border-radius: 20px;
            padding: 1.2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .grafico-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            color: #212529;
        }
        
        .grafico-header i {
            color: #0d6efd;
            font-size: 1.2rem;
        }
        
        .grafico-container {
            position: relative;
            height: 200px;
            width: 100%;
        }
        
        /* Últimos movimientos */
        .movimientos-titulo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #212529;
        }
        
        .movimientos-lista {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .movimiento-item {
            background: white;
            border-radius: 15px;
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            border: 1px solid #dee2e6;
        }
        
        .movimiento-icono {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        
        .movimiento-info {
            flex: 1;
        }
        
        .movimiento-producto {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.2rem;
        }
        
        .movimiento-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .movimiento-meta i {
            margin-right: 0.2rem;
        }
        
        .movimiento-tipo {
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .tipo-entrada { background: #d1e7dd; color: #198754; }
        .tipo-salida { background: #f8dddd; color: #dc3545; }
        
        .movimiento-cantidad {
            font-weight: 700;
            font-size: 1.2rem;
            min-width: 60px;
            text-align: right;
        }
        
        /* Para tablets */
        @media (min-width: 768px) {
            .metricas-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .graficos-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .grafico-container {
                height: 250px;
            }
            
            .movimientos-lista {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        /* Para desktop */
        @media (min-width: 1024px) {
            .metricas-grid {
                grid-template-columns: repeat(6, 1fr);
            }
            
            .movimientos-lista {
                display: none;
            }
            
            .tabla-container {
                display: block;
                overflow-x: auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            }
            
            .table {
                margin-bottom: 0;
            }
            
            .table thead th {
                background-color: #212529;
                color: white;
                padding: 1rem;
            }
            
            .table td {
                padding: 1rem;
                vertical-align: middle;
            }
        }
        
        @media (max-width: 1023px) {
            .tabla-container {
                display: none;
            }
        }
        
        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .metrica-card, .grafico-card, .movimiento-item {
            animation: fadeIn 0.3s ease-out forwards;
        }
        
        /* N progress bar */
        .stock-indicator {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        
        .stock-indicator-fill {
            height: 100%;
            background: #0d6efd;
            border-radius: 2px;
        }
    </style>
</head>
<body class="bg-light">

<!-- HEADER MEJORADO -->
<div class="dashboard-header">
    <div class="header-content">
        <h1>
            <i class="bi bi-grid"></i> Dashboard
        </h1>
        <a href="index.php" class="btn-menu">
            <i class="bi bi-list"></i> Menú
        </a>
    </div>
    <div class="fecha-actual">
        <i class="bi bi-calendar3"></i> <?= date('l, d F Y') ?>
    </div>
</div>

<div class="container-fluid px-3">

    <!-- MÉTRICAS EN GRID RESPONSIVE -->
    <div class="metricas-grid">
        
        <div class="metrica-card">
            <div class="metrica-icono bg-soft-primary">
                <i class="bi bi-box"></i>
            </div>
            <div class="metrica-valor"><?= $totalProductos ?></div>
            <div class="metrica-label">Productos</div>
            <div class="stock-indicator">
                <div class="stock-indicator-fill" style="width: <?= min(100, $totalProductos) ?>%"></div>
            </div>
        </div>
        
        <div class="metrica-card">
            <div class="metrica-icono bg-soft-success">
                <i class="bi bi-boxes"></i>
            </div>
            <div class="metrica-valor"><?= number_format($stockTotal) ?></div>
            <div class="metrica-label">Stock total</div>
        </div>
        
        <div class="metrica-card">
            <div class="metrica-icono bg-soft-danger">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="metrica-valor"><?= $stockBajo ?></div>
            <div class="metrica-label">Stock bajo</div>
            <?php if ($stockBajo > 0): ?>
            <div class="metrica-trend text-danger">
                <i class="bi bi-arrow-up"></i> urgente
            </div>
            <?php endif; ?>
        </div>
        
        <div class="metrica-card">
            <div class="metrica-icono bg-soft-secondary">
                <i class="bi bi-people"></i>
            </div>
            <div class="metrica-valor"><?= $totalClientes ?></div>
            <div class="metrica-label">Clientes</div>
        </div>
        
        <div class="metrica-card">
            <div class="metrica-icono bg-soft-warning">
                <i class="bi bi-truck"></i>
            </div>
            <div class="metrica-valor"><?= $totalProveedores ?></div>
            <div class="metrica-label">Proveedores</div>
        </div>
        
        <div class="metrica-card">
            <div class="metrica-icono bg-soft-dark">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <div class="metrica-valor"><?= $movHoy ?></div>
            <div class="metrica-label">Mov. hoy</div>
        </div>
    </div>

    <!-- GRÁFICOS -->
    <div class="graficos-grid">
        
        <!-- Gráfico de línea (7 días) -->
        <div class="grafico-card">
            <div class="grafico-header">
                <i class="bi bi-graph-up"></i> Movimientos últimos 7 días
            </div>
            <div class="grafico-container">
                <canvas id="grafDias"></canvas>
            </div>
        </div>
        
        <!-- Gráfico de dona (Entradas/Salidas) -->
        <div class="grafico-card">
            <div class="grafico-header">
                <i class="bi bi-pie-chart"></i> Entradas vs Salidas
            </div>
            <div class="grafico-container">
                <canvas id="grafIO"></canvas>
            </div>
        </div>
        
        <!-- Gráfico de barras (Top productos) -->
        <div class="grafico-card" style="grid-column: span 2;">
            <div class="grafico-header">
                <i class="bi bi-bar-chart"></i> Top 5 productos más movidos
            </div>
            <div class="grafico-container" style="height: 250px;">
                <canvas id="grafTop"></canvas>
            </div>
        </div>
        
    </div>

    <!-- ÚLTIMOS MOVIMIENTOS -->
    <div class="movimientos-titulo">
        <i class="bi bi-clock-history"></i> Últimos movimientos
    </div>
    
    <!-- Vista móvil (tarjetas) -->
    <div class="movimientos-lista">
        <?php foreach ($ultimos as $m): ?>
        <div class="movimiento-item">
            <div class="movimiento-icono <?= $m['tipo'] == 'entrada' ? 'bg-soft-success' : 'bg-soft-danger' ?>">
                <i class="bi <?= $m['tipo'] == 'entrada' ? 'bi-box-arrow-in-down' : 'bi-box-arrow-up' ?>"></i>
            </div>
            <div class="movimiento-info">
                <div class="movimiento-producto"><?= htmlspecialchars($m['descripcion']) ?></div>
                <div class="movimiento-meta">
                    <span><i class="bi bi-calendar3"></i> <?= date('d/m H:i', strtotime($m['fecha'])) ?></span>
                    <span class="movimiento-tipo <?= 'tipo-' . $m['tipo'] ?>">
                        <?= strtoupper($m['tipo']) ?>
                    </span>
                </div>
            </div>
            <div class="movimiento-cantidad <?= $m['tipo'] == 'entrada' ? 'text-success' : 'text-danger' ?>">
                <?= $m['cantidad'] ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Vista desktop (tabla) -->
    <div class="tabla-container">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ultimos as $m): ?>
                <tr>
                    <td><i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y H:i', strtotime($m['fecha'])) ?></td>
                    <td><i class="bi bi-box me-1"></i><?= htmlspecialchars($m['descripcion']) ?></td>
                    <td>
                        <span class="badge bg-<?= $m['tipo'] == 'entrada' ? 'success' : 'danger' ?>">
                            <?= strtoupper($m['tipo']) ?>
                        </span>
                    </td>
                    <td class="fw-bold"><?= $m['cantidad'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
// Gráfico de días
const diasLabels = <?= json_encode($labelsDias) ?>;
const diasData   = <?= json_encode($valoresDias) ?>;

new Chart(document.getElementById('grafDias'), {
    type: 'line',
    data: {
        labels: diasLabels,
        datasets: [{
            label: 'Movimientos',
            data: diasData,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.3,
            fill: true,
            pointBackgroundColor: '#0d6efd',
            pointBorderColor: 'white',
            pointBorderWidth: 2,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        }
    }
});

// Gráfico de dona (Entradas/Salidas)
const ioLabels = <?= json_encode($labelsIO) ?>;
const ioData   = <?= json_encode($valoresIO) ?>;

new Chart(document.getElementById('grafIO'), {
    type: 'doughnut',
    data: {
        labels: ioLabels,
        datasets: [{
            data: ioData,
            backgroundColor: ['#198754', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// Gráfico de barras horizontales (Top productos)
const topLabels = <?= json_encode($labelsTop) ?>;
const topData   = <?= json_encode($valoresTop) ?>;

new Chart(document.getElementById('grafTop'), {
    type: 'bar',
    data: {
        labels: topLabels,
        datasets: [{
            label: 'Cantidad',
            data: topData,
            backgroundColor: '#0d6efd'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: {
            legend: { display: false }
        }
    }
});
</script>

</body>
</html>