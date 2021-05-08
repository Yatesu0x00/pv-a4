<?php
    function success($filternr, $wert1, $tol1, $wert2, $tol2, $guete)
    {
        return "<!DOCTYPE html>
        <html lang=\"de\">
        <title>Datenbank</title>
				<head>
					<meta charset=\"utf-8\">
                    <style>
                    body
                    {
                        background-color: lightgrey;
                    }
                    #success 
                    {                
                        font-size:14px;
                        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                        color: blue;
                    }
                    #back{
                        font-size: 14px;                    
                        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                        color: purple;
                    }
                    </style>
                </head>     
                <body> 
                    <form>           
                        <div id=\"success\">Filternummer: $filternr, Wert1: $wert1, Tol1: $tol1, Wert2: $wert2, Tol2: $tol2, Guete: $guete</div>
                        <p>
                        <a id=\"back\" href=\"index.html\">Neuen Datensatz eingeben</a> 
                        </p>
                    </form>
                </body>
        </html>";
    }
    
    function error($str)
    {
        return "<!DOCTYPE html>
        <html lang=\"de\">
        <title>Datenbank</title>
				<head>
					<meta charset=\"utf-8\">
                    <style>
                    body
                    {
                        background-color: lightgrey;
                    }
                    #errorText
                    {                
                        font-size:14px;
                        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                        color: red;
                    }
                    #back
                    {
                        font-size: 14px;                     
                        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                    } 
                    </style>
                </head>     
                <body> 
                    <form>    
                        <p id = \"errorText\">$str</p>       
                        <p>
                            <a id=\"back\" href=\"index.html\">Zurück</a> 
                        </p>
                    </form>
                </body>
        </html>";
    }
    
    $db = mysqli_connect("localhost", "root", "", "it31_goralewski");

    if (mysqli_connect_errno())
    {
        printf("Verbindung fehlgeschlagen: " . mysqli_connect_error());
        exit();
    }
    
    $filternr = mysqli_real_escape_string($db, $_POST['filternr']);
    $xmax = mysqli_real_escape_string($db, $_POST['xmax']);
    $xmin = mysqli_real_escape_string($db, $_POST['xmin']);

    $avg = 128;
    $avgPlus = $avg * 1.1;
    $avgMinus = $avg * 0.9;

    if($xmax < $avgPlus && $xmax > $avgMinus)
    {
        $tol1 = "j";
    }
    else
    {
        $tol1 = "n";
    }

    if($xmin < $avgPlus && $xmin > $avgMinus)
    {
        $tol2 = "j";
    }
    else
    {
        $tol2 = "n";
    }

    if($tol1 == "j" && $tol2 == "j")
    {
        $guete = 1;
    }
    else if($tol1 == "n" && $tol2 == "n")
    {
        $guete = 3;
    }
    else
    {
        $guete = 2;
    }
    
    $query = "SELECT filternr, wert1, tol1, wert2, tol2, guete  FROM filtertest WHERE filternr = '$filternr' and wert1 = '$xmax' and tol1='$tol1' and wert2='$xmin' and tol2='$tol2' and guete='$guete'";
    $res = mysqli_query($db, $query);

    if(!$row = mysqli_fetch_assoc($res))
    {
        $query = "INSERT INTO filtertest (filternr, wert1, tol1, wert2, tol2, guete) VALUES ('$filternr','$xmax','$tol1','$xmin','$tol2','$guete')";
        mysqli_query($db, $query) or die(error(mysqli_error($db)));        
        echo(success($filternr, $xmax, $tol1, $xmin, $tol2, $guete));        
    } 
?>