<?php
require_once "../../config/db.php";

$stmt = $pdo->query("SELECT * FROM pedidos ORDER BY id DESC");
$pedidos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel - Pedidos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    
    <!-- Bootstrap 5 + Íconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* ============================================
           ESTILOS MOBILE-FIRST 
           ============================================ */
        
        /* Para pantallas muy pequeñas (menos de 480px) */
        .card-pedido {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }
        
        .card-pedido:active {
            background-color: #f8f9fa;
            transform: scale(0.99);
        }
        
        .pedido-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px dashed #dee2e6;
        }
        
        .pedido-id {
            font-weight: 700;
            color: #0d6efd;
            background: #e7f1ff;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        .pedido-cliente {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .pedido-detalle {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        
        .pedido-detalle-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #495057;
        }
        
        .pedido-detalle-item i {
            color: #6c757d;
            width: 1.2rem;
            font-size: 1.1rem;
        }
        
        .pedido-total {
            font-weight: 700;
            color: #198754;
            font-size: 1.3rem;
        }
        
        .badge-custom {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 30px;
            display: inline-block;
        }
        
        .btn-ver-mobile {
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.75rem;
            border-radius: 10px;
            font-weight: 600;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #0d6efd;
            transition: all 0.2s;
        }
        
        .btn-ver-mobile:hover,
        .btn-ver-mobile:active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        
        /* Para tablets (pantallas medianas) */
        @media (min-width: 768px) {
            .pedidos-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .card-pedido {
                margin-bottom: 0;
            }
            
            .btn-ver-mobile {
                width: auto;
                margin-top: 0;
            }
        }
        
        /* Para desktop (pantallas grandes) - VOLVEMOS A LA TABLA */
        @media (min-width: 1024px) {
            .pedidos-grid {
                display: none;  /* Ocultamos las tarjetas */
            }
            
            .tabla-container {
                display: block;  /* Mostramos la tabla */
                overflow-x: auto;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            }
            
            .table {
                margin-bottom: 0;
                background: white;
            }
            
            .table thead th {
                background-color: #212529;
                color: white;
                font-weight: 600;
                padding: 1rem;
                white-space: nowrap;
            }
            
            .table td {
                padding: 1rem;
                vertical-align: middle;
            }
            
            .table tbody tr:hover {
                background-color: #f8f9fa;
            }
            
            .btn-sm {
                padding: 0.4rem 1rem;
            }
        }
        
        /* Ocultamos la tabla en móvil/tablet */
        @media (max-width: 1023px) {
            .tabla-container {
                display: none;
            }
        }
        
        /* Mejoras generales de touch */
        .btn, 
        .badge-custom,
        .card-pedido,
        [role="button"] {
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Espaciado general */
        .container {
            padding: 1rem;
        }
        
        h3 i {
            color: #0d6efd;
            margin-right: 0.5rem;
        }
    </style>
</head>

<body class="bg-light">

<div class="container py-3">
    
    <!-- Header con ícono -->
     <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-4 d-flex align-items-center">
        <i class="bi bi-box-seam fs-1 me-2"></i>
        <span>Pedidos</span>
        <span class="badge bg-secondary ms-2"><?= count($pedidos) ?></span>
        </h3>
        <a href="../../public/index.php" class="btn btn-secondary">⬅ Volver</a>
    </div>
    
    <!-- ============================================ -->
    <!-- VISTA MÓVIL / TABLET (Tarjetas) -->
    <!-- ============================================ -->
    <div class="pedidos-grid">
        <?php foreach ($pedidos as $p): ?>
        <div class="card-pedido" onclick="window.location.href='pedido_ver.php?id=<?= $p['id'] ?>'">
            
            <div class="pedido-header">
                <span class="pedido-id">#<?= $p['id'] ?></span>
                <span class="badge-custom bg-<?php 
                    echo $p['estado']=='pendiente'?'warning text-dark':
                         ($p['estado']=='pagado'?'success':'primary');
                ?>">
                    <?php 
                    $estados = [
                        'pendiente' => '⏳ Pendiente',
                        'pagado' => '✅ Pagado',
                        'enviado' => '📦 Enviado',
                        'entregado' => '🎉 Entregado'
                    ];
                    echo $estados[$p['estado']] ?? strtoupper($p['estado']);
                    ?>
                </span>
            </div>
            
            <div class="pedido-cliente">
                <i class="bi bi-person-circle me-1"></i>
                <?= htmlspecialchars($p['nombre']) ?>
            </div>
            
            <div class="pedido-detalle">
                <div class="pedido-detalle-item">
                    <i class="bi bi-cash-stack"></i>
                    <span class="pedido-total">$<?= number_format($p['total'], 2, ',', '.') ?></span>
                </div>
                
                <div class="pedido-detalle-item">
                    <i class="bi bi-calendar3"></i>
                    <?= date('d/m/Y H:i', strtotime($p['fecha'])) ?>
                </div>
            </div>
            
            <!-- Botón Ver (para mejor UX) -->
            <div class="btn-ver-mobile text-center">
                Ver detalles <i class="bi bi-arrow-right-short"></i>
            </div>
            
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($pedidos)): ?>
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <p class="text-muted mt-2">No hay pedidos para mostrar</p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- ============================================ -->
    <!-- VISTA DESKTOP (Tabla original mejorada) -->
    <!-- ============================================ -->
    <div class="tabla-container">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $p): ?>
                <tr>
                    <td class="fw-bold"><?= $p['id'] ?></td>
                    <td>
                        <i class="bi bi-person me-1"></i>
                        <?= htmlspecialchars($p['nombre']) ?>
                    </td>
                    <td class="fw-bold text-success">$<?= number_format($p['total'], 2, ',', '.') ?></td>
                    <td>
                        <span class="badge bg-<?php 
                            echo $p['estado']=='pendiente'?'warning text-dark':
                                 ($p['estado']=='pagado'?'success':'primary');
                        ?> p-2">
                            <?= strtoupper($p['estado']) ?>
                        </span>
                    </td>
                    <td><i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y H:i', strtotime($p['fecha'])) ?></td>
                    <td>
                        <a href="pedido_ver.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Botón flotante para nuevo pedido (opcional) -->
    <a href="pedido_nuevo.php" class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4 shadow-lg" 
       style="width: 60px; height: 60px; font-size: 2rem; display: flex; align-items: center; justify-content: center;">
        <i class="bi bi-plus"></i>
    </a>
    
</div>

<!-- Script para mejorar experiencia táctil (opcional) -->
<script>
    // Prevenir el doble tap zoom en botones
    document.querySelectorAll('.card-pedido, .btn').forEach(el => {
        el.addEventListener('touchstart', () => {}, {passive: true});
    });
    
    // Mostrar cantidad de pedidos en el título
    console.log('<?= count($pedidos) ?> pedidos cargados');
</script>

</body>
</html>