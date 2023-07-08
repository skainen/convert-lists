<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<link rel="stylesheet" href="lomake.css">
<title>Lista</title>
<script>
function compare3(countries)
{
 
  //Sarjanumeron vertaamista varten funktio, joka saa arrayn, mikä
  //sisältää listan sarjanumeroista
var e = document.getElementById("sarjanumerot").value;
j=0;
for (i = 0; i < countries.length; i++)
{
if(e.toLowerCase()==countries[i].toLowerCase())
{//Muutetaan pieniksi kirjaimiksi, jotta kirjainerot eivät vaikuta.

  j=1;
}
 

}

if(j==1)
{
  alert("Sarjanumerolla "+ e+" on jo syötetty laite! \n Hae arkistosta laitteen täytetty esitietolomake.");
  document.getElementById("sarjanumerot").value = "";

}


}
//Compare3 loppuu
</script>
</head>
<body>
  
<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "listaprojekti";

// Luo yhteys
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");


// Tarkastaa yhteyden
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
else{

  $sql = "SELECT Sarjanumero FROM laite";
  $result = mysqli_query($conn, $sql);
  //Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.
  if (mysqli_num_rows($result) > 0){
    $a=array();
    while($row = mysqli_fetch_assoc($result)) {
      array_push($a, $row["Sarjanumero"]);
      
    }
  }
  
  ?>
  
  <script>
    
  // Access the array elements
  var passedArray = 
      <?php echo json_encode($a); ?>;
        

  </script>

<?php
   echo "<table>
   <tr><th colspan='2' style='font-size:140%;'>Esitietolomake</th></tr>
        <form action='./esitietotarkastus.php' method='post'>";


$sql = "SELECT kysymysID, kysymys, lyhenne, tyyppiID FROM esitieto_kysymykset";

$result = mysqli_query($conn, $sql);

//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.

if (mysqli_num_rows($result) > 0){
 echo " <!--Luodaan taulukko-->
      <!--Taulukon ensimmäinen rivi on lomakkeen otsikko-->
      <!--Luodaan taulukon sisälle HTML-lomake-->";

      $i=0;

  while($row = mysqli_fetch_assoc($result)) {
    $kysy = $row["kysymys"];
    $kysyID=$row["kysymysID"];
    $lyhenne=$row["lyhenne"];
    $tyyppi=$row["tyyppiID"];
    if(isset($_POST['etusivulle'])){
      $vastaus=$_SESSION["vastaukset"][$i];

      if($tyyppi==1)
      {
      if($kysyID=='7'){
        echo "<tr><td><label for='text'><b>$kysy</b></label></td><td><input type='text' id='sarjanumerot' onblur='compare3(passedArray)' name='vastaus[]' pattern='[A-Öa-ö0-9-,.:\s]{1,}'  value='$vastaus' required></td></tr>";
        
      }
      else{
        echo "<tr><td><label for='text'><b>$kysy</b></label></td><td><input type='text'  name='vastaus[]' pattern='[A-Öa-ö0-9-,.:\s]{1,}'  value='$vastaus' required></td></tr>";
      }
    }

    elseif($tyyppi==3)
    {
      echo "<tr><td><label for='text'><b>$kysy</b></label></td><td><input type='text'  name='vastaus[]' pattern='[A-Öa-ö0-9-,.:\s]{1,}'  value='$vastaus'></td></tr>";
    }

    elseif($tyyppi==7)
    {

      $sql2 = "SELECT vaihtoehto, arvo FROM vaihtoehdot WHERE kysymysID=$kysyID";

      $result2 = mysqli_query($conn, $sql2);

      //Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.

      if (mysqli_num_rows($result2) > 0){
       echo " <!--Luodaan taulukko-->
            <!--Taulukon ensimmäinen rivi on lomakkeen otsikko-->
            
            <!--Luodaan taulukon sisälle HTML-lomake-->";
         echo"<tr><td><label for='text'><b>$kysy</b></label></td>";
         echo"<td><select name='vastaus[]' required>";
        while($row = mysqli_fetch_assoc($result2)) {
          $vaihtoehto=$row["vaihtoehto"];
          $arvo=$row["arvo"];
          if($vastaus==$arvo){
            echo"<option value='".$arvo."'selected>".$vaihtoehto."</option>";
          }
         else{
          echo"<option value='".$arvo."'>".$vaihtoehto."</option>";
         }
        }
        echo"</select></td></tr>";
       
    }
    
  }
}
    else
    {
      if($tyyppi==1)
      {
      if($kysyID=='7'){
        echo "<tr><td><label for='text'><b>$kysy</b></label></td><td><input type='text' id='sarjanumerot' onblur='compare3(passedArray)' name='vastaus[]' pattern='[A-Öa-ö0-9-,.\s]{1,}' required></td></tr>";
        
      }
      else{
        echo "<tr><td><label for='text'><b>$kysy</b></label></td><td><input type='text'  name='vastaus[]' pattern='[A-Öa-ö0-9-,.:\s]{1,}' required></td></tr>";
      }
    }
  
    
    elseif($tyyppi==3)
    {
      echo "<tr><td><label for='text'><b>$kysy</b></label></td><td><input type='text'  name='vastaus[]' pattern='[A-Öa-ö0-9-,.:\s]{1,}' value=''></td></tr>";
    }

    elseif($tyyppi==7)
    {

      $sql2 = "SELECT vaihtoehto, arvo FROM vaihtoehdot WHERE kysymysID='$kysyID'";

      $result2 = mysqli_query($conn, $sql2);

      //Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.

      if (mysqli_num_rows($result2) > 0){
       echo " <!--Luodaan taulukko-->
            <!--Taulukon ensimmäinen rivi on lomakkeen otsikko-->
            
            <!--Luodaan taulukon sisälle HTML-lomake-->";
            echo"<tr><td><label for='text'><b>$kysy</b></label></td>";
         echo"<td><select name='vastaus[]' required>";
        while($row = mysqli_fetch_assoc($result2)) {
          $vaihtoehto=$row["vaihtoehto"];
          $arvo=$row["arvo"];
         echo"<option value='".$arvo."'>".$vaihtoehto."</option>";
        }
        echo"</select></td></tr>";
    }
    }
  }
    $i++;
  }
  echo "</table>";
} else {
// Jos tuloksia 0, tulostetaan tieto.
  echo "0 results";
}
echo '<input type="submit" id="siirry" value="Siirry">
</form><br><br><br><br>';
echo'<form action="./etusivu.php" method="post">
<input  type="submit" id="etusivu" value="Etusivulle">
</form>';




//Lopuksi tietokantayhteyden katkaiseminen.
$conn->close();
}

?>

</body>
</html>
