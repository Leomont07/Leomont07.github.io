<?php

    session_start();

    include('php/conexion.php');

    $con=conexion();

    if($_SESSION['tipo'] === 'cliente'){
        echo '     
            <script> 
                alert("No puede acceder a esta página");
                window.location = "/Barbieland/public/index_cliente.php";
            </script>
            ';
            exit();
    }

    $tipo = $_GET['tipo'];

    $sucur=mysqli_query($con, "SELECT * FROM sucursal");

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
                                <h1 class="h4 text-gray-900 mb-4">Cuenta <?php echo $tipo; ?></h1>
                            </div>
                            <form action="/Barbieland/public/php/registro_admin.php" method="POST" class="user">   
                            <?php if ($tipo === 'empleado'): ?>
                                <div class="form-group">

                                    <select class="form-control" name="id_sucursal">
                                        <option value="" disabled selected hidden>Sucursal</option>
                                        <?php while($row = mysqli_fetch_array($sucur)): ?>
                                        <option value="<?= $row ['id_sucursal'] ?>" > <?= $row ['nombre'] ?> </option>
                                        <?php endwhile; ?>
                                    </select>

                                </div>
                                <?php endif; ?>
                                
                            <div class="form-group">
                            <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
                            <input type="text" class="form-control form-control-user" name="nombre" placeholder="Nombre">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-user" name="app" placeholder="Apellido Paterno">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-user" name="apm" placeholder="Apellido Materno">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <input type="date" class="form-control form-control-user" max="2005-01-01" name="fecha_nacimiento" placeholder="Fecha Nacimiento">
                            </div>
                            <div class="col-sm-6">
                                <input type="number" class="form-control form-control-user" name="telefono" placeholder="Telefono">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control form-control-user" name="correo" placeholder="Correo">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control form-control-user" name="psw" placeholder="Contraseña">
                            </div>
                        <button class="btn btn-primary btn-user btn-block" >Crear cuenta</button><br>
                        <?php if($tipo=='cliente' AND $_SESSION['tipo']=='administrador'){ ?>
                        <a class="btn btn-primary btn-user btn-block" href="clientes.php">Cancelar</a>
                        <?php } ?>
                        <?php if($tipo=='cliente' AND $_SESSION['tipo']=='empleado'){ ?>
                        <a class="btn btn-primary btn-user btn-block" href="PanelEmpleado.php">Cancelar</a>
                        <?php } ?>
                        <?php if($tipo=='empleado'){ ?>
                        <a class="btn btn-primary btn-user btn-block" href="empleados.php">Cancelar</a>
                        <?php } ?>
                        <?php if($tipo=='administrador'){ ?>
                        <a class="btn btn-primary btn-user btn-block" href="administradores.php">Cancelar</a>
                        <?php } ?>
                            </form>
                            <hr>
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







