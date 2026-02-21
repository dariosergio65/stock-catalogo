<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";

$stmt = $pdo->prepare("INSERT INTO categorias (nombre) VALUES (?)");
$stmt->execute([ $_POST['nombre'] ]);

header("Location: index.php");
