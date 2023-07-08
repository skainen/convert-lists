<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<link rel="stylesheet" href="lomake.css">
<title>Tarkastuslistan tarkastus</title>
</head>
<body>

<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "listaprojekti";
$_SESSION["vastaukset"]=array();
$_SESSION["vastaukset"]=$_POST['vastaus'];
$laitetunnus=$_POST['laitetunnus'];
$_SESSION['laitetunnus']=$laitetunnus;
$sarjanumero=$_POST['sarjanumero'];
$_SESSION['sarjanumero']=$sarjanumero;
$laite=$_POST['kauppanimike'];
$_SESSION['kauppanimike']=$laite;
$date=date("Y-m-d");


// Luo yhteys
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");


// Tarkastaa yhteyden
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
else{



//SQL -kysely

$sql = "SELECT * FROM tarkastuslista_kysymykset";
$result = mysqli_query($conn, $sql);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.
if (mysqli_num_rows($result) > 0){
 echo " <!--Luodaan taulukko-->
  <table>
      <!--Taulukon ensimmäinen rivi on lomakkeen otsikko-->
      <tr><th colspan='2'><h3>Yhteenveto</h3></th></tr>
      <tr><th colspan='2' style='font-size:140%;'>THT Vastaanottotarkastajan tarkastuslista (huom. laitevalmistajan ohjeistukset)</th></tr>
      <!--Luodaan taulukon sisälle HTML-lomake-->";
       
    $i=0;


    echo"<tr><td><b>Laite: </b></td><td class='vasen'>".$laite."</td></tr>";
    echo"<tr><td><b>Sarjanumero: </b></td><td class='vasen'>".$sarjanumero."</td></tr>";
    echo"<tr><td><b>Laitetunnus: </b></td><td class='vasen'>".$laitetunnus."</td></tr>";
    echo "</table><table>";
    
    

  while($row = mysqli_fetch_assoc($result)) {

   
    $kysy = $row["kysymys"];
    $kysymysID=$row["kysymysID"];
    $kysy = $row["kysymys"];
    if($row["kysymysID"]==10){
     echo '</table><table>';
    

    }
    elseif($row["kysymysID"]==14 ){
      echo '</table><table>';
     
    }
    elseif($row["kysymysID"]==18){
        echo '</table><table><th colspan=2 style="font-size:115%;">Lisäosa terveydenhuollon röntgenlaitteen tarkastukseen (huom. STUK:in ja laitevalmistajan ohjeistukse';
        
    }
    elseif($row["kysymysID"]==20){
     echo '</table><table>';
     
      
    }
    
    
   
  
    

    
    if($row["tyyppiID"]==2){
    
    if($_POST["vastaus"][$i]=='1'){ 
      echo "<tr><td colspan='2' class='vasen' style='font-size:115%;'><label for='checkbox'></label><label class='container'><input type='checkbox' checked disabled = 'disabled'><span class='checkmark'></span></label>";
     

      }
    elseif($_POST["vastaus"][$i]=='0'){ 
      echo "<tr><td colspan='2' class='vasen' style='font-size:115%;'><label for='checkbox'></label><label class='container'><input type='checkbox' disabled = 'disabled'><span class='checkmark'></span></label>";
      
        }
      
    else
    {
      echo "<tr><td class='vasen' style='font-size:115%;'>".$_POST["vastaus"][$i]."";
      
    }
    echo "<b>".$kysy."</b></td></tr>";


  $i++;
}

else{

  echo "<tr><td class='vasen' style='font-size:115%;'><b>".$kysy."</b></td></tr>";
}

  $sql2 = "SELECT *  FROM tarkastuslista_alikysymykset WHERE kysymysID=$kysymysID";
$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
  // output data of each row
  while($row = $result2->fetch_assoc()) {
    
    $_POST["vastaus"][$i]." ";
    $alikysy = $row["alikysymys"];
    $alikysymysID=$row["alikysymysID"];
    
    
    
    
    

    
    
    
    if($_POST["vastaus"][$i]=='1'){ 
      echo "<tr class='sisennys'><td colspan='2' class='vasen'><label class='container'><input type='checkbox' checked disabled = 'disabled'>";
      echo"<span class='checkmark'></span></label><label for='checkbox'></label>";
     

      }
    elseif($_POST["vastaus"][$i]=='0'){ 
      echo "<tr class='sisennys'><td colspan='2' class='vasen'><label class='container'><input type='checkbox' disabled = 'disabled'>";
      echo"<span class='checkmark'></span></label><label for='checkbox'></label>";
      
        }
      
    else
    {
      echo "<tr><td class='keskelle'>".$_POST["vastaus"][$i]."";
      
    }

    echo "".$alikysy."</td></tr>";
    $i++;}}

  }
  echo "<tr><td><p>HUOM.<i>”Säteilylaitteen turvallinen toiminta on varmistettava merkittävän korjauksen,
  huollon tai ohjelmistopäivityksen jälkeen ja aina, kun on aihetta epäillä laitteen toiminnassa häiriöitä tai muutoksia.</i>
  Säteilyturvallisuuteen vaikuttavat viat ja puutteet on korjattava ennen käyttöä”<i>S/5/2019 kohta 24 §</i></p></td></tr></table>";
} 

else {
// Jos tuloksia 0, tulostetaan tieto.
  echo "0 results";
}

echo "<form id='tarka'action='./tarkastuslista.php' method='post'>";
echo "<input type='submit' id='muokkaa2' name='etusivu' value='Muokkaa&#9998;'><br><br><br>

</form>";


echo '<form id="lista" action="./tarkastuslista_tallennus.php" method="post">';
echo "<input type='submit' id='tallenna2' value='Tallenna'><br><br><br>";

echo '</form>';




$laiteID=$_POST['LaiteID'];
$_SESSION['laite']=$laiteID;




//Lopuksi tietokantayhteyden katkaiseminen.
$conn->close();
}

?>

</body>
</html>
