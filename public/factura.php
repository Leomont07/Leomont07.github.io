<?php

include('php/conexion.php');

$con=conexion();

session_start();

$id = $_SESSION['id_usuario'];

if (!isset($_SESSION['correo']) AND !isset($_SESSION['psw'])) {
  header("Location:php/login_usuario.php");
  exit();
}

$facturas = mysqli_query($con, "SELECT v.id_venta, GROUP_CONCAT(p.nombre SEPARATOR ', ') AS productos, v.fecha, v.id_factura
FROM venta v
INNER JOIN detalleventa d ON v.id_venta = d.id_venta
INNER JOIN inventario i ON d.id_inventario = i.id_inventario 
INNER JOIN producto p ON i.id_producto = p.id_producto
WHERE id_usuario=$id
GROUP BY v.id_venta, v.fecha, v.id_factura
ORDER BY v.id_venta DESC;");


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
    <title>Factura</title>


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

  </head>


  <body>
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
                  </svg><?php echo $_SESSION['nombre']; ?></a>
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

    <main class="main">
        <div class="py-5">
          <h1><center>Facturación</center></h1>
          <div class="container">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">No. compra</th>
                    <th scope="col">Detalle de compra</th>
                    <th scope="col">Fecha de compra</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                 $contador = 1;
                while($row = mysqli_fetch_array($facturas)): ?>
                  <tr>
                    <th scope="row"><?= $contador ?></th>
                    <td><?= $row ['productos'] ?></td>
                    <td><?= $row ['fecha'] ?></td>

                    <?php if ($row ['id_factura']<=0): ?>
                    <td>
                      <a href="facturaSelected.php?id_venta=<?= $row ['id_venta'] ?>"><button type="button" class="btn btn-outline-success">Facturar</button></a>
                    </td>
                    <?php endif; ?>

                    <?php if ($row ['id_factura']>0): ?>
                    <td>
                      <a href="ver_factura.php?id_factura=<?= $row ['id_factura'] ?>"><button type="button" class="btn btn-outline-info">Ver factura</button></a>
                    </td>
                    <?php endif; ?>

                  </tr>
                  <?php 
                $contador++;
                endwhile; ?>
                </tbody>
              </table>
            </div>
            </div>
      </main>

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
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">
  </body>

</html>