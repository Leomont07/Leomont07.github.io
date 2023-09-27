<?php

    session_start();

    include('conexion.php');

    $con=conexion();


    $id_usuario = $_POST['id_usuario'];
    $calle= $_POST['calle'];
    $colonia= $_POST['colonia'];
    $num= $_POST['numero_exterior'];
    $cp= $_POST['cp'];
    $estatus= 1;

    

    

    if (empty($calle) || empty($colonia) || empty($num) || empty($cp)){
        echo '     
        <script> 
            alert("Debes llenar todos los campos");
            window.location = "/Barbieland/public/direccionFormulario2.php?id_usuario='.$id_usuario.'";
        </script>
    ';
    } else{
        if(strlen($cp) < 5  OR strlen($cp)> 5 ){
            echo '     
            <script> 
                alert("Ingresa un código postal válido");
                window.location = "/Barbieland/public/direccionFormulario2.php?id_usuario='.$id_usuario.'";
            </script>
        ';
        } else {
            $verificar_direccion= mysqli_query($con, "SELECT * FROM direccionenvio WHERE calle='$calle' AND id_usuario='$id_usuario' AND estatus=0");
            if(mysqli_num_rows($verificar_direccion)>0){
                mysqli_query($con, "UPDATE direccionenvio SET estatus=1 WHERE calle='$calle' AND id_usuario='$id_usuario'");
                echo '     
                    <script> 
                        window.location = "/Barbieland/public/venta.php";
                    </script>
                    ';
                    exit();
            } elseif (mysqli_num_rows(mysqli_query($con, "SELECT * FROM direccionenvio WHERE id_usuario='$id_usuario' AND estatus=1"))<3) {
                $direccion = "INSERT INTO direccionenvio (id_usuario, calle, colonia, numero_exterior, cp, estatus) 
                VALUES ('$id_usuario' ,'$calle', '$colonia', '$num', '$cp', '$estatus')";
                $query = mysqli_query($con, $direccion);
            } else {
                echo '     
                <script> 
                    alert("Solo puedes tener 3 direcciones registradas");
                    window.location = "/Barbieland/public/venta.php";
                </script>
            '; 
            }
        }
        
    }






        if ($query) {
            echo '<script> 
            alert("Direccion registrada");
            window.location = "/Barbieland/public/venta.php";
            </script>';
        }else {
            echo '<script> 
            alert("No se pudo realizar el registro");
            window.location = "/Barbieland/public/venta.php";
            </script>';
        }


?>