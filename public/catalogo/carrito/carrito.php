<?php
session_start();

$carrito = $_SESSION['carrito'] ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }
        .producto-card {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
        }
        .img-prod {
            max-height: 90px;
            object-fit: contain;
        }
    </style>
</head>

<body>

<div class="container py-3">

    <h3 class="mb-3 text-center">🛒 Mi carrito</h3>

    <?php if (empty($carrito)): ?>

        <div class="alert alert-info text-center">
            Tu carrito está vacío.
        </div>

        <div class="d-grid gap-2">
            <a href="../index.php" class="btn btn-primary btn-lg">
                ⬅ Volver al catálogo
            </a>
        </div>

    <?php else: ?>

        <?php $total = 0; ?>

        <div class="row g-3">

            <?php foreach ($carrito as $id => $p): 
                $subtotal = $p['precio'] * $p['cantidad'];
                $total += $subtotal;
            ?>

                <div class="col-12">
                    <div class="card producto-card p-2">
                        <div class="row g-2 align-items-center">

                            <div class="col-3 text-center">
                                <?php if (!empty($p['imagen'])): ?>
                                    <img src="../../../uploads/productos/<?= $p['imagen'] ?>" class="img-fluid rounded"  style="max-height:70px;">
                                <?php endif; ?>
                            </div>

                            <div class="col-9">
                                <strong><?= htmlspecialchars($p['descripcion']) ?></strong><br>

                                <small>
                                    Cantidad: <?= $p['cantidad'] ?> <br>
                                    Precio: $<?= number_format($p['precio'], 2) ?>
                                </small>

                                <div class="mt-1 d-flex justify-content-between align-items-center">
                                    <strong>$<?= number_format($subtotal, 2) ?></strong>

                                    <a href="agregar.php?eliminar=<?= $id ?>" 
                                       class="btn btn-sm btn-danger">
                                        ✖
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        <hr>

        <div class="d-flex justify-content-between align-items-center">
            <h4>Total:</h4>
            <h4>$<?= number_format($total, 2) ?></h4>
        </div>

        <div class="d-grid gap-2 mt-3">
            <a href="../index.php" class="btn btn-outline-primary btn-lg">
                ⬅ Seguir comprando
            </a>

            <a href="../finalizar.php" class="btn btn-success w-100 mt-3">
                ✅ Finalizar pedido
            </a>

            <a href="vaciar.php" class="btn btn-outline-danger">
                Vaciar carrito
            </a>
        </div>

    <?php endif; ?>

</div>

</body>
</html>