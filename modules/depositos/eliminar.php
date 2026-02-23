<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";
require_once "../../config/auditoria.php";

verificarPermiso('depositos');

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT nombre FROM depositos WHERE id=?");
$stmt->execute([$id]);
$dep = $stmt->fetch();

if (!$dep) die("Depósito inexistente");

$stmt = $pdo->prepare("DELETE FROM depositos WHERE id=?");
$stmt->execute([$id]);

registrarAuditoria('Eliminación depósito: '.$dep['nombre'], 'depositos', "Eliminó depósito ID: $id");

header("Location: index.php");
exit;
