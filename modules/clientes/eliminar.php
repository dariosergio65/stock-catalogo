<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";

$pdo->prepare("DELETE FROM clientes WHERE id=?")
    ->execute([$_GET['id']]);

header("Location: index.php");
