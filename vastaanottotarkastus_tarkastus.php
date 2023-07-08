<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://www.istekki.fi/istekki-theme-v2/images/favicon.ico" type="image/x-icon" rel="icon">
<meta charset="UTF-8">
<link rel="stylesheet" href="lomake.css">
<title>Vantaanottotarkastuksen tarkastus</title>
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

$sql = "SELECT * FROM vastaanotto_kysymykset";
$result = mysqli_query($conn, $sql);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.
if (mysqli_num_rows($result) > 0){
 echo " <!--Luodaan taulukko-->
  <table>
      <!--Taulukon ensimmäinen rivi on lomakkeen otsikko-->
      <tr><th colspan=2><h2>Yhteenveto</h2></th></tr>
      <tr><th colspan='2' style='font-size:140%;'>Terveydenhuollon laitteen vastaanottotarkastuspöytäkirja</th></tr>
      <!--Luodaan taulukon sisälle HTML-lomake-->";
      $_SESSION["laitetunnus"]=$_POST["laitetunnus"];
      $laitetunnus = $_POST["laitetunnus"];
      $_SESSION["sarjanumero"]=$_POST["sarjanumero"];
      $sarjanumero=$_POST["sarjanumero"];
      $_SESSION["kauppanimike"]=$_POST["kauppanimike"];
      $laite = $_POST["kauppanimike"];
      $_SESSION["toimittaja"]=$_POST["toimittaja"];
      $toimittaja=$_POST["toimittaja"];

      
      echo"<tr><td><b>Laite:</b></td><td class='vasen'>".$laite."</td></tr>";  
      echo"<tr><td><b>Toimittaja:</b></td><td class='vasen'>".$toimittaja."</td></tr>";
      echo"<tr><td><b>Sarjanumero:</b></td><td class='vasen'>".$sarjanumero."</td></tr>";  
      echo"<tr><td><b>Laitetunnus:</b></td><td class='vasen'>".$laitetunnus."</td></tr>";
    
      
  
   
    $i=0;
    $_SESSION["vastaukset"]=$_POST['vastaus'];
   
     
    echo '<form id="tale" action="./vastaanotto_tallennus.php" method="post">';

  while($row = mysqli_fetch_assoc($result)) {
  
    $vastaus = $_POST['vastaus'][$i];
    $kysy = $row["kysymys"];
    $kysymysID=$row["kysymysID"];
    
    if($row["kysymysID"]==4){
      echo '</table><table><th colspan=2 style="font-size:115%;">Tarkastuslista</th>';
    }
    elseif($row["kysymysID"]==13 ){
      echo '</table><table style="table-layout:fixed;font-size:115%;"">';
    }
    elseif($row["kysymysID"]==14){
      echo '</table><table style="table-layout:fixed;"><th style="font-size:115%;"> Yhteenveto vastaanottotarkastuksesta</th>';
    }
    elseif($row["kysymysID"]==18){
      echo '</table><table>';
    }
    elseif($row["kysymysID"]==19){
      echo '</table><table>';
    }
    elseif($row["kysymysID"]==20){
      echo '</table><table>';
    }
   
    
    

    
    if($_POST["vastaus"][$i]=='1'){ 
      echo "<tr><td class='vasen' style='font-size:120%;'><label class='container'><input type='checkbox' checked disabled = 'disabled'>";
      echo"<span class='checkmark'></span></label><label for='checkbox'></label>";
      echo "$kysy</td></tr>";

      }
    elseif($_POST["vastaus"][$i]=='0'){ 
      echo "<tr><td class='vasen' style='font-size:120%;'><label class='container'><input type='checkbox' disabled = 'disabled'>";
      echo "<span class='checkmark'></span></label><label for='checkbox'></label>";
      echo "$kysy</td></tr>";
        }
        
    else
    {
      if($kysymysID==13)
      {
        echo "<tr><th><b>".$kysy."</b></th></tr><tr>";
      }
      else{

        echo "<tr><td><b>".$kysy."</b></td>";
      }
      
      echo "<td class='vasen'>".$_POST["vastaus"][$i]."</td></tr>";
      
    
    }

     
     
    // Jos huom on tyhjä, ei tulosteta.
    
    if(isset($_POST["$kysymysID"]))
    { 
      $huom=$_POST["$kysymysID"];

    if($_POST["$kysymysID"]!="")
    { 
      echo "<tr><td class='vasen' style='display:block;'><b>Huom:&nbsp&nbsp&nbsp</b>".$huom."<br></td></tr>";
    }
    echo"<input type='hidden' form='tale' name='".$kysymysID."' value='".$huom."'>";
  }
    if(isset($_POST["e$kysymysID"]))
    { 
      $huom2=$_POST["e$kysymysID"];

      if($_POST["e$kysymysID"]!="")
      {
      echo "<tr><td class='vasen' style='display:block;'> ".$huom2."<br></td></tr>";
    }
    echo"<input type='hidden' form='tale' name='".$kysymysID."' value='".$huom2."'>";
  }
  
  
  $i++;

  }
  echo "</table>";
  echo "<input type='submit' id='tallenna1' name='tallenna' value='Tallenna'><br><br><br>
</form>";



echo "<form id='huom' action='./vastaanottotarkastus.php' method='post'>";
  
$sql = "SELECT * FROM vastaanotto_kysymykset";
$result = mysqli_query($conn, $sql);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.
if (mysqli_num_rows($result) > 0){





  while($row = mysqli_fetch_assoc($result)) {
  
    $kysymysID=$row["kysymysID"];

    if(isset($_POST["$kysymysID"]))
    { 
      $huom=$_POST["$kysymysID"];

    echo"<input type='hidden' form='huom' name='".$kysymysID."' value='".$huom."'>";
  }

  
    if(isset($_POST["e$kysymysID"]))
    { 
      $huom2=$_POST["e$kysymysID"];
    
    echo"<input type='hidden' form='huom' name='".$kysymysID."' value='".$huom2."'>";
   
  }




}
}

  
}


else {
// Jos tuloksia 0, tulostetaan tieto.
  echo "0 results";
}



echo "<input type='submit' id='muokkaa1'name='etusivu' value='Muokkaa&#9998;'><br><br><br>
</form>";







$laiteID=$_POST['LaiteID'];
$_SESSION['laite']=$laiteID;




//Lopuksi tietokantayhteyden katkaiseminen.
$conn->close();
}

?>

</body>
</html>
