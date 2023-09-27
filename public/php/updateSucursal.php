<?php

  include('conexion.php');

    session_start();

    $con=conexion();

    $id= $_GET['id_sucursal'];

    $sql = "SELECT * FROM sucursal WHERE id_sucursal='$id'";

    $query = mysqli_query($con, $sql);

    $row = mysqli_fetch_array($query);

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
            <form action="/Barbieland/public/php/edit_sucursal.php" method="POST" class="row g-3 p-4 pt-0">
            <input type="hidden" name="id_sucursal" value="<?= $row ['id_sucursal'] ?>">  
                <div class="col-12 mt-4">
                    <label for="inputNombrePerfil" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Nombre" value="<?= $row ['nombre'] ?>">
                  </div>
                <div class="col-12 mt-4">
                    <label for="inputNombrePerfil" class="form-label">Calle</label>
                    <input type="text" class="form-control" name="calle" placeholder="Calle" value="<?= $row ['calle'] ?>">
                  </div>
                <div class="col-6 mt-4">
                  <label for="inputRFC" class="form-label">Colonia</label>
                  <input type="text" class="form-control" name="colonia" placeholder="Colonia" value="<?= $row ['colonia'] ?>">
                </div>
                <div class="col-6 mt-4">
                  <label for="razonSocial" class="form-label">No. Ext</label>
                  <input type="text" class="form-control" name="numero_exterior" placeholder="No. Exterior" value="<?= $row ['numero_exterior'] ?>">
                </div>
                <div class="col-12 mt-4">
                    <label for="inputNombrePerfil" class="form-label">Correo</label>
                    <input type="mail" class="form-control" name="correo" placeholder="Correo" value="<?= $row ['correo'] ?>">
                </div>
                  <div class="col-12 mt-4">
                    <label for="inputNombrePerfil" class="form-label">Telefono</label>
                    <input type="number" class="form-control"name="telefono" placeholder="Teléfono" value="<?= $row ['telefono'] ?>">
                  </div>
                <button type="submit" class="btn btn-primary col-12 col-md-6">Guardar</button>
                <a href="/Barbieland/public/gestionSucursales.php" type="submit" class="btn btn-danger col-12 col-md-6">Cerrar</a>
              </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>








