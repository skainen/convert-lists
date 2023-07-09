<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="lomake.css">
<meta charset="UTF-8">
<title>Tarkastuslista_tallennus</title>
</head>
<body onload="viesti()">
<script>
function viesti() {
  alert("Tiedot tallennettu");
}
</script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
<form action="./etusivu.php" method="post">
<input type="submit" value="Etusivulle">
</form>
<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "listaprojekti";
$laiteid=$_SESSION['laite'];
$laitetunnus=$_SESSION['laitetunnus'];
$sarjanumero=$_SESSION['sarjanumero'];
$laite=$_SESSION['kauppanimike'];

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

// SQL -kysely
$i=0;
$sql = "INSERT INTO tarkastuslista (LaiteID, PVM)
VALUES ($laiteid, '$date')";

//Valitaan viimeiseksi lisätty lomake ID:n perusteella. DESC LIMIT 1

if ($conn->query($sql) === TRUE) {
  $sql10 = "SELECT tarkastuslistaID FROM tarkastuslista ORDER BY tarkastuslistaID DESC LIMIT 1"; 
  $result10 = mysqli_query($conn, $sql10);

  if (mysqli_num_rows($result10) > 0){
    while($row=mysqli_fetch_assoc($result10)){
      $tarkastuslistaID=$row['tarkastuslistaID'];
      $_SESSION["tarkastuslistaID"]=$tarkastuslistaID;
      
    }
    $i=0;
    
    if(!empty($_SESSION['vastaukset'])){
      //SESSIO tarkastettu ok
        
      { 

        $sql5="SELECT kysymysID, tyyppiID FROM tarkastuslista_kysymykset";
        $result5 = mysqli_query($conn, $sql5);

        if (mysqli_num_rows($result5) > 0){

        while($row=mysqli_fetch_assoc($result5)){
          
        $tyyppiID=$row["tyyppiID"];
        $vastaus = $_SESSION["vastaukset"][$i];
        $kysymysID=$row["kysymysID"];
          
        if($tyyppiID!=8)
           {
        $sql9 ="INSERT INTO tarkastuslista_kvastaukset (kysymysID, tarkastuslistaID, vastaus, alikysymysID)
        VALUES ($kysymysID, $tarkastuslistaID, '$vastaus', NULL)";

if ($conn->query($sql9) === TRUE){

         $i++;
       
            $vastaus = $_SESSION["vastaukset"][$i];}
          }
            
        $sql6="SELECT alikysymysID FROM tarkastuslista_alikysymykset WHERE kysymysID=$kysymysID";
        $result6 = mysqli_query($conn, $sql6);

        if (mysqli_num_rows($result6) > 0){

        while($row=mysqli_fetch_assoc($result6)){
          $vastaus = $_SESSION["vastaukset"][$i];
          $alikysymysID=$row["alikysymysID"];

        $sql8 ="INSERT INTO tarkastuslista_kvastaukset (kysymysID, tarkastuslistaID, vastaus, alikysymysID)
        VALUES ($kysymysID, $tarkastuslistaID, '$vastaus', '$alikysymysID')";

if ($conn->query($sql8) === TRUE){

  $i++;
  
}}}}}}}}}

else {
    // Jos tuloksia 0, tulostetaan tieto.
      echo "Ei lisättävÄÄ tietoja";
      echo "Error: " . $sql . "<br>" . $conn->error;
    } 
}
  else
  {
   $tarkastuslistaID = $_SESSION["tarkastuslistaID"];
  }
  
//SQL -kysely loppuu

//SQL -kysely

echo '<div id="printable"><img src="logo.jpg" alt="logo" class="logo"><table>';
$sql = "SELECT tarkastuslista.pvm as PVM, laite.Kauppanimike as Laitenimi FROM tarkastuslista INNER JOIN laite ON tarkastuslista.LaiteID=laite.LaiteID WHERE tarkastuslista.tarkastuslistaID='$tarkastuslistaID'" ;
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


$sql = "SELECT * FROM tarkastuslista_kysymykset";
$result = mysqli_query($conn, $sql);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.

