<?php

session_start();

include('php/conexion.php');

$con=conexion();

if (!isset($_SESSION['correo']) AND !isset($_SESSION['psw'])) {
    header("Location:index.php");
    exit();
  }

if($_SESSION['tipo'] === 'cliente'){
    echo '     
        <script> 
            alert("No puede acceder a esta página");
            window.location = "/Barbieland/public/index_cliente.php";
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


$id= $_GET['id_producto'];


if (isset($_SESSION['id_usuario'])) {
    $usuario = $_SESSION['id_usuario'];
    $sucur = "SELECT id_sucursal FROM usuario WHERE id_usuario = $usuario";
    $result = mysqli_query($con, $sucur);
    $row = mysqli_fetch_assoc($result);
    $id_sucursal = $row['id_sucursal'];
}

if($_SESSION['tipo']==='administrador'){
    $inv = "SELECT i.id_inventario, s.nombre, i.cantidad FROM inventario i
    INNER JOIN sucursal s ON i.id_sucursal = s.id_sucursal
    WHERE id_producto='$id'";
    $query = mysqli_query($con, $inv);
} elseif ($_SESSION['tipo']==='empleado'){
    $inv = "SELECT i.id_inventario, s.nombre, i.cantidad FROM inventario i
    INNER JOIN sucursal s ON i.id_sucursal = s.id_sucursal
    WHERE i.id_producto='$id' AND i.id_sucursal='$id_sucursal'";
    $query = mysqli_query($con, $inv);
    $row = mysqli_fetch_assoc($query);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Registro</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="assets/css/theme.css" rel="stylesheet"/>
  <link href="css/sb-admin-2.css" rel="stylesheet">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
  <link rel="manifest" href="assets/img/favicons/manifest.json">
  <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
  <meta name="theme-color" content="#ffffff">

</head>

<body class="bg-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-login-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Producto</h1>
                            </div>
                            <?php if ($_SESSION['tipo']==='administrador'): ?>
                            <?php while($row = mysqli_fetch_array($query)): ?>
                            <form action="/Barbieland/public/php/nuevo_inventario.php" method="POST" class="user">  
                            <h3>Sucursal <?= $row ['nombre'] ?></h3> 
                            <div class="form-group">
                            <input type="hidden" class="form-control form-control-user" name="id_inventario" value="<?= $row ['id_inventario'] ?>">
                            <input type="text" class="form-control form-control-user" name="cantidad" placeholder="Cantidad" value="<?= $row ['cantidad'] ?>">
                        </div>
                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Guardar">
                        <hr>
                            </form>
                            <?php endwhile; ?>
                            <?php endif; ?>
                            <?php if ($_SESSION['tipo']==='empleado'): ?>
                                <form action="/Barbieland/public/php/nuevo_inventario.php" method="POST" class="user">  
                            <h3>Inventario <?= $row ['nombre'] ?></h3> 
                            <div class="form-group">
                            <input type="hidden" class="form-control form-control-user" name="id_producto" value="<?= $id ?>">
                            <input type="hidden" class="form-control form-control-user" name="id_inventario" value="<?= $row ['id_inventario'] ?>">
                            <input type="text" class="form-control form-control-user" name="cantidad" min="0" placeholder="Cantidad" value="<?= $row ['cantidad'] ?>">
                        </div>
                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Guardar">
                        <hr>
                            </form>
                            <?php endif; ?>


                            <?php if ($_SESSION['tipo']==='administrador'): ?>
                                <a href="gestion.php"><button class="btn btn-primary btn-user btn-block">Cancelar</button></a> 
                            <?php endif; ?>
                            <?php if ($_SESSION['tipo']==='empleado'): ?>
                                <a href="gestion_empleados.php"><button class="btn btn-primary btn-user btn-block">Cancelar</button></a> 
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Barbienet 2023</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>