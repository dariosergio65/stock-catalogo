<?php 
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('categorias');
 ?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Nueva Categoría</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h3>Nueva Categoría</h3>

<form method="post" action="guardar.php">
  <input name="nombre" class="form-control mb-3" placeholder="Nombre categoría" required>
  <button class="btn btn-success">Guardar</button>
  <a href="index.php" class="btn btn-secondary">Volver</a>
</form>

</body>
</html>
