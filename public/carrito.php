<?php

include('php/conexion.php');

    $con=conexion();

session_start();


if (!isset($_SESSION['correo']) AND !isset($_SESSION['psw'])) {
  header("Location:php/login_usuario.php");
  exit();
}

$user_id = $_SESSION['id_usuario'];

$cart_query = mysqli_query($con, "SELECT c.id_carrito, p.nombre, c.cantidad, p.precio, i.cantidad AS inventario, s.nombre AS sucursal
FROM carrito c
INNER JOIN inventario i ON c.id_inventario = i.id_inventario
INNER JOIN producto p ON i.id_producto = p.id_producto
INNER JOIN sucursal s ON i.id_sucursal = s.id_sucursal
WHERE id_usuario = '$user_id'
GROUP BY c.id_carrito") or die('query failed');


if(isset($_GET['remove'])){
  $remove_id = $_GET['remove'];
  mysqli_query($con, "DELETE FROM carrito WHERE id_carrito = '$remove_id'") or die('query failed');
  header('location:carrito.php');
}

if(isset($_POST['update_cart'])){
  $nueva_cantidad = $_POST['cantidad'];
  $id_carrito = $_POST['id_carrito'];
  mysqli_query($con, "UPDATE carrito SET cantidad = '$nueva_cantidad' WHERE id_carrito = '$id_carrito'") or die('query failed');
  header('location:carrito.php');
}

$sub=0;
$grand_total = 0;
$total = 0;


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
    <title>carrito</title>


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
                </svg><span> <?php echo $_SESSION['nombre']; ?></span></a>
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

    <main id="main">
        <div class="py-5">
            <div class="container">
                <h1>
                    <center>Carrito</center>
                </h1>
                <div class="container-fluid">
                    <div class="container p-0" style="background-color: white;">
                    <form id="selected" action="venta.php" method="post" >
                        <table class="row g-0">
                            <thead class="list-group list-group-flush">
                                <tr>
                                    <th class="col-12 col-md-1 list-group-item-dark">Seleccionar</th>
                                    <th class="col-12 col-md-2 list-group-item-dark">Eliminar</th>
                                    <th class="col-12 col-md-2 list-group-item-dark">Producto</th>
                                    <th class="col-12 col-md-2 list-group-item-dark">Sucursal</th>
                                    <th class="col-12 col-md-3 list-group-item-dark">Cantidad</th>
                                    <th class="col-12 col-md-2 list-group-item-dark">Precio</th>
                                    <th class="col-12 col-md-2 list-group-item-dark">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="row g-0">
                                <?php while ($row = mysqli_fetch_array($cart_query)): ?>
                                    <tr class="list-group-item">
                                        <td class="col-12 col-md-1">
                                            
                                                <input class="form-check-input-primary mx-1" type="checkbox" name="selected[]" value="<?= $row['id_carrito']?>">
                                           
                                        </td>
                                        <td class="col-12 col-md-2">
                                            <a href="carrito.php?remove=<?= $row['id_carrito'] ?>" onclick="return confirm('¿Eliminar elemento del carrito?');">
                                                <input class="btn-primary" type="button" value="Eliminar">
                                            </a>
                                        </td>
                                        <td class="col-12 col-md-2"><?= $row['nombre'] ?></td>
                                        <td class="col-12 col-md-2"><?= $row['sucursal'] ?></td>
                                        <td class="col-12 col-md-3">

                                        <form>
                                          <input type="hidden">
                                        </form>

                                            <form id="update_cart<?= $row['id_carrito'] ?>" method="post" action="carrito.php">
                                                <input type="hidden" name="id_carrito" value="<?= $row['id_carrito'] ?>">
                                                <input class="col-12 col-md-3" type="number" min="1" max="<?= $row['inventario'] ?>" name="cantidad" value="<?= $row['cantidad'] ?>">
                                                <input type="submit" name="update_cart" value="Actualizar" class="btn-primary" >
                                            </form>
                                        </td>
                                        <td class="col-12 col-md-2">$<?= $row['precio'] ?></td>
                                        <td class="col-12 col-md-2">$<?= $subtotal = ($row['precio'] * $row['cantidad']) ?></td>
                                    </tr>
                                    <?php
                                    $sub += $subtotal;
                                    $grand_total = $sub;
                                    $total = $grand_total;
                                endwhile;
                                ?>
                                <tr class="list-group-item">
                                    <td class="col-12 col-md-19">Total de todos los productos:  </td>
                                    <td class="col-12 col-md-3">$<?= $total ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="container">
                                <input class="btn btn-primary col-12 col-md-3 mt-2 mb-2" type="submit" value="Comprar" form="selected" >
                          </form>
                        </div>
                    </div>
                </div>
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