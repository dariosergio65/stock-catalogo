<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title><?= $title ?? 'Sistema de Stock' ?></title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS propio -->
<link rel="stylesheet" href="/stock/assets/css/styles.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <span class="navbar-brand">Stock</span>

    <div class="d-flex gap-2">
      <a href="/stock/public/index.php" class="btn btn-sm btn-outline-light">Menú</a>
      <a href="/stock/public/logout.php" class="btn btn-sm btn-outline-danger">Salir</a>
    </div>
  </div>
</nav>

<div class="container">

