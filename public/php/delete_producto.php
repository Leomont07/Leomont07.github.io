<?php

    session_start();

    include('conexion.php');

    $con=conexion();


    $id= $_GET['id_producto'];


    $sql = "UPDATE producto SET estatus=0 WHERE id_producto='$id'";

    $query = mysqli_query($con, $sql);

    if ($_SESSION['tipo']==='administrador'){

        if ($query) {
            echo '<script> 
            window.location = "/Barbieland/public/gestion.php";
            </script>';
        } else {
            echo '<script> 
            alert("No se elimino el producto");
            window.location = "/Barbieland/public/gestion.php";
            </script>';
        }

    } elseif ($_SESSION['tipo']=== 'empleado'){
        if($query){
        echo '<script> 
        window.location = "/Barbieland/public/gestion_empleados.php";
        </script>';
        }else {
            echo '<script> 
            alert("No se elimino el producto");
            window.location = "/Barbieland/public/gestion_empleados.php";
            </script>';
        }
    }


?>