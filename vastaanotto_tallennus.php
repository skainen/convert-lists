<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="lomake.css">
<meta charset="UTF-8">
<title>Vastaanotto tallennus</title>
</head>
<body onload="viesti()">
<script>
function viesti() {
  alert("Tiedot tallennettu");
}
</script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://raw.githubusercontent.com/CodeYellowBV/html2canvas/master/build/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
<!--<button onClick="printToPDF();">
  Tulosta PDF
</button><br><br>-->
<form action="./etusivu.php" method="post">
<input class="noPrint" type="submit" value="Etusivulle">
</form>
<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "listaprojekti";
$laiteid=$_SESSION['laite'];
$laitetunnus=$_SESSION['laitetunnus'];
$date=date("Y-m-d");



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if(isset($_SESSION["vastaukset"]))
{
$sql = "UPDATE laite SET laitetunnus='$laitetunnus' WHERE LaiteID=$laiteid";

if ($conn->query($sql) === TRUE) {
  
  
// SQL -kysely
$i=0;
$sql = "INSERT INTO vastaanotto (LaiteID, PVM)
VALUES ($laiteid, '$date')";

//Valitaan viimeiseksi lisätty lomake ID:n perusteella. DESC LIMIT 1

if ($conn->query($sql) === TRUE) {
  $sql = "SELECT PoytakirjaID FROM vastaanotto ORDER BY PoytakirjaID DESC LIMIT 1"; 
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0){
    while($row=mysqli_fetch_assoc($result)){
      $poytakirjaID=$row['PoytakirjaID'];
      $_SESSION["poytakirjaID"]=$poytakirjaID;
    }
    $i=$i+2;
    
    if(!empty($_SESSION['vastaukset'])){
      foreach($_SESSION['vastaukset'] as $vastaus)

      {
        if(isset($_POST["$i"]))
        {
          $huom=$_POST["$i"];
        $sql ="INSERT INTO vastaanotto_vastaukset (kysymysID, PoytakirjaID, vastaus, huom)
        VALUES ($i, $poytakirjaID, '$vastaus', '$huom')";
      }
      else
      {
        $sql ="INSERT INTO vastaanotto_vastaukset (kysymysID, PoytakirjaID, vastaus, huom)
        VALUES ($i, $poytakirjaID, '$vastaus', NULL)";
      }

if ($conn->query($sql) === TRUE){
  $i++;
}
      }
      
    }


}
}
}
else {
    // Jos tuloksia 0, tulostetaan tieto.
      echo "Ei lisättävÄÄ tietoja";
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

    
  }
  
  else
  {
   $poytakirjaID = $_SESSION["poytakirjaID"];
   
   
  }
  
//SQL -kysely loppuu

//SQL -kysely
echo '<div id="printable"><img src="logo.jpg" alt="logo" class="logo"><table>';
$sql = "SELECT vastaanotto.pvm as PVM, laite.Kauppanimike as Laitenimi FROM vastaanotto INNER JOIN laite ON vastaanotto.LaiteID=laite.LaiteID WHERE vastaanotto.PoytakirjaID='$poytakirjaID'" ;
$result = mysqli_query($conn, $sql);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.


if (mysqli_num_rows($result) > 0){

   echo"<tr><th style='font-size:115%;'>Päivämäärä</th><th style='font-size:115%;'>Kauppanimike</th></tr>";
   
  while($row = mysqli_fetch_assoc($result)) { 
    echo  '<tr><td class="keskelle"><b>'.$row["PVM"].'</b></td><td class="keskelle"><b>'.$row["Laitenimi"].'</b></td></tr>';
  }
  
} else {
// Jos tuloksia 0, tulostetaan tieto.
  echo "Ei lisättäviä tietoja";
}
//SQL -kysely

$sarjanumero=$_SESSION["sarjanumero"];
$toimittaja=$_SESSION["toimittaja"];

$sql = "SELECT vastaanotto_vastaukset.vastaus as vastaus, vastaanotto_vastaukset.huom as huom, vastaanotto_kysymykset.kysymys as kysymys, vastaanotto_kysymykset.kysymysID as kysymysID, vastaanotto_kysymykset.tyyppiID as tyyppiID FROM vastaanotto_vastaukset INNER JOIN vastaanotto_kysymykset ON vastaanotto_vastaukset.kysymysID=vastaanotto_kysymykset.kysymysID WHERE vastaanotto_vastaukset.PoytakirjaID=$poytakirjaID ORDER BY vastaanotto_kysymykset.kysymysID";
$result = mysqli_query($conn, $sql);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.


