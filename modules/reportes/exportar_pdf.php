<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('reportes');

require_once "../../config/db.php";
require_once "../../vendor/autoload.php";

use Dompdf\Dompdf;

$data = $pdo->query("
SELECT p.codigo, p.descripcion, c.nombre categoria, p.stock,
       p.precio_compra, p.precio_venta
FROM productos p
LEFT JOIN categorias c ON p.categoria_id = c.id
")->fetchAll();

$html = '
<h2 style="text-align:center">Reporte de Stock</h2>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
<tr>
<th>Código</th>
<th>Producto</th>
<th>Categoría</th>
<th>Stock</th>
<th>Compra</th>
<th>Venta</th>
</tr>';

foreach($data as $r){
  $html .= "
  <tr>
    <td>{$r['codigo']}</td>
    <td>{$r['descripcion']}</td>
    <td>{$r['categoria']}</td>
    <td>{$r['stock']}</td>
    <td>{$r['precio_compra']}</td>
    <td>{$r['precio_venta']}</td>
  </tr>";
}

$html .= '</table>';

$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper('A4', 'landscape');
$pdf->render();
$pdf->stream("stock.pdf", ["Attachment" => true]);
exit;
