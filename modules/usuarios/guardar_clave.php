<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";

$id = $_SESSION['usuario_id'];

$actual  = $_POST['actual'];
$nueva   = $_POST['nueva'];
$repetir = $_POST['repetir'];

if ($nueva !== $repetir) {
    header("Location: cambiar_clave.php?error=1");
    exit;
}

$stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user || !password_verify($actual, $user['password'])) {
    header("Location: cambiar_clave.php?error=1");
    exit;
}

$hash = password_hash($nueva, PASSWORD_DEFAULT);

$pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?")
    ->execute([$hash, $id]);

header("Location: cambiar_clave.php?ok=1");
exit;
