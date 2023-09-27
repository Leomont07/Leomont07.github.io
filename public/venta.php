<?php

include('php/conexion.php');

$con=conexion();

session_start();

$id = $_SESSION['id_usuario'];


if (!isset($_SESSION['correo']) AND !isset($_SESSION['psw'])) {
  header("Location:php/login_usuario.php");
  exit();
}


$direcciones = mysqli_query($con, "SELECT * FROM direccionenvio WHERE id_usuario=$_SESSION[id_usuario] AND estatus=1");



if(isset($_POST['delete'])){
  $id_direccion = $_POST['id_direccion'];
  mysqli_query($con, "UPDATE direccionenvio SET estatus = 0 WHERE id_direccion = '$id_direccion'") or die('query failed');
  header('location:venta.php');
}

if(isset($_POST['cambiar'])){
  $_SESSION['dir']=0;
}

$_SESSION['dir']=0;

if(isset($_POST['direcciones'])){
  if ($_POST['dir'] == null) {
    echo '     
    <script> 
        alert("Selecciona una dirección de envío");
        window.location = "/Barbieland/public/venta.php";
    </script>
    ';
  } else {
      $_SESSION['dir'] = $_POST['dir'];
  }
  

}

//COMPRAS

if (isset($_POST['compra_directa'])) {
  $_SESSION['cart']=array();


  $nombre = $_POST['nombre'];
  $precio = $_POST['precio'];
  $cantidad = $_POST['cantidad'];
  $subtotal = $_POST['cantidad']*$_POST['precio'];
  $inventario = $_POST['id_inventario'];
  $sucursal = $_POST['sucursal'];
  $iva = $subtotal*0.16;
  $total = $subtotal + $iva;

  $stock = mysqli_query($con, "SELECT cantidad FROM inventario WHERE id_inventario = '$inventario'") or die('query failed');
  $row = mysqli_fetch_assoc($stock);

  if($cantidad > $row['cantidad']){
    echo '     
      <script> 
          alert("Solo hay '.$row['cantidad'].' productos disponibles");
          window.location.href = "/Barbieland/public/item.php?id_inventario='.$inventario.'"
      </script>
      ';
  } else {

    $producto = array(
      "nombre" => $nombre,
      "precio" => $precio,
      "cantidad" => $cantidad,
      "subtotal" => $subtotal,
      "iva" => $iva,
      "total" => $total,
      "inventario" => $inventario,
      "sucursal" => $sucursal
   );
 
   $found = false;
   foreach ($_SESSION['cart'] as $key => $item) {
     if ($item['nombre'] === $nombre) {
       $found = true;
       break;
     }
   }
 
   if (!$found) {
     array_push($_SESSION['cart'], $producto);

     if($_SESSION['cart']==NULL){
      echo '     
      <script> 
          alert("Seleccione un producto");
          window.location = "/Barbieland/public/shop_cliente.php";
      </script>
      ';
     }

   } 

  }

} elseif( isset($_POST['selected']) ){

    $_SESSION['cart']=array();

    foreach($_POST['selected'] as $carrito){
      $query = mysqli_query($con, "SELECT c.id_carrito, c.id_inventario, p.nombre, c.cantidad, p.precio, s.nombre AS sucursal
      FROM carrito c
      INNER JOIN inventario i ON c.id_inventario = i.id_inventario
      INNER JOIN producto p ON i.id_producto = p.id_producto
      INNER JOIN sucursal s ON i.id_sucursal = s.id_sucursal
      WHERE c.id_carrito = '$carrito'
      GROUP BY c.id_carrito");

      $row = mysqli_fetch_array($query);
      $nombre = $row['nombre'];
      $precio = $row['precio'];
      $cantidad = $row['cantidad'];
      $inventario = $row['id_inventario'];
      $carrito = $row['id_carrito'];
      $sucursal = $row['sucursal'];
   
      $producto = array(
        "nombre" => $nombre,
        "precio" => $precio,
        "cantidad" => $cantidad,
        "inventario" => $inventario,
        "carrito" => $carrito,
        "sucursal" => $sucursal
      );
    
      $found = false;
      foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['inventario'] === $nombre) {
          $found = true;
          break;
        }
      }
  
      if (!$found) {
        array_push($_SESSION['cart'], $producto);
      }
  }
} 

