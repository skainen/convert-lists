<!DOCTYPE html>
<html>
<head>
  <title>Esitietotarkastus</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://www.istekki.fi/istekki-theme-v2/images/favicon.ico" type="image/x-icon" rel="icon">
<meta charset="UTF-8">
<link rel="stylesheet" href="lomake.css">
</head>
<body>

<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "listaprojekti";
$_SESSION["vastaukset"]=array();
$date=date("Y-m-d");


// Luo yhteys
$conn = new mysqli($servername, $username, $password, $dbname);//
$conn->set_charset("utf8");

// Tarkastaa yhteyden
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
else{
//SQL -kysely
$sql = "SELECT * FROM esitieto_kysymykset";
$result = mysqli_query($conn, $sql);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.
if (mysqli_num_rows($result) > 0){
 echo " <!--Luodaan taulukko-->
  <table>
      <!--Taulukon ensimmäinen rivi on lomakkeen otsikko-->
      <tr><th colspan='2' style='font-size:140%;'>Esitietolomake</th></tr>
      <!--Luodaan taulukon sisälle HTML-lomake-->
      <form action='./lista.php' method='post'>" ;

      $i=0;
      $vastaus=$_POST['vastaus'];
      $_SESSION['vastaukset']=$vastaus;
      

  while($row = mysqli_fetch_assoc($result)) {
    $kysy = $row["kysymys"];
    
    echo "<tr><td><b>$kysy</b></td>";
    echo "<td class='vasen'>".$_POST["vastaus"][$i]."</td></tr>";
    $i++;

  }
  echo "</table>";
} else {
// Jos tuloksia 0, tulostetaan tieto.
  echo "0 results";
}
echo '<input type="submit" id="muokkaa" name="etusivulle" value="Muokkaa&#9998;"><br><br><br>
</form><br>';

echo '<form action="./tallennus.php" method="post">';
echo "<input type='submit' id='tallenna' value='Tallenna'>";

echo '</form>';
//Lopuksi tietokantayhteyden katkaiseminen.
$conn->close();
}

?>

</body>
</html>
