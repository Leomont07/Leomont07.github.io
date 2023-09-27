<?php

session_start();

$user = $_SESSION['id_usuario'];

if (!isset($_SESSION['correo']) AND !isset($_SESSION['psw'])) {
  header("Location:php/login_usuario.php");
  exit();
}

include('php/conexion.php');

$con=conexion();

if (isset($_GET['id_inventario'])){
  $inventario=$_GET['id_inventario'];
} else{
  $inventario=null;
}


$sql = "SELECT p.id_producto, p.nombre, p.categoria, p.descripcion, p.marca, p.precio, i.ruta, inv.id_inventario, inv.id_sucursal, inv.cantidad, s.nombre AS sucursal
FROM producto p
INNER JOIN inventario inv ON p.id_producto = inv.id_producto
INNER JOIN imagen i ON inv.id_producto = i.id_producto
INNER JOIN sucursal s ON inv.id_sucursal = s.id_sucursal
WHERE inv.cantidad>0 AND inv.id_inventario = '$inventario'
GROUP BY p.id_producto, p.nombre, p.categoria, p.descripcion, p.marca, p.precio, i.ruta, inv.id_inventario;";

$row = mysqli_fetch_assoc(mysqli_query($con, $sql));

if(isset($_POST['add_to_cart'])){

  $id_producto = $_POST['id_producto'];
  $id_inv = $_POST['id_inventario'];
  $cantidad = $_POST['cantidad'];
  $precio = $_POST['precio'];


  $select_cart = mysqli_query($con, "SELECT * FROM carrito WHERE id_inventario = '$id_inv' AND id_usuario = '$user'") or die('query failed');


  if(mysqli_num_rows($select_cart) > 0){
          echo '     
            <script> 
                alert("El producto ya esta en el carrito");
                window.location.href = "/Barbieland/public/item.php?id_inventario='.$id_inv.'"
            </script>
            ';
            exit();
  }
  
  $stock = mysqli_query($con, "SELECT cantidad FROM inventario WHERE id_inventario = '$id_inv'") or die('query failed');
  $row = mysqli_fetch_assoc($stock);

  if($cantidad > $row['cantidad']){
    echo '     
      <script> 
          alert("Solo hay '.$row['cantidad'].' productos disponibles");
          window.location.href = "/Barbieland/public/item.php?id_inventario='.$id_inv.'"
      </script>
      ';
  } else {
    mysqli_query($con, "INSERT INTO carrito (id_usuario, id_inventario, cantidad, subtotal) VALUES('$user', '$id_inv', '$cantidad', ($precio*$cantidad))") or die('query failed');
    echo '     
            <script> 
                alert("Producto añadido");
                window.location.href = "/Barbieland/public/carrito.php"
            </script>
            ';
  }

};

?>

<!DOCTYPE html>
<html lang="en">
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

    </head>
    <body>
        <!-- Navigation-->
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
        <!-- Product section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="row gx-4 gx-lg-5 align-items-center">
                    <div class="col-md-6"><img class="card-img-top" src="<?php echo $row['ruta']; ?>" alt="<?php echo $row['nombre']; ?>" /></div>
                    <div class="col-md-6">
                        <h1 class="display-5 fw-bolder"><?php echo $row['nombre']; ?></h1>
                        <div class="fs-5 mb-5">
                            <span>$<?php echo $row['precio']; ?></span>
                            <p class="lead"><?php echo $row['sucursal']; ?></p>
                            <p class="lead"><?php echo $row['descripcion']; ?></p>
                        </div>
                        <div class="d-flex">
                          <form action="item.php" method="post" >
                            <input class="col-md-2 me-3" type="number" min="1" name="cantidad" value="1">
                            <input type="hidden" name="id_producto" value="<?php echo $row['id_producto']; ?>">
                            <input type="hidden" name="id_inventario" value="<?php echo $row['id_inventario']; ?>">
                            <input type="hidden" name="precio" value="<?php echo $row['precio']; ?>">
                            <input type="submit" name="add_to_cart" class="btn btn-outline-dark flex-shrink-0" value="Añadir al carrito">
                            
                          </form>
                        </div>

                        <hr>

                        <div class="d-flex">
                          <form action="venta.php" method="post" >
                            <input class="col-md-2 me-3" type="number" min="1" name="cantidad" value="1">
                            <input type="hidden" name="id_producto" value="<?php echo $row['id_producto']; ?>">
                            <input type="hidden" name="sucursal" value="<?php echo $row['sucursal']; ?>">
                            <input type="hidden" name="nombre" value="<?php echo $row['nombre']; ?>">
                            <input type="hidden" name="id_inventario" value="<?php echo $row['id_inventario']; ?>">
                            <input type="hidden" name="precio" value="<?php echo $row['precio']; ?>">
                            <input type="submit" name="compra_directa" class="btn btn-outline-dark flex-shrink-0" value="Comprar ahora">
                            
                          </form>
                        </div>
                     
                    </div>
                </div>
            </div>
        </section>
        <!-- Related items section-->

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
    </body>
</html>
