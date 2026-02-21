<?php
require_once "../../config/db.php";
require_once "../../config/permisos.php";
verificarPermiso('movimientos');

require_once "../../layout/header.php";

$sql = "SELECT m.*, p.descripcion producto, u.nombre usuario,
               c.nombre cliente, pr.nombre proveedor
        FROM movimientos m
        JOIN productos p ON p.id=m.producto_id
        JOIN usuarios u ON u.id=m.usuario_id
        LEFT JOIN clientes c ON c.id=m.cliente_id
        LEFT JOIN proveedores pr ON pr.id=m.proveedor_id
        ORDER BY m.fecha DESC";

$res = $pdo->query($sql);
?>

<div class="container mt-4">
  <h3>Movimientos de stock</h3>

  <a href="nuevo.php" class="btn btn-primary mb-3">Nuevo movimiento</a>
  <a href="../../public/index.php" class="btn btn-secondary mb-3">Volver</a>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Fecha</th>
        <th>Producto</th>
        <th>Tipo</th>
        <th>Cantidad</th>
        <th>Lote</th>
        <th>Cliente</th>
        <th>Proveedor</th>
        <th>Usuario</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $res->fetch(PDO::FETCH_ASSOC)): ?>
      <tr>
        <td><?= $row['fecha'] ?></td>
        <td><?= $row['producto'] ?></td>
        <td>
          <span class="badge bg-<?= $row['tipo']=='entrada'?'success':'danger' ?>">
            <?= strtoupper($row['tipo']) ?>
          </span>
        </td>
        <td><?= $row['cantidad'] ?></td>
        <td><?= $row['lote'] ?></td>
        <td><?= $row['cliente'] ?></td>
        <td><?= $row['proveedor'] ?></td>
        <td><?= $row['usuario'] ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php require_once "../../layout/footer.php"; ?>
