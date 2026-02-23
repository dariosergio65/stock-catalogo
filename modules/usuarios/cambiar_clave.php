<?php
require_once "../../config/auth.php";
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Cambiar contraseña</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body>

<div class="container mt-5" style="max-width:500px">

  <div class="card shadow">
    <div class="card-header bg-dark text-white">
      🔐 Cambiar contraseña
    </div>

    <div class="card-body">

      <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success">Contraseña actualizada correctamente</div>
      <?php endif; ?>

      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">La contraseña actual no es correcta</div>
      <?php endif; ?>

      <form method="post" action="guardar_clave.php">

        <input type="password" name="actual" class="form-control mb-2" placeholder="Contraseña actual" required>

        <input type="password" name="nueva" class="form-control mb-2" placeholder="Nueva contraseña" required>

        <input type="password" name="repetir" class="form-control mb-3" placeholder="Repetir nueva contraseña" required>

        <button class="btn btn-primary w-100">Actualizar contraseña</button>

      </form>

    </div>
  </div>

  <div class="text-center mt-3">
    <a href="../../public/index.php">⬅ Volver al menú</a>
  </div>

</div>

</body>
</html>