if (mysqli_num_rows($result) > 0){

   echo"<tr><th colspan='2' style='font-size:140%;'>THT Vastaanottotarkastajan tarkastuslista (huom. laitevalmistajan ohjeistukset)</th></tr>";
   echo"<tr><td><b>Sarjanumero: </b></td><td class='vasen'>".$sarjanumero."</td></tr>";
   echo"<tr><td><b>Laitetunnus: </b></td><td class='vasen'>".$laitetunnus."</td></tr>";
   echo "</table><table>";
   
  while($row = mysqli_fetch_assoc($result)) {

    $kysymysID=$row["kysymysID"];
    if($row["kysymysID"]==10){
      echo '</table><div style="page-break-before:always"></div><table>';
     
 
     }
     elseif($row["kysymysID"]==14 ){
       echo '</table><div style="page-break-inside:avoid"></div><table>';
      
     }
     elseif($row["kysymysID"]==18){
         echo '</table><div style="page-break-before:always"></div><table><th colspan=2 style="font-size:115%;">Lisäosa terveydenhuollon röntgenlaitteen tarkastukseen (huom. STUK:in ja laitevalmistajan ohjeistukset)';
         
     }
     elseif($row["kysymysID"]==20){
      echo '</table><div style="page-break-inside:avoid"></div><table>';   
       }
       $kysymys=$row["kysymys"];
    
       if($row["tyyppiID"]==8)
       {
         echo "<tr><td class='vasen' style='font-size:115%;'><b>".$kysymys."</b></td></tr>";
       }

       $sql7 = "SELECT * FROM tarkastuslista_kvastaukset WHERE tarkastuslistaID=$tarkastuslistaID AND kysymysID=$kysymysID AND alikysymysID IS NULL";
       $result7 = mysqli_query($conn, $sql7);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.


if (mysqli_num_rows($result7) > 0){
    while($row = mysqli_fetch_assoc($result7)) {

      if($row["vastaus"] =='1'){ 
        echo "<tr><td colspan='2' class='vasen' style='font-size:115%;'><label for='checkbox'></label><label class='container'><input type='checkbox' checked disabled = 'disabled'><span class='checkmark'></span></label>";
        }
      elseif($row["vastaus"] =='0'){ 
        echo "<tr><td colspan='2' class='vasen' style='font-size:115%;'><label for='checkbox'></label><label class='container'><input type='checkbox' disabled = 'disabled'><span class='checkmark'></span></label>";
          }
          echo "<b>".$kysymys."</b></td></tr>";
    }
}

$sql4="SELECT * FROM tarkastuslista_alikysymykset WHERE kysymysID=$kysymysID";

$result4 = mysqli_query($conn, $sql4);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.


if (mysqli_num_rows($result4) > 0){
    while($row = mysqli_fetch_assoc($result4)) {

      $alikysymys=$row["alikysymys"];
        
        $alikysymysID=$row["alikysymysID"];

        $sql3 = "SELECT * FROM tarkastuslista_kvastaukset WHERE tarkastuslistaID=$tarkastuslistaID AND kysymysID=$kysymysID AND alikysymysID=$alikysymysID";

       $result3 = mysqli_query($conn, $sql3);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.


if (mysqli_num_rows($result3) > 0){
    while($row = mysqli_fetch_assoc($result3)) {

      if($row["vastaus"] =='1'){ 
        echo "<tr class='sisennys'><td colspan='2' class='vasen' style='font-size:115%;'><label for='checkbox'></label><label class='container'><input type='checkbox' checked disabled = 'disabled'><span class='checkmark'></span></label>";
       
  
        }
      elseif($row["vastaus"] =='0'){ 
        echo "<tr class='sisennys'><td colspan='2' class='vasen' style='font-size:115%;'><label for='checkbox'></label><label class='container'><input type='checkbox' disabled = 'disabled'><span class='checkmark'></span></label>";
        
          }
          echo "".$alikysymys."</td></tr>";

    }}}}


//Lopuksi tietokantayhteyden katkaiseminen.

}
echo "</table><img src='yhteys.png' alt='yhteys' class='yhteys'></div>";
}
else {
    // Jos tuloksia 0, tulostetaan tieto.
      echo "Ei lisättävÄÄ tietoja";
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
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
