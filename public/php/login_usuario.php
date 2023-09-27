<?php

    session_start();

    include('conexion.php');

    $con=conexion();


    $correo= $_POST['correo'];
    $psw= md5($_POST['psw']);

    $validar_login = mysqli_query($con, "SELECT * FROM usuario WHERE correo='$correo' AND  psw='$psw' AND estatus=1");
    $tipo_usuario = mysqli_fetch_assoc(mysqli_query($con, "SELECT tipo FROM usuario WHERE correo='$correo' AND psw='$psw'"));
    $estatus = mysqli_fetch_assoc(mysqli_query($con, "SELECT estatus FROM usuario WHERE correo='$correo' AND psw='$psw'"));


    if (mysqli_num_rows($validar_login) > 0) {

        $pass = mysqli_fetch_assoc(mysqli_query($con, "SELECT psw FROM usuario WHERE correo='$correo'"))['psw'];

        if($psw===$pass){

        $id = mysqli_fetch_assoc(mysqli_query($con, "SELECT id_usuario FROM usuario WHERE correo='$correo' AND psw='$psw'"))['id_usuario'];
        $_SESSION['id_usuario'] = mysqli_fetch_assoc(mysqli_query($con, "SELECT id_usuario FROM usuario WHERE correo='$correo' AND psw='$psw'"))['id_usuario'];;
        $_SESSION['correo'] = $correo;
        $_SESSION['tipo'] = mysqli_fetch_assoc(mysqli_query($con, "SELECT tipo FROM usuario WHERE id_usuario='$id'"))['tipo'];
        $_SESSION['psw'] = $psw;
        $_SESSION['nombre'] = mysqli_fetch_assoc(mysqli_query($con, "SELECT nombre FROM usuario WHERE id_usuario='$id'"))['nombre'];
        $_SESSION['app'] = mysqli_fetch_assoc(mysqli_query($con, "SELECT app FROM usuario WHERE id_usuario='$id'"))['app'];
        $_SESSION['apm'] = mysqli_fetch_assoc(mysqli_query($con, "SELECT apm FROM usuario WHERE id_usuario='$id'"))['apm'];
        $_SESSION['telefono'] = mysqli_fetch_assoc(mysqli_query($con, "SELECT telefono FROM usuario WHERE id_usuario='$id'"))['telefono'];
        $_SESSION['fecha_nacimiento'] = mysqli_fetch_assoc(mysqli_query($con, "SELECT fecha_nacimiento FROM usuario WHERE id_usuario='$id'"))['fecha_nacimiento'];

        if ($tipo_usuario['tipo'] === 'cliente') {
            header("Location: /Barbieland/public/index_cliente.php");
            exit();
        } elseif ($tipo_usuario['tipo'] === 'empleado') {
            header("Location: /Barbieland/public/PanelEmpleado.php");
            exit();
        } elseif ($tipo_usuario['tipo'] === 'administrador') {
            header("Location: /Barbieland/public/PanelAdmin.php");
            exit();
        }
        } else {
            echo '
            <script> 
                alert("Contraseña inválida");
                window.location = "/Barbieland/public/login.php";
            </script>
        ';
        }
    } else {
        echo '
            <script> 
                alert("Correo y contraseña inválidos");
                window.location = "/Barbieland/public/login.php";
            </script>
        ';
        exit();
    }
   

    

?>