<?php

    session_start();

    include('conexion.php');

    $con=conexion();


    $id= $_GET['id_usuario'];


    $sql = "UPDATE usuario SET estatus=0 WHERE id_usuario='$id'";

    $tipo_usuario = mysqli_fetch_assoc(mysqli_query($con, "SELECT tipo FROM usuario WHERE id_usuario=$id"));

    $query = mysqli_query($con, $sql);



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
        window.location = "/Barbieland/public/index.php";
        </script>';
        }else {
            echo '<script> 
            alert("No se guardaron los datos");
            window.location = "/Barbieland/public/cuenta.php";
            </script>';
        }
    }


?>