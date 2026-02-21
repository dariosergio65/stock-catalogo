<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";

$pdo->prepare("
UPDATE proveedores
SET nombre=?, telefono=?, email=?
WHERE id=?
")->execute([
$_POST['nombre'],
$_POST['telefono'],
$_POST['email'],
$_POST['id']
]);

header("Location: index.php");
?>