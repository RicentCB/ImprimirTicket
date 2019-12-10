<?php

    include_once ("global.php");

    /*******************************************************************/
    $keyPage = "ARC0IRIS123RBTECV0C123";
    date_default_timezone_set("America/Mexico_City");
    /* Call this file 'hello-world.php' */
    require __DIR__ . '/vendor/autoload.php';
    use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
    use Mike42\Escpos\EscposImage;
    use Mike42\Escpos\Printer;
    //Constructor
    $PrintName = $PRINTER_NAME;
    $connector = new WindowsPrintConnector($PrintName);
    $printer = new Printer($connector);
    
    /*==============================================================
        P  R  I  N  T      T  I  C  K  E  T      C  L  I  E  N  T
    ==============================================================*/
    if(isset($_GET["key"])){
        if($_GET["key"] == $keyPage){
            /*-------------------------------------------*/
            /*------------- C A B E C E R A -------------*/
            /*-------------------------------------------*/
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            //Logo
            try{
                $logo = EscposImage::load("img/logo-completo-100.jpg", true);
                $printer -> bitImage($logo);
            }catch(Exception $e){ var_dump($e); }
            //Datos de la sucursal
            $printer -> text("-------------------------------\n");
            $printer -> setEmphasis(true);
            $printer -> text($_GET["namePl"]."\n");     //Nombre del Lugar
            //Domicilio
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer -> setEmphasis(false);
            $printer -> text($_GET["streetPl"].", ");
            $printer -> text($_GET["negPl"]."\n");
            $printer -> text($_GET["cityPl"]."\n");
            $printer -> text("Tel: ".$_GET["phonePlace"]."\n");
            
            //ID de Venta
            $prefZero ="";
            $prefSpace = "";
            for ($i=0; $i < (12 - strlen($_GET["idSale"])); $i++) {
                $prefZero .= "0";
                $prefSpace .= " ";
            }
            //Nombre de Usuario
            $prefSpaceUs = "";
            for ($i=0; $i < (18 - strlen($_GET["user"])); $i++) $prefSpaceUs .= " ";
            //Datos de Venta
            $printer -> text("-------------------------------\n");
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer -> text("Ticket No:"."    ".$prefSpace."NVE  ".$_GET["idSale"]."\n");
            $printer -> text("Fecha:               ".date("d-m-Y")."\n");
            $printer -> text("Hora:                  ".date("H:i:s")."\n");
            $printer -> text("Atendido por:".$prefSpaceUs.$_GET["user"]."\n");
            /*-------------------------------------------*/
            /*--------------- C U E R P O ---------------*/
            /*-------------------------------------------*/
            $allProducts = $_GET["products"];
            $allProducts = urldecode($allProducts);
            $allProducts = base64_decode($allProducts);
            $allProducts = unserialize($allProducts);
            $printer -> text("-------------------------------\n");
            $printer -> setEmphasis(true);
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer -> text("Cant. Descripcion del Producto\n");
            $printer -> text("      Precio Uni.        Importe\n");
            $printer -> setEmphasis(false);
            //Lista de Productos
            $printer -> text("-------------------------------\n");
            $totalGlobal = 0;
            foreach($allProducts as $product){
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $printer -> text("  ".$product["Cant"]." | ");
                $printer -> text($product["Desc"]."\n");
                //Precio
                $price = '$'.number_format($product["Precio"], 2, ".", ",");
                //Total
                $total = (float) $product["Precio"] * (int) $product["Cant"];
                $totalGlobal = $totalGlobal + $total;
                $total = '$'.number_format($total, 2, ".", ",");
                //Imprimir
                $printer -> setJustification(Printer::JUSTIFY_RIGHT);
                $printer -> text("$price        $total\n");
            }
            /*-------------------------------------------*/
            /*---------------   P  I  E   ---------------*/
            /*-------------------------------------------*/
            $printer -> text("-------------------------------\n");
            $prefSpaceTotal = "";
            $totalLetras = $totalGlobal;
            $totalGlobal = '$'.number_format($totalGlobal, 2, ".", ",");
            for ($i=0; $i < (24 - strlen($totalGlobal)); $i++) $prefSpaceTotal .= " ";
            $printer -> setJustification(Printer::JUSTIFY_RIGHT);
            $printer -> text("TOTAL:".$prefSpaceTotal.$totalGlobal."\n\n");
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer -> text(valorEnLetras($totalLetras));
            $printer -> feed(2);
            //Ultimo Pie
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("En devolucion o aclacarion\n presentar este ticket\n\n");
            $printer -> text("Lo invitamos nos visite en:\nwww.arcoiris.com\n\n");
            $printer -> text("¡¡¡Gracias por su Compra!!!\n");
            $printer -> setBarcodeHeight(70);
            // $printer -> barcode($prefZero.$_GET["idSale"], Printer::BARCODE_UPCA);
            $printer -> feed(1); //4
            $printer -> cut();
            $printer -> text("===============================\n");
            
        }
    }
    $printer -> cut();
    $printer -> close();

    $urlAdm = "";
    foreach ($_GET as $key => $value) {
        if ($key != "")
            $urlAdm .= $key."=".$value."&";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">

    <script src="plugins/sweetalert2/sweetalert2.all.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>

    <title>Imprimir Ticket Cliente</title>
</head>
<body>
    <div id="back"></div>
    <script>
        swal({
	    type: 'success', 
	    title: 'No olvides entregar el ticket al cliente',
	    showConfirmButton: true,
	    confirmButtonText: 'Continuar' ,
        }).then((result=>{ 
            	if(result.value) 
            		window.location = '<?php echo $SERVER_LOCAL_ADDR.$urlAdm ?>'
            }))
    </script>
</body>
</html>
