<?php

$id = $_GET['id_usuario'];

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>
  <body style="background-color: rgba(255,255,255,0.5);">
    <div class="position-absolute top-50 start-50 translate-middle">
        <div class="container" style="background-color: gainsboro; border-radius: 5%;">
            <form action="php/alta_direccion.php" method="post" class="row g-3 p-4 pt-0">
                <div class="col-12 mt-4">
                    <input type="hidden" class="form-control" id="id_usuario" value="$id">
                  </div>
                <div class="col-md-4 col-12">
                  <label for="inputRFC" class="form-label">Calle</label>
                  <input type="text" class="form-control" id="calle">
                </div>
                <div class="col-md-4 col-12">
                  <label for="inputCP" class="form-label">Colonia</label>
                  <input type="text" class="form-control" id="colonia">
                </div>
                <div class="col-md-4 col-12">
                  <label for="razonSocial" class="form-label">No. Ext</label>
                  <input type="text" class="form-control" id="numero_exterior">
                </div>
                <div class="col-md-6 col-12">
                    <label for="razonSocial" class="form-label">CP</label>
                    <input type="text" class="form-control" id="cp">
                </div>
                <button type="submit" class="btn btn-primary col-12 col-md-6">Guardar</button>
                <a href="venta.html" type="submit" class="btn btn-danger col-12 col-md-6">Cerrar</a>
              </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>








