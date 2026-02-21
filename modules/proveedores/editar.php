<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('proveedores');

require_once "../../config/db.php";

$stmt = $pdo->prepare("SELECT * FROM proveedores WHERE id=?");
$stmt->execute([$_GET['id']]);
$c = $stmt->fetch();
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Título</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS propio -->
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body>


<form method="post" action="actualizar.php" class="container mt-4">
<h3>Editar proveedor</h3>

<input type="hidden" name="id" value="<?= $c['id'] ?>">

<input name="nombre" value="<?= $c['nombre'] ?>" class="form-control mb-2">
<input name="telefono" value="<?= $c['telefono'] ?>" class="form-control mb-2">
<input name="email" value="<?= $c['email'] ?>" class="form-control mb-2">

<button class="btn btn-success">Actualizar</button>
<a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>
</body>
</html>

