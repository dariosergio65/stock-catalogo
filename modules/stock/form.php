<form method="post" action="mover.php" class="container mt-4">

<select name="tipo" class="form-control mb-2">
  <option value="entrada">Entrada</option>
  <option value="salida">Salida</option>
</select>

<input type="number" name="producto_id" class="form-control mb-2" placeholder="ID producto">
<input type="number" name="cantidad" class="form-control mb-2" placeholder="Cantidad">
<input type="text" name="lote" class="form-control mb-2" placeholder="Lote">

<input type="number" name="cliente_id" class="form-control mb-2" placeholder="Cliente (si salida)">
<input type="number" name="proveedor_id" class="form-control mb-2" placeholder="Proveedor (si entrada)">

<button class="btn btn-success">Confirmar</button>
</form>
