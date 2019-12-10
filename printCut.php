<?php
    include_once ("global.php");

    /*******************************************************************/
    
    date_default_timezone_set("America/Mexico_City");
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
    if ($_GET["key"] == $KEY_PAGE){
        $egCash = number_format($_GET["tEgCash"], 2, ".", ",");
        $egOther = number_format($_GET["tEgOther"], 2, ".", ",");
        $ingCash = number_format($_GET["tIngCash"], 2, ".", ",");
        $ingOther = number_format($_GET["tIngOther"], 2, ".", ",");
        //
        $printer -> setEmphasis(true);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> setTextSize(1,2);
        $printer -> text($_GET["nomSuc"]."\n");     //Nombre del Lugar
        $printer -> setUnderline(Printer::UNDERLINE_DOUBLE);
        $printer -> text("                               \n");
        $printer -> setEmphasis(false);
        $printer -> setTextSize(1,1);
        $printer -> setUnderline(Printer::UNDERLINE_NONE);
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> feed(1);
        $printer -> text("EGRESOS\n");
        $printer -> text("   Efectivo: $".$egCash."\n");
        $printer -> text("   Otros: $".$egOther."\n");
        $printer -> feed(1);
        $printer -> text("INGRESOS\n");
        $printer -> text("   Efectivo: $".$ingCash."\n");
        $printer -> text("   Otros: $".$ingOther."\n");
        $printer -> setUnderline(Printer::UNDERLINE_DOUBLE);
        $printer -> text("                               \n");
        $printer -> setUnderline(Printer::UNDERLINE_NONE);
        $printer -> feed(1);
        //Total
        $printer -> text("TOTAL\n");
        $totalEf = (float) $_GET["tIngCash"] - (float) $_GET["tEgCash"];
        $totalOt = (float) $_GET["tIngOther"] - (float) $_GET["tEgOther"];
            //Format
            $frmToEf = number_format($totalEf, 2, ".", ",");
            $frmToOth = number_format($totalOt, 2, ".", ",");

        $printer -> text("   Efectivo: $".$frmToEf."\n");
        $printer -> text("   Otros: $".$frmToOth."\n");
        
        $printer -> feed(3);
    
        //Imprimir Contenido
        $printer -> cut();
        $printer -> close();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ticket CUT</title>
</head>
<body>
    <script>
         <?php echo "window.location = ".$RETURN_URL_FINAL; ?>
    </script>
</body>
</html>