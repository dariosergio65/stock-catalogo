<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";
require_once "../../config/auditoria.php";

verificarPermiso('productos');

$codigo         = $_POST['codigo'];
$descripcion    = $_POST['descripcion'];
$deposito       = $_POST['deposito_id'];
$stock_inicial  = $_POST['stock_inicial'];   // NUEVO
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

$pdo->beginTransaction();

try {

    // 1️⃣ insertar producto
    $stmt = $pdo->prepare("
        INSERT INTO productos 
        (codigo, descripcion, imagen, deposito_id, stock_minimo, precio_compra, precio_venta, categoria_id)
        VALUES (?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $codigo,
        $descripcion,
        $imagen,
        $deposito,
        $stock_minimo,
        $precio_compra,
        $precio_venta,
        $categoria_id
    ]);

    // 2️⃣ obtener id del producto
    $producto_id = $pdo->lastInsertId();

    // 3️⃣ crear stock inicial en stock_deposito
    if ($stock_inicial > 0) {

        $stmt = $pdo->prepare("
            INSERT INTO stock_deposito
            (producto_id, deposito_id, lote, cantidad)
            VALUES (?,?, '', ?)
        ");

        $stmt->execute([
            $producto_id,
            $deposito,
            $stock_inicial
        ]);
    }

    // auditoría
    registrarAuditoria(
        'productos',
        'crear',
        "Alta de producto: $descripcion (Código: $codigo)"
    );

    $pdo->commit();

} catch (Exception $e) {

    $pdo->rollBack();
    die("Error al guardar producto: " . $e->getMessage());
}

header("Location: index.php");
exit;
?>