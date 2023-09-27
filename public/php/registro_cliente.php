<?php

    session_start();

    include('conexion.php');

    $con=conexion();

    $nombre= $_POST['nombre'];
    $app= $_POST['app'];
    $apm= $_POST['apm'];
    $correo= $_POST['correo'];
    $contra= $_POST['psw'];
    $psw= md5($contra);
    $telefono= $_POST['telefono'];
    $fecha= $_POST['fecha_nacimiento'];
    $tipo= 'cliente' ;
    $estatus=1;

    


    if ($tipo === 'cliente' AND empty($nombre) || empty($app) || empty($apm) || empty($correo) || empty($psw) || empty($telefono) || empty($fecha)){
        echo '     
        <script> 
            alert("Debes llenar todos los campos");
            window.location = "/Barbieland/public/usuarioFormulario2.php?tipo=cliente";
        </script>
    ';
    }else{
        if (strlen($telefono)>10 OR strlen($telefono)<10) {
            echo '     
            <script> 
                alert("Ingresa un teléfono válido");
                window.location = "/Barbieland/public/usuarioFormulario2.php?tipo=cliente";
            </script>
        ';
        }else{
            if (strlen($contra)<8) {
                echo '     
                <script> 
                    alert("La contraseña debe tener más de 8 caracteres");
                    window.location = "/Barbieland/public/usuarioFormulario2.php?tipo=cliente";
                </script>
            ';
            } else {
                $verificar_correo = mysqli_query($con, "SELECT * FROM usuario WHERE correo='$correo'");
                $comprobar = mysqli_fetch_assoc($verificar_correo);
    
                if(mysqli_num_rows($verificar_correo)>0 ){
                    echo '     
                        <script> 
                            alert("Utilice otro correo");
                            window.location = "/Barbieland/public/register.php";
                        </script>
                        ';
                        exit();
                } else {
                    $verificar_usuario = mysqli_query($con, "SELECT * FROM usuario WHERE correo='$correo' AND psw='$psw' ");
                    $comprobar2 = mysqli_fetch_assoc($verificar_correo);

                    if(mysqli_num_rows($verificar_usuario)>0){
                        echo '     
                            <script> 
                                alert("El usuario ya existe");
                                window.location = "/Barbieland/public/register.php";
                            </script>
                            ';
                            exit();
                    } else {
                        $sql = "INSERT INTO usuario (nombre, app, apm, correo, psw, telefono, fecha_nacimiento, tipo, estatus) 
                        VALUES ('$nombre', '$app', '$apm', '$correo', '$psw', '$telefono', '$fecha', '$tipo', '$estatus')";
                    
                        $query = mysqli_query($con, $sql);
                    }
                }    
            }
        }
    }


    if($query){
        echo '     
            <script> 
                alert("Usuario registrado exitosamente");
                window.location = "/Barbieland/public/login.php";
            </script>
            ';
    } else {
        echo '     
            <script> 
                alert("¡Error! Usuario no registrado");
                window.location = "/Barbieland/public/register.php";
            </script>
            ';
    }

    mysqli_close($con);

?>