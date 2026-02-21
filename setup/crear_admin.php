<?php
require_once "../config/db.php";

$clave = password_hash("1234", PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO usuarios (nombre, usuario, password, rol)
    VALUES (?, ?, ?, ?)
");

$stmt->execute([
    "Administrador",
    "admin",
    $clave,
    "admin"
]);

echo "Usuario administrador creado";
?>