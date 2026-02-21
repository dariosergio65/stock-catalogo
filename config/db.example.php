<?php
$pdo = new PDO(
    "mysql:host=localhost;dbname=stock_db;charset=utf8",
    "usuario",
    "password",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
