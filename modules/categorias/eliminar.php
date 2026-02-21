<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";

$id = $_GET['id'];

$check = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE categoria_id=?");
$check->execute([$id]);

if ($check->fetchColumn() > 0) {
    die("No se puede eliminar: hay productos asociados.");
}

$stmt = $pdo->prepare("DELETE FROM categorias WHERE id=?");
$stmt->execute([$id]);

header("Location: index.php");
