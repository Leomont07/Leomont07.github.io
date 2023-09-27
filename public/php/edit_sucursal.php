<?php

    session_start();

    include('conexion.php');

    $con = conexion();

    $id = $_POST['id_sucursal'];
    $nombre = $_POST['nombre'];
    $colonia = $_POST['colonia'];
    $calle = $_POST['calle'];
    $num = $_POST['numero_exterior'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];


    if (strlen($telefono)>10 OR strlen($telefono)<10) {
        echo '     
        <script> 
            alert("Ingresa un teléfono válido");
            window.location = "/Barbieland/public/php/updateSucursal.php?id_sucursal='.$id.'";
        </script>
    ';
    } else {
        $sql = "UPDATE sucursal
        SET 
        nombre = '$nombre', 
        colonia = '$colonia', 
        calle = '$calle', 
        numero_exterior = '$num', 
        correo = '$correo', 
        telefono = '$telefono' 
        WHERE id_sucursal = '$id'"; 

$query = mysqli_query($con, $sql);
    }
    



        if ($query) {
            echo '<script> 
            window.location = "/Barbieland/public/gestionSucursales.php";
            </script>';
        } else {
            echo '<script> 
            alert("No se guardaron los datos");
            window.location = "/Barbieland/public/gestionSucursales.php";
            </script>';
        }



?>
