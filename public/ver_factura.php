<?php

include('php/conexion.php');

$con=conexion();

session_start();

$id = $_SESSION['id_usuario'];

        require_once('fpdf.php');
        require_once 'vendor/autoload.php';

        $perfil = $_GET['id_factura'];


        $facturas = mysqli_query($con, "SELECT p.nombrePerfil, p.RFC, p.razonSocial, p.regimenFiscal, p.CP, pr.nombre AS producto, s.nombre AS sucursal, d.cantidad, d.precio, d.subtotal, s.nombre AS sucursal
        FROM factura f
        INNER JOIN perfilfactura p ON f.id_perfilFactura=p.id_perfilFactura
        INNER JOIN venta v ON f.id_factura=v.id_factura
        INNER JOIN detalleventa d ON v.id_venta = d.id_venta
        INNER JOIN inventario i ON i.id_inventario = d.id_inventario
        INNER JOIN sucursal s ON s.id_sucursal = i.id_sucursal
        INNER JOIN producto pr ON i.id_producto = pr.id_producto
        WHERE f.id_factura=$perfil
        GROUP BY p.nombrePerfil, p.razonSocial, p.regimenFiscal, p.CP, pr.nombre, s.nombre, d.cantidad, d.precio, d.subtotal");
        $row = mysqli_fetch_array($facturas);


        $nombrePerfil = $row['nombrePerfil'];
        $RFC = $row['RFC'];
        $razonSocial = $row['razonSocial'];
        $regimenFiscal = $row['regimenFiscal'];
        $CP = $row['CP'];
        $sucursal = $row['sucursal'];
        

        $mpdf = new \Mpdf\Mpdf();

        $logoImagePath = '/Barbieland/public/PRODUCTOSIMG/logo.png';


        $mpdf->SetTitle('Factura');
        $mpdf->AddPage();
        
        // Encabezado de la factura

        $mpdf->WriteHTML('<div style="text-align: center;"><img src="' . $logoImagePath . '" alt="Logo" width="150"></div>');

        $mpdf->SetFont('Arial', 'B', 14);
        $mpdf->Cell(0, 8, 'Emisor', 0, 1, 'L');
        
        $mpdf->SetFont('Arial', '', 10);
        $mpdf->Cell(0, 4, 'Barbieland '. $sucursal, 0, 1, 'L');
        $mpdf->Cell(0, 4, 'Tel: 1234567890', 0, 1, 'L');
        $mpdf->Cell(0, 4, 'Razón Social: Barbieland', 0, 1, 'L');
        $mpdf->Cell(0, 4, 'Regimen Fiscal: 601 - General de Ley Personas Morales', 0, 1, 'L');
        $mpdf->Ln();


        $mpdf->SetFont('Arial', 'B', 14);
        $mpdf->Cell(0, 8, 'Cliente', 0, 1, 'L');
        
        $mpdf->SetFont('Arial', '', 10);
        $mpdf->Cell(0, 4, 'Numero de Factura: ' . $perfil, 0, 1, 'L');
        $mpdf->Cell(0, 4, 'Cliente: ' . $nombrePerfil, 0, 1, 'L');
        $mpdf->Cell(0, 4, 'RFC: ' . $RFC, 0, 1, 'L');
        $mpdf->Cell(0, 4, 'Razón Social: ' . $razonSocial, 0, 1, 'L');
        $mpdf->Cell(0, 4, 'Régimen Fiscal: ' . $regimenFiscal, 0, 1, 'L');
        $mpdf->Cell(0, 4, 'CP ' . $CP, 0, 1, 'L');
        $mpdf->Ln();
        
        // Detalle de la factura
        $mpdf->SetFont('Arial', 'B', 14);
        $mpdf->Cell(0, 15, 'Detalle de la factura:', 0, 1, 'L');
        
        $mpdf->SetFont('Arial', 'B', 9);
        
        // Cabecera de la tabla de detalles
        $mpdf->Cell(25, 8, 'Cantidad', 1, 0, 'C');
        $mpdf->Cell(35, 8, 'Producto', 1, 0, 'C');
        $mpdf->Cell(50, 8, 'Sucursal', 1, 0, 'C');
        $mpdf->Cell(35, 8, 'Precio unitario', 1, 0, 'C');
        $mpdf->Cell(35, 8, 'Importe', 1, 0, 'C');
        $mpdf->Ln();
        
        // Contenido de la tabla de detalles
        $total = 0;
        $mpdf->SetFont('Arial', '', 9);
        mysqli_data_seek($facturas, 0);
        while ($producto = mysqli_fetch_array($facturas)) {
          $cantidad = $producto['cantidad'];
            $nombreProducto = $producto['producto'];
            $sucursal = $producto['sucursal'];
            $precioUnitario = $producto['precio']/1.16;
            $importe = $precioUnitario * $cantidad;
            $subtotal += $importe;
            $IVA = $subtotal*0.16;
            $total = $subtotal + $IVA;
        
            $mpdf->Cell(25, 8, $cantidad, 1, 0, 'C');
            $mpdf->Cell(35, 8, $nombreProducto, 1, 0, 'C');
            $mpdf->Cell(50, 8, $sucursal, 1, 0, 'C');
            $mpdf->Cell(35, 8, '$' . number_format($precioUnitario, 2), 1, 0, 'C');
            $mpdf->Cell(35, 8, '$' . number_format($importe, 2), 1, 0, 'C');
            $mpdf->Ln();
        }
        
        // Total
        $mpdf->Cell(145, 8, 'Subtotal  ', 1, 0, 'R');
        $mpdf->Cell(35, 8, '$' . number_format($subtotal, 2), 1, 1, 'C');

        $mpdf->Cell(145, 8, 'IVA  ', 1, 0, 'R');
        $mpdf->Cell(35, 8, '$' . number_format($IVA, 2), 1, 1, 'C');

        $mpdf->Cell(145, 8, 'Total  ', 1, 0, 'R');
        $mpdf->Cell(35, 8, '$' . number_format($total, 2), 1, 1, 'C');

        // Footer
        $mpdf->SetY(-40);
        $mpdf->SetFont('Arial', 'I', 10);
        $mpdf->Cell(0, 10, 'Gracias por su compra', 0, 1, 'C');
        $mpdf->Cell(0, 10, 'Para cualquier consulta, por favor contacte a nuestro equipo de soporte.', 0, 1, 'C');


        $nombreArchivoDescarga = 'factura_'.$num.'.pdf';
        $mpdf->Output($nombreArchivoDescarga, 'I');

?>