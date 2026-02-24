<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('productos');

$id = $_POST['id'];

$producto = $pdo->prepare("SELECT imagen FROM productos WHERE id=?");
$producto->execute([$id]);
$p = $producto->fetch();

$imagen = $p['imagen'];

if (!empty($_FILES['imagen']['name'])) {

    if ($imagen && file_exists("../../uploads/productos/$imagen")) {
        unlink("../../uploads/productos/$imagen");
    }

    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $imagen = uniqid() . "." . $ext;

    move_uploaded_file(
        $_FILES['imagen']['tmp_name'],
        "../../uploads/productos/" . $imagen
    );
}

$pdo->prepare("
UPDATE productos SET
codigo = ?,
descripcion = ?,
imagen = ?,
categoria_id = ?,
deposito_id = ?,
stock_minimo = ?,
precio_compra = ?,
precio_venta = ?
WHERE id = ?
")->execute([
$_POST['codigo'],
$_POST['descripcion'],
$imagen,
$_POST['categoria_id'] ?: null,
$_POST['deposito_id'] ?: null,
$_POST['stock_minimo'],
$_POST['precio_compra'],
$_POST['precio_venta'],
$id
]);

header("Location: index.php");
exit();
