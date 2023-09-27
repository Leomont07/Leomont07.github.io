<?php

    session_start();

    include('conexion.php');

    $con=conexion();

    if (isset($_POST['id_sucursal'])){
        $sucursal = $_POST['id_sucursal'];
    }
 
    if (isset($_SESSION['tipo'])){
        $usuario=$_SESSION['tipo'];
    }

    $tipo = $_POST['tipo'];
    $nombre= $_POST['nombre'];
    $app= $_POST['app'];
    $apm= $_POST['apm'];
    $correo= $_POST['correo'];
    $contra= $_POST['psw'];
    $psw= md5($contra);
    $telefono= $_POST['telefono'];
    $fecha= $_POST['fecha_nacimiento'];
    $estatus=1;

    

    if ($tipo === 'cliente' AND  empty($nombre) || empty($app) || empty($apm) || empty($correo) || empty($psw) || empty($telefono) || empty($fecha)){
        echo '     
        <script> 
            alert("Debes llenar todos los campos");
            window.location = "/Barbieland/public/usuarioFormulario.php?tipo=cliente";
        </script>
    ';
    } elseif ($tipo === 'aministrador' AND  empty($nombre) || empty($app) || empty($apm) || empty($correo) || empty($psw) || empty($telefono) || empty($fecha)){
        echo '     
        <script> 
            alert("Debes llenar todos los campos");
            window.location = "/Barbieland/public/usuarioFormulario.php?tipo=administrador";
        </script>
    ';
    }elseif ($tipo === 'empleado' AND empty($sucursal) || empty($nombre) || empty($app) || empty($apm) || empty($correo) || empty($psw) || empty($telefono) || empty($fecha)){
        echo '     
        <script> 
            alert("Debes llenar todos los campos");
            window.location = "/Barbieland/public/usuarioFormulario.php?tipo=empleado";
        </script>
    ';
    }else{
        if (strlen($contra)<8) {
            echo '     
            <script> 
                alert("La contraseña debe tener más de 8 caracteres");
                window.location = "/Barbieland/public/usuarioFormulario.php?tipo=cliente";
            </script>
        ';
        }else{
            $verificar_correo = mysqli_query($con, "SELECT * FROM usuario WHERE correo='$correo'");

    
            if(mysqli_num_rows($verificar_correo)>0){
                echo '     
                    <script> 
                        alert("El correo ya existe");
                        window.location = "/Barbieland/public/usuarioFormulario.php";
                    </script>
                    ';
                    exit();
            } else{
                $verificar_usuario = mysqli_query($con, "SELECT * FROM usuario WHERE correo='$correo' AND psw='$psw'");


                if(mysqli_num_rows($verificar_usuario)>0){
                    echo '     
                        <script> 
                            alert("El usuario ya existe");
                            window.location = "/Barbieland/public/usuarioFormulario.php";
                        </script>
                        ';
                        exit();
                } else{
                    if($tipo === 'administrador' OR $tipo === 'cliente'){
                        $sql = "INSERT INTO usuario (tipo, nombre, app, apm, correo, psw, telefono, fecha_nacimiento, estatus) 
                        VALUES ('$tipo', '$nombre', '$app', '$apm', '$correo', '$psw', '$telefono', '$fecha', '$estatus')";
                        $query = mysqli_query($con, $sql);
                   } elseif($tipo === 'empleado'){
                        $sql = "INSERT INTO usuario (id_sucursal, tipo, nombre, app, apm, correo, psw, telefono, fecha_nacimiento, estatus) 
                        VALUES ('$sucursal' ,'$tipo', '$nombre', '$app', '$apm', '$correo', '$psw', '$telefono', '$fecha', '$estatus')";
                        $query = mysqli_query($con, $sql);
                   }
                }
            }
        }
    }



 

   if ($usuario==='administrador'){
        if ($query && $tipo === 'cliente') {
            echo '<script> 
            alert("Usuario registrado exitosamente");
            window.location = "/Barbieland/public/clientes.php";
            </script>';
        } elseif($query && $tipo === 'empleado') {
            echo '<script> 
            alert("Usuario registrado exitosamente");
            window.location = "/Barbieland/public/empleados.php";
            </script>';
        } elseif ($query && $tipo === 'administrador') {
            echo '<script> 
            alert("Usuario registrado exitosamente");
            window.location = "/Barbieland/public/administradores.php";
            </script>';
        } else {
            echo '<script> 
            alert("No se pudo realizar el registro");
            window.location = "/Barbieland/public/PanelAdmin.php";
            </script>';
        }

    } elseif ($usuario=== 'empleado'){
        if($query){
        echo '<script> 
        alert("Usuario registrado exitosamente");
        window.location = "/Barbieland/public/PanelEmpleado.php";
        </script>';
        }else {
            echo '<script> 
            alert("No se pudo realizar el registro");
            window.location = "/Barbieland/public/PanelEmpleado.php";
            </script>';
        }
    }

?>