<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('permisos');

$usuarios = $pdo->query("SELECT id,nombre,usuario,rol FROM usuarios")->fetchAll();

$modulos = [
  'dashboard','productos','categorias','clientes','proveedores',
  'movimientos','kardex','reportes','usuarios'
];

if ($_POST) {
    $usuario_id = $_POST['usuario_id'];
    $pdo->prepare("DELETE FROM permisos_usuario WHERE usuario_id=?")->execute([$usuario_id]);

    if (!empty($_POST['permisos'])) {
        $stmt = $pdo->prepare("INSERT INTO permisos_usuario (usuario_id,modulo) VALUES (?,?)");
        foreach ($_POST['permisos'] as $m) {
            $stmt->execute([$usuario_id,$m]);
        }
    }
    header("Location: index.php?ok=1");
    exit;
}
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Permisos por usuario</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h3>🔐 Permisos por usuario</h3>

<form method="post">
<select name="usuario_id" class="form-select mb-3" required>
<option value="">Seleccione usuario</option>
<?php foreach($usuarios as $u): ?>
<option value="<?= $u['id'] ?>">
<?= $u['nombre'] ?> (<?= $u['rol'] ?>)
</option>
<?php endforeach; ?>
</select>

<div class="row">
<?php foreach($modulos as $m): ?>
<div class="col-md-3">
<label>
<input type="checkbox" name="permisos[]" value="<?= $m ?>"> <?= ucfirst($m) ?>
</label>
</div>
<?php endforeach; ?>
</div>

<button class="btn btn-success mt-3">Guardar permisos</button>
<a href="../../public/index.php" class="btn btn-secondary mt-3">Volver</a>

</form>

</body>
</html>
