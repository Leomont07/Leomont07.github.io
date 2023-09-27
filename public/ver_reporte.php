<?php

include('php/conexion.php');

$con=conexion();

session_start();

$id = $_SESSION['id_usuario'];

require_once('fpdf.php');
require_once 'vendor/autoload.php';

if(isset($_POST['reporte1'])){

    if(isset($_POST['fecha_inicio']) AND isset($_POST['fecha_fin'])){

        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        
        $reporte1 = mysqli_query($con, "SELECT v.id_venta, GROUP_CONCAT(p.nombre SEPARATOR ', ') AS productos, v.fecha, v.total
        FROM venta v
        INNER JOIN detalleventa d ON v.id_venta = d.id_venta
        INNER JOIN inventario i ON d.id_inventario = i.id_inventario
        INNER JOIN producto p ON i.id_producto = p.id_producto
        WHERE v.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
        GROUP BY v.id_venta, v.fecha, v.id_factura");
        mysqli_data_seek($reporte1, 0);
        $row = mysqli_fetch_array($reporte1);
    
        $id_venta = $row['id_venta'];
        $productos = $row['productos'];
        $fecha = $row['fecha'];
        $total = $row['total'];
        $contador = 1;

        $final = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(subtotal) AS final FROM detalleventa dv
        INNER JOIN venta v ON dv.id_venta = v.id_venta
        WHERE v.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'"));
    
    
        $mpdf = new \Mpdf\Mpdf();
    
        $logoImagePath = '/Barbieland/public/PRODUCTOSIMG/logo.png';
    
    
        $mpdf->SetTitle('Factura');
        $mpdf->AddPage();
    
        // Encabezado de la factura
    
        $mpdf->WriteHTML('<div style="text-align: center;"><img src="' . $logoImagePath . '" alt="Logo" width="150"></div>');
    
        $mpdf->SetFont('Arial', 'B', 14);
        $mpdf->Cell(0, 8, 'Reporte por fecha', 0, 1, 'C');
    
    
        // Detalle de la factura
    
        $mpdf->SetFont('Arial', 'B', 9);
    
        // Cabecera de la tabla de detalles
        $mpdf->Cell(45, 8, 'Venta', 1, 0, 'C');
        $mpdf->Cell(45, 8, 'Productos', 1, 0, 'C');
        $mpdf->Cell(45, 8, 'Total ($'. $final['final'].')', 1, 0, 'C');
        $mpdf->Cell(45, 8, 'Fecha', 1, 0, 'C');
        $mpdf->Ln();
    
        // Contenido de la tabla de detalles
        $mpdf->SetFont('Arial', '', 9);
        mysqli_data_seek($reporte1, 0);
        while ($producto = mysqli_fetch_array($reporte1)) {
            $id_venta = $producto['id_venta'];
            $productos = $producto['productos'];
            $fecha = $producto['fecha'];
            $total = $producto['total'];
    
            $mpdf->Cell(45, 8, $contador, 1, 0, 'C');
            $mpdf->Cell(45, 8, $productos, 1, 0, 'C');
            $mpdf->Cell(45, 8,'$'. $total, 1, 0, 'C');
            $mpdf->Cell(45, 8, $fecha, 1, 0, 'C');
            $mpdf->Ln();
            $contador++;
        }
    
    
        $mpdf->Output('Reporte 1', 'I');
    
    } else {

        $reporte1 = mysqli_query($con, "SELECT v.id_venta, GROUP_CONCAT(p.nombre SEPARATOR ', ') AS productos, v.fecha, v.total
        FROM venta v
        INNER JOIN detalleventa d ON v.id_venta = d.id_venta
        INNER JOIN inventario i ON d.id_inventario = i.id_inventario
        INNER JOIN producto p ON i.id_producto = p.id_producto
        GROUP BY v.id_venta, v.fecha, v.id_factura");
        $row = mysqli_fetch_array($reporte1);
    
        $id_venta = $row['id_venta'];
        $productos = $row['productos'];
        $fecha = $row['fecha'];
        $total = $row['total'];
        $contador = 1;

        $final = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(subtotal) AS final FROM detalleventa dv
        INNER JOIN venta v ON dv.id_venta = v.id_venta"));
    
        $mpdf = new \Mpdf\Mpdf();
    
        $logoImagePath = '/Barbieland/public/PRODUCTOSIMG/logo.png';
    
    
        $mpdf->SetTitle('Factura');
        $mpdf->AddPage();
    
        // Encabezado de la factura
    
        $mpdf->WriteHTML('<div style="text-align: center;"><img src="' . $logoImagePath . '" alt="Logo" width="150"></div>');
    
        $mpdf->SetFont('Arial', 'B', 14);
        $mpdf->Cell(0, 8, 'Reporte por fecha', 0, 1, 'C');
    
    
        // Detalle de la factura
    
        $mpdf->SetFont('Arial', 'B', 9);
    
        // Cabecera de la tabla de detalles
        $mpdf->Cell(45, 8, 'Venta', 1, 0, 'C');
        $mpdf->Cell(45, 8, 'Productos', 1, 0, 'C');
        $mpdf->Cell(45, 8, 'Total ($'. $final['final'].')' , 1, 0, 'C');
        $mpdf->Cell(45, 8, 'Fecha', 1, 0, 'C');
        $mpdf->Ln();
    
        // Contenido de la tabla de detalles
        $mpdf->SetFont('Arial', '', 9);
        mysqli_data_seek($reporte1, 0);
        while ($producto = mysqli_fetch_array($reporte1)) {
            $id_venta = $producto['id_venta'];
            $productos = $producto['productos'];
            $fecha = $producto['fecha'];
            $total = $producto['total'];
    
            $mpdf->Cell(45, 8, $contador, 1, 0, 'C');
            $mpdf->Cell(45, 8, $productos, 1, 0, 'C');
            $mpdf->Cell(45, 8,'$'. $total, 1, 0, 'C');
            $mpdf->Cell(45, 8, $fecha, 1, 0, 'C');
            $mpdf->Ln();
            $contador++;
        }
    
    
        $mpdf->Output('Reporte 1', 'I');

    }

} elseif(isset($_POST['reporte2'])){

    if(isset($_POST['sucursal'])){

        $sucursal = $_POST['sucursal'];

        $reporte2 = mysqli_query($con, "SELECT p.nombre AS producto, p.categoria, s.nombre, i.cantidad
        FROM producto p
        INNER JOIN inventario i ON p.id_producto = i.id_producto
        INNER JOIN sucursal s ON i.id_sucursal = s.id_sucursal
        WHERE s.id_sucursal = '$sucursal'
        GROUP BY p.nombre, p.categoria, s.nombre, i.cantidad");
        $row = mysqli_fetch_array($reporte2);
    
        $producto = $row['producto'];
        $categoria = $row['categoria'];
        $nombre = $row['nombre'];
        $fecha = $row['cantidad'];
        $contador = 1;
    
    
        $mpdf = new \Mpdf\Mpdf();
    
        $logoImagePath = '/Barbieland/public/PRODUCTOSIMG/logo.png';
    
    
        $mpdf->SetTitle('Factura');
        $mpdf->AddPage();
    
        // Encabezado de la factura
    
        $mpdf->WriteHTML('<div style="text-align: center;"><img src="' . $logoImagePath . '" alt="Logo" width="150"></div>');
    
        $mpdf->SetFont('Arial', 'B', 14);
        $mpdf->Cell(0, 8, 'Reporte de inventarios', 0, 1, 'C');
    
    
        // Detalle de la factura
    
        $mpdf->SetFont('Arial', 'B', 9);
    
        // Cabecera de la tabla de detalles
        $mpdf->Cell(36, 8, 'Inventario', 1, 0, 'C');
        $mpdf->Cell(36, 8, 'Producto', 1, 0, 'C');
        $mpdf->Cell(36, 8, 'Categoría', 1, 0, 'C');
        $mpdf->Cell(36, 8, 'Sucursal', 1, 0, 'C');
        $mpdf->Cell(36, 8, 'Cantidad', 1, 0, 'C');
        $mpdf->Ln();
    
        // Contenido de la tabla de detalles
        $mpdf->SetFont('Arial', '', 9);
        mysqli_data_seek($reporte2, 0);
        while ($info = mysqli_fetch_assoc($reporte2)) {
            $producto = $info['producto'];
            $categoria = $info['categoria'];
            $nombre = $info['nombre'];
            $cantidad = $info['cantidad'];
    
            $mpdf->Cell(36, 8, $contador, 1, 0, 'C');
            $mpdf->Cell(36, 8, $producto, 1, 0, 'C');
            $mpdf->Cell(36, 8, $categoria, 1, 0, 'C');
            $mpdf->Cell(36, 8, $nombre, 1, 0, 'C');
            $mpdf->Cell(36, 8, $cantidad, 1, 0, 'C');
            $mpdf->Ln();
            $contador++;
        }
    
        $mpdf->Output('Reporte 2', 'I');
    } else {
        $reporte2 = mysqli_query($con, "SELECT p.nombre AS producto, p.categoria, s.nombre, i.cantidad
        FROM producto p
        INNER JOIN inventario i ON p.id_producto = i.id_producto
        INNER JOIN sucursal s ON i.id_sucursal = s.id_sucursal
        GROUP BY p.nombre, p.categoria, s.nombre, i.cantidad");
        $row = mysqli_fetch_array($reporte2);
    
        $producto = $row['producto'];
        $categoria = $row['categoria'];
        $nombre = $row['nombre'];
        $fecha = $row['cantidad'];
        $contador = 1;
    
    
        $mpdf = new \Mpdf\Mpdf();
    
        $logoImagePath = '/Barbieland/public/PRODUCTOSIMG/logo.png';
    
    
        $mpdf->SetTitle('Factura');
        $mpdf->AddPage();
    
        // Encabezado de la factura
    
        $mpdf->WriteHTML('<div style="text-align: center;"><img src="' . $logoImagePath . '" alt="Logo" width="150"></div>');
    
        $mpdf->SetFont('Arial', 'B', 14);
        $mpdf->Cell(0, 8, 'Reporte de inventarios', 0, 1, 'C');
    
    
        // Detalle de la factura
    
        $mpdf->SetFont('Arial', 'B', 9);
    
        // Cabecera de la tabla de detalles
        $mpdf->Cell(36, 8, 'Inventario', 1, 0, 'C');
        $mpdf->Cell(36, 8, 'Producto', 1, 0, 'C');
        $mpdf->Cell(36, 8, 'Categoría', 1, 0, 'C');
        $mpdf->Cell(36, 8, 'Sucursal', 1, 0, 'C');
        $mpdf->Cell(36, 8, 'Cantidad', 1, 0, 'C');
        $mpdf->Ln();
    
        // Contenido de la tabla de detalles
        $mpdf->SetFont('Arial', '', 9);
        mysqli_data_seek($reporte2, 0);
        while ($info = mysqli_fetch_assoc($reporte2)) {
            $producto = $info['producto'];
            $categoria = $info['categoria'];
            $nombre = $info['nombre'];
            $cantidad = $info['cantidad'];
    
            $mpdf->Cell(36, 8, $contador, 1, 0, 'C');
            $mpdf->Cell(36, 8, $producto, 1, 0, 'C');
            $mpdf->Cell(36, 8, $categoria, 1, 0, 'C');
            $mpdf->Cell(36, 8, $nombre, 1, 0, 'C');
            $mpdf->Cell(36, 8, $cantidad, 1, 0, 'C');
            $mpdf->Ln();
            $contador++;
        }
    
        $mpdf->Output('Reporte 2', 'I');
    }
    

} elseif(isset($_POST['reporte3'])){

    if(isset($_POST['product'])){

        $product = $_POST['product'];
        
        if($product > 0){

            $selected = "SELECT COUNT(dv.id_detalleventa) AS total 
        FROM venta v
        INNER JOIN detalleventa dv ON v.id_venta=dv.id_venta
        INNER JOIN inventario i ON dv.id_inventario = i.id_inventario
        WHERE i.id_producto='$product'";

        $total = mysqli_fetch_array(mysqli_query($con, $selected));

        $reporte3 = mysqli_query($con, "SELECT GROUP_CONCAT(p.nombre SEPARATOR ', ') AS productos, p.categoria, v.fecha
        FROM venta v
        INNER JOIN detalleventa d ON v.id_venta = d.id_venta
        INNER JOIN inventario i ON d.id_inventario = i.id_inventario
        INNER JOIN producto p ON i.id_producto = p.id_producto
        WHERE i.id_producto = '$product'
        GROUP BY v.id_venta, v.fecha, p.categoria");
        $row = mysqli_fetch_array($reporte3);
    
        $productos = $row['productos'];
        $categoria = $row['categoria'];
        $fecha = $row['fecha'];
        $contador = 1;
    
    
        $mpdf = new \Mpdf\Mpdf();
    
        $logoImagePath = '/Barbieland/public/PRODUCTOSIMG/logo.png';
    
    
        $mpdf->SetTitle('Reporte 3');
        $mpdf->AddPage();
    
        // Encabezado de la factura
    
        $mpdf->WriteHTML('<div style="text-align: center;"><img src="' . $logoImagePath . '" alt="Logo" width="150"></div>');
    
        $mpdf->SetFont('Arial', 'B', 14);
        $mpdf->Cell(0, 8, 'Reporte por producto', 0, 1, 'C');
    
    
        // Detalle de la factura
    
        $mpdf->SetFont('Arial', 'B', 9);
    
        // Cabecera de la tabla de detalles
        $mpdf->Cell(45, 8, 'Ventas ('.$total['total'].') ', 1, 0, 'C');
        $mpdf->Cell(45, 8, 'Productos', 1, 0, 'C');
        $mpdf->Cell(45, 8, 'Categoría', 1, 0, 'C');
        $mpdf->Cell(45, 8, 'Fecha', 1, 0, 'C');
        $mpdf->Ln();
    
        // Contenido de la tabla de detalles
        $mpdf->SetFont('Arial', '', 9);
        mysqli_data_seek($reporte3, 0);
        while ($producto = mysqli_fetch_array($reporte3)) {
            $productos = $producto['productos'];
            $categoria = $producto['categoria'];
            $fecha = $producto['fecha'];
    
            $mpdf->Cell(45, 8, $contador, 1, 0, 'C');
            $mpdf->Cell(45, 8, $productos, 1, 0, 'C');
            $mpdf->Cell(45, 8, $categoria, 1, 0, 'C');
            $mpdf->Cell(45, 8, $fecha, 1, 0, 'C');
            $mpdf->Ln();
            $contador ++;
        }
    
    
        $mpdf->Output('Reporte 1', 'I');

        } else {

            $selected = "SELECT COUNT(id_detalleventa) AS total FROM detalleventa";

            $total = mysqli_fetch_array(mysqli_query($con, $selected));
    
            $reporte3 = mysqli_query($con, "SELECT GROUP_CONCAT(p.nombre SEPARATOR ', ') AS productos, p.categoria, v.fecha
            FROM venta v
            INNER JOIN detalleventa d ON v.id_venta = d.id_venta
            INNER JOIN inventario i ON d.id_inventario = i.id_inventario
            INNER JOIN producto p ON i.id_producto = p.id_producto
            GROUP BY v.id_venta, v.fecha, p.categoria");
            $row = mysqli_fetch_array($reporte3);
        
            $productos = $row['productos'];
            $categoria = $row['categoria'];
            $fecha = $row['fecha'];
            $contador = 1;
        
        
            $mpdf = new \Mpdf\Mpdf();
        
            $logoImagePath = '/Barbieland/public/PRODUCTOSIMG/logo.png';
        
        
            $mpdf->SetTitle('Reporte 3');
            $mpdf->AddPage();
        
            // Encabezado de la factura
        
            $mpdf->WriteHTML('<div style="text-align: center;"><img src="' . $logoImagePath . '" alt="Logo" width="150"></div>');
        
            $mpdf->SetFont('Arial', 'B', 14);
            $mpdf->Cell(0, 8, 'Reporte por producto', 0, 1, 'C');
        
        
            // Detalle de la factura
        
            $mpdf->SetFont('Arial', 'B', 9);
        
            // Cabecera de la tabla de detalles
            $mpdf->Cell(45, 8, 'Ventas ('.$total['total'].') ', 1, 0, 'C');
            $mpdf->Cell(45, 8, 'Productos', 1, 0, 'C');
            $mpdf->Cell(45, 8, 'Categoría', 1, 0, 'C');
            $mpdf->Cell(45, 8, 'Fecha', 1, 0, 'C');
            $mpdf->Ln();
        
            // Contenido de la tabla de detalles
            $mpdf->SetFont('Arial', '', 9);
            mysqli_data_seek($reporte3, 0);
            while ($producto = mysqli_fetch_array($reporte3)) {
                $productos = $producto['productos'];
                $categoria = $producto['categoria'];
                $fecha = $producto['fecha'];
        
                $mpdf->Cell(45, 8, $contador, 1, 0, 'C');
                $mpdf->Cell(45, 8, $productos, 1, 0, 'C');
                $mpdf->Cell(45, 8, $categoria, 1, 0, 'C');
                $mpdf->Cell(45, 8, $fecha, 1, 0, 'C');
                $mpdf->Ln();
                $contador ++;
            }
        
        
            $mpdf->Output('Reporte 1', 'I');
            
        }

        
    
    } else {
        
        $selected = "SELECT COUNT(id_detalleventa) AS total FROM detalleventa";

        $total = mysqli_fetch_array(mysqli_query($con, $selected));

        $reporte3 = mysqli_query($con, "SELECT GROUP_CONCAT(p.nombre SEPARATOR ', ') AS productos, p.categoria, v.fecha
        FROM venta v
        INNER JOIN detalleventa d ON v.id_venta = d.id_venta
        INNER JOIN inventario i ON d.id_inventario = i.id_inventario
        INNER JOIN producto p ON i.id_producto = p.id_producto
        GROUP BY v.id_venta, v.fecha, p.categoria");
        $row = mysqli_fetch_array($reporte3);
    
        $productos = $row['productos'];
        $categoria = $row['categoria'];
        $fecha = $row['fecha'];
        $contador = 1;
    
    
        $mpdf = new \Mpdf\Mpdf();
    
        $logoImagePath = '/Barbieland/public/PRODUCTOSIMG/logo.png';
    
    
        $mpdf->SetTitle('Reporte 3');
        $mpdf->AddPage();
    
        // Encabezado de la factura
    
        $mpdf->WriteHTML('<div style="text-align: center;"><img src="' . $logoImagePath . '" alt="Logo" width="150"></div>');
    
        $mpdf->SetFont('Arial', 'B', 14);
        $mpdf->Cell(0, 8, 'Reporte por producto', 0, 1, 'C');
    
    
        // Detalle de la factura
    
        $mpdf->SetFont('Arial', 'B', 9);
    
        // Cabecera de la tabla de detalles
        $mpdf->Cell(45, 8, 'Ventas ('.$total['total'].') ', 1, 0, 'C');
        $mpdf->Cell(45, 8, 'Productos', 1, 0, 'C');
        $mpdf->Cell(45, 8, 'Categoría', 1, 0, 'C');
        $mpdf->Cell(45, 8, 'Fecha', 1, 0, 'C');
        $mpdf->Ln();
    
        // Contenido de la tabla de detalles
        $mpdf->SetFont('Arial', '', 9);
        mysqli_data_seek($reporte3, 0);
        while ($producto = mysqli_fetch_array($reporte3)) {
            $productos = $producto['productos'];
            $categoria = $producto['categoria'];
            $fecha = $producto['fecha'];
    
            $mpdf->Cell(45, 8, $contador, 1, 0, 'C');
            $mpdf->Cell(45, 8, $productos, 1, 0, 'C');
            $mpdf->Cell(45, 8, $categoria, 1, 0, 'C');
            $mpdf->Cell(45, 8, $fecha, 1, 0, 'C');
            $mpdf->Ln();
            $contador ++;
        }
    
    
        $mpdf->Output('Reporte 1', 'I');

    }
}




?>