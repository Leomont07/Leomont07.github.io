<?php

    session_start();

    include('conexion.php');

    $con=conexion();


    $calle = $_POST['calle'];
    $nombre= $_POST['nombre'];
    $colonia= $_POST['colonia'];
    $num= $_POST['numero_exterior'];
    $correo= $_POST['correo'];
    $telefono= $_POST['telefono'];




    $verificar_sucursal = mysqli_query($con, "SELECT * FROM sucursal WHERE nombre='$nombre'");


    if(empty($calle) || empty($nombre) || empty($colonia) || empty($num) || empty($correo) || empty($telefono)){
        echo '     
            <script> 
                alert("Debe llenar todos los campos");
                window.location = "/Barbieland/public/sucursalFormulario.php";
            </script>
            ';
    } else{
        if (strlen($telefono)>10 OR strlen($telefono)<10) {
            echo '     
            <script> 
                alert("Ingresa un teléfono válido");
                window.location = "/Barbieland/public/sucursalFormulario.php";
            </script>
        ';
        } else {
            if(mysqli_num_rows($verificar_sucursal)>0){
                echo '     
                    <script> 
                        alert("La sucrusal ya existe");
                        window.location = "/Barbieland/public/sucursalFormulario.php";
                    </script>
                    ';
                    exit();
            } else{
                $sucursal = "INSERT INTO sucursal (calle, nombre, colonia, correo, numero_exterior, telefono) 
                VALUES ('$calle', '$nombre', '$colonia', '$correo', '$num', '$telefono')";
            
                $query = mysqli_query($con, $sucursal);
            }
        }
        
    }



        if ($query) {
            echo '<script> 
            alert("Sucursal registrada exitosamente");
            window.location = "/Barbieland/public/gestionSucursales.php";
            </script>';
        }else {
            echo '<script> 
            alert("No se pudo realizar el registro");
            window.location = "/Barbieland/public/gestionSucursales.php";
            </script>';
        }


?>