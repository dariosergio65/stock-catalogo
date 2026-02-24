<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";
require_once "../../config/auditoria.php";

verificarPermiso('productos');

$codigo         = $_POST['codigo'];
$descripcion    = $_POST['descripcion'];
$deposito       = $_POST['deposito_id'];
$stock_minimo   = $_POST['stock_minimo'];
$precio_compra  = $_POST['precio_compra'];
$precio_venta   = $_POST['precio_venta'];
$categoria_id   = $_POST['categoria_id'] ?? null;

$imagen = null;

if (!empty($_FILES['imagen']['name'])) {

    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);

    $imagen = uniqid() . "." . $ext;

    move_uploaded_file(
        $_FILES['imagen']['tmp_name'],
        "../../uploads/productos/" . $imagen
    );
}

$pdo->prepare("
    INSERT INTO productos 
    (codigo, descripcion, imagen, deposito_id, stock_minimo, precio_compra, precio_venta, categoria_id)
    VALUES (?,?,?,?,?,?,?,?)
")->execute([
    $codigo,
    $descripcion,
    $imagen,
    $deposito,
    $stock_minimo,
    $precio_compra,
    $precio_venta,
    $categoria_id
]);

// ✅ REGISTRO DE AUDITORÍA
registrarAuditoria(
    'productos',
    'crear',
    "Alta de producto: $descripcion (Código: $codigo)"
);

header("Location: index.php");
exit;
?>
