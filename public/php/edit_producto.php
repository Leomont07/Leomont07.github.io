<?php

    session_start();

    include('conexion.php');

    $con = conexion();

    $id = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $descr = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $marca = $_POST['marca'];
    $ruta = $_POST['ruta'];
    $cat = $_POST['categoria'];


    if (empty($cat) || empty($nombre) || empty($descr) || empty($marca) || empty($precio) || empty($ruta)){
        echo '     
        <script> 
            alert("Debes llenar todos los campos");
            window.location = "updatecopy.php?id_producto='.$id.'";
        </script>
    ';
    } else {
            if(($precio)<0){
                echo '     
                <script> 
                    alert("Ingrese un precio válido");
                    window.location = "updatecopy.php?id_producto='.$id.'";
                </script>
                ';
                exit();
            } else {
                $sql = "UPDATE producto
                SET 
                nombre = '$nombre', 
                categoria = '$cat', 
                marca = '$marca', 
                precio = '$precio', 
                descripcion = '$descr' 
                WHERE id_producto = '$id'"; 
    
                $query = mysqli_query($con, $sql);
            } 
        }






    if ($_SESSION['tipo']==='administrador'){

        if ($query) {
            $img = "UPDATE imagen
            SET 
            id_producto = '$id',
            nombre = '$nombre', 
            ruta = 'PRODUCTOSIMG/$ruta'
            WHERE id_producto = '$id'";
            $query1 = mysqli_query($con, $img);

            echo '<script> 
            window.location = "/Barbieland/public/gestion.php";
            </script>';
        } else {
            echo '<script> 
            alert("No se guardaron los datos");
            window.location = "/Barbieland/public/gestion.php";
            </script>';
        }

    } elseif ($_SESSION['tipo']=== 'empleado'){

        if($query){
            $img = "UPDATE imagen
            SET 
            id_producto = '$id',
            nombre = '$nombre', 
            ruta = 'PRODUCTOSIMG/$ruta'
            WHERE id_producto = '$id'";
            $query1 = mysqli_query($con, $img);
        echo '<script> 
        window.location = "/Barbieland/public/gestion_empleados.php";
        </script>';
        }else {
            echo '<script> 
            alert("No se guardaron los datos");
            window.location = "/Barbieland/public/gestion_empleados.php";
            </script>';
        }
    }


?>
