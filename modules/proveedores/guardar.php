<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";

$pdo->prepare("
INSERT INTO proveedores (nombre,telefono,email)
VALUES (?,?,?)
")->execute([
$_POST['nombre'],
$_POST['telefono'],
$_POST['email']
]);

header("Location: index.php");