if(!$_SESSION['cart']){
  echo '     
  <script> 
      alert("Seleccione un producto");
      window.location = "/Barbieland/public/carrito.php";
  </script>
  ';
}

if(isset($_POST['cancelar'])){
  $_SESSION['cart']=NULL;
  echo '     
  <script> 
      alert("La compra fue cancelada");
      window.location = "/Barbieland/public/index_cliente.php";
  </script>
  ';
}

$sub=0;
$iva = 0;
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
    <title>Proceso de compra</title>


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
            <h1>
                <center>Pago</center>
            </h1>
            <div class="container-fluid">
                <div class="container pt-3"><h2>Dirección de envio</h2></div>
                <?php if($_SESSION['dir']==0){ ?>
                <div class="container">
                    <a href="direccionFormulario2.php?id_usuario=<?php echo $id; ?>" class="btn btn-primary col-12 col-md-3 mt-2 mb-2" type="button">Añadir dirección</a>
                </div>

                <form method="post" id="direcciones" action="venta.php">
                <div class="container" style="background-color: white;">
                <?php while($row=mysqli_fetch_array($direcciones)): ?>
                    <div class="row">
                        
                        <div class="form-check">
                          <br>
                            <input class="form-check-input-primary mx-1" type="checkbox" name="dir" value="<?= $row ['id_direccion'] ?>" >
                          </div>

                        <div class="col-12 col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Calle: <?= $row ['calle'] ?></li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Colonia: <?= $row ['colonia'] ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">No.ext: <?= $row ['numero_exterior'] ?></li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">CP: <?= $row ['cp'] ?></li>
                            </ul>
                        </div>
                    </div>

                    <form method="post">
                      <input type="hidden" name="id_direccion" value="<?= $row ['id_direccion'] ?>">
                      <input type="submit" name="delete" value="Eliminar" class="btn btn-dark col-12 col-md-2 mt-2 mb-2">
                      <a href="php/updateDireccion.php?id_direccion=<?= $row ['id_direccion'] ?>" class="btn btn-primary col-12 col-md-2 mt-2 mb-2" type="button">Modificar</a>
                    </form>
                    <hr>
                    
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($direcciones)>0){ ?>
                      <input type="submit" name="direcciones" value="Guardar" class="btn btn-primary col-12 col-md-3 mt-2 mb-2">
                    <?php } ?>
                    
                </div>
                </form>
                <?php } else { $row = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM direccionenvio WHERE id_direccion=$_SESSION[dir]")); ?>
                  <div class="container" style="background-color: white;">
                    <div class="row">

                        <div class="col-12 col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Calle: <?= $row ['calle'] ?></li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Colonia: <?= $row ['colonia'] ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">No.ext: <?= $row ['numero_exterior'] ?></li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">CP: <?= $row ['cp'] ?></li>
                            </ul>
                        </div>
                    </div>

                    <form method="post">
                      <input type="hidden" name="id_direccion" value="<?= $row ['id_direccion'] ?>">
                      <input type="submit" name="cambiar" value="Cambiar" class="btn btn-dark col-12 col-md-2 mt-2 mb-2">
                    </form>

                </div>
                <?php } ?>
            </div>
        </div>


        <div class="container p-0" style="background-color: white;">
    <div class="row g-0">

            <div class="col-12 col-md-3">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item list-group-item-dark fw-bolder">Producto</li>
                    <?php foreach ($_SESSION['cart'] as $producto) : ?>
                    <li class="list-group-item"><?php echo $producto['nombre']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-12 col-md-3">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item list-group-item-dark fw-bolder">Sucursal</li>
                    <?php foreach ($_SESSION['cart'] as $producto) : ?>
                    <li class="list-group-item"><?php echo $producto['sucursal']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-12 col-md-2">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item list-group-item-dark fw-bolder">Cantidad</li>
                    <?php foreach ($_SESSION['cart'] as $producto) : ?>
                    <li class="list-group-item"><?php echo $producto['cantidad']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-12 col-md-2">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item list-group-item-dark fw-bolder">Precio</li>
                    <?php foreach ($_SESSION['cart'] as $producto) : ?>
                    <li class="list-group-item">$<?php echo $producto['precio']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-12 col-md-2">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item list-group-item-dark fw-bolder">Subtotal</li>
                    <?php foreach ($_SESSION['cart'] as $producto) : ?>
                    <li class="list-group-item">$<?php echo $subtotal = ($producto['precio'] * $producto['cantidad']); ?></li>
                    <?php 
                    $sub += $subtotal;
                    $total = $sub;
                  endforeach; ?>
                </ul>
            </div>
            <div class="col-12 col-md-10">
                <ul class="list-group list-group-flush align-items-md-end list-group-item-dark">
                    <li class="list-group-item fw-bolder list-group-item-dark">Total</li>
                </ul>
            </div>
            <div class="col-12 col-md-2 justify-content-end">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">$<?php echo $total; ?></li>
                </ul>
            </div> 
           
    </div>
