<?php

include('php/conexion.php');

    $con=conexion();

    $sql = "SELECT p.id_producto, p.nombre, p.categoria, p.descripcion, p.marca, p.precio, i.ruta, inv.id_inventario, inv.id_sucursal, inv.cantidad
    FROM producto p
    INNER JOIN imagen i ON p.id_producto = i.id_producto
    INNER JOIN inventario inv ON p.id_producto = inv.id_producto
    WHERE inv.cantidad>0
    GROUP BY p.id_producto, p.nombre, p.categoria, p.descripcion, p.marca, p.precio, i.ruta, inv.id_inventario;";


    $querySignature = mysqli_query($con, $sql);
    $queryProfesiones = mysqli_query($con, $sql);
    $queryMovie = mysqli_query($con, $sql);
    $queryFashionistas = mysqli_query($con, $sql);

    $sucursales = "SELECT * FROM sucursal WHERE estatus=1;";
    $sucur = mysqli_query($con, $sucursales);

?>


<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>majestic | Landing, Ecommerce &amp; Business Templatee</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="assets/css/theme.css" rel="stylesheet"/>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->


  </head>


  <body>

    <header class="container-fluid">
      <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
          <div class="container">
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item px-2"><a class="nav-link fw-medium active" aria-current="page" href="index.php">Inicio</a></li>
                <li class="nav-item px-2"><a class="nav-link fw-medium" href="shop.php">Catálogo</a></li>
              </ul>
              <form class="d-flex nav-item dropdown no-arrow"><a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <svg class="feather feather-user me-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                  </svg></a>
                  <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                              aria-labelledby="userDropdown">
                              <a class="dropdown-item" href="login.php">
                                  <i class="fas fa-sm fa-fw mr-2 text-gray-400"></i>
                                  Iniciar Sesión
                              </a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="usuarioFormulario2.php?tipo=cliente">
                                  <i class="fas fa-sm fa-fw mr-2 text-gray-400"></i>
                                  Registrarse
                              </a>
                          </div>
                </form>
            </div>
          </div>
        </nav>
  </header>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">

    <section id="categoryWomen">
  <div class="container">
    <div class="row h-100">
      <div class="col-lg-7 mx-auto text-center mb-6">
        <h5 class="fw-bold fs-3 fs-lg-5 lh-sm mb-3">Tienda</h5>
      </div>
      <div class="col-lg-7 mx-auto text-center mb-6">
      <h3 class="fw-bold fs-2 fs-lg-3 lh-sm mb-2">Selecciona la sucursal</h3>
      </div>
        
      <div class="col-12">
        <nav>
          <div class="nav nav-tabs majestic-tabs mb-4 justify-content-center" id="nav-tab" role="tablist">
            <?php while ($row = mysqli_fetch_array($sucur)): ?>
              <button class="nav-link" id="nav-<?= $row['nombre'] ?>-tab" data-bs-toggle="tab" data-bs-target="#nav-<?= $row['nombre'] ?>" type="button" role="tab" aria-controls="nav-<?= $row['nombre'] ?>" aria-selected="true"><?= $row['nombre'] ?></button>
            <?php endwhile; ?>
          </div>

          <div class="tab-content" id="nav-tabContent">
            <?php mysqli_data_seek($sucur, 0); while ($sucursal = mysqli_fetch_array($sucur)): ?>
              <div class="tab-pane fade" id="nav-<?= $sucursal['nombre'] ?>" role="tabpanel" aria-labelledby="nav-<?= $sucursal['nombre'] ?>-tab">
                <ul class="nav nav-pills justify-content-center mb-5" id="pills-tab-<?= $sucursal['nombre'] ?>" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="<?= $sucursal['nombre'] ?>-Signature-tab" data-bs-toggle="pill" data-bs-target="#<?= $sucursal['nombre'] ?>-Signature" type="button" role="tab" aria-controls="<?= $sucursal['nombre'] ?>-Signature" aria-selected="true">Signature</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="<?= $sucursal['nombre'] ?>-Profesiones-tab" data-bs-toggle="pill" data-bs-target="#<?= $sucursal['nombre'] ?>-Profesiones" type="button" role="tab" aria-controls="<?= $sucursal['nombre'] ?>-Profesiones" aria-selected="false">Profesiones</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="<?= $sucursal['nombre'] ?>-Movie-tab" data-bs-toggle="pill" data-bs-target="#<?= $sucursal['nombre'] ?>-Movie" type="button" role="tab" aria-controls="<?= $sucursal['nombre'] ?>-Movie" aria-selected="false">Movie</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="<?= $sucursal['nombre'] ?>-Fashionistas-tab" data-bs-toggle="pill" data-bs-target="#<?= $sucursal['nombre'] ?>-Fashionistas" type="button" role="tab" aria-controls="<?= $sucursal['nombre'] ?>-Fashionistas" aria-selected="false">Fashionistas</button>
                  </li>
                </ul>

                <div class="tab-content" id="pills-tabContent-<?= $sucursal['nombre'] ?>">
                  <div class="tab-pane fade show active" id="<?= $sucursal['nombre'] ?>-Signature" role="tabpanel" aria-labelledby="<?= $sucursal['nombre'] ?>-Signature-tab">
                    <div class="row">
                      <?php mysqli_data_seek($querySignature, 0); while ($row = mysqli_fetch_array($querySignature)): ?>
                        <?php if ($row['categoria'] === 'Signature' && $row['id_sucursal'] === $sucursal['id_sucursal']): ?>
                          <div class="col-md-4">
                              <div class="card-body">
                              <form class="card" method="post">
                              <a href="login.php"><img src="<?= $row['ruta'] ?>" class="card-img-top"></a>
                              <br>
                              <div class="container">
                                <h5 class="card-title"><?= $row['nombre'] ?></h5>
                                <p class="card-text"><strong>Marca: </strong><?= $row['marca'] ?></p>
                                <p class="card-text"><strong>Precio: </strong><?= $row['precio'] ?></p>
                                <input type="hidden" name="precio" value="<?= $row['precio'] ?>">
                                </div>
                                </form>
                              </div>
                          </div>
                        <?php endif; ?>
                      <?php endwhile; ?>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="<?= $sucursal['nombre'] ?>-Profesiones" role="tabpanel" aria-labelledby="<?= $sucursal['nombre'] ?>-Profesiones-tab">
                    <div class="row">
                      <?php mysqli_data_seek($queryProfesiones, 0); while ($row = mysqli_fetch_array($queryProfesiones)): ?>
                        <?php if ($row['categoria'] === 'Profesiones' && $row['id_sucursal'] === $sucursal['id_sucursal']): ?>
                          <div class="col-md-4">
                              <div class="card-body">
                              <form class="card" method="post">
                              <a href="login.php"><img src="<?= $row['ruta'] ?>" class="card-img-top"></a>
                              <br>
                              <div class="container">
                                <h5 class="card-title"><?= $row['nombre'] ?></h5>
                                <p class="card-text"><strong>Marca: </strong><?= $row['marca'] ?></p>
                                <p class="card-text"><strong>Precio: </strong><?= $row['precio'] ?></p>
                                <input type="hidden" name="precio" value="<?= $row['precio'] ?>">
                                </div>
                                </form>
                              </div>
                          </div>
                        <?php endif; ?>
                      <?php endwhile; ?>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="<?= $sucursal['nombre'] ?>-Movie" role="tabpanel" aria-labelledby="<?= $sucursal['nombre'] ?>-Movie-tab">
                    <div class="row">
                      <?php mysqli_data_seek($queryMovie, 0); while ($row = mysqli_fetch_array($queryMovie)): ?>
                        <?php if ($row['categoria'] === 'Movie' && $row['id_sucursal'] === $sucursal['id_sucursal']): ?>
                          <div class="col-md-4">
                              <div class="card-body">
                              <form class="card" method="post">
                              <a href="login.php"><img src="<?= $row['ruta'] ?>" class="card-img-top"></a>
                              <br>
                              <div class="container">
                                <h5 class="card-title"><?= $row['nombre'] ?></h5>
                                <p class="card-text"><strong>Marca: </strong><?= $row['marca'] ?></p>
                                <p class="card-text"><strong>Precio: </strong><?= $row['precio'] ?></p>
                                <input type="hidden" name="precio" value="<?= $row['precio'] ?>">
                                </div>
                                </form>
                              </div>
                          </div>
                        <?php endif; ?>
                      <?php endwhile; ?>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="<?= $sucursal['nombre'] ?>-Fashionistas" role="tabpanel" aria-labelledby="<?= $sucursal['nombre'] ?>-Fashionistas-tab">
                    <div class="row">
                      <?php mysqli_data_seek($queryFashionistas, 0); while ($row = mysqli_fetch_array($queryFashionistas)): ?>
                        <?php if ($row['categoria'] === 'Fashionistas' && $row['id_sucursal'] === $sucursal['id_sucursal']): ?>
                          <div class="col-md-4">
                              <div class="card-body">
                              <form class="card" method="post">
                              <a href="login.php"><img src="<?= $row['ruta'] ?>" class="card-img-top"></a>
                              <br>
                              <div class="container">
                                <h5 class="card-title"><?= $row['nombre'] ?></h5>
                                <p class="card-text"><strong>Marca: </strong><?= $row['marca'] ?></p>
                                <p class="card-text"><strong>Precio: </strong><?= $row['precio'] ?></p>
                                <input type="hidden" name="precio" value="<?= $row['precio'] ?>">
                                </div>
                                </form>
                              </div>
                          </div>
                        <?php endif; ?>
                      <?php endwhile; ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </nav>
      </div>
    </div>
  </div>
