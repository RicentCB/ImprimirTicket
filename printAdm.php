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

    /**********************************************************
        * ---------    S  U  C  U  R  S  A  L   --------- *    
    **********************************************************/
    $printer -> setEmphasis(true);
    $printer -> text($_GET["namePl"]."\n");     //Nombre del Lugar
    $printer -> setEmphasis(false);
    //Nombre de Usuario
    $prefSpaceUs = "";
    for ($i=0; $i < (18 - strlen($_GET["user"])); $i++) $prefSpaceUs .= " ";
    //ID de Venta
    $prefZero ="";
    $prefSpace = "";
    for ($i=0; $i < (12 - strlen($_GET["idSale"])); $i++) {
        $prefZero .= "0";
        $prefSpace .= " ";
    }
    //Datos de Venta
    $printer -> text("-------------------------------\n");
    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $printer -> text("Ticket No:"."    ".$prefSpace."NVE  ".$_GET["idSale"]."\n");
    $printer -> text("Fecha:               ".date("d-m-Y")."\n");
    $printer -> text("Hora:                  ".date("H:i:s")."\n");
    $printer -> text("Atendido por:".$prefSpaceUs.$_GET["user"]."\n");
    /* ---------------------------------------------------- */
    /* -------------------- C U E R P O ------------------- */
    /* ---------------------------------------------------- */
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
    $printer -> feed(3);

    //Imprimir Contenido
    $printer -> cut();
    $printer -> close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ticket Admn</title>
</head>
<body>
    <script>
         <?php echo "window.location = ".$RETURN_URL_FINAL; ?>
    </script>
</body>
</html>