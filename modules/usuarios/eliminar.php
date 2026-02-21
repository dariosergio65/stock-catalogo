<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";
require_once "../../config/auditoria.php";

verificarPermiso('usuarios');

//soloAdmin();

$id = $_GET['id'];

$pdo->prepare("DELETE FROM usuarios WHERE id=?")
    ->execute([$_GET['id']]);

registrarAuditoria(
    'eliminar',
    'usuarios',
    "Eliminó usuario ID: $id"
);


header("Location: index.php");
?>
<head>
<meta charset="utf-8">
<title>Productos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>