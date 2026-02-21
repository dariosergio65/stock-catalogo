<?php
require_once "../../config/db.php";

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=stock.csv");

$out = fopen("php://output", "w");
fputcsv($out, ["Código","Producto","Stock"]);

$rows = $pdo->query("SELECT codigo,descripcion,stock FROM productos");
foreach($rows as $r){
    fputcsv($out, $r);
}
fclose($out);
?>