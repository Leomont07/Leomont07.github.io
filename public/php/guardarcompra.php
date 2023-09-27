<?php

session_start();

$id = $_SESSION['id_usuario'];

include('conexion.php');
$con = conexion();

$compra = json_decode($_POST['compra'], true);

if (!is_array($compra) || !isset($compra['inventario']) || !isset($compra['cantidad']) || !isset($compra['precio'])) {
    die('Error: Los datos de la compra no son válidos.');
}

if(isset($compra['carrito'])){
    $carrito = $compra['carrito'];
}


$inventario = $compra['inventario'];
$cantidad = $compra['cantidad'];
$pr = $compra['precio'];


if($_SESSION['dir']==0){
    echo '     
    <script> 
        alert("Seleccione una dirección de envio");
        window.location = "/Barbieland/public/venta.php";
    </script>
';
}else{
    $direccion = $_SESSION['dir'];
}

$calle = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM direccionenvio WHERE id_direccion=$direccion"));


$venta = "INSERT INTO venta (id_usuario, fecha, direccion) VALUES ($id, NOW(), CONCAT('$calle[colonia]',' ','$calle[calle]',' ','$calle[numero_exterior]'))";
mysqli_query($con, $venta) or die('Error en la consulta: ' . mysqli_error($con));

for ($i = 0; $i < count($inventario); $i++) {
    $id_inventario = $inventario[$i];
    $cant_comprada = $cantidad[$i];
    $precio = $pr[$i];

    if(isset($carrito)){
        $id_carrito = $carrito[$i];
    }

    $row = mysqli_fetch_array(mysqli_query($con, "SELECT cantidad FROM inventario WHERE id_inventario=$id_inventario"));

    if($row['cantidad'] < $cant_comprada){
        echo '     
        <script> 
            alert("No hay suficientes productos disponibles");
            window.location = "/Barbieland/public/venta.php";
        </script>
    ';
    } else {
        $query = "UPDATE inventario SET cantidad = cantidad - $cant_comprada WHERE id_inventario = $id_inventario";
        if(isset($id_carrito)){
            $eliminar = "DELETE FROM carrito WHERE id_carrito=$id_carrito";
        }

        mysqli_query($con, $query) or die('Error en la consulta: ' . mysqli_error($con));

        if(isset($eliminar)){
            mysqli_query($con, $eliminar) or die('Error en la consulta: ' . mysqli_error($con));
        }

        if(isset($venta)){
            $detventa = "INSERT INTO detalleventa (id_venta,  id_inventario, cantidad, precio, subtotal) VALUES (
                (SELECT MAX(id_venta) FROM Venta), 
                $id_inventario,
                $cant_comprada,
                $precio,
                ($cant_comprada * $precio)
                )";
            mysqli_query($con, $detventa) or die('Error en la consulta: ' . mysqli_error($con));
        }
        
    
        if($detventa){
            $total="UPDATE venta 
            SET total = (SELECT SUM(subtotal) FROM detalleventa WHERE id_venta = (SELECT MAX(id_venta) FROM detalleventa))
            WHERE id_venta = (SELECT MAX(id_venta) FROM detalleventa)";
            mysqli_query($con, $total) or die('Error en la consulta: ' . mysqli_error($con));
        }

        $_SESSION['cart']=null;

        echo '     
        <script> 
            alert("Si desea facturar, seleccione su compra");
            window.location = "/Barbieland/public/factura.php";
        </script>
    ';

        session_cache_expire();
    }
    


}
?>




