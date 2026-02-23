<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";
require_once "../../config/auditoria.php";

verificarPermiso('permisos');

$usuarios = $pdo->query("
    SELECT id,nombre,usuario,rol 
    FROM usuarios 
    ORDER BY nombre
")->fetchAll();

$modulos = [
  'dashboard','productos','categorias','depositos','clientes','proveedores',
  'movimientos','kardex','reportes','usuarios'
];

$usuarioSeleccionado = $_GET['usuario_id'] ?? null;
$permisosActuales = [];
$permisosRol = [];

if ($usuarioSeleccionado) {

    // Obtener rol del usuario
    $stmt = $pdo->prepare("SELECT rol FROM usuarios WHERE id=?");
    $stmt->execute([$usuarioSeleccionado]);
    $rolUsuario = $stmt->fetchColumn();

    // Permisos base del rol
    $permisosRol = permisosPorRol()[$rolUsuario] ?? [];

    // Permisos extra del usuario
    $stmt = $pdo->prepare("
        SELECT modulo 
        FROM permisos_usuario 
        WHERE usuario_id = ?
    ");
    $stmt->execute([$usuarioSeleccionado]);
    $permisosActuales = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

if ($_POST) {

    $usuario_id = $_POST['usuario_id'];

    // Borrar extras actuales
    $pdo->prepare("DELETE FROM permisos_usuario WHERE usuario_id=?")
        ->execute([$usuario_id]);

    if (!empty($_POST['permisos'])) {
        $stmt = $pdo->prepare("
            INSERT INTO permisos_usuario (usuario_id,modulo) 
            VALUES (?,?)
        ");
        foreach ($_POST['permisos'] as $m) {
            $stmt->execute([$usuario_id,$m]);
        }
    }

    registrarAuditoria(
    'modificar',
    'permisos',
    "Modificó permisos del usuario ID $usuario_id"
    );

    header("Location: index.php?usuario_id=".$usuario_id."&ok=1");
    exit;
}
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Permisos por Usuario</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="container mt-4">

<h3>🔐 Gestión de permisos por usuario</h3>

<?php if (isset($_GET['ok'])): ?>
<div class="alert alert-success">Permisos actualizados correctamente</div>
<?php endif; ?>

<form method="post">

<div class="mb-3">
<label class="form-label">Usuario</label>
<select name="usuario_id" class="form-select"
        onchange="location='index.php?usuario_id='+this.value" required>
<option value="">Seleccione usuario</option>
<?php foreach($usuarios as $u): ?>
<option value="<?= $u['id'] ?>"
<?= ($usuarioSeleccionado == $u['id']) ? 'selected' : '' ?>>
<?= $u['nombre'] ?> (<?= $u['rol'] ?>)
</option>
<?php endforeach; ?>
</select>
</div>

<?php if ($usuarioSeleccionado): ?>

<div class="alert alert-info">
Los permisos <b>marcados y bloqueados</b> pertenecen al rol del usuario.
Solo se guardan los permisos <b>adicionales</b>.
</div>

<div class="row">
<?php foreach($modulos as $m): ?>

<?php
$esRol   = in_array($m, $permisosRol);
$esExtra = in_array($m, $permisosActuales);
?>

<div class="col-md-3 col-sm-6 mb-2">
<label class="form-check-label">

<input type="checkbox" class="form-check-input"
       name="permisos[]" value="<?= $m ?>"
       <?= ($esRol || $esExtra) ? 'checked' : '' ?>
       <?= $esRol ? 'disabled' : '' ?>>

<?= ucfirst($m) ?>

<?php if ($esRol): ?>
<span class="badge bg-secondary ms-1">rol</span>
<?php endif; ?>

<?php if ($esExtra): ?>
<span class="badge bg-success ms-1">extra</span>
<?php endif; ?>

</label>
</div>

<?php endforeach; ?>
</div>

<button class="btn btn-success mt-3">💾 Guardar permisos</button>

<?php endif; ?>

<a href="../../public/index.php" class="btn btn-secondary mt-3 ms-2">
⬅ Volver al menú
</a>

</form>

</body>
</html>
