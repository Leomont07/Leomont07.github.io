<?php

    session_start();

    include('conexion.php');

    $con=conexion();

    if (isset($_SESSION['tipo'])){
        $usuario=$_SESSION['tipo'];
    }

    $categoria = $_POST['categoria'];
    $nombre= $_POST['nombre'];
    $descripcion= $_POST['descripcion'];
    $marca= $_POST['marca'];
    $precio= $_POST['precio'];
    $ruta= $_POST['ruta'];

    $sql="SELECT id_sucursal FROM sucursal";
    $sucursal=mysqli_query($con, $sql);


    if (empty($categoria) || empty($nombre) || empty($descripcion) || empty($marca) || empty($precio) || empty($ruta)){
        echo '     
        <script> 
            alert("Debes llenar todos los campos");
            window.location = "/Barbieland/public/productoFormulario.php";
        </script>
    ';
    } else {
        $verificar_producto = mysqli_query($con, "SELECT * FROM producto WHERE nombre='$nombre'");


        if(mysqli_num_rows($verificar_producto)>0){
            mysqli_query($con, "UPDATE producto SET estatus=1 WHERE nombre='$nombre'");
            echo '     
                <script> 
                    alert("El producto ya existe");
                    window.location = "/Barbieland/public/productoFormulario.php";
                </script>
                ';
                exit();
        } else {
            if(($precio)<0){
                echo '     
                <script> 
                    alert("Ingrese un precio válido");
                    window.location = "/Barbieland/public/productoFormulario.php";
                </script>
                ';
                exit();
            } else {
                $producto = "INSERT INTO producto (categoria, nombre, descripcion, marca, precio) 
                VALUES ('$categoria', '$nombre', '$descripcion', '$marca', '$precio')";
            
                $query = mysqli_query($con, $producto);
            } 
        }
    }



    if ($usuario==='administrador'){
        if ($query) {
            $id=mysqli_query($con, "SELECT id_producto FROM producto WHERE nombre='$nombre'");
            $row = mysqli_fetch_assoc($id);
            $id_producto = $row['id_producto'];
            $imagen= "INSERT INTO imagen (id_producto, nombre, ruta) VALUES ('$id_producto', '$nombre', 'PRODUCTOSIMG/$ruta')";
            $query2 = mysqli_query($con, $imagen);
            echo '<script> 
            alert("Producto registrado exitosamente");
            window.location = "/Barbieland/public/gestion.php";
            </script>';
        }else {
            echo '<script> 
            alert("No se pudo realizar el registro");
            window.location = "/Barbieland/public/gestion.php";
            </script>';
        }

    }

?>

