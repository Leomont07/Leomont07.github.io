<?php

    session_start();

    include('conexion.php');

    $con=conexion();


    $id= $_GET['id_sucursal'];


    $sql = "UPDATE sucursal SET estatus=0 WHERE id_sucursal='$id'";

    $query = mysqli_query($con, $sql);



        if ($query) {
            echo '<script> 
            window.location = "/Barbieland/public/gestionSucursales.php";
            </script>';
        } else {
            echo '<script> 
            alert("No se elimino la sucursal");
            window.location = "/Barbieland/public/gestionSucursales.php";
            </script>';
        }


?>