</div>

    <div class="container" >
    <form method="post" id="cancelar" >
      <input type="submit" name="cancelar" value="Cancelar compra" class="btn btn-primary col-12 col-md-2 mt-2 mb-2">
    </form>
    </div>
    <br><hr>


  <?php  if(isset($_POST['direcciones'])): ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=AfMA7stcHbKArILFLrxdkWjEVEpKtyr3y0qzEyU9bjLfpef_Vj0D3I5hD9rkWMn00fglxh0sRFheT84w&currency=MXN"></script>
<div class="container" id="paypal-button-container" ></div>

<script>
    paypal.Buttons({
        style:{
            color:'blue',
            shape:'pill',
            label:'pay',
        },

        createOrder: function(data,actions){
            return actions.order.create({
                purchase_units:[{
                    amount:{
                        value: <?php echo $total; ?>
                    }
                }]
            })
        },

        onApprove: function (data, actions) {
          actions.order.capture().then(function (details) {
              // Datos de la compra
              const compra = {
                  inventario: <?php echo json_encode(array_column($_SESSION['cart'], 'inventario')); ?>,
                  cantidad: <?php echo json_encode(array_column($_SESSION['cart'], 'cantidad')); ?>,
                  carrito: <?php echo json_encode(array_column($_SESSION['cart'], 'carrito')); ?>,
                  precio: <?php echo json_encode(array_column($_SESSION['cart'], 'precio')); ?>
              };

              // Crear un campo oculto con los datos de la compra en formato JSON
              const compraInput = document.createElement('input');
              compraInput.type = 'hidden';
              compraInput.name = 'compra';
              compraInput.value = JSON.stringify(compra);

              // Crear un formulario y agregar el campo oculto
              const form = document.createElement('form');
              form.method = 'POST';
              form.action = 'php/guardarcompra.php';
              form.appendChild(compraInput);

              // Agregar el formulario al cuerpo del documento y enviarlo
              document.body.appendChild(form);
              form.submit();
          });
      },


        onCancel: function(data){
            alert("Pago cancelado");
            console.log(data);
        }

    }).render('#paypal-button-container');
    
</script>
  

  <?php endif; ?>

  <?php  if(!isset($_POST['direcciones'])): ?>

      <h5 class="btn-primary" >
          <center>Seleccione y guarde una dirección para</br></center>
          <center>continuar con la compra</center>
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