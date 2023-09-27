<?php

include('conexion.php');

session_start();

$con=conexion();

$id = $_POST['id_perfilFactura'];
$venta = $_POST['id_venta'];

$sql = "SELECT * FROM perfilFactura WHERE id_perfilFactura='$id'";

$query = mysqli_query($con, $sql);

$row = mysqli_fetch_array($query);

if(isset($_POST['guardar'])){
    $nombre = $_POST['nombrePerfil'];
    $id = $_POST['id_perfilFactura'];
    $id_venta = $_POST['id_venta'] ;
    $rfc = $_POST['rfc'];
    $razon = $_POST['razon'];
    $regimen = $_POST['regimen'];
    $cp = $_POST['cp'];
    mysqli_query($con, "UPDATE perfilFactura 
    SET 
    nombrePerfil = '$nombre', 
    RFC = '$rfc', 
    razonSocial = '$razon', 
    regimenFiscal = '$regimen',
    CP = $cp
    WHERE id_perfilFactura = $id") or die('query failed');
    header('location:../facturaSelected.php?id_venta='.$id_venta.'');
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

    <title>Direccion</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="../assets/css/theme.css" rel="stylesheet"/>
  <link href="../css/sb-admin-2.css" rel="stylesheet">
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
                                <h1 class="h4 text-gray-900 mb-4">Perfil</h1>
                            </div>
                            <form method="POST" class="row g-3 p-4 pt-0">
                                <input type="hidden" class="form-control" name="id_perfilFactura" value="<?= $row ['id_perfilFactura'] ?>">
                                <input type="hidden" class="form-control" name="id_venta" value="<?php echo $venta; ?>">
                                <div class="col-12 mt-4">
                                    <label for="inputNombrePerfil" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombrePerfil" placeholder="Nombre" value="<?= $row ['nombrePerfil'] ?>" >
                                </div>
                                <div class="col-6 mt-4">
                                    <label for="inputNombrePerfil" class="form-label">RFC</label>
                                    <input type="text" class="form-control" minlength="12" maxlength="12" name="rfc" placeholder="RFC" value="<?= $row ['RFC'] ?>" >
                                </div>
                                <div class="col-6 mt-4">
                                <label for="inputRFC" class="form-label">Razon Social</label>
                                <input type="text" class="form-control" name="razon" placeholder="Razón Social" value="<?= $row ['razonSocial'] ?>" >
                                </div>
                                <div class="col-6 mt-4">
                                <label for="razonSocial" class="form-label">Régimen Fiscal</label>
                                <input type="text" class="form-control" name="regimen" placeholder="Régimen Fiscal" value="<?= $row ['regimenFiscal'] ?>" >
                                </div>
                                <div class="col-6 mt-4">
                                <label for="razonSocial" class="form-label">CP</label>
                                <input type="text" class="form-control" name="cp" placeholder="C.P" value="<?= $row ['CP'] ?>" >
                                </div>
                                <button type="submit" name="guardar" class="btn btn-primary col-12 col-md-6">Guardar</button>
                                <a href="../facturaSelected.php?id_venta=<?= $venta; ?>" class="btn btn-danger col-12 col-md-6">Cerrar</a>
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







