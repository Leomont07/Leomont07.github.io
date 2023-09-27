<?php
    session_start();
    include('php/conexion.php');

    $con=conexion();

    if (!isset($_SESSION['correo']) AND !isset($_SESSION['psw'])) {
        echo '     
            <script> 
                alert("Inicia sesión para acceder");
                window.location = "/Barbieland/public/index.php";
            </script>
            ';
      }

    if($_SESSION['tipo'] === 'cliente'){
        echo '     
            <script> 
                alert("No puede acceder a esta página");
                window.location = "/Barbieland/public/index_cliente.php";
            </script>
            ';
            exit();
    } elseif($_SESSION['tipo'] === 'empleado') {
        echo '     
        <script> 
            alert("No puede acceder a esta página");
            window.location = "/Barbieland/public/PanelEmpleado.php";
        </script>
        ';
        exit();
    } elseif(!$_SESSION['id_usuario']){
        echo '     
        <script> 
            alert("No puede acceder a esta página");
            window.location = "/Barbieland/public/PanelEmpleado.php";
        </script>
        ';
    }


    if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {

        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];

        if(empty($fecha_inicio) || empty($fecha_fin)){
            echo '     
            <script> 
                alert("Ingrese una fecha válida");
                window.location = "/Barbieland/public/PanelAdmin.php";
            </script>
            ';
        } else {

            if($fecha_inicio < $fecha_fin){
                $reporte1 = mysqli_query($con, "SELECT v.id_venta, GROUP_CONCAT(p.nombre SEPARATOR ', ') AS productos, v.total, v.fecha, v.id_factura
                FROM venta v
                INNER JOIN detalleventa d ON v.id_venta = d.id_venta
                INNER JOIN inventario i ON d.id_inventario = i.id_inventario
                INNER JOIN producto p ON i.id_producto = p.id_producto
                WHERE v.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
                GROUP BY v.id_venta, v.fecha, v.id_factura");

                $final = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(subtotal) AS final FROM detalleventa dv
                INNER JOIN venta v ON dv.id_venta = v.id_venta
                WHERE v.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'"));

            } else {
                echo '     
                <script> 
                    alert("Seleccione un rango de fechas válido");
                    window.location = "/Barbieland/public/PanelAdmin.php";
                </script>
                ';
            }
        
        }

    } else {
        $reporte1 = mysqli_query($con, "SELECT v.id_venta, GROUP_CONCAT(p.nombre SEPARATOR ', ') AS productos, v.total, v.fecha, v.id_factura, SUM(v.total) AS final
        FROM venta v
        INNER JOIN detalleventa d ON v.id_venta = d.id_venta
        INNER JOIN inventario i ON d.id_inventario = i.id_inventario
        INNER JOIN producto p ON i.id_producto = p.id_producto
        GROUP BY v.id_venta, v.fecha, v.id_factura");

        $final = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(subtotal) AS final FROM detalleventa dv
        INNER JOIN venta v ON dv.id_venta = v.id_venta"));
    }

if(isset($_POST['sucursal'])){

    $sucursal = $_POST['sucursal'];


        
        if($sucursal > 0){

            $reporte2 = mysqli_query($con, "SELECT p.nombre AS producto, p.categoria, s.nombre, i.cantidad
            FROM producto p
            INNER JOIN inventario i ON p.id_producto = i.id_producto
            INNER JOIN sucursal s ON i.id_sucursal = s.id_sucursal
            WHERE s.id_sucursal = '$sucursal'
            GROUP BY p.nombre, p.categoria, s.nombre, i.cantidad");

        } else {
            
            $reporte2 = mysqli_query($con, "SELECT p.nombre AS producto, p.categoria, s.nombre, i.cantidad
            FROM producto p
            INNER JOIN inventario i ON p.id_producto = i.id_producto
            INNER JOIN sucursal s ON i.id_sucursal = s.id_sucursal
            GROUP BY p.nombre, p.categoria, s.nombre, i.cantidad");
        }
        
    

} else {

    $reporte2 = mysqli_query($con, "SELECT p.nombre AS producto, p.categoria, s.nombre, i.cantidad
    FROM producto p
    INNER JOIN inventario i ON p.id_producto = i.id_producto
    INNER JOIN sucursal s ON i.id_sucursal = s.id_sucursal
    GROUP BY p.nombre, p.categoria, s.nombre, i.cantidad");
}

if(isset($_POST['producto'])){
    $producto = $_POST['producto'] ;
    
    if($producto>0){

        $selected = "SELECT COUNT(dv.id_detalleventa) AS total 
        FROM venta v
        INNER JOIN detalleventa dv ON v.id_venta=dv.id_venta
        INNER JOIN inventario i ON dv.id_inventario = i.id_inventario
        WHERE i.id_producto='$producto'";
    
        $total = mysqli_fetch_array(mysqli_query($con, $selected));
    
        $reporte3 = mysqli_query($con, "SELECT GROUP_CONCAT(p.nombre SEPARATOR ', ') AS productos, p.categoria, v.fecha
        FROM venta v
        INNER JOIN detalleventa d ON v.id_venta = d.id_venta
        INNER JOIN inventario i ON d.id_inventario = i.id_inventario
        INNER JOIN producto p ON i.id_producto = p.id_producto
        WHERE p.id_producto = '$producto'
        GROUP BY v.id_venta, v.fecha, p.categoria");
    

    } else {
        $selected = "SELECT COUNT(id_detalleventa) AS total FROM detalleventa";
        $total = mysqli_fetch_array(mysqli_query($con, $selected));

        $reporte3 = mysqli_query($con, "SELECT GROUP_CONCAT(p.nombre SEPARATOR ', ') AS productos, p.categoria, v.fecha
        FROM venta v
        INNER JOIN detalleventa d ON v.id_venta = d.id_venta
        INNER JOIN inventario i ON d.id_inventario = i.id_inventario
        INNER JOIN producto p ON i.id_producto = p.id_producto
        GROUP BY v.id_venta, v.fecha, p.categoria");
    }

} else {
    
    $selected = "SELECT COUNT(id_detalleventa) AS total FROM detalleventa";
    $total = mysqli_fetch_array(mysqli_query($con, $selected));

    $reporte3 = mysqli_query($con, "SELECT GROUP_CONCAT(p.nombre SEPARATOR ', ') AS productos, p.categoria, v.fecha
    FROM venta v
    INNER JOIN detalleventa d ON v.id_venta = d.id_venta
    INNER JOIN inventario i ON d.id_inventario = i.id_inventario
    INNER JOIN producto p ON i.id_producto = p.id_producto
    GROUP BY v.id_venta, v.fecha, p.categoria");
}


$productos = mysqli_query($con, "SELECT nombre, id_producto 
FROM producto") ; 

$sucursales = mysqli_query($con, "SELECT * FROM sucursal");

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Adim</title>
    
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

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="PanelAdmin.php">
                <div class="sidebar-brand-text mx-3">Panel <sup>Admin</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="PanelAdmin.php">
                    <i class="fas fa-fw"></i>
                    <span>Reportes</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Gestión
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw"></i>
                    <span>Usuarios</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="clientes.php">Clientes</a>
                        <a class="collapse-item" href="empleados.php">Empleados</a>
                        <a class="collapse-item" href="administradores.php">Administradores</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            
            <li class="nav-item">
                <a class="nav-link collapsed" href="gestion.php">
                    <i class="fas fa-fw"></i>
                    <span>Productos</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="gestionSucursales.php">
                    <i class="fas fa-fw"></i>
                    <span>Sucursales</span></a>
            </li>
            <!-- Divider -->


            <!-- Heading -->

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->


        
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['nombre']; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="perfilAdmin.php">
                                    <i class=" fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cuenta
                                </a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class=" fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Content Row -->
                    <div class="row" style="align-content: center;">

                    <!-- Reportes  -->   

                    <div class="col-md-4">
                        <select class="form-control" id="reportDropdown3">
                            <option value="tabla1">Ventas por fecha</option>
                            <option value="tabla2">Inventario</option>
                            <option value="tabla3">Ventas por producto</option>
                        </select>
                    </div>

                    <div class="col-md-12 mt-4" id="tabla1">
                    
                        <form method="post">
                            <label for="fecha_inicio">Fecha de inicio:</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio">
                            <label for="fecha_fin">Fecha de fin:</label>
                            <input type="date" id="fecha_fin" name="fecha_fin">
                            <input type="submit" class="btn-primary" value="Filtrar">
                        </form> <br>


                        <?php if(!isset($fecha_inicio) AND !isset($fecha_fin)): ?>
                            <form action="ver_reporte.php" id="reporte1" method="post" >
                            <input class="btn-primary" type="submit" name="reporte1"value="Generar Reporte" >
                            </form><br>
                        <?php endif; ?>

                        <?php if(isset($fecha_inicio) AND isset($fecha_fin)): ?>
                            <form action="ver_reporte.php" id="reporte1" method="post" >
                            <input type="hidden" name="fecha_inicio" value="<?= $fecha_inicio ?>" >
                            <input type="hidden" name="fecha_fin" value="<?= $fecha_fin ?>" >
                            <input class="btn-primary" type="submit" name="reporte1"value="Generar Reporte" >
                        </form><br>
                            <?php endif; ?>

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Venta</th>
                                <th scope="col">Productos</th>
                                <th scope="col">Total ($ <?php echo $final['final']  ?>)</th>
                                <th scope="col">Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $contador = 1;
                            mysqli_data_seek($reporte1, 0);
                            while($row = mysqli_fetch_array($reporte1)): ?>
                            <tr>
                                <th scope="row"><?= $contador ?></th>
                                <td><?= $row ['productos'] ?></td>
                                <td>$<?= $row ['total'] ?></td>
                                <td><?= $row ['fecha'] ?></td>
                            </tr>
                            <?php 
                            $contador++;
                            endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12 mt-4" id="tabla2" style="display: none;">

                        <form method="post" class="col-md-4">
                            <label for="producto">Seleccione una sucursal:</label>
                            <select class="form-control" id="sucursal" name="sucursal">
                                <option value=0>Todas las sucursales</option>
                            <?php while($row = mysqli_fetch_array($sucursales)): ?>
                                <option value="<?= $row ['id_sucursal'] ?>"><?= $row ['nombre'] ?></option>
                            <?php endwhile; ?>
                            </select>
                            <input type="submit" class="btn-primary" value="Filtrar">
                        </form> <br>

                        <?php if(!isset($sucursal)): ?>
                            <form action="ver_reporte.php" id="reporte2" method="post" >
                            <input class="btn-primary" type="submit" name="reporte2"value="Generar Reporte" >
                            </form><br>
                        <?php endif; ?>

                        <?php if(isset($sucursal)): ?>
                            <form action="ver_reporte.php" id="reporte2" method="post" >
                            <input type="hidden" name="sucursal" value="<?= $sucursal ?>" >
                            <input class="btn-primary" type="submit" name="reporte2"value="Generar Reporte" >
                        </form><br>
                            <?php endif; ?>

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Inventario</th>
                                <th scope="col">Producto</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Sucursal</th>
                                <th scope="col">cantidad</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $contador = 1;
                            while($row = mysqli_fetch_array($reporte2)): ?>
                            <tr>
                                <th scope="row"><?= $contador ?></th>
                                <td><?= $row ['producto'] ?></td>
                                <td><?= $row ['categoria'] ?></td>
                                <td><?= $row ['nombre'] ?></td>
                                <td><?= $row ['cantidad'] ?></td>
                            </tr>
                            <?php 
                            $contador++;
                            endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12 mt-4" id="tabla3" style="display: none;">

                    <form method="post" class="col-md-4">
                        <label for="producto">Seleccione un producto:</label>
                        <select class="form-control" id="producto" name="producto">
                            <option value=0 >Todos los productos</option>
                        <?php while($row = mysqli_fetch_array($productos)): ?>
                            <option value="<?= $row ['id_producto'] ?>"><?= $row ['nombre'] ?></option>
                        <?php endwhile; ?>
                        </select>
                        <input type="submit" class="btn-primary" value="Filtrar productos">
                    </form><br>

                        <?php if(!isset($producto)): ?>
                        <form action="ver_reporte.php" id="reporte3" method="post" >
                            <input class="btn-primary" type="submit" name="reporte3"value="Generar Reporte" >
                        </form><br>
                        <?php endif; ?>

                        <?php if(isset($producto)): ?>
                            <form action="ver_reporte.php" id="reporte3" method="post" >
                            <input type="hidden" name="product" value="<?= $producto ?>" >
                            <input class="btn-primary" type="submit" name="reporte3"value="Generar Reporte" >
                            </form><br>
                            <?php endif; ?>

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Ventas (<?php echo $total['total']  ?>)</th>
                                <th scope="col">Producto</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $contador = 1;
                            mysqli_data_seek($reporte3, 0);
                            while($row = mysqli_fetch_array($reporte3)): ?>
                            <tr>
                                <th scope="row"><?= $contador ?></th>
                                <td><?= $row ['productos'] ?></td>
                                <td><?= $row ['categoria'] ?></td>
                                <td><?= $row ['fecha'] ?></td>
                            </tr>
                            <?php 
                            $contador++;
                            endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    
                    

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
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
                <div class="modal-body">Selecciona "Cerrar Ssesión" abajo si deseas cerrar tu sesión.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="php/logout.php">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById("reportDropdown3").addEventListener("change", function() {
        var selectedOption = this.value;
        var tables = document.querySelectorAll(".col-md-12");
        
        tables.forEach(function(table) {
            if (table.id === selectedOption) {
                table.style.display = "block";
            } else {
                table.style.display = "none";
            }
        });
    });
</script>




    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>