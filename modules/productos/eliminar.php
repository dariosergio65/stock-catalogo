<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";

$id = $_GET['id'];

$stmt = $pdo->prepare("UPDATE productos SET activo=0 WHERE id=?");
$stmt->execute([$id]);

//$pdo->prepare("DELETE FROM productos WHERE id=?")
  //  ->execute([$_GET['id']]);

header("Location: index.php");
?>
<head>
<meta charset="utf-8">
<title>Productos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>