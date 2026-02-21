<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
soloAdmin();

$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$pdo->prepare("
INSERT INTO usuarios (nombre,usuario,password,rol)
VALUES (?,?,?,?)
")->execute([
$_POST['nombre'],
$_POST['usuario'],
$hash,
$_POST['rol']
]);

header("Location: index.php");
?>