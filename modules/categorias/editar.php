<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";

verificarPermiso('categorias');
require_once "../../config/db.php";

$stmt = $pdo->prepare("SELECT * FROM categorias WHERE id=?");
$stmt->execute([ $_GET['id'] ]);
$categoria = $stmt->fetch();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Editar Categoría</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h3>Editar Categoría</h3>

<form method="post" action="actualizar.php">
  <input type="hidden" name="id" value="<?= $categoria['id'] ?>">
  <input name="nombre" class="form-control mb-3"
         value="<?= htmlspecialchars($categoria['nombre']) ?>" required>

  <button class="btn btn-primary">Actualizar</button>
  <a href="index.php" class="btn btn-secondary">Volver</a>
</form>

</body>
</html>
