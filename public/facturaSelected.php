<?php

include('php/conexion.php');

$con=conexion();

session_start();

$id = $_SESSION['id_usuario'];

if(isset($_GET['id_venta'])){
  $venta = $_GET['id_venta'];
}

if( mysqli_fetch_array(mysqli_query($con, "SELECT id_factura from venta WHERE id_venta='$venta'"))['id_factura'] > 0){
  echo '     
          <script> 
              alert("Ya se generó la factura");
              window.location = "/Barbieland/public/factura.php";
          </script>
          ';
}


if (!isset($_SESSION['correo']) AND !isset($_SESSION['psw'])) {
  header("Location:php/login_usuario.php");
  exit();
}

$facturas = mysqli_query($con, "SELECT p.nombre AS producto, s.nombre AS sucursal, d.cantidad, d.precio, d.subtotal, v.fecha, p.descripcion, v.id_factura AS id_factura
FROM venta v
INNER JOIN detalleventa d ON v.id_venta = d.id_venta
INNER JOIN inventario i ON i.id_inventario = d.id_inventario
INNER JOIN producto p ON i.id_producto = p.id_producto
INNER JOIN sucursal s ON s.id_sucursal = i.id_sucursal
WHERE v.id_venta=$venta
GROUP BY p.nombre, s.nombre, d.cantidad, d.precio, d.subtotal, p.descripcion, v.id_factura");

$total = mysqli_fetch_assoc(mysqli_query($con, "SELECT total FROM venta WHERE id_venta=$venta"));

$perfiles = mysqli_query($con, "SELECT * FROM perfilfactura WHERE id_usuario = $id AND estatus=1");


if(isset($_POST['delete'])){
  $factura = $_POST['id_perfilFactura'];
  $sql = "UPDATE perfilfactura SET estatus = 0 WHERE id_perfilFactura=$factura";
  $query = mysqli_query($con, $sql);
  header("Location:facturaSelected.php?id_venta=$venta");
}

if(isset($_POST['generar'])){
  $perfil = $_SESSION['perfil'];
  $verificar = $_POST['id_factura'];

    if($verificar!=null){
      echo '     
      <script> 
          alert("Ya se generó una factura");
          window.location = "/Barbieland/public/factura.php";
      </script>
      ';
    } else {

        if(empty($perfil)){
          echo '     
          <script> 
              alert("Seleccione un perfil de facturación");
              window.location = "/Barbieland/public/facturaSelected.php?id_venta='.$venta.'";
          </script>
          ';
        } else {
            $perfil = $_SESSION['perfil'];


            $generar = mysqli_query($con, "INSERT INTO factura (id_perfilFactura, fecha) 
            VALUES ('$perfil', NOW())");
    
            $sql = mysqli_query($con, "SELECT * FROM perfilfactura WHERE id_perfilFactura=$perfil");
            $row = mysqli_fetch_array($sql);
    
    
            $nombrePerfil = $row['nombrePerfil'];
            $RFC = $row['RFC'];
            $razonSocial = $row['razonSocial'];
            $regimenFiscal = $row['regimenFiscal'];
            $CP = $row['CP'];
    
            $fac= mysqli_fetch_array(mysqli_query($con, "SELECT MAX(id_factura) AS id_factura FROM factura"));
            $num = $fac['id_factura'];

            $nuevo = mysqli_query($con, "UPDATE venta SET id_factura=(SELECT MAX(id_factura) FROM factura) WHERE id_venta=$venta");
            
    
            header("Location:factura.php");
        }
        
    }
  
}

$_SESSION['perfil']=0;

if(!$_SESSION['perfil']){
  $_SESSION['perfil']=0;
}


if(isset($_POST['cambiar'])){
  $_SESSION['perfil']=0;
}

if(isset($_POST['perfiles'])){
  if ($_POST['id_perfilFactura'] == 0) {
    echo '     
    <script> 
        alert("Selecciona un perfil de facturación");
        window.location = "/Barbieland/public/facturaSelected.php?id_venta='.$venta.'";
    </script>
    ';
  } else {
      $_SESSION['perfil'] = $_POST['id_perfilFactura'];
  }
}

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
  <title>Facturación</title>


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
      <h1>
        <center>Facturación</center>
      </h1>
      <div class="container-fluid">
      
        <div class="container"><h2>Perfil de facturación</h2></div>
        <?php if($_SESSION['perfil']==0){ ?>
        <div class="container">
          <a href="perfilFormulario.php?id_venta=<?= $venta; ?>" class="btn btn-info col-12 col-md-3 mt-2 mb-2" type="button" id="añadirFiscal">Añadir perfil</a>
        </div>
        
        <div class="container">
          <div class="row">
            <div class="col">
            <form method="post" id="perfiles" action="facturaSelected.php?id_venta=<?= $venta ?>">
              <ul class="list-group">
              <?php while($row=mysqli_fetch_array($perfiles)): ?>
                <li class="list-group-item">

                  <input class="form-check-input-primary mx-1" type="checkbox" value="<?= $row ['id_perfilFactura'] ?>" name="id_perfilFactura">
                  
                  <label class="form-check-label" for="firstCheckbox"><?= $row ['nombrePerfil'] ?></label>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">RFC: <?= $row ['RFC'] ?></li>
                    <li class="list-group-item">CP: <?= $row ['CP'] ?></li>
                    <li class="list-group-item">Razón Social: <?= $row ['razonSocial'] ?></li>
                    <li class="list-group-item">Régimen fiscal: <?= $row ['regimenFiscal'] ?></li>

                    <form>
                      <input type="hidden">
                    </form>
                    
                    <form action="php/updatePerfil.php" id="mod" name="mod" method="post" >
                      <input type="hidden" name="id_perfilFactura" id="mod" value="<?= $row ['id_perfilFactura'] ?>">
                      <input type="hidden" name="id_venta" id="mod" value="<?= $venta ?>">
                      <input class="btn btn-info col-12 col-md-2 mt-2 mb-2" id="mod" name="mod" type="submit" value="Modificar">
                    </form>

                    <form method="post">
                      <input type="hidden" name="id_perfilFactura" value="<?= $row ['id_perfilFactura'] ?>">
                      <input type="hidden" name="id_venta" value="<?= $venta ?>">
                      <input class="btn btn-dark col-12 col-md-2 mt-2 mb-2" type="submit" name="delete" value="Eliminar">
                    </form>
                    
                  </ul>
                  
                </li>
                <?php endwhile; ?>
                <?php if(mysqli_num_rows($perfiles)>0): ?>
                <input type="submit" name="perfiles" value="Guardar" class="btn btn-info col-12 col-md-2 mt-2 mb-2">
                <?php endif; ?>
              
              </ul>
              </form>
            </div>
          </div>
        </div>
        
        <?php } else { $row = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM perfilfactura WHERE id_perfilFactura=$_SESSION[perfil]")); ?>

        <div class="container">
        <div class="row">
          <div class="col">
            <ul class="list-group">

          <li class="list-group-item">

              <label class="form-check-label" for="firstCheckbox"><?= $row ['nombrePerfil'] ?></label>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">RFC: <?= $row ['RFC'] ?></li>
              <li class="list-group-item">CP: <?= $row ['CP'] ?></li>
              <li class="list-group-item">Razón Social: <?= $row ['razonSocial'] ?></li>
              <li class="list-group-item">Regimen fiscal: <?= $row ['regimenFiscal'] ?></li>
            </ul>
            </li>

            </ul>
            </div>
          </div>

              <form method="post">
                <input type="hidden" name="id_direccion" value="<?= $row ['id_perfilFactura'] ?>">
                <input type="submit" name="cambiar" value="Cambiar" class="btn btn-info col-12 col-md-2 mt-2 mb-2">
              </form>
        </div>

            <?php } ?>
        <br>
        <br>
        
        
        <div class="container p-0" style="background-color: white;">
          <div class="row g-0">
              <div class="col-12 col-md-3">
                  <ul class="list-group list-group-flush">
                      <li class="list-group-item list-group-item-info fw-bolder">Producto</li>
                      <?php while($row=mysqli_fetch_array($facturas)): ?>
                        <input type="hidden" name="id_factura" value="<?= $row ['id_factura']?>">
                      <li class="list-group-item "><?= $row ['producto'] ?></li>
                      <?php endwhile; ?>
                  </ul>
              </div>
              <div class="col-12 col-md-2">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item list-group-item-info fw-bolder">Sucursal</li>
                    <?php mysqli_data_seek($facturas, 0); while($row=mysqli_fetch_array($facturas)): ?>
                      <li class="list-group-item "><?= $row ['sucursal'] ?></li>
                      <?php endwhile; ?>
                </ul>
            </div>
              <div class="col-12 col-md-3">
                  <ul class="list-group list-group-flush">
                      <li class="list-group-item list-group-item-info fw-bolder">Cantidad</li>
                      <?php mysqli_data_seek($facturas, 0); while($row=mysqli_fetch_array($facturas)): ?>
                      <li class="list-group-item "><?= $row ['cantidad'] ?></li>
                      <?php endwhile; ?>
                  </ul>
              </div>
              <div class="col-12 col-md-2">
                  <ul class="list-group list-group-flush">
                      <li class="list-group-item list-group-item-info fw-bolder">Precio</li>
                      <?php mysqli_data_seek($facturas, 0); while($row=mysqli_fetch_array($facturas)): ?>
                      <li class="list-group-item ">$<?= $row ['precio'] ?></li>
                      <?php endwhile; ?>
                  </ul>
              </div>
              <div class="col-12 col-md-2">
                  <ul class="list-group list-group-flush">
                      <li class="list-group-item list-group-item-info fw-bolder">Subtotal</li>
                      <?php mysqli_data_seek($facturas, 0); while($row=mysqli_fetch_array($facturas)): ?>
                      <li class="list-group-item ">$<?= $row ['subtotal'] ?></li>
                      <?php endwhile; ?>
                  </ul>
              </div>
              <div class="col-12 col-md-9">
                  <ul class="list-group list-group-flush align-items-md-end list-group-item-info">
                      <li class="list-group-item fw-bolder list-group-item-info">Total</li>
                  </ul>
              </div>
              <div class="col-12 col-md-3 justify-content-end">
                  <ul class="list-group list-group-flush align-items-md-center">
                      <li class="list-group-item">$<?php echo $total['total']; ?></li>
                  </ul>
              </div>
          </div>
      </div>
      <?php  if(isset($_POST['perfiles'])): ?>
      <form action="facturaSelected.php?id_venta=<?= $venta; ?>" method="post" name="generar">
    <div class="container">
      <input class="btn btn-info col-12 col-md-3 mt-2 mb-2" type="submit" value="Generar Factura" name="generar" >
    </div>
    </form>
    <?php endif; ?>   
      </div><br><br>

      <?php  if(!isset($_POST['perfiles'])): ?>

      <h5 class="btn-danger" >
          <center>Seleccione y guarde un perfil de facturación para</br></center>
          <center>continuar con la factura</center>
      </h5>

      <?php endif; ?>

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