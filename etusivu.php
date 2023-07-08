<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<link rel="stylesheet" href="lomake.css">
<title>Etusivu</title>
</head>
<body class="comp">
  <nav>
      <h1>Uusi lomake</h1>
<ul>
  <li><a href="lista.php">Esitietolomake</a></li>
  <li><a href="vastaanottotarkastus.php">Vastaanottotarkastuspöytäkirja</a></li>
  <li><a href="tarkastuslista.php">Vastaanottotarkastajan tarkastuslista</a></li>
</ul>

<h1>Täytetyt lomakkeet</h2>
<ul>
  <li><a href="arkisto.php">Arkisto</a></li>
</ul>
</nav>

<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "listaprojekti";

// Luo yhteys
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");


$conn->close();


?>
</body>
</html>
