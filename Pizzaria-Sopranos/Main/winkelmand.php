<?php include('database.php');
    require_once "function-winkelmand.php";?>
<?php error_reporting(E_ERROR | E_PARSE); ?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/newstyle.css">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <title>Winkelmand</title>
</head>
<body>
    <img src="img/pizza.jpg" style="position: absolute; width: 100%; overflow: hidden;" alt="pizza_achtergrond">
    <header>
    <div id="logo"><a href="index.html";><img src="img/sopranos-logo.png" alt="logo"></a></div>
        
<div style="position: absolute; left: 90%; top: 50%;"><a href="menu.php">
<img src="img/pizza-icon.png" style="width: 81px; height: 82px;" alt="pizza-icoon" height="50px" width="50px"></a></div>
    </header>

    <div class="content">
    <?php

    $stmt= $database->prepare("SELECT w.id, w.name, w.amount, w.price, s.name size FROM `winkelwagen` w  
     inner join sizes s on w.size_id = s.id"); //Alles uit winkelmand selecteren en size meenemen met inner join
    $stmt->execute();
    

    ?>
    <div class="cart">
    <form method="post" action=""><input id="del" type="submit" name="delete" value="Verwijder producten"></form>
    <form method="post" action="">

            <table class="tbl-cart" cellpadding="6" cellspacing="1">
            <tbody>
            <tr>
            <th style="text-align:left;">Pizza</th>
            <th style="text-align:left;">Formaat</th>
            <th style="text-align:right;" width="5%">Aantal</th>
            <th style="text-align:right;" width="5%">Prijs</th>
            </tr>	
            <?php 
            $totalprice = 0; //variable een waarde geven
            $amount = 0;     //variable een waarde geven
            while($row = $stmt->fetch(PDO::FETCH_OBJ)) { //een loop maken zodat alle producten in het tabel zichtbaar zijn
                        echo "<tr>";
                        echo    "<td><input type='hidden'  value='$row->id'> $row->name </td>";
                        echo    "<td><input type='hidden'  value='$row->size_id'> $row->size </td>";
                        echo    "<td style='text-align:right;'><input type='hidden' name='hidpid' value='$row->amount'> $row->amount </td>";
                        echo    "<td  style='text-align:right;'><input type='hidden' name='hidpid' value='$row->price'>€$row->price </td>";
                        // echo    "<td  style='text-align:right;'> $totalprice </td>";
                        $totalprice += $row->amount * $row->price; //Berekening voor de totale prijs
                        $amount += $row->amount; //optelling van het aantal pizzas
                        $totalpriceDisc = $totalprice / 2; 
        }
                        // echo "<input type='hidden' name='order-nr' value='$ordn'>";
                        echo "<td align='left' colspan='1'>Totale aantal:<br> $amount </td>";
                        echo "<td align='right' colspan='2'><strong>Totale prijs:<br>€$totalprice</strong></td>";
                        echo "<td><input type='hidden' name='total' value='".number_format($totalpriceDisc, 2, '.', ',')."'>Totale prijs (incl. korting): <br>€".number_format($totalpriceDisc, 2, '.', ',');  "</td>";
        ?>
        </tr>
            </tbody>
            </table>

    </div>
    <div class="form-customer">
    <label>Voornaam/Voorletter(s)</label>
    <input type="text" name="fname" placeholder="Uw voornaam" required>

    <label>Achternaam</label>
    <input type="text" name="lname" placeholder="Uw achternaam.." required>

    <label>Email</label>
    <input type="text" name="email" placeholder="Uw email.." required>

    <label>Telefoonnummer</label>
    <input type="text" name="phone" placeholder="Uw Telefoonnummer.." required>

    <label>Afhaal locaties</label>
    <select name="ves" required>
      <option value="rotterdam">Sopranos pizza Rotterdam</option>
      <option value="amsterdam">Sopranos pizza Amsterdam</option>
      <option value="utrecht">Sopranos pizza Utrecht</option>
    </select>

    <input id="afr" type="submit" name="continue" value="Afronden">
    </div>
    </form>
    </div>
    
<footer>
<h1><br> info@sopranos.nl <br> 06 - 34 53 22 46 </h1>
</footer>
</body>
</html>