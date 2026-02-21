<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('usuarios');

$logs = $pdo->query("
    SELECT a.*, u.nombre 
    FROM auditoria a
    JOIN usuarios u ON a.usuario_id = u.id
    ORDER BY a.fecha DESC
    LIMIT 500
")->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Auditoría</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h3>📜 Auditoría del sistema</h3><br>

<a href="../../public/index.php" class="btn btn-secondary">⬅ Volver</a>
<br><br>

<table class="table table-bordered table-hover">
<tr class="table-dark">
<th>Fecha</th>
<th>Usuario</th>
<th>Módulo</th>
<th>Acción</th>
<th>Descripción</th>
<th>IP</th>
</tr>

<?php foreach($logs as $l): ?>
<tr>
<td><?= $l['fecha'] ?></td>
<td><?= $l['nombre'] ?></td>
<td><?= $l['modulo'] ?></td>
<td><?= $l['accion'] ?></td>
<td><?= $l['descripcion'] ?></td>
<td><?= $l['ip'] ?></td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
