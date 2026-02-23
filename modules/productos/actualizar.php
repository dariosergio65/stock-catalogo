<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";
require_once "../../config/auditoria.php";

verificarPermiso('productos');

$id = $_POST['id'];

# Traemos datos anteriores para auditoría
$stmt = $pdo->prepare("SELECT deposito_id, categoria_id FROM productos WHERE id = ?");
$stmt->execute([$id]);
$anterior = $stmt->fetch();

if (!$anterior) {
    echo "Producto no encontrado";
    exit;
}

# Actualizamos producto
$stmt = $pdo->prepare("
UPDATE productos SET 
    codigo = ?, 
    descripcion = ?, 
    categoria_id = ?, 
    deposito_id = ?, 
    stock_minimo = ?, 
    precio_compra = ?, 
    precio_venta = ?
WHERE id = ?
");

$stmt->execute([
    $_POST['codigo'],
    $_POST['descripcion'],
    $_POST['categoria_id'] ?: null,
    $_POST['deposito_id'],
    $_POST['stock_minimo'],
    $_POST['precio_compra'],
    $_POST['precio_venta'],
    $id
]);

# Auditoría de cambios importantes
if ($anterior['deposito_id'] != $_POST['deposito_id']) {

    $d1 = $pdo->prepare("SELECT nombre FROM depositos WHERE id = ?");
    $d1->execute([$anterior['deposito_id']]);
    $oldDep = $d1->fetchColumn();

    $d2 = $pdo->prepare("SELECT nombre FROM depositos WHERE id = ?");
    $d2->execute([$_POST['deposito_id']]);
    $newDep = $d2->fetchColumn();

    registrarAuditoria(
        'Cambio depósito',
        'productos',
        "Producto ID $id: $oldDep → $newDep"
    );
}

if ($anterior['categoria_id'] != $_POST['categoria_id']) {

    $c1 = $pdo->prepare("SELECT nombre FROM categorias WHERE id = ?");
    $c1->execute([$anterior['categoria_id']]);
    $oldCat = $c1->fetchColumn() ?: 'Sin categoría';

    $c2 = $pdo->prepare("SELECT nombre FROM categorias WHERE id = ?");
    $c2->execute([$_POST['categoria_id']]);
    $newCat = $c2->fetchColumn() ?: 'Sin categoría';

    registrarAuditoria(
        'Cambio categoría',
        'productos',
        "Producto ID $id: $oldCat → $newCat"
    );
}

registrarAuditoria(
    'Edición producto',
    'productos',
    "Editó producto ID $id"
);

header("Location: index.php");
exit;
