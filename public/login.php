<?php
session_start();
if (isset($_SESSION['ingresado'])) {
    header("Location: index.php");
    exit;
}
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Ingreso al sistema</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #1d2671, #c33764);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    width: 100%;
    max-width: 380px;
    box-shadow: 0 15px 30px rgba(0,0,0,.2);
}

.login-title {
    font-weight: 600;
    text-align: center;
    margin-bottom: 20px;
}

.login-logo {
    font-size: 50px;
    text-align: center;
    margin-bottom: 10px;
}
</style>
</head>

<body>

<div class="login-card">

  <div class="login-logo">📦</div>
  <div class="login-title">Sistema de Stock</div>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger text-center py-2">
      Usuario o contraseña incorrectos
    </div>
  <?php endif; ?>

  <form method="post" action="validar_login.php">

    <div class="mb-3">
      <label class="form-label">Usuario</label>
      <input type="text" name="usuario" class="form-control" required autofocus>
    </div>

    <div class="mb-3">
      <label class="form-label">Contraseña</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <button class="btn btn-primary w-100 py-2">
      🔐 Ingresar
    </button>

  </form>

  <div class="text-center mt-3 text-muted small">
    © <?= date('Y') ?> — Sistema profesional de stock
  </div>

</div>

</body>
</html>
