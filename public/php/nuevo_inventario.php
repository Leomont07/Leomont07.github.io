<?php

    session_start();

    include('conexion.php');

    $con = conexion();

    $id = $_POST['id_inventario'];
    $producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];

    if ($cantidad < 0 AND $_SESSION['tipo']==='administrador') {
        echo '<script> 
        alert("Cantidad no válida");
        window.location = "/Barbieland/public/gestion.php";
        </script>';
    } elseif ($cantidad < 0 AND $_SESSION['tipo']==='empleado') {
        echo '<script> 
        alert("Cantidad no válida");
        window.location = "/Barbieland/public/inventario.php?id_producto='.$producto.'";
        </script>';
    } else{
        $sql = "UPDATE inventario
        SET 
        cantidad = '$cantidad' 
        WHERE id_inventario = '$id'"; 
    }




    $query = mysqli_query($con, $sql);



        if ($query AND $_SESSION['tipo']==='administrador') {
            echo '<script> 
            window.location = "/Barbieland/public/gestion.php";
            </script>';
        } elseif ($query AND $_SESSION['tipo']==='empleado') {
            echo '<script> 
            window.location = "/Barbieland/public/gestion_empleados.php";
            </script>';
        }



?>