<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
soloAdmin();

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id=?");
$stmt->execute([$_GET['id']]);
$u = $stmt->fetch();
?>
<head>
<meta charset="utf-8">
<title>Productos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<form method="post" action="actualizar.php" class="container mt-4">
<input type="hidden" name="id" value="<?= $u['id'] ?>">

<input name="nombre" value="<?= $u['nombre'] ?>" class="form-control mb-2">
<input name="usuario" value="<?= $u['usuario'] ?>" class="form-control mb-2">

<select name="rol" class="form-control mb-2">
  <option value="operador" <?= $u['rol']=='operador'?'selected':'' ?>>Operador</option>
  <option value="consulta" <?= $u['rol']=='consulta'?'selected':'' ?>>Consulta</option>
  <option value="empleado" <?= $u['rol']=='empleado'?'selected':'' ?>>Empleado</option>
  <option value="admin" <?= $u['rol']=='admin'?'selected':'' ?>>Admin</option>
</select>

<select name="activo" class="form-control mb-2">
  <option value="1">Activo</option>
  <option value="0">Inactivo</option>
</select>

<button class="btn btn-success">Actualizar</button>
</form>
