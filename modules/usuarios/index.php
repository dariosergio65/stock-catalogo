<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('usuarios');

require_once "../../config/db.php";
soloAdmin();

$usuarios = $pdo->query("SELECT id,nombre,usuario,rol,activo FROM usuarios")->fetchAll();
?>
<!doctype html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">
<h3>Usuarios</h3>
<a href="crear.php" class="btn btn-primary mb-2">Nuevo usuario</a>

<a href="../../public/index.php" class="btn btn-secondary">⬅ Menú</a>
</div>

<table class="table table-bordered table-sm">
<tr>
<th>Nombre</th><th>Usuario</th><th>Rol</th><th>Activo</th><th></th>
</tr>

<?php foreach($usuarios as $u): ?>
<tr>
<td><?= $u['nombre'] ?></td>
<td><?= $u['usuario'] ?></td>
<td><?= $u['rol'] ?></td>
<td><?= $u['activo'] ? 'Sí' : 'No' ?></td>
<td>
<a href="editar.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
<a href="eliminar.php?id=<?= $u['id'] ?>" class="btn btn-danger btn-sm"
   onclick="return confirm('¿Eliminar usuario?')">X</a>
</td>
</tr>
<?php endforeach ?>
</table>

</body>
</html>
