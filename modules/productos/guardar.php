<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";
require_once "../../config/auditoria.php";

verificarPermiso('productos');

$codigo         = $_POST['codigo'];
$descripcion    = $_POST['descripcion'];
$stock_minimo   = $_POST['stock_minimo'];
$precio_compra  = $_POST['precio_compra'];
$precio_venta   = $_POST['precio_venta'];
$categoria_id   = $_POST['categoria_id'] ?? null;

$pdo->prepare("
    INSERT INTO productos 
    (codigo, descripcion, stock_minimo, precio_compra, precio_venta, categoria_id)
    VALUES (?,?,?,?,?,?)
")->execute([
    $codigo,
    $descripcion,
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