</section>


<footer class="py-0 pt-7">
        <div class="container">
          
            <div class="col-6 col-lg-2 mb-3">
              <ul class="list-unstyled mb-md-4 mb-lg-0">
                <li class="lh-lg"><a class="text-800 text-decoration-none" href="#!">Contáctanos</a></li>
                <p class="text-800">
                <svg class="feather feather-phone me-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg><span class="text-800">+4429582753</span>
              </p>
              </ul>
            </div>
            <div class="col-sm-6 col-lg-auto ms-auto">
              <p class="text-800">
                <svg class="feather feather-mail me-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg><span class="text-800">barbienet@gmail.com</span>
              </p>
            </div>
          </div>
          <div class="border-bottom border-3"></div>
          <div class="row flex-center my-3">
            <div class="col-md-6">
              <div class="text-center text-md-end"><a href="#!"><span class="me-4" data-feather="facebook"></span></a><a href="#!"> <span class="me-4" data-feather="instagram"></span></a><a href="#!"> <span class="me-4" data-feather="youtube"></span></a><a href="#!"> <span class="me-4" data-feather="twitter"></span></a></div>
            </div>
          </div>
        </div>
    </footer>
    
    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->




    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="vendors/feather-icons/feather.min.js"></script>
    <script>
      feather.replace();
    </script>
    <script src="assets/js/theme.js"></script>
     <!-- Bootstrap core JavaScript-->
     <script src="vendor/jquery/jquery.min.js"></script>
     <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
 
     <!-- Core plugin JavaScript-->
     <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
 
     <!-- Custom scripts for all pages-->
     <script src="js/sb-admin-2.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
  </body>

</html>