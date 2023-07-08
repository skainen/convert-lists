<!DOCTYPE html>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="lomake.css">
<meta charset="UTF-8">
<title>Esitietolomakkeet</title>
</head>
<body>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
  
<form action="./etusivu.php" method="post">
<input class="noPrint" type="submit" value="Etusivulle">
</form>
<?php
session_start();
$tilausnro=$_SESSION["tilausnro"];
$sarjanumero=$_SESSION["sarjanumero"];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "listaprojekti";
$date=date("Y-m-d");
$esitietoID=$_GET["EsitietoID"];


echo'<form action="./arkisto.php" method="post">
<input type="hidden" name="tilausnro" value="'.$tilausnro.'">
<input type="hidden" name="sarjanumero" value="'.$sarjanumero.'">
<input class="noPrint" type="submit" value="Takaisin">
</form>';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



  

//SQL -kysely
echo '<div id="printable"><img src="logo.jpg" alt="logo" class="logo"><table>';
$sql = "SELECT esitietolomake.pvm as PVM, laite.Kauppanimike as Laitenimi, laite.LaiteID as laiteID  FROM esitietolomake INNER JOIN laite ON esitietolomake.LaiteID=laite.LaiteID WHERE esitietolomake.EsitietoID='$esitietoID'" ;
$result = mysqli_query($conn, $sql);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.


if (mysqli_num_rows($result) > 0){

   echo"<tr><th style='font-size:115%;'>Päivämäärä</th><th style='font-size:115%;'>Kauppanimike</th></tr>";
  while($row = mysqli_fetch_assoc($result)) { 
    echo  '<tr><td class="keskelle"><b>'.$row["PVM"].'</b></td><td class="keskelle"><b>'.$row["Laitenimi"].'</b></td></tr>';
    $laiteID=$row["laiteID"];
    
  }
  
} else {
// Jos tuloksia 0, tulostetaan tieto.
  echo "Ei lisättäviä tietoja";
}


$sql2 = "SELECT * FROM laite WHERE LaiteID=$laiteID";
$result2 = mysqli_query($conn, $sql2);

if (mysqli_num_rows($result2) > 0){
  echo"<tr></tr>";
 while($row = mysqli_fetch_assoc($result2)) { 

  $kauppanimike=$row['Kauppanimike'];
  $tuotenumero=$row['Tuotenumero'];
  $valmistaja=$row['Valmistaja'];
  $toimittaja=$row['Toimittaja'];
  $edustaja=$row['Edustaja'];
  $sarjanumero=$row['Sarjanumero'];
  $udi=$row['UDI'];
  $sahko=$row['Sahkoturvallisuusluokitus'];
  $laakinta=$row['Laakintalaiteluokitus'];
  $kayttojarjestelma=$row['Kayttojarjestelma'];
  $ohjelmisto=$row['Ohjelmistoversio'];
  $mac=$row['MAC'];
  $muuta=$row['Muuta'];
  $tilausnro=$row['Tilausnro'];




 }
}
else{
  echo "Error: " . $sql . "<br>" . $conn->error;


}
    

//SQL -kysely
$sql = "SELECT * FROM esitieto_kysymykset";
$result = mysqli_query($conn, $sql);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.


if (mysqli_num_rows($result) > 0){

   echo"<tr><th colspan='2' style='font-size:140%;'>Esitietolomake</th></tr>";


  while($row = mysqli_fetch_assoc($result)) { 
    echo  '<tr><td><b>'.$row["kysymys"].'</b></td>';

    if($row["kysymysID"]==1){
      echo '<td class="vasen">'.$tilausnro.'</td></tr>';

    }

    elseif($row["kysymysID"]==2){
      echo '<td class="vasen">'.$kauppanimike.'</td></tr>';

    }

    elseif($row["kysymysID"]==3){
      echo '<td class="vasen">'.$tuotenumero.'</td></tr>';

    }

    elseif($row["kysymysID"]==4){
      echo '<td class="vasen">'.$valmistaja.'</td></tr>';

    }

    elseif($row["kysymysID"]==5){
      echo '<td class="vasen">'.$toimittaja.'</td></tr>';

    }

    elseif($row["kysymysID"]==6){
      echo '<td class="vasen">'.$edustaja.'</td></tr>';

    }

    elseif($row["kysymysID"]==7){
      echo '<td class="vasen">'.$sarjanumero.'</td></tr>';

    }

    elseif($row["kysymysID"]==8){
      echo '<td class="vasen">'.$udi.'</td></tr>';

    }

    elseif($row["kysymysID"]==9){
      echo '<td class="vasen">'.$sahko.'</td></tr>';

    }

    elseif($row["kysymysID"]==10){
      echo '<td class="vasen">'.$laakinta.'</td></tr>';

    }

    elseif($row["kysymysID"]==11){
      echo '<td class="vasen">'.$kayttojarjestelma.'</td></tr>';

    }

    elseif($row["kysymysID"]==12){
      echo '<td class="vasen">'.$ohjelmisto.'</td></tr>';

    }

    elseif($row["kysymysID"]==13){
      echo '<td class="vasen">'.$mac.'</td></tr>';

    }
    elseif($row["kysymysID"]==14){
      echo '<td class="vasen">'.$muuta.'</td></tr>';
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

 mywindow.document.close(); // necessary for IE >= 10
 mywindow.focus(); // necessary for IE >= 10*/
 mywindow.setTimeout(function(){ mywindow.print();mywindow.close();}, 3000);


  return true;
}
</script>

<button id="tulostabtn" onclick="printDiv('printable', 'Title')">Tulosta</button>
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
