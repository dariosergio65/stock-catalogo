<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";
require_once "../../config/auditoria.php";

verificarPermiso('depositos');

$stmt = $pdo->prepare("INSERT INTO depositos (nombre, descripcion) VALUES (?, ?)");
$stmt->execute([
    $_POST['nombre'],
    $_POST['descripcion']
]);

registrarAuditoria('Alta depósito', 'depositos', 'Creó depósito id: ' . $pdo->lastInsertId());

header("Location: index.php");
exit;
