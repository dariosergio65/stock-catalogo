<?php 
require_once "../../config/auth.php";
require_once "../../config/permisos.php";

verificarPermiso('usuarios');
require_once "../../config/auth.php"; soloAdmin(); ?>
<head>
<meta charset="utf-8">
<title>Productos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<form method="post" action="guardar.php" class="container mt-4">

<input name="nombre" class="form-control mb-2" placeholder="Nombre">
<input name="usuario" class="form-control mb-2" placeholder="Usuario">
<input name="password" type="password" class="form-control mb-2" placeholder="Clave">

<select name="rol" class="form-control mb-2">
  <option value="operador">Operador</option>
  <option value="consulta">Consulta</option>
  <option value="empleado">Empleado</option>
  <option value="admin">Admin</option>
</select>

<button class="btn btn-success">Guardar</button>
</form>
