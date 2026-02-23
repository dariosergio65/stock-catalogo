<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('depositos');

$stmt = $pdo->prepare("SELECT * FROM depositos WHERE id = ?");
$stmt->execute([$_GET['id']]);
$d = $stmt->fetch();

if (!$d) die("Depósito inexistente");
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Editar Depósito</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">
  <h3>✏ Editar Depósito</h3>

  <form method="post" action="actualizar.php">
    <input type="hidden" name="id" value="<?= $d['id'] ?>">

    <input name="nombre" class="form-control mb-2" value="<?= htmlspecialchars($d['nombre']) ?>" required>
    <textarea name="descripcion" class="form-control mb-2"><?= htmlspecialchars($d['descripcion']) ?></textarea>

    <select name="activo" class="form-control mb-2">
      <option value="1" <?= $d['activo'] ? 'selected' : '' ?>>Activo</option>
      <option value="0" <?= !$d['activo'] ? 'selected' : '' ?>>Inactivo</option>
    </select>

    <button class="btn btn-primary">Actualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>

</body>
</html>
