<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";
require_once "../../config/auditoria.php";

verificarPermiso('productos');

$id = $_POST['id'];

$codigo         = $_POST['codigo'];
$descripcion    = $_POST['descripcion'];
$categoria_id   = $_POST['categoria_id'] ?? null;
$deposito_id    = $_POST['deposito_id'];
$stock_actual   = $_POST['stock_actual'];
$stock_minimo   = $_POST['stock_minimo'];
$precio_compra  = $_POST['precio_compra'];
$precio_venta   = $_POST['precio_venta'];


/* =========================
   OBTENER PRODUCTO ACTUAL
========================= */

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id=?");
$stmt->execute([$id]);
$productoActual = $stmt->fetch();

$imagen = $productoActual['imagen'];


/* =========================
   SUBIR NUEVA IMAGEN
========================= */

if (!empty($_FILES['imagen']['name'])) {

    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);

    $imagenNueva = uniqid().".".$ext;

    move_uploaded_file(
        $_FILES['imagen']['tmp_name'],
        "../../uploads/productos/".$imagenNueva
    );

    // borrar imagen anterior
    if ($imagen && file_exists("../../uploads/productos/".$imagen)) {
        unlink("../../uploads/productos/".$imagen);
    }

    $imagen = $imagenNueva;
}


/* =========================
   ACTUALIZAR PRODUCTO
========================= */

$pdo->prepare("
UPDATE productos SET
codigo=?,
descripcion=?,
categoria_id=?,
deposito_id=?,
stock_minimo=?,
precio_compra=?,
precio_venta=?,
imagen=?
WHERE id=?
")->execute([
$codigo,
$descripcion,
$categoria_id,
$deposito_id,
$stock_minimo,
$precio_compra,
$precio_venta,
$imagen,
$id
]);


/* =========================
   ACTUALIZAR STOCK
========================= */

$stmt = $pdo->prepare("
SELECT id FROM stock_deposito
WHERE producto_id=? AND deposito_id=?
");

$stmt->execute([$id,$deposito_id]);
$existe = $stmt->fetch();


if($existe){

    $pdo->prepare("
    UPDATE stock_deposito
    SET cantidad=?
    WHERE producto_id=? AND deposito_id=?
    ")->execute([$stock_actual,$id,$deposito_id]);

}else{

    $pdo->prepare("
    INSERT INTO stock_deposito
    (producto_id,deposito_id,cantidad)
    VALUES (?,?,?)
    ")->execute([$id,$deposito_id,$stock_actual]);

}


/* =========================
   AUDITORIA
========================= */

registrarAuditoria(
    'productos',
    'editar',
    "Edición de producto: $descripcion (ID: $id)"
);


/* =========================
   REDIRECT
========================= */

header("Location: index.php");
exit;