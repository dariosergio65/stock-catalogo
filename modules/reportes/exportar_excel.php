<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('reportes');

require_once "../../config/db.php";
require_once "../../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Stock");

$sheet->fromArray([
    ['Código','Producto','Categoría','Stock','Precio compra','Precio venta']
], NULL, 'A1');

$data = $pdo->query("
SELECT p.codigo, p.descripcion, c.nombre categoria, p.stock,
       p.precio_compra, p.precio_venta
FROM productos p
LEFT JOIN categorias c ON p.categoria_id = c.id
")->fetchAll(PDO::FETCH_ASSOC);

$sheet->fromArray($data, NULL, 'A2');

foreach(range('A','F') as $col)
    $sheet->getColumnDimension($col)->setAutoSize(true);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="stock.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
