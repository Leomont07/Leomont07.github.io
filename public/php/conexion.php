<?php 

function conexion(){
    $con = mysqli_connect("localhost", "root", "230304", "barbieland");


    if($con){  

    } else{

    }
    return $con;
    
}

?>