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

    if(isset($_POST['productos'])){

        $producto = $_POST['producto'];

        if($producto==0){
            
            $sql = "SELECT
            p.id_producto, p.nombre, p.categoria, p.descripcion, p.marca, p.precio, i.ruta
            FROM producto p
            INNER JOIN imagen i ON p.id_producto = i.id_producto
            WHERE p.estatus=1
            GROUP BY p.id_producto, p.nombre, p.categoria, p.descripcion, p.marca, p.precio, i.ruta;";
        
            $query = mysqli_query($con, $sql);
        } else {

            $sql = "SELECT
            p.id_producto, p.nombre, p.categoria, p.descripcion, p.marca, p.precio, i.ruta
            FROM producto p
            INNER JOIN imagen i ON p.id_producto = i.id_producto
            WHERE p.estatus=1 AND p.id_producto='$producto'
            GROUP BY p.id_producto, p.nombre, p.categoria, p.descripcion, p.marca, p.precio, i.ruta;";
        
            $query = mysqli_query($con, $sql);
        }

    } else {
        
        $sql = "SELECT
            p.id_producto, p.nombre, p.categoria, p.descripcion, p.marca, p.precio, i.ruta
            FROM producto p
            INNER JOIN imagen i ON p.id_producto = i.id_producto
            WHERE p.estatus=1
            GROUP BY p.id_producto, p.nombre, p.categoria, p.descripcion, p.marca, p.precio, i.ruta;";
        
            $query = mysqli_query($con, $sql);
    }

    

    if($_SESSION['tipo'] === 'cliente' OR $_SESSION['tipo'] === 'empleado'){
        echo '     
            <script> 
                alert("No puede acceder a esta página");
                window.location = "/Barbieland/public/index_cliente.php";
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

    $productos = mysqli_query($con, "SELECT * FROM producto");

?>
<!DOCTYPE html>
<!-- saved from url=(0042)http://127.0.0.1:5500/public/producto.html -->
<html lang="en-US" dir="ltr" coupert-item="9AF8D9A4E502F3784AD24272D81F0381" class="chrome windows"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
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
    <link href="assets/css/theme.css" rel="stylesheet"/>
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="stylejoshua.css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">

    <style>
        
    </style>
</head>


  <body id="top">
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

          <!-- Nav Item - Utilities Collapse Menu -->
          <li class="nav-item">
              <a class="nav-link collapsed" href="gestion.php">
                  <i class="fas fa-fw"></i>
                  <span>Productos</span></a>
          </li>

          <!-- Nav Item - Pages Collapse Menu -->
              
          <li class="nav-item">
              <a class="nav-link collapsed" href="gestionSucursales.php">
                  <i class="fas fa-fw"></i>
                  <span>Sucursales</span></a>
          </li>
          <!-- Divider -->
          <hr class="sidebar-divider">

          <!-- Heading -->


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
                  <form method="post" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                      <div class="input-group">
                        <select class="form-control" id="producto" name="producto">
                        <option value="0">Todos los productos</option>
                            <?php while($row = mysqli_fetch_array($productos)): ?>
                                <option value="<?= $row ['id_producto'] ?>"><?= $row ['nombre'] ?></option>
                            <?php endwhile; ?>
                            </select>
                          <div class="input-group-append">
                                  <input class="btn-primary" type="submit" value="Buscar" name="productos" id=""> 
                          </div>
                      </div>
                  </form>

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
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <div class="container-fluid">

      <section style="padding-top: 0; margin-top: 0;">
        <h1>
            Lista de Productos
        </h1>

        <h3><a style="text-decoration:none; color: inherit;" href="/Barbieland/public/productoFormulario.php"><button class="add-product-button">Añadir producto</button></a></h3>

        <div class="product-container">
        <?php $contador = 1;  while($row = mysqli_fetch_array($query)): ?>
            <div class="product">
                <img src="<?= $row ['ruta'] ?>" alt="Barbie " <?= $row ['nombre'] ?>>
                <div class="product-details">
                    <p><strong>#</strong> <?= $contador ?></p>
                    <p><strong>Categoría:</strong> <?= $row ['categoria'] ?></p>
                    <p><strong>Precio: </strong>$<?= $row ['precio'] ?></p>
                    <p><strong>Nombre:</strong> <?= $row ['nombre'] ?></p>
                    <p><strong>Descripción:</strong> <?= $row ['descripcion'] ?></p>
                    <p><strong>Marca:</strong> <?= $row ['marca'] ?></p>
                    <div class="product-buttons">
                    <a style="text-decoration:none; color: inherit;" href="php/updatecopy.php?id_producto=<?= $row['id_producto'] ?>" ><button class="product-button">Modificar</button></a>
                    <a style="text-decoration:none; color: inherit;" href="php/delete_producto.php?id_producto=<?= $row['id_producto'] ?>"><button class="product-button">Eliminar</a></button>
                    </div>
                </div>
            </div>
            <?php $contador++; endwhile; ?>
    
    
        </div>


      
    <!-- Ventana emergente para modificar inventario -->
    <div id="modifyInventoryPopup" class="popup">
        <h2>Modificar Inventario</h2>
        <form class="popup-form">
            <input type="text" placeholder="Inventario (GDL)" />
            <input type="text" placeholder="Inventario (QRO)" />
            <input type="text" placeholder="Inventario (CDMX)" />
            <button class="popup-button">Guardar</button>
        </form>
        <hr>
        <button class="popup-button" onclick="closeModifyInventoryPopup()">Cerrar</button>
    </div>

        </section>
    
           <!-- ============================================-->
      <!-- <section> begin ============================-->

      <!-- <section> close ============================-->
      <!-- ============================================-->

    </div>

    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Your Website 2023</span>
            </div>
        </div>
    </footer>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->
    <a class="scroll-to-top rounded" href="#">
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


<div id="8d0a06c2-29ff-41ee-b9d0-3c4af6189b3d" style="display: block !important; z-index: 2147483647 !important;"><template shadowrootmode="closed"><div style="color: initial; font: initial; font-palette: initial; font-synthesis: initial; forced-color-adjust: initial; text-orientation: initial; text-rendering: initial; -webkit-font-smoothing: initial; -webkit-locale: initial; -webkit-text-orientation: initial; -webkit-writing-mode: initial; writing-mode: initial; zoom: initial; accent-color: initial; place-content: initial; place-items: initial; place-self: initial; alignment-baseline: initial; animation-composition: initial; animation: initial; app-region: initial; appearance: initial; aspect-ratio: initial; backdrop-filter: initial; backface-visibility: initial; background: initial; background-blend-mode: initial; baseline-shift: initial; baseline-source: initial; block-size: initial; border-block: initial; border: initial; border-radius: initial; border-collapse: initial; border-end-end-radius: initial; border-end-start-radius: initial; border-inline: initial; border-start-end-radius: initial; border-start-start-radius: initial; inset: initial; box-shadow: initial; box-sizing: initial; break-after: initial; break-before: initial; break-inside: initial; buffered-rendering: initial; caption-side: initial; caret-color: initial; clear: initial; clip: initial; clip-path: initial; clip-rule: initial; color-interpolation: initial; color-interpolation-filters: initial; color-rendering: initial; color-scheme: initial; columns: initial; column-fill: initial; gap: initial; column-rule: initial; column-span: initial; contain: initial; contain-intrinsic-block-size: initial; contain-intrinsic-size: initial; contain-intrinsic-inline-size: initial; container: initial; content: initial; content-visibility: initial; counter-increment: initial; counter-reset: initial; counter-set: initial; cursor: initial; cx: initial; cy: initial; d: initial; display: initial; dominant-baseline: initial; empty-cells: initial; fill: initial; fill-opacity: initial; fill-rule: initial; filter: initial; flex: initial; flex-flow: initial; float: initial; flood-color: initial; flood-opacity: initial; grid: initial; grid-area: initial; height: initial; hyphenate-character: initial; hyphenate-limit-chars: initial; hyphens: initial; image-orientation: initial; image-rendering: initial; initial-letter: initial; inline-size: initial; inset-block: initial; inset-inline: initial; isolation: initial; letter-spacing: initial; lighting-color: initial; line-break: initial; list-style: initial; margin-block: initial; margin: initial; margin-inline: initial; marker: initial; mask: initial; mask-type: initial; math-depth: initial; math-shift: initial; math-style: initial; max-block-size: initial; max-height: initial; max-inline-size: initial; max-width: initial; min-block-size: initial; min-height: initial; min-inline-size: initial; min-width: initial; mix-blend-mode: initial; object-fit: initial; object-position: initial; object-view-box: initial; offset: initial; opacity: initial; order: initial; orphans: initial; outline: initial; outline-offset: initial; overflow-anchor: initial; overflow-clip-margin: initial; overflow-wrap: initial; overflow: initial; overscroll-behavior-block: initial; overscroll-behavior-inline: initial; overscroll-behavior: initial; padding-block: initial; padding: initial; padding-inline: initial; page: initial; page-orientation: initial; paint-order: initial; perspective: initial; perspective-origin: initial; pointer-events: initial; position: initial; quotes: initial; r: initial; resize: initial; rotate: initial; ruby-position: initial; rx: initial; ry: initial; scale: initial; scroll-behavior: initial; scroll-margin-block: initial; scroll-margin: initial; scroll-margin-inline: initial; scroll-padding-block: initial; scroll-padding: initial; scroll-padding-inline: initial; scroll-snap-align: initial; scroll-snap-stop: initial; scroll-snap-type: initial; scrollbar-gutter: initial; shape-image-threshold: initial; shape-margin: initial; shape-outside: initial; shape-rendering: initial; size: initial; speak: initial; stop-color: initial; stop-opacity: initial; stroke: initial; stroke-dasharray: initial; stroke-dashoffset: initial; stroke-linecap: initial; stroke-linejoin: initial; stroke-miterlimit: initial; stroke-opacity: initial; stroke-width: initial; tab-size: initial; table-layout: initial; text-align: initial; text-align-last: initial; text-anchor: initial; text-combine-upright: initial; text-decoration: initial; text-decoration-skip-ink: initial; text-emphasis: initial; text-emphasis-position: initial; text-indent: initial; text-overflow: initial; text-shadow: initial; text-size-adjust: initial; text-transform: initial; text-underline-offset: initial; text-underline-position: initial; white-space: initial; touch-action: initial; transform: initial; transform-box: initial; transform-origin: initial; transform-style: initial; transition: initial; translate: initial; user-select: initial; vector-effect: initial; vertical-align: initial; view-transition-name: initial; visibility: initial; border-spacing: initial; -webkit-box-align: initial; -webkit-box-decoration-break: initial; -webkit-box-direction: initial; -webkit-box-flex: initial; -webkit-box-ordinal-group: initial; -webkit-box-orient: initial; -webkit-box-pack: initial; -webkit-box-reflect: initial; -webkit-highlight: initial; -webkit-line-break: initial; -webkit-line-clamp: initial; -webkit-mask-box-image: initial; -webkit-mask: initial; -webkit-mask-composite: initial; -webkit-print-color-adjust: initial; -webkit-rtl-ordering: initial; -webkit-ruby-position: initial; -webkit-tap-highlight-color: initial; -webkit-text-combine: initial; -webkit-text-decorations-in-effect: initial; -webkit-text-fill-color: initial; -webkit-text-security: initial; -webkit-text-stroke: initial; -webkit-user-drag: initial; -webkit-user-modify: initial; widows: initial; width: initial; will-change: initial; word-break: initial; word-spacing: initial; x: initial; y: initial; z-index: 2147483647;"></div></template></div></body></html>




    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
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
    <script src="./newtmp_files/popper.min.js.download"></script>
    <script src="./newtmp_files/bootstrap.min.js.download"></script>
    <script src="./newtmp_files/is.min.js.download"></script>
    <script src="./newtmp_files/polyfill.min.js.download"></script>
    <script src="./newtmp_files/feather.min.js.download"></script>
    <script>
      feather.replace();
    </script>
    <script src="./newtmp_files/theme.js.download"></script>

    <link href="./newtmp_files/css2" rel="stylesheet">
  <!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>

    function confirmDelete() {
            var result = confirm("¿Seguro que quieres eliminar el producto?");
            if (result) {
                // Aquí puedes agregar la lógica para eliminar el producto
                alert("Producto eliminado correctamente");
            }
        }

        function openPopup() {
            document.getElementById("popup").style.display = "block";
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none";
        }

        function deleteProduct() {
            // Aquí puedes agregar la lógica para eliminar el producto
            alert("Producto eliminado correctamente");
            closePopup();
        }

        function openAddProductPopup() {
        document.getElementById("addProductPopup").style.display = "block";
    }

    function closeAddProductPopup() {
        document.getElementById("addProductPopup").style.display = "none";
    }

    function openModifyProductPopup() {
        document.getElementById("modifyProductPopup").style.display = "block";
    }

    function closeModifyProductPopup() {
        document.getElementById("modifyProductPopup").style.display = "none";
    }

    function openDeleteProductPopup() {
        // Aquí puedes agregar la lógica para mostrar la ventana emergente de confirmación de eliminación
        document.getElementById("deleteProductPopup").style.display = "block";
    }

    function closeDeleteProductPopup() {
        // Aquí puedes agregar la lógica para cerrar la ventana emergente de confirmación de eliminación
        document.getElementById("deleteProductPopup").style.display = "none";
    }

    function openModifyInventoryPopup() {
            document.getElementById("modifyInventoryPopup").style.display = "block";
        }

        function closeModifyInventoryPopup() {
            document.getElementById("modifyInventoryPopup").style.display = "none";
        }
</script>


<div id="8d0a06c2-29ff-41ee-b9d0-3c4af6189b3d" style="display: block !important; z-index: 2147483647 !important;"><template shadowrootmode="closed"><div style="color: initial; font: initial; font-palette: initial; font-synthesis: initial; forced-color-adjust: initial; text-orientation: initial; text-rendering: initial; -webkit-font-smoothing: initial; -webkit-locale: initial; -webkit-text-orientation: initial; -webkit-writing-mode: initial; writing-mode: initial; zoom: initial; accent-color: initial; place-content: initial; place-items: initial; place-self: initial; alignment-baseline: initial; animation-composition: initial; animation: initial; app-region: initial; appearance: initial; aspect-ratio: initial; backdrop-filter: initial; backface-visibility: initial; background: initial; background-blend-mode: initial; baseline-shift: initial; baseline-source: initial; block-size: initial; border-block: initial; border: initial; border-radius: initial; border-collapse: initial; border-end-end-radius: initial; border-end-start-radius: initial; border-inline: initial; border-start-end-radius: initial; border-start-start-radius: initial; inset: initial; box-shadow: initial; box-sizing: initial; break-after: initial; break-before: initial; break-inside: initial; buffered-rendering: initial; caption-side: initial; caret-color: initial; clear: initial; clip: initial; clip-path: initial; clip-rule: initial; color-interpolation: initial; color-interpolation-filters: initial; color-rendering: initial; color-scheme: initial; columns: initial; column-fill: initial; gap: initial; column-rule: initial; column-span: initial; contain: initial; contain-intrinsic-block-size: initial; contain-intrinsic-size: initial; contain-intrinsic-inline-size: initial; container: initial; content: initial; content-visibility: initial; counter-increment: initial; counter-reset: initial; counter-set: initial; cursor: initial; cx: initial; cy: initial; d: initial; display: initial; dominant-baseline: initial; empty-cells: initial; fill: initial; fill-opacity: initial; fill-rule: initial; filter: initial; flex: initial; flex-flow: initial; float: initial; flood-color: initial; flood-opacity: initial; grid: initial; grid-area: initial; height: initial; hyphenate-character: initial; hyphenate-limit-chars: initial; hyphens: initial; image-orientation: initial; image-rendering: initial; initial-letter: initial; inline-size: initial; inset-block: initial; inset-inline: initial; isolation: initial; letter-spacing: initial; lighting-color: initial; line-break: initial; list-style: initial; margin-block: initial; margin: initial; margin-inline: initial; marker: initial; mask: initial; mask-type: initial; math-depth: initial; math-shift: initial; math-style: initial; max-block-size: initial; max-height: initial; max-inline-size: initial; max-width: initial; min-block-size: initial; min-height: initial; min-inline-size: initial; min-width: initial; mix-blend-mode: initial; object-fit: initial; object-position: initial; object-view-box: initial; offset: initial; opacity: initial; order: initial; orphans: initial; outline: initial; outline-offset: initial; overflow-anchor: initial; overflow-clip-margin: initial; overflow-wrap: initial; overflow: initial; overscroll-behavior-block: initial; overscroll-behavior-inline: initial; overscroll-behavior: initial; padding-block: initial; padding: initial; padding-inline: initial; page: initial; page-orientation: initial; paint-order: initial; perspective: initial; perspective-origin: initial; pointer-events: initial; position: initial; quotes: initial; r: initial; resize: initial; rotate: initial; ruby-position: initial; rx: initial; ry: initial; scale: initial; scroll-behavior: initial; scroll-margin-block: initial; scroll-margin: initial; scroll-margin-inline: initial; scroll-padding-block: initial; scroll-padding: initial; scroll-padding-inline: initial; scroll-snap-align: initial; scroll-snap-stop: initial; scroll-snap-type: initial; scrollbar-gutter: initial; shape-image-threshold: initial; shape-margin: initial; shape-outside: initial; shape-rendering: initial; size: initial; speak: initial; stop-color: initial; stop-opacity: initial; stroke: initial; stroke-dasharray: initial; stroke-dashoffset: initial; stroke-linecap: initial; stroke-linejoin: initial; stroke-miterlimit: initial; stroke-opacity: initial; stroke-width: initial; tab-size: initial; table-layout: initial; text-align: initial; text-align-last: initial; text-anchor: initial; text-combine-upright: initial; text-decoration: initial; text-decoration-skip-ink: initial; text-emphasis: initial; text-emphasis-position: initial; text-indent: initial; text-overflow: initial; text-shadow: initial; text-size-adjust: initial; text-transform: initial; text-underline-offset: initial; text-underline-position: initial; white-space: initial; touch-action: initial; transform: initial; transform-box: initial; transform-origin: initial; transform-style: initial; transition: initial; translate: initial; user-select: initial; vector-effect: initial; vertical-align: initial; view-transition-name: initial; visibility: initial; border-spacing: initial; -webkit-box-align: initial; -webkit-box-decoration-break: initial; -webkit-box-direction: initial; -webkit-box-flex: initial; -webkit-box-ordinal-group: initial; -webkit-box-orient: initial; -webkit-box-pack: initial; -webkit-box-reflect: initial; -webkit-highlight: initial; -webkit-line-break: initial; -webkit-line-clamp: initial; -webkit-mask-box-image: initial; -webkit-mask: initial; -webkit-mask-composite: initial; -webkit-print-color-adjust: initial; -webkit-rtl-ordering: initial; -webkit-ruby-position: initial; -webkit-tap-highlight-color: initial; -webkit-text-combine: initial; -webkit-text-decorations-in-effect: initial; -webkit-text-fill-color: initial; -webkit-text-security: initial; -webkit-text-stroke: initial; -webkit-user-drag: initial; -webkit-user-modify: initial; widows: initial; width: initial; will-change: initial; word-break: initial; word-spacing: initial; x: initial; y: initial; z-index: 2147483647;"></div></template></div></body></html>