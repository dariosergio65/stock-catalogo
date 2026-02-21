<?php
require_once "../../config/auth.php";
require_once "../../config/permisos.php";
verificarPermiso('movimientos');

require_once "../../config/db.php";
require_once "../../layout/header.php";

$productos   = $pdo->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);
$clientes    = $pdo->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
$proveedores = $pdo->query("SELECT * FROM proveedores")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
  <h3>Nuevo movimiento</h3>

  <form action="guardar.php" method="post">

    <label>Producto</label>
    <select name="producto_id" class="form-control mb-2" required>
      <option value="">Seleccione...</option>
      <?php foreach($productos as $p): ?>
        <option value="<?= $p['id'] ?>">
          <?= $p['descripcion'] ?> (Stock: <?= $p['stock'] ?>)
        </option>
      <?php endforeach; ?>
    </select>

    <label>Tipo</label>
    <select name="tipo" class="form-control mb-2" required>
      <option value="entrada">Entrada (Compra)</option>
      <option value="salida">Salida (Venta)</option>
    </select>

    <label>Cantidad</label>
    <input type="number" step="0.01" name="cantidad" class="form-control mb-2" required>

    <label>Lote (opcional)</label>
    <input type="text" name="lote" class="form-control mb-2">

    <label>Cliente (si es venta)</label>
    <select name="cliente_id" class="form-control mb-2">
      <option value="">-- ninguno --</option>
      <?php foreach($clientes as $c): ?>
        <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
      <?php endforeach; ?>
    </select>

    <label>Proveedor (si es compra)</label>
    <select name="proveedor_id" class="form-control mb-2">
      <option value="">-- ninguno --</option>
      <?php foreach($proveedores as $pr): ?>
        <option value="<?= $pr['id'] ?>"><?= $pr['nombre'] ?></option>
      <?php endforeach; ?>
    </select>

    <button class="btn btn-success mt-2">Guardar</button>
    <a href="index.php" class="btn btn-secondary mt-2">Cancelar</a>

  </form>
</div>

<?php require_once "../../layout/footer.php"; ?>
