<?php
session_start();
require_once "../../config/db.php";

$filtro = $_GET['estado'] ?? '';

$contadores = [];

$stmt = $pdo->query("
    SELECT estado, COUNT(*) total
    FROM pedidos
    GROUP BY estado
");

foreach($stmt as $row){
    $contadores[$row['estado']] = $row['total'];
}

if($filtro){
    $stmt = $pdo->prepare("
        SELECT id, nombre, total, estado, fecha
        FROM pedidos
        WHERE estado = ?
        ORDER BY FIELD(estado,
        'pendiente',
        'pagado',
        'enviado',
        'entregado',
        'cancelado'),
        fecha DESC
    ");
    $stmt->execute([$filtro]);
}else{
    $stmt = $pdo->query("
        SELECT id, nombre, total, estado, fecha
        FROM pedidos
        ORDER BY FIELD(estado,
        'pendiente',
        'pagado',
        'enviado',
        'entregado',
        'cancelado'),
        fecha DESC
    ");
}
$pedidos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Pedidos</title>

<style>

body{
    font-family: Arial;
    margin:0;
    padding:10px;
    background:#f5f5f5;
}

.topbar{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:15px;
}

.volver{
    text-decoration:none;
    background:#333;
    color:white;
    padding:6px 10px;
    border-radius:6px;
    font-size:14px;
}

/* tarjeta pedido */
.pedido{
    background:white;
    padding:12px;
    margin-bottom:10px;
    border-radius:8px;
    box-shadow:0 2px 4px rgba(0,0,0,0.1);
}

/* encabezado */
.pedido-top{
    display:flex;
    justify-content:space-between;
    font-size:14px;
    margin-bottom:6px;
}

.estado{
    padding:3px 8px;
    border-radius:4px;
    font-size:12px;
}

/* colores estado */

.pendiente{ background:#ffe9a8; }
.pagado{ background:#b8f5b1; }
.enviado{ background:#b5e0ff; }
.cancelado{ background:#ffb5b5; }

.total{
    font-weight:bold;
    margin-top:4px;
}

/* botón */

.ver{
    display:block;
    text-align:center;
    margin-top:8px;
    padding:6px;
    background:#333;
    color:white;
    text-decoration:none;
    border-radius:4px;
}

.dashboard{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:8px;
    margin-bottom:15px;
}

.box{
    background:white;
    padding:10px;
    text-align:center;
    border-radius:8px;
    font-size:14px;
    box-shadow:0 2px 4px rgba(0,0,0,0.1);
    text-decoration:none;
    color:black;
}

.box strong{
    font-size:18px;
}

.box.pendiente{ background:#ffe9a8; }
.box.pagado{ background:#b8f5b1; }
.box.enviado{ background:#b5e0ff; }
.box.cancelado{ background:#ffb5b5; }
.estado.entregado{
    background:#ddd;
}
.estado.entregado{
    background:#ddd;
}

</style>

</head>

<body>
    
<div class="topbar">
    <a href="../index.php" class="volver">← Menú</a>
    <h2>Pedidos</h2>
</div>

<div class="dashboard">

    <a class="box pendiente" href="?estado=pendiente">
    Pendientes<br>
    <strong><?php echo $contadores['pendiente'] ?? 0; ?></strong>
    </a>

    <a class="box pagado" href="?estado=pagado">
    Pagados<br>
    <strong><?php echo $contadores['pagado'] ?? 0; ?></strong>
    </a>

    <a class="box enviado" href="?estado=enviado">
    Enviados<br>
    <strong><?php echo $contadores['enviado'] ?? 0; ?></strong>
    </a>

    <a class="box cancelado" href="?estado=cancelado">
    Cancelados<br>
    <strong><?php echo $contadores['cancelado'] ?? 0; ?></strong>
    </a>

    <a class="box entregado" href="?estado=entregado">
    Entregados<br>
    <strong><?php echo $contadores['entregado'] ?? 0; ?></strong>
    </a>

</div>

<?php foreach($pedidos as $p): ?>

<div class="pedido">

<div class="pedido-top">
<span>#<?php echo $p['id']; ?></span>

<span class="estado <?php echo $p['estado']; ?>">
<?php echo $p['estado']; ?>
</span>
</div>

<div>
<?php echo htmlspecialchars($p['nombre']); ?>
</div>

<div class="total">
$<?php echo number_format($p['total'],2); ?>
</div>

<a class="ver" href="pedido_ver.php?id=<?php echo $p['id']; ?>">
Ver pedido
</a>

</div>

<?php endforeach; ?>

</body>

</html>