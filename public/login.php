<?php
require_once "../config/db.php";
session_start();

if ($_POST) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario=? AND activo=1");
    $stmt->execute([$_POST['usuario']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];
        header("Location: index.php");
        exit;
    }
    $error = "Datos incorrectos";
}
?>
<form method="post">
    <input name="usuario" placeholder="Usuario">
    <input name="password" type="password" placeholder="Clave">
    <button>Ingresar</button>
    <?= isset($error) ? $error : '' ?>
</form>
