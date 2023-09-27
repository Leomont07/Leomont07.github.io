<?php

    session_start();

    $id_usuario = $_SESSION['id_usuario'];

    include('conexion.php');

    $con=conexion();

    $id_venta = $_POST['id_venta'];
    $id_usuario = $_SESSION['id_usuario'];
    $nombre= $_POST['nombre'];
    $rfc= $_POST['rfc'];
    $razon= $_POST['razon'];
    $regimen= $_POST['regimen'];
    $cp= $_POST['cp'];

    if (empty($nombre) || empty($rfc) || empty($razon) || empty($regimen) || empty($cp)){
        echo '     
        <script> 
            alert("Debes llenar todos los campos");
            window.location = "/Barbieland/public/perfilFormulario.php?id_venta='.$id_venta.'";
        </script>
    ';
    } else{
        
        if(strlen($cp)<5 OR strlen($cp)>5){
            echo '     
            <script> 
                alert("Ingresa un código postal válido");
                window.location = "/Barbieland/public/perfilFormulario.php?id_venta='.$id_venta.'";
            </script>
        ';
        } else{
            $verificar_perfil= mysqli_query($con, "SELECT * FROM perfilfactura WHERE nombrePerfil='$nombre' AND rfc='$rfc' AND estatus=1 ");
            $verificar_perfi2= mysqli_query($con, "SELECT * FROM perfilfactura WHERE nombrePerfil='$nombre' AND rfc='$rfc' AND estatus=0 ");
    
            if(mysqli_num_rows($verificar_perfil)>0){
                echo '     
                    <script>
                        alert("El perfil ya existe"); 
                        window.location = "/Barbieland/public/perfilFormulario.php?id_venta='.$id_venta.'";
                    </script>
                    ';
                    exit();
            } elseif (mysqli_num_rows($verificar_perfi2)>0) {
                $perfil = "UPDATE perfilfactura SET estatus=1 WHERE nombrePerfil='$nombre' AND rfc='$rfc'";
                $query = mysqli_query($con, $perfil);
            } elseif(mysqli_num_rows(mysqli_query($con, "SELECT * FROM perfilfactura WHERE id_usuario='$id_usuario' AND estatus=1"))<3) {
                $perfil = "INSERT INTO perfilfactura (id_usuario, nombrePerfil, RFC, razonSocial, regimenFiscal, CP) 
                VALUES ('$id_usuario' ,'$nombre', '$rfc', '$razon', '$regimen', '$cp')";
                $query = mysqli_query($con, $perfil);
            } else {
                echo '     
                    <script>
                        alert("Solo se pueden tener 3 perfiles de facturación"); 
                        window.location = "/Barbieland/public/facturaSelected.php?id_venta='.$id_venta.'";
                    </script>
                    ';
            }
        }
        
    }






        if ($query) {
            echo '<script> 
            alert("Perfil registrado");
            window.location = "/Barbieland/public/facturaSelected.php?id_venta='.$id_venta.'";
            </script>';
        }else {
            echo '<script> 
            alert("No se pudo realizar el registro");
            window.location = "/Barbieland/public/facturaSelected.php?id_venta='.$id_venta.'";
            </script>';
        }


?>