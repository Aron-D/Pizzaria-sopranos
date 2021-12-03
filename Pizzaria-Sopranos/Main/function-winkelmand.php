<?php
 if(isset($_POST['delete'])) {
 $stmt = $database->prepare("TRUNCATE TABLE `winkelwagen`");
 $stmt->execute();
 }

 if(isset($_POST['continue'])) {
     $naarKlantTabel = "INSERT INTO customer(name, last_name, email, phone_number ) VALUES(:nm, :ln, :em, :pn )";
     $opdrachtNaarKlant = $database->prepare($naarKlantTabel);
     $opdrachtNaarKlant->execute([
         "nm" => $_POST['fname'],
         "ln" => $_POST['lname'],
         "em" =>$_POST['email'],
         "pn" =>$_POST['phone'],
     ]);
     $ordn = "PizzaOrd" . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT); 
     $haalWinkelmand = $database->prepare("SELECT w.id, w.pid, w.name, w.size_id, w.amount, w.price, s.name size FROM `winkelwagen` w
     inner join sizes s on w.size_id = s.id");
     $haalWinkelmand->execute();
     $row = $haalWinkelmand->fetch(PDO::FETCH_OBJ);
     $naarOrder = "INSERT INTO orders(pid, size_id, total_price, locatie, order_number) VALUES(:pid, :si, :tp, :lo, :od)";
     $opdrachtNaarOrder = $database->prepare($naarOrder);
     $opdrachtNaarOrder->execute([
         "pid"=> $row->pid,
         "si" => $row->size_id,
         "tp" => $_POST['total'],
         "lo" => $_POST['ves'],
         "od" => $ordn,
     ]);

     $haalKlant = $database->prepare("SELECT * FROM customer");
     $haalKlant->execute();
     $klant = $haalKlant->fetch(PDO::FETCH_OBJ);
     $haalWinkelmandDoc = $database->prepare("SELECT w.id, w.pid, w.name, w.size_id, w.amount, w.price, s.name size FROM `winkelwagen` w
     inner join sizes s on w.size_id = s.id");
     $haalWinkelmandDoc->execute();
     $haalOrder = $database->prepare("SELECT * FROM orders");
     $haalOrder->execute();
     $order = $haalOrder->fetch(PDO::FETCH_OBJ);
     $totalprice = 0;
     //email opbouw start hier //
     echo "<img src='img/sopranos-logo.png' style:'height:200px;'<br><br>";
     echo "<br><h1>Email</h1>"; //Begin kopstuk
     echo  "<br><br><h3>to:</h3> $klant->email <br><br>";
     echo  "<h3>from:</h3> $order->locatie"."@sopranos.nl <br><br>";
     echo  "<h3>Subject:</h3>Bedankt voor uw bestelling! ($order->order_number)<br><br><br><hr>";
     echo  "<p>Beste $klant->name,<br><br>U heeft vandaag een een bestelling bij ons vestiging geplaatst.<br>Bedankt! wij zullen zo spoedig mogelijk met uw order aan de slag gaan.<br>";
     echo  "Geschatte tijd dat uw order bereid is: 30-45 minuten.<br>Dit is uw informatie:</p><br><br>"; //Eind kopstuk
     echo  "<h2>Uw klantgegevens:</h2>"; //KLANTGEGEVENS
     echo  "Volledige naam: $klant->name $klant->last_name <br>";
     echo  "Email: $klant->email <br>";
     echo  "Telefoonnummer: $klant->phone_number <br><br>"; //EIND KlANTGEGEVENS
     echo  "<h2>Order Informatie:</h2>"; //ORDER INFO
     echo  "Order besteld op: <ul>$order->date</ul> <br>";
     echo  "Gekozen afhaallocatie: <ul>Sopranos Pizzaria $order->locatie</ul><br>";
     echo  "uw Ordernummer: <ul>$order->order_number</ul><br><br>"; //EIND ORDER INFO
     echo  "<table class='tbl-cart' cellpadding='6' cellspacing='1'>"; //TABLE voor de producten. net als bij de winkelmand pagina
     echo  "<tbody>";
     echo  "<tr>";
     echo  "<th style='text-align:left; width=10%'>Uw Pizza's</th><th style='text-align:left; width=10%';'>Aantal</th><th style='text-align:left; width=10%;'>Formaat</th><th style='text-align:left; width=10%;'>Prijs per stuk</th><br>";
     echo  "</tr>";
     echo  "<h2>Bestelling:</h2>";
     while($row3 = $haalWinkelmandDoc->fetch(PDO::FETCH_OBJ)) { //loopen voor een mooiere tabel
     echo  "<tr>";
     echo "<td>$row3->name</td><td>$row3->amount</td><td>$row3->size</td><td>$row3->price</td><br>";
     $totalprice += $row3->amount * $row3->price; //Berekening voor totale prijs
     }
     echo  "</tr>"; 
     echo  "<td></td>";
     echo  "</tr>";
     echo  "</tbody>";
     echo  "</table>"; //EINDE TABLE
     echo  "<h2>Totaal Bedrag: €$totalprice,-</h2>";
     echo  "<h2>Totaal Bedrag (Incl. korting): €$order->total_price,-<hr></h2>"; //Korting is in het SQL opgenomen
     echo  "<br><br>";
     echo  "<h2>Vestiging informatie:</h2>"; //Pizzaria Informatie
     echo  "<h4>Wij zijn bereikbaar op Ma/Vr van 16:00 - 22:00.</h4>";
     echo  "<h4>U kunt ons bereiken op:</h4> 06 - 34 53 22 46.<br><br>";
     echo  "<h4>Onze locaties:</h4>(Pizzaria Sopranos Amsterdam) - Veldzicht 253B, 1068 PR Amsterdam,<br>(Pizzaria Sopranos Rotterdam) - Zwart Janstraat 30A, 3035 AT Rotterdam,<br>(Pizzaria Sopranos Utrecht) - Jan van Scorelstraat 33, 3583 CK Utrecht.<br><br>";
     echo  "<br><h2><a href='index.html'> Ga naar Homepagina </a></h2>";

     $winkelmandLegen = $database->prepare("TRUNCATE TABLE `winkelwagen`"); //Winkelwagen is niet meer nodig na het afronden
     $winkelmandLegen->execute();
     $customerLegen = $database->prepare("TRUNCATE TABLE `customer`");
     $customerLegen->execute();
     $ordersLegen = $database->prepare("TRUNCATE TABLE `orders`");
     $ordersLegen->execute();
     exit();
    }
?>