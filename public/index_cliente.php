<?php
session_start();


if (!isset($_SESSION['correo']) AND !isset($_SESSION['psw'])) {
  header("Location:index.php");
  exit();
}

if($_SESSION['tipo'] === 'empleado'){
  echo '     
      <script> 
          alert("No puede acceder a esta página");
          window.location = "/Barbieland/public/PanelEmpleado.php";
      </script>
      ';
      exit();
} elseif($_SESSION['tipo'] === 'administrador') {
  echo '     
  <script> 
      alert("No puede acceder a esta página");
      window.location = "/Barbieland/public/PanelAdministrador.php";
  </script>
  ';
  exit();
}

include('php/conexion.php');

    $con=conexion();

    $sql = "SELECT cuenta, id_producto, nombre, categoria, descripcion, marca, precio, ruta, id_inventario, cantidad
    FROM (
        SELECT COUNT(p.id_producto) AS cuenta, 
               p.id_producto, 
               p.nombre, 
               p.categoria, 
               p.descripcion, 
               p.marca, 
               p.precio, 
               i.ruta, 
               inv.id_inventario,
               inv.cantidad,
               ROW_NUMBER() OVER (PARTITION BY p.id_producto ORDER BY inv.id_inventario) AS rn
        FROM producto p
        INNER JOIN imagen i ON p.id_producto = i.id_producto
        INNER JOIN inventario inv ON p.id_producto = inv.id_producto
        INNER JOIN detalleventa dv ON inv.id_inventario = dv.id_inventario
        GROUP BY p.id_producto, p.nombre, p.categoria, p.descripcion, p.marca, p.precio, i.ruta, inv.id_inventario, inv.cantidad
    ) AS ranked
    WHERE rn = 1 AND cantidad > 0
    ORDER BY cuenta DESC
    LIMIT 4;";

    $query = mysqli_query($con, $sql);

    $segundo = "SELECT id_inventario, precio, nombre, ruta, cantidad
    FROM (
        SELECT inv.id_inventario,
               p.precio,
               p.nombre,
               i.ruta,
               inv.cantidad,
               ROW_NUMBER() OVER (PARTITION BY p.nombre ORDER BY inv.id_inventario DESC) AS rn
        FROM inventario inv
        INNER JOIN producto p ON inv.id_producto = p.id_producto
        INNER JOIN imagen i ON p.id_producto = i.id_producto
    ) AS ranked
    WHERE rn = 1 AND cantidad > 0
    ORDER BY id_inventario DESC
    LIMIT 4;";

    $nuevos = mysqli_query($con, $segundo);

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
    <title>Inicio</title>


     <!-- Custom fonts for this template-->
     <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
     <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
     <!-- Custom styles for this template-->
     <link href="css/sb-admin-2.css" rel="stylesheet">
     <link href="assets/css/theme.css" rel="stylesheet">
     <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
     <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
     <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
     <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
     <link rel="manifest" href="assets/img/favicons/manifest.json">
     <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
     <meta name="theme-color" content="#ffffff">

  </head>


  <body>

  <audio autoplay loop>
  <source src="SONGS/Aqua — Barbie Girl [Letra en Español.mp3" type="audio/mpeg">
  </audio>

    <header class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container">
              <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
              <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item px-2"><a class="nav-link fw-medium active" aria-current="page" href="index_cliente.php">Inicio</a></li>
                  <li class="nav-item px-2"><a class="nav-link fw-medium" href="shop_cliente.php">Catálogo</a></li>
                </ul>
                <form class="d-flex nav-item dropdown no-arrow"><a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg class="feather feather-user me-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                      <circle cx="12" cy="7" r="4"></circle>
                    </svg> <span> <?php echo $_SESSION['nombre']; ?></span></a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="cuenta.php">
                                    <i class="fas fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cuenta
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="carrito.php">
                                    <i class="fas fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Carrito
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="factura.php">
                                    <i class="fas fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Factura
                                </a>
                                <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                  <i class="fas fa-sm fa-fw mr-2 text-gray-400"></i>
                                  Cerrar Sesión
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
      
      <section class="py-11 bg-light-gradient border-bottom border-white border-5">
        <div class="bg-holder overlay overlay-light" style="background-image:url(assets/img/gallery/back.jpeg);background-size:cover;">
        </div>
        <!--/.bg-holder-->

        <div class="container">
          <div class="row flex-center">
            <div class="col-12 mb-10">
              <div class="d-flex align-items-center flex-column">
                <h1 class="fw-normal"> Tu puedes ser lo que quieras ser</h1>
                <img  class="card-img-bottom" src="assets/img/barbie.png" alt="logo">
                <div class="col-12 d-flex justify-content-center mt-5"> <a class="btn btn-lg btn-dark" href="shop_cliente.php">Compra Ya!</a></div>
              </div>
            </div>
          </div>
        </div>
      </section>


      <!-- ============================================-->




      <!-- ============================================-->
      <!-- <section> begin ============================-->
      <section class="py-0">
        <div class="container">
          <div class="row h-100">
            <div class="col-lg-7 mx-auto text-center mt-7 mb-5">
              <h5 class="fw-bold fs-3 fs-lg-5 lh-sm">Mas vendidos</h5>
            </div>
            <div class="row">
                      <?php mysqli_data_seek($query, 0); while ($row = mysqli_fetch_array($query)): ?>
                          <div class="col-md-3">
                              <div class="card-body">
                              <form class="card" method="post">
                              <a href="item.php?id_inventario=<?= $row['id_inventario'] ?>"><img src="<?= $row['ruta'] ?>" class="card-img-top"></a>
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
                      <?php endwhile; ?>
                    </div>
            <div class="col-12 d-flex justify-content-center mt-5"> <a class="btn btn-lg btn-dark" href="shop_cliente.php">Ver todos </a></div>
          </div>
        </div>
        <!-- end of .container-->

      </section><hr>
      <!-- <section> close ============================-->
      <!-- ============================================-->

      <!-- ============================================-->


      <section>
        <div class="container">
          <div class="row h-100">
            <div class="col-lg-7 mx-auto text-center mb-6">
              <h4 class="fs-3 fs-lg-5 lh-sm mb-3">Nuevos productos</h4>
            </div>
            <div class="col-12">
              <div class="row h-100 align-items-center g-2">
              <?php mysqli_data_seek($nuevos, 0); while ($row = mysqli_fetch_array($nuevos)): ?>
                      <div class="col-sm-6 col-md-3 mb-3 mb-md-0 h-100">
                      <a href="item.php?id_inventario=<?= $row['id_inventario'] ?>">
                        <div class="card card-span h-100 text-white">
                          <img class="card-img h-30" src="<?= $row['ruta'] ?>" alt="..." />
                          <div class="card-img-overlay bg-dark-gradient d-flex flex-column-reverse">
                            <h6 class="text-primary">$<?= $row['precio'] ?></h6>
                            <h4 class="text-light"><?= $row['nombre'] ?></h4>
                          </div><a class="stretched-link" href="item.php?id_inventario=<?= $row['id_inventario'] ?>"></a>
                        </div>
                        </a>
                      </div>
                <?php endwhile; ?>
              </div>
            </div>
          </div>
        </div>
      </section>






      <!-- ============================================-->
      <!-- <section> begin ============================-->
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
      <!-- <section> close ============================-->
      <!-- ============================================-->


    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cerrar sesión?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecciona "Cerrar Sesión" abajo si deseas cerrar tu sesión.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="/Barbieland/public/php/logout.php?logout=1">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>


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