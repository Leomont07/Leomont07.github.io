<?php

    session_start();

    include('conexion.php');

    $con = conexion();

    $id = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $app = $_POST['app'];
    $apm = $_POST['apm'];
    $correo = $_POST['correo'];
    $psw = $_POST['psw'];

    if(mysqli_fetch_array(mysqli_query($con, "SELECT psw FROM usuario WHERE id_usuario = '$id'"))['psw'] == $psw){
        $contra = $psw;
    } else {
        $contra = md5($psw);
    }
    
    $telefono = $_POST['telefono'];
    $fecha = $_POST['fecha_nacimiento'];
    $tipo = $_POST['tipo'];
    $estatus = 1;

    if (empty($nombre) || empty($app) || empty($apm) || empty($correo) || empty($psw) || empty($telefono) || empty($fecha)){
        echo '     
        <script> 
            alert("Debes llenar todos los campos");
            window.location = "update.php?id_usuario='.$id.'";
        </script>
    ';
    } else{
        if (strlen($psw)<8) {
            echo '     
            <script> 
                alert("La contraseña debe tener más de 8 caracteres");
                window.location = "update.php?id_usuario='.$id.'";
            </script>
        ';
        }else{
            if (strlen($telefono)!=10) {
                    echo '     
                    <script> 
                    alert("Ingrese un teléfono válido");
                    window.location = "update.php?id_usuario='.$id.'";
                    </script>
                    ';
                    }else{

                        $sql = "UPDATE usuario 
                        SET 
                        nombre = '$nombre', 
                        app = '$app', 
                        apm = '$apm', 
                        correo = '$correo', 
                        psw = '$contra', 
                        telefono = '$telefono', 
                        fecha_nacimiento = '$fecha', 
                        tipo = '$tipo', 
                        estatus = '$estatus' 
                        WHERE id_usuario = '$id'"; 

                        $query = mysqli_query($con, $sql);

                       
                    }
                    
                }
            }







    $tipo_usuario = mysqli_fetch_assoc(mysqli_query($con, "SELECT tipo FROM usuario WHERE id_usuario=$id"));


    if ($_SESSION['tipo']==='administrador'){

        if ($query && $tipo_usuario['tipo'] === 'cliente') {
            echo '<script> 
            window.location = "/Barbieland/public/clientes.php";
            </script>';
        } elseif($query && $tipo_usuario['tipo'] === 'empleado') {
            echo '<script> 
            window.location = "/Barbieland/public/empleados.php";
            </script>';
        } elseif ($query && $tipo_usuario['tipo'] === 'administrador') {
            echo '<script> 
            window.location = "/Barbieland/public/administradores.php";
            </script>';
        } else {
            echo '<script> 
            alert("No se guardaron los datos");
            window.location = "/Barbieland/public/PanelAdmin.php";
            </script>';
        }

    } elseif ($_SESSION['tipo']=== 'empleado'){
        if($query){
        echo '<script> 
        window.location = "/Barbieland/public/PanelEmpleado.php";
        </script>';
        }else {
            echo '<script> 
            alert("No se guardaron los datos");
            window.location = "/Barbieland/public/PanelEmpleado.php";
            </script>';
        }
    }elseif ($_SESSION['tipo']=== 'cliente'){
        if($query){
        echo '<script> 
        window.location = "/Barbieland/public/cuenta.php";
        </script>';
        }else {
            echo '<script> 
            alert("No se guardaron los datos");
            window.location = "/Barbieland/public/cuenta.php";
            </script>';
        }
    }


?>
