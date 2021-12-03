<?php
    include('database.php');
    require_once "function-bestel.php";
    ?>
<?php // Formaat aanroepen en dropdown ervan voegen voor de form
function makeSelect($pid) {
    global $database;

    $stmt = $database->prepare("SELECT * FROM sizes");
    $stmt->execute();

    $s = "<SELECT name='sellpizza$pid'>";
    
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) { //loopen
        $s .= "<option value='{$row->id}'>{$row->name}</option>";
    }
    $s .= "</SELECT>";
    return $s;

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/newstyle.css">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <title>menu</title>
</head>
<body>
    <img src="img/pizza.jpg" style="position: absolute; width: 100%; overflow: hidden;" alt="pizza_achtergrond">
    <header>
<div id="logo"><a href="index.html";><img src="img/sopranos-logo.png" alt="logo"></a></div>
        
<div style="position: absolute; left: 90%; top: 50%;"><a href="winkelmand.php"><img src="img/cart-icon.png"  alt="cart-icon" height="50px" width="50px"></a></div>
    </header>

    <div class="content2">
    <?php
$stmt = $database->prepare("SELECT * FROM pizzas"); //alles selecteren van de pizza tabel
$stmt->execute();

    ?>

     <div class="menubox">
     <form method="post" action=""> 
     <input type="submit" value="Toevoegen in winkelmand" name="btnBestel" class="tov" /><br>
    <?php
        while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            // echo "<pre>".print_r($row, true)."</pre>";}

            echo "\n<img width='120' src='$row->image'>"; //productfoto's
            echo "\n\t<label for='pizzas' style='position: relative; left: -1%;'>$row->name</label>";
            echo "\n\t<input type='hidden' name='hidpid' value='pid$row->id'>";
            echo makeSelect($row->id) . "<br>"; //De formaat dropdown met de id's gekoppeld zie lijn 6
            echo "\n\t<label>Bedrag €</label><input type='text' disabled name='pprice' value='".number_format($row->price, 2, '.', ',')."'><br>"; //de prijs per product
            echo "\t<label>Aantal</label><br><input type='number' class='product-quantity' name='amount$row->id' min='0' value='0'/><br>"; //de aantal pizza's dat je wilt. de waarde kan niet onder de nul komen.
        }
    ?>

</form>
  </div>
    </div>

<footer>
    <h1><br> info@sopranos.nl <br> 06 - 34 53 22 46 </h1>
</footer>
</body>
</html>