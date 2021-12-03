<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
  $database = new PDO("mysql:host=$servername;dbname=pizzaria_sopranos", $username, $password);
  // set the PDO error mode to exception
  $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?> 