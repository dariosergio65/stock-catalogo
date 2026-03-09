<?php
require_once "../../config/db.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id=?");
$stmt->execute([$id]);
$pedido = $stmt->fetch();

if (!$pedido) die("Pedido no encontrado");

$stmt = $pdo->prepare("SELECT * FROM pedido_detalle WHERE pedido_id=?");
$stmt->execute([$id]);
$items = $stmt->fetchAll();

// Función para formatear estado con ícono y color
function formatoEstado($estado) {
    $estados = [
        'pendiente' => ['⏳ Pendiente', 'warning', 'text-dark'],
        'pagado' => ['✅ Pagado', 'success', 'text-white'],
        'enviado' => ['📦 Enviado', 'primary', 'text-white'],
        'entregado' => ['🎉 Entregado', 'info', 'text-dark'],
        'cancelado' => ['❌ Cancelado', 'danger', 'text-white']
    ];
    return $estados[$estado] ?? ['❓ ' . strtoupper($estado), 'secondary', 'text-white'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido #<?= $id ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    
    <!-- Bootstrap 5 + Íconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* ============================================
           ESTILOS MOBILE-FIRST
           ============================================ */
        
        /* Header del pedido */
        .pedido-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2);
        }
        
        .pedido-titulo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .pedido-titulo h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .badge-estado {
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        .pedido-meta {
            display: flex;
            gap: 1.5rem;
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* Tarjeta de cliente */
        .cliente-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .cliente-titulo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #0d6efd;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px dashed #dee2e6;
        }
        
        .cliente-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .cliente-item {
            display: flex;
            flex-direction: column;
        }
        
        .cliente-label {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.2rem;
        }
        
        .cliente-valor {
            font-size: 1rem;
            font-weight: 500;
            word-break: break-word;
        }
        
        .cliente-valor i {
            color: #0d6efd;
            width: 1.2rem;
        }
        
        /* Tarjeta de total */
        .total-card {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            color: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 20px rgba(25, 135, 84, 0.2);
        }
        
        .total-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }
        
        .total-monto {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
        }
        
        /* Productos en formato tarjetas */
        .productos-titulo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #212529;
        }
        
        .producto-card {
            background: white;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 0.8rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            border: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        
        .producto-icono {
            width: 50px;
            height: 50px;
            background: #e7f1ff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: #0d6efd;
            font-size: 1.5rem;
        }
        
        .producto-info {
            flex: 1;
        }
        
        .producto-nombre {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.2rem;
        }
        
        .producto-detalles {
            display: flex;
            gap: 1rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .producto-precio {
            font-weight: 700;
            color: #198754;
        }
        
        .producto-subtotal {
            text-align: right;
            font-weight: 700;
            font-size: 1.1rem;
            color: #0d6efd;
            min-width: 100px;
        }
        
        /* Acciones */
        .acciones {
            margin-top: 2rem;
        }
        
        .btn-accion {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            padding: 1rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.8rem;
            transition: all 0.2s;
        }
        
        .btn-accion i {
            font-size: 1.3rem;
        }
        
        .btn-accion:active {
            transform: scale(0.98);
        }
        
        .btn-volver {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            color: #495057;
        }
        
        .btn-volver:active {
            background: #e9ecef;
        }
        
        /* Para tablets (pantallas medianas) */
        @media (min-width: 768px) {
            .cliente-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .producto-card {
                padding: 1.2rem;
            }
            
            .acciones {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .btn-volver {
                grid-column: span 2;
            }
        }
        
        /* Para desktop (pantallas grandes) - VOLVEMOS A LA TABLA */
        @media (min-width: 1024px) {
            .productos-lista {
                display: none;
            }
            
            .tabla-container {
                display: block;
                overflow-x: auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
                margin-bottom: 1.5rem;
            }
            
            .table {
                margin-bottom: 0;
            }
            
            .table thead th {
                background-color: #212529;
                color: white;
                padding: 1rem;
                white-space: nowrap;
            }
            
            .table td {
                padding: 1rem;
                vertical-align: middle;
            }
            
            .acciones {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
            }
            
            .btn-accion {
                flex: 1;
                margin-bottom: 0;
            }
            
            .btn-volver {
                flex: 0.5;
            }
        }
        
        /* Ocultar tabla en móvil/tablet */
        @media (max-width: 1023px) {
            .tabla-container {
                display: none;
            }
        }
        
        /* Espaciado general */
        .container {
            padding: 1rem;
        }
    </style>
</head>

<body class="bg-light">

<div class="container py-3">

    <!-- HEADER con ID y estado -->
    <div class="pedido-header">
        <div class="pedido-titulo">
            <h2>
                <i class="bi bi-receipt"></i> #<?= $id ?>
            </h2>
            <?php 
                list($estadoTexto, $estadoColor, $textColor) = formatoEstado($pedido['estado']);
            ?>
            <span class="badge-estado bg-<?= $estadoColor ?> <?= $textColor ?>">
                <?= $estadoTexto ?>
            </span>
        </div>
        <div class="pedido-meta">
            <span><i class="bi bi-calendar3"></i> <?= date('d/m/Y', strtotime($pedido['fecha'])) ?></span>
            <span><i class="bi bi-clock"></i> <?= date('H:i', strtotime($pedido['fecha'])) ?></span>
        </div>
    </div>

    <!-- TARJETA DEL CLIENTE -->
    <div class="cliente-card">
        <div class="cliente-titulo">
            <i class="bi bi-person-circle"></i> Datos del cliente
        </div>
        <div class="cliente-grid">
            <div class="cliente-item">
                <span class="cliente-label">Nombre</span>
                <span class="cliente-valor"><i class="bi bi-person"></i> <?= htmlspecialchars($pedido['nombre']) ?></span>
            </div>
            <div class="cliente-item">
                <span class="cliente-label">Teléfono</span>
                <span class="cliente-valor"><i class="bi bi-telephone"></i> <?= htmlspecialchars($pedido['telefono']) ?></span>
            </div>
            <div class="cliente-item">
                <span class="cliente-label">Email</span>
                <span class="cliente-valor"><i class="bi bi-envelope"></i> <?= htmlspecialchars($pedido['email']) ?></span>
            </div>
            <div class="cliente-item" style="grid-column: span 2;">
                <span class="cliente-label">Dirección</span>
                <span class="cliente-valor"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($pedido['direccion']) ?></span>
            </div>
        </div>
    </div>

    <!-- TARJETA DEL TOTAL -->
    <div class="total-card">
        <div class="total-header">
            <i class="bi bi-cash-stack"></i> Total del pedido
        </div>
        <div class="total-monto">
            $<?= number_format($pedido['total'], 2, ',', '.') ?>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- VISTA MÓVIL / TABLET (Tarjetas de productos) -->
    <!-- ============================================ -->
    <div class="productos-titulo">
        <i class="bi bi-box-seam"></i> Productos (<?= count($items) ?>)
    </div>
    
    <div class="productos-lista">
        <?php foreach($items as $i): ?>
        <div class="producto-card">
            <div class="producto-icono">
                <i class="bi bi-box"></i>
            </div>
            <div class="producto-info">
                <div class="producto-nombre"><?= htmlspecialchars($i['descripcion']) ?></div>
                <div class="producto-detalles">
                    <span><i class="bi bi-tag"></i> $<?= number_format($i['precio'],2,',','.') ?></span>
                    <span><i class="bi bi-x"></i> <?= $i['cantidad'] ?></span>
                </div>
            </div>
            <div class="producto-subtotal">
                $<?= number_format($i['subtotal'],2,',','.') ?>
            </div>
        </div>
        <?php endforeach ?>
    </div>

    <!-- ============================================ -->
    <!-- VISTA DESKTOP (Tabla de productos) -->
    <!-- ============================================ -->
    <div class="tabla-container">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $i): ?>
                <tr>
                    <td><i class="bi bi-box me-2"></i><?= htmlspecialchars($i['descripcion']) ?></td>
                    <td class="text-nowrap">$<?= number_format($i['precio'],2,',','.') ?></td>
                    <td class="text-center"><?= $i['cantidad'] ?></td>
                    <td class="fw-bold text-primary">$<?= number_format($i['subtotal'],2,',','.') ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- ============================================ -->
    <!-- ACCIONES SEGÚN ESTADO -->
    <!-- ============================================ -->
    <div class="acciones">
        
        <?php if($pedido['estado'] == 'pendiente'): ?>
            <a href="cambiar_estado.php?id=<?= $id ?>&estado=pagado" 
               class="btn-accion btn btn-success">
                <i class="bi bi-check-circle"></i> Marcar Pagado
            </a>
            
            <form action="cancelar_pedido.php" method="post" class="m-0">
                <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                <button class="btn-accion btn btn-danger w-100">
                    <i class="bi bi-x-circle"></i> Cancelar pedido
                </button>
            </form>
        <?php endif; ?>

        <?php if($pedido['estado'] == 'pagado'): ?>
            <a href="cambiar_estado.php?id=<?= $id ?>&estado=enviado" 
               class="btn-accion btn btn-primary">
                <i class="bi bi-truck"></i> Marcar Enviado
            </a>
        <?php endif; ?>
        
        <?php if($pedido['estado'] == 'enviado'): ?>
            <a href="cambiar_estado.php?id=<?= $id ?>&estado=entregado" 
               class="btn-accion btn btn-info text-white">
                <i class="bi bi-check2-all"></i> Marcar Entregado
            </a>
        <?php endif; ?>
        
    </div>
    
    <!-- Botón volver -->
    <a href="pedidos.php" class="btn-accion btn-volver w-100">
        <i class="bi bi-arrow-left"></i> Volver a pedidos
    </a>

</div>

</body>
</html>