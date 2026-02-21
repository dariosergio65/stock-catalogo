<?php
session_start();
require_once "../config/db.php";

$usuario  = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';

if ($usuario == '' || $password == '') {
    header("Location: login.php?error=1");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
$stmt->execute([$usuario]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['ingresado']  = true;
    $_SESSION['usuario_id'] = $user['id'];
    $_SESSION['nombre']     = $user['nombre'];
    $_SESSION['rol']        = $user['rol'];

    header("Location: index.php");
    exit;
}

header("Location: login.php?error=1");
exit;
