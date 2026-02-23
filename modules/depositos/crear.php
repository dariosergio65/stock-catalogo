<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";

verificarPermiso('depositos');
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Nuevo Depósito</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">
  <h3>➕ Nuevo Depósito</h3>

  <form method="post" action="guardar.php">
    <input name="nombre" class="form-control mb-2" placeholder="Nombre" required>
    <textarea name="descripcion" class="form-control mb-2" placeholder="Descripción"></textarea>

    <button class="btn btn-success">Guardar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>

</body>
</html>
