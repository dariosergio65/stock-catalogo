<?php
session_start();

if (empty($_SESSION['carrito'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Finalizar pedido</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">

<h3 class="mb-3">🛒 Finalizar pedido</h3>

<form action="confirmar.php" method="post" class="card card-body shadow-sm">

    <div class="mb-2">
        <label>Nombre *</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="mb-2">
        <label>Teléfono</label>
        <input type="text" name="telefono" class="form-control">
    </div>

    <div class="mb-2">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>

    <div class="mb-3">
        <label>Dirección</label>
        <textarea name="direccion" class="form-control"></textarea>
    </div>

    <button class="btn btn-success w-100">
        Confirmar pedido
    </button>

</form>

</div>

</body>
</html>