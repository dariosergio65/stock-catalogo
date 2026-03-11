<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";
require_once "../../config/auditoria.php";

verificarPermiso('productos');

$producto_id = $_POST['producto_id'];
$stocks = $_POST['stock'];

foreach($stocks as $deposito_id => $cantidad){

    $stmt = $pdo->prepare("
    SELECT id FROM stock_deposito
    WHERE producto_id=? AND deposito_id=?
    ");

    $stmt->execute([$producto_id,$deposito_id]);
    $existe = $stmt->fetch();

    if($existe){

        $pdo->prepare("
        UPDATE stock_deposito
        SET cantidad=?
        WHERE producto_id=? AND deposito_id=?
        ")->execute([$cantidad,$producto_id,$deposito_id]);

    }else{

        $pdo->prepare("
        INSERT INTO stock_deposito
        (producto_id,deposito_id,cantidad)
        VALUES (?,?,?)
        ")->execute([$producto_id,$deposito_id,$cantidad]);

    }

}

/* auditoria */

registrarAuditoria(
'stock',
'editar',
"Actualización de stock del producto ID $producto_id"
);

header("Location: index.php");
exit;