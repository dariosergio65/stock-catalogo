<?php 
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('proveedores');
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


<form method="post" action="guardar.php" class="container mt-4">

<h3>Nuevo Proveedor</h3>

<input name="nombre" class="form-control mb-2" placeholder="Nombre" required>
<input name="telefono" class="form-control mb-2" placeholder="Teléfono">
<input name="email" type="email" class="form-control mb-2" placeholder="Email">

<button class="btn btn-success">Guardar</button>
<a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

</body>
</html>
