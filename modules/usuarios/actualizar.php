<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
soloAdmin();

$pdo->prepare("
UPDATE usuarios 
SET nombre=?, usuario=?, rol=?, activo=?
WHERE id=?
")->execute([
$_POST['nombre'],
$_POST['usuario'],
$_POST['rol'],
$_POST['activo'],
$_POST['id']
]);

header("Location: index.php");
?>
<head>
<meta charset="utf-8">
<title>Productos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>