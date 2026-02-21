<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";

$stmt = $pdo->prepare("UPDATE categorias SET nombre=? WHERE id=?");
$stmt->execute([ $_POST['nombre'], $_POST['id'] ]);

header("Location: index.php");
