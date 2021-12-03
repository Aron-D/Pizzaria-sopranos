<?php
    function f($value) {
        return number_format($value, 2, ',', '.');
    }

    if(isset($_POST['btnBestel'])) { //wanneer de er op de submit knop wordt geklikt gebeurd er het onderstaande

            $opdrachtHaalpizza = $database->prepare("SELECT * FROM pizzas WHERE id = :id");

            
            $totalprice = 0;
            $winkelwagen = [];


            $sql = "INSERT INTO winkelwagen(pid, name, size_id, amount, price ) VALUES(:pid, :nm, :csize, :amt, :pri )"; 
            //Producten worden in de SQL toegevoegd en gaan als opdracht naar de winkelmand
            $opdrachtNaarWinkelwagen = $database->prepare($sql);

        

            foreach($_POST as $key => $amount) { //zodat de id van de pizzas en sizes meegenomen wordt

                if(substr($key, 0, -1) == 'amount' && $amount != 0) {  
                    $opdrachtHaalpizza->execute(
                        [":id" => substr($key, -1)] // haal de pizza op wanneer de waarde hoger dan 0 is
                    );
                    $row = $opdrachtHaalpizza->fetch(PDO::FETCH_OBJ);
                    $opdrachtNaarWinkelwagen->execute([ //in een array wordt alles geselecteerd en doorverstuurd naar winkelmand
                        ":pid"   => $row->id,
                        ":nm"    => $row->name,
                        ":csize" => $_POST["sellpizza".$row->id],
                        ":amt"   => $amount,
                        ":pri"   => $row->price,
                    ]);

                    echo "Pizza ".$row->name ." is besteld voor $amount keer<br>&euro; ".f($amount * $row->price)."<hr>";
                    $totalprice += $amount * $row->price; // Berekening voor totaalbedrag
                }

            }
            
            echo  "<h1>De totale prijs: " . f($totalprice);
            echo  "<h1>De totale prijs (Incl. korting): " . f($totalprice / 2);
            echo "<br><br>";
            echo "<a href='winkelmand.php'> Ga naar winkelmand </a>";


            exit();
    }

?>