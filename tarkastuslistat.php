<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://www.istekki.fi/istekki-theme-v2/images/favicon.ico" type="image/x-icon" rel="icon">
<link rel="stylesheet" href="lomake.css">
<meta charset="UTF-8">
<title>Tarkastuslistat</title>
</head>
<body>


<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
<!--<button onClick="printToPDF();">
  Tulosta PDF
</button><br><br>-->
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
$tarkastuslistaID=$_GET["tarkastuslistaID"];
$date=date("Y-m-d");


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
$sql = "SELECT tarkastuslista.pvm as PVM, laite.Kauppanimike as Laitenimi, laite.Laitetunnus as Laitetunnus, laite.LaiteID as LaiteID FROM tarkastuslista INNER JOIN laite ON tarkastuslista.LaiteID=laite.LaiteID WHERE tarkastuslista.tarkastuslistaID='$tarkastuslistaID'" ;
$result = mysqli_query($conn, $sql);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.


if (mysqli_num_rows($result) > 0){

   echo"<tr><th style='font-size:115%;'>Päivämäärä</th><th style='font-size:115%;'>Kauppanimike</th></tr>";
   
  while($row = mysqli_fetch_assoc($result)) { 
    echo  '<tr><td class="keskelle"><b>'.$row["PVM"].'</b></td><td class="keskelle"><b>'.$row["Laitenimi"].'</b></td></tr>';
    $laitetunnus=$row["Laitetunnus"];
    $laiteID=$row["LaiteID"];
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
   echo"</table><table>";
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
        echo "<tr class='sisennys'><td colspan='2' class='vasen'><label for='checkbox'></label><label class='container'><input type='checkbox' checked disabled = 'disabled'><span class='checkmark'></span></label>";
       
  
        }
      elseif($row["vastaus"] =='0'){ 
        echo "<tr class='sisennys'><td colspan='2' class='vasen'><label for='checkbox'></label><label class='container'><input type='checkbox' disabled = 'disabled'><span class='checkmark'></span></label>";
        
          }
          echo "".$alikysymys."</td></tr>";


    }
}

    }
}


   /* if($row["vastaus"]=='1'){
      
      echo'<tr><td class="vasen"><input type="checkbox" disabled = "disabled" checked>';
      echo  ''.$row["kysymys"].'</td></tr>';
       }
     elseif($row["vastaus"]=='0'){
       echo'<tr><td class="vasen"><input type="checkbox" disabled = "disabled">';
       echo  ''.$row["kysymys"].'</td></tr>';
         }
     else
     { 
      

        echo "<tr><td><b>".$row["kysymys"]."</b></td>";
      
      
      echo "<td class='vasen'>".$row["vastaus"]."</td></tr>";

      
      
     }*/
     



   // echo  '<tr><td>'.$row["kysymys"].'</td><td class="vasen">'.$row["vastaus"].'</td></tr>';









//Lopuksi tietokantayhteyden katkaiseminen.

}
echo "</table><img src='istekkiyhteys.png' alt='yhteys' class='yhteys'></div>";
}
else {
    // Jos tuloksia 0, tulostetaan tieto.
      echo "Ei lisättävÄÄ tietoja";
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();

    
    

    
?>
<script>
function printDiv(printable, 
  title) {

  let mywindow = window.open("","PRINT","width=600px","");

  mywindow.document.write(`<html><head>`);
  mywindow.document.write('<link rel="stylesheet" href="lomake.css">');
  mywindow.document.write('</head><body>');
  
  mywindow.document.write(document.getElementById(printable).innerHTML);
  mywindow.document.write('</body></html>');

 //mywindow.document.close(); // necessary for IE >= 10
 mywindow.focus(); // necessary for IE >= 10*/
 mywindow.setTimeout(function(){ mywindow.print();mywindow.close();}, 1000);


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


<!--
<script>
             function printToPDF() {
                 console.log('converting...');

                var printableArea = document.getElementById('printable');

                html2canvas(printableArea, {
                     useCORS: true,
                     onrendered: function (canvas) {
                      
                        var pdf = new jsPDF('p', 'pt', 'letter');

                        var pageHeight = 1030;
                         var pageWidth = 1400;
                         for (var i = 0; i <= printableArea.clientHeight / pageHeight; i++) {
                             var srcImg = canvas;
                             var sX = 0;
                             var sY = pageHeight * i; // start 1 pageHeight down for every new page
                             var sWidth = pageWidth;
                             var sHeight = pageHeight;
                             var dX = 0;
                             var dY = 0;
                             var dWidth = pageWidth;
                             var dHeight = pageHeight;

                            window.onePageCanvas = document.createElement("canvas");
                             onePageCanvas.setAttribute('width', pageWidth);
                             onePageCanvas.setAttribute('height', pageHeight);
                             var ctx = onePageCanvas.getContext('2d');
                            ctx.fillStyle="#FFFFFF";
                            ctx.fillRect(0,0, onePageCanvas.width,onePageCanvas.height);
                            ctx.drawImage(srcImg, sX, sY, sWidth, sHeight, dX, dY, dWidth, dHeight);
                             

                            var canvasDataURL = onePageCanvas.toDataURL("image/png", 1.0);
                             var width = onePageCanvas.width;
                             var height = onePageCanvas.clientHeight;

                            if (i > 0) // if we're on anything other than the first page, add another page
                                 pdf.addPage(612, 791); // 8.5" x 11" in pts (inches*72)

                            pdf.setPage(i + 1); // now we declare that we're working on that page
                             pdf.addImage(canvasDataURL, 'PNG', 20, 40, (width * .62), (height * .62)); // add content to the page

                        }
                         pdf.save('esitieto.pdf');
                     }
                 });
                }

        </script>-->
        
</body>
</html>
