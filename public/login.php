<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

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

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bienvenido!</h1>
                                        <h1 class="h4 text-gray-900 mb-4">Inicia Sesión</h1>
                                        <br>
                                    </div>
                                    <form action="/Barbieland/public/php/login_usuario.php" method="POST" class="user">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" placeholder="Correo Electronico" name="correo">
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" placeholder="Contraseña" name="psw">
                                        </div>
                                        <br>
                                        <button class="btn btn-primary btn-user btn-block">Iniciar Sesión</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="usuarioFormulario2.php?tipo=cliente">Crear Cuenta</a>
                                    </div>
                                </div>
                            </div>
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