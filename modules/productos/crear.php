<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('productos');

$depositos = $pdo->query("SELECT id, nombre FROM depositos WHERE activo = 1 ORDER BY nombre")->fetchAll();
$categoria = $pdo->query("SELECT id, nombre FROM categorias ORDER BY nombre")->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Nuevo Producto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body>

<div class="container mt-4">

<h4 class="mb-3">📦 Nuevo producto</h4>

<form method="post" action="guardar.php"enctype="multipart/form-data" class="card card-body shadow-sm">

  <div class="mb-2">
    <label class="form-label">Código</label>
    <input name="codigo" class="form-control" required>
  </div>

  <div class="mb-2">
    <label class="form-label">Descripción</label>
    <input name="descripcion" class="form-control" required>
  </div>

  <div class="mb-2">
    <input type="file" name="imagen" id="imagen" class="form-control mb-2" accept="image/*" capture="environment">
    <img id="preview" style="max-width:150px; display:none; border-radius:6px;">
  </div>

  <div class="mb-2">
    <label class="form-label">Depósito</label>
    <select name="deposito_id" class="form-select" required>
      <option value="">Seleccione depósito...</option>
      <?php foreach ($depositos as $d): ?>
        <option value="<?= $d['id'] ?>">
            <?= htmlspecialchars($d['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-2">
    <label class="form-label">Categoría</label>
    <select name="categoria_id" class="form-select" required>
      <option value="">Seleccione categoría...</option>
      <?php foreach ($categoria as $c): ?>
        <option value="<?= $c['id'] ?>">
            <?= htmlspecialchars($c['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="row">

  <div class="col-md-3 mb-2">
    <label class="form-label">Stock inicial</label>
    <input type="number" name="stock_inicial" class="form-control" required>
  </div>

  <div class="col-md-3 mb-2">
    <label class="form-label">Stock mínimo</label>
    <input type="number" name="stock_minimo" class="form-control" required>
  </div>

  <div class="col-md-3 mb-2">
    <label class="form-label">Precio compra</label>
    <input type="number" step="0.01" name="precio_compra" class="form-control" required>
  </div>

  <div class="col-md-3 mb-2">
    <label class="form-label">Precio venta</label>
    <input type="number" step="0.01" name="precio_venta" class="form-control" required>
  </div>

</div>

  <div class="mt-3">
    <button class="btn btn-success">Guardar</button>
    <a href="index.php" class="btn btn-secondary">Volver</a>
  </div>

</form>

</div>

<script>

const inputImagen = document.getElementById("imagen");
const preview = document.getElementById("preview");

inputImagen.addEventListener("change", function(e){

    const file = e.target.files[0];
    if(!file) return;

    const reader = new FileReader();

    reader.onload = function(event){

        const img = new Image();

        img.onload = function(){

            const canvas = document.createElement("canvas");
            const MAX = 1200; // tamaño máximo

            let width = img.width;
            let height = img.height;

            if(width > height){
                if(width > MAX){
                    height *= MAX / width;
                    width = MAX;
                }
            }else{
                if(height > MAX){
                    width *= MAX / height;
                    height = MAX;
                }
            }

            canvas.width = width;
            canvas.height = height;

            const ctx = canvas.getContext("2d");
            ctx.drawImage(img,0,0,width,height);

            canvas.toBlob(function(blob){

                const newFile = new File([blob], file.name, {
                    type: "image/jpeg"
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(newFile);

                inputImagen.files = dataTransfer.files;

                preview.src = URL.createObjectURL(blob);
                preview.style.display = "block";

            }, "image/jpeg", 0.75); // calidad 75%

        };

        img.src = event.target.result;

    };

    reader.readAsDataURL(file);

});

</script>

</body>
</html>