if (mysqli_num_rows($result) > 0){

   echo"<tr><th colspan='2' style='font-size:140%;'>Terveydenhuollon laitteen vastaanottotarkastuspöytäkirja</th></tr>";
   echo"<tr><td><b>Toimittaja: </b></td><td class='vasen'>".$toimittaja."</td></tr>";
   echo"<tr><td><b>Sarjanumero: </b></td><td class='vasen'>".$sarjanumero."</td></tr>";
   echo"<tr><td><b>Laitetunnus: </b></td><td class='vasen'>".$laitetunnus."</td></tr>";
   
  while($row = mysqli_fetch_assoc($result)) {
    
    $kysy = $row["kysymys"];


    $kysymysID=$row["kysymysID"];
    if($row["kysymysID"]==4){
      echo '</table><table><th colspan=2 style="font-size:115%;">Tarkastuslista</th>';
    }
    elseif($row["kysymysID"]==13 ){
      echo '</table><div style="page-break-before:always"></div><table style="table-layout:fixed;font-size:115%;">';
    }
    elseif($row["kysymysID"]==14){
      echo '</table><table style="table-layout:fixed;"><th style="font-size:115%;">Yhteenveto vastaanottotarkastuksesta</th>';
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
    


    //Checkboxit

    if($row["vastaus"]=='1'){
      
     echo "<tr><td class='vasen' style='font-size:120%;'><label class='container'>";
      echo "<input type='checkbox' checked disabled = 'disabled'>";
      echo"<span class='checkmark'></span></label>";
     echo "<label for='checkbox'></label>";
      echo "".$kysy."</td></tr>";
       }
     elseif($row["vastaus"]=='0'){

      echo "<tr><td class='vasen' style='font-size:120%;'><label class='container'>";
      echo "<input type='checkbox' disabled = 'disabled'>";
      echo"<span class='checkmark'></span></label>";
     echo"<label for='checkbox'></label>";
      echo "".$kysy."</td></tr>";
         }
         
     else
     { 
      if($row["kysymysID"]==13)
      {
        echo "<tr><th>".$row["kysymys"]."</th></tr><tr>";
      }
      else{

        echo "<tr><td><b>".$row["kysymys"]."</b></td>";
      }
      
      echo "<td class='vasen'>".$row["vastaus"]."</td></tr>";

      
      
     }
     
     if($row["huom"]!=NULL)
     { $huom=$row["huom"];
      if($row["tyyppiID"]==3)
      {
        echo "<tr><td class='vasen' style='display:block;'><b>Huom:&nbsp&nbsp&nbsp</b>".$huom."<br></td></tr>";
      }
      elseif($row["tyyppiID"]==6)
      {
        echo "<tr><td class='vasen' style='display:block;'>".$huom."<br></td></tr>";
      }
    }
  
} else {
// Jos tuloksia 0, tulostetaan tieto.
  echo "Ei lisättäviä tietoja";
  echo "Error: " . $sql . "<br>" . $conn->error;
}


echo "</table><img src='yhteys.png' alt='yhteys' class='yhteys'></div>";


//Lopuksi tietokantayhteyden katkaiseminen.
$conn->close();
    unset($_SESSION["vastaukset"]);
?>

<script>
function printDiv(printable, 
  title) {

  let mywindow = window.open("","PRINT","width=600px","");

  mywindow.document.write(`<html><head>`);
  mywindow.document.write('<link rel="stylesheet" href="lomake.css">');
  mywindow.document.write('</head><body >');
  
  mywindow.document.write(document.getElementById(printable).innerHTML);
  mywindow.document.write('</body></html>');

 //mywindow.document.close(); // necessary for IE >= 10
 mywindow.focus(); // necessary for IE >= 10*/
 mywindow.setTimeout(function(){ mywindow.print();mywindow.close();}, 3000);


  return true;
}
</script>

<button class="noPrint" id="tulostabtn" onclick="printDiv('printable', 'Title')">Tulosta</button>
<button class="noPrint" id = "IE" onClick="window.print()">
            Tulosta
        </button>
        <script>
            /* IE:n tunnistaminen alkaa tästä*/
            function isIE() {
                ua = navigator.userAgent;
                /* MSIE used to detect old browsers and Trident used to newer ones*/
                var is_ie = ua.indexOf("MSIE ") > -1 || ua.indexOf("Trident/") > -1;

                return is_ie;
            }
            /* Create an alert to show if the browser is IE or not */
            if (isIE()) {
                document.getElementById("IE").style.display = "block";
                document.getElementById("tulostabtn").style.display = "none";
            } else {
                document.getElementById("tulostabtn").style.display = "block";
                document.getElementById("IE").style.display = "none";
            }
            </script>

</body>
</html>
