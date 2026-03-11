<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";
require_once "../../config/auditoria.php";

verificarPermiso('productos');

$id = $_GET['id'] ?? 0;


/* =========================
   VERIFICAR STOCK
========================= */

$stmt = $pdo->prepare("
SELECT SUM(cantidad)
FROM stock_deposito
WHERE producto_id=?
");

$stmt->execute([$id]);

$stock = $stmt->fetchColumn();


if($stock > 0){

    echo "
    <div class='container mt-5'>
        <div class='alert alert-danger'>
            <h4>❌ No se puede eliminar</h4>
            <p>El producto todavía tiene stock disponible.</p>
            <a href='index.php' class='btn btn-secondary'>Volver</a>
        </div>
    </div>
    ";

    exit;
}


/* =========================
   DESACTIVAR PRODUCTO
========================= */

$pdo->prepare("
UPDATE productos
SET activo=0
WHERE id=?
")->execute([$id]);


/* =========================
   AUDITORIA
========================= */

registrarAuditoria(
'productos',
'eliminar',
"Desactivó producto ID: $id"
);


/* =========================
   REDIRECT
========================= */

header("Location: index.php");
exit;