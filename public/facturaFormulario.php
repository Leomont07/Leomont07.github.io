<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>
  <body style="background-color: rgba(255,255,255,0.5);" >
    <div class="position-absolute top-50 start-50 translate-middle" id="facturaFormulario">
        <div class="container" style="background-color: gainsboro; border-radius: 5%;">
            <form class="row g-3 p-4 pt-0">
                <div class="col-12 mt-4">
                    <label for="inputNombrePerfil" class="form-label">Asigne un nombre a este perfil</label>
                    <input type="text" class="form-control" id="inputNombrePerfil" placeholder="Personal">
                  </div>
                <div class="col-md-6 col-12">
                  <label for="inputRFC" class="form-label">RFC</label>
                  <input type="text" class="form-control" id="inputRFC">
                </div>
                <div class="col-md-6 col-12">
                  <label for="inputCP" class="form-label">CP</label>
                  <input type="text" class="form-control" id="inputCP">
                </div>
                <div class="col-md-6 col-12">
                  <label for="razonSocial" class="form-label">Razón social</label>
                  <input type="text" class="form-control" id="razonSocial">
                </div>
                <div class="col-md-6 col-12">
                  <label for="razonSocial" class="form-label">Regimen Fiscal</label>
                  <select class="form-select" aria-label=".form-select-lg example">
                      <option selected>Seleccione un regimen</option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
              </div>
                <button type="submit" class="btn btn-primary col-12 col-md-6">Guardar</button>
                <a href="facturarSelected.php" type="submit" class="btn btn-danger col-12 col-md-6" id="cerrarFF">Cerrar</a>
              </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>








