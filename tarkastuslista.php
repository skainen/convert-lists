<!DOCTYPE html>
<html>
<head>
<script>
window.onload=function()
  { document.getElementById("search").disabled = true;
  }
  </script>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://www.istekki.fi/istekki-theme-v2/images/favicon.ico" type="image/x-icon" rel="icon">
<link rel="stylesheet" href="lomake.css"> 
<title>Tarkastuslista</title>
</head>
<body>
  <?php
  session_start();
  
  ?>






<form autocomplete="off" action="./tarkastuslista.php" method="post">
  <div class="autocomplete" style="width:300px;">
    <input id="myInput" type="text" pattern="[A-Öa-ö0-9-,.\s]{1,}" name="sarjanumero" placeholder="Sarjanumero">
  </div>
  <label class="laite"><b>Valitse sarjanumero</b></label>
  <input type="submit" id="search" value="Näytä">
</form>
<form action="./etusivu.php" method="post">
<input type="submit" id="etusivu" value="Etusivulle">
</div>
</form>

<script>
function autocomplete(inp, arr) {

  var currentFocus;

  
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      document.getElementById("search").disabled = true;
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
    
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
     
      this.parentNode.appendChild(a);
    
      for (i = 0; i < arr.length; i++) {
        
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
         
          b = document.createElement("DIV");
     
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
       
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
        
          b.addEventListener("click", function(e) {
          
              inp.value = this.getElementsByTagName("input")[0].value;
              document.getElementById("search").disabled = false;

              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });

  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
       
        currentFocus++;
       
        addActive(x);
      } else if (e.keyCode == 38) { 
        currentFocus--;
        
        addActive(x);
      } else if (e.keyCode == 13) {
       
        e.preventDefault();
        if (currentFocus > -1) {
      
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {

    if (!x) return false;
 
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
  
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {

    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {

    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }

  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}


//var countries = ["Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua & Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia & Herzegovina","Botswana","Brazil","British Virgin Islands","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central Arfrican Republic","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica","Cote D Ivoire","Croatia","Cuba","Curacao","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauro","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","North Korea","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre & Miquelon","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","St Kitts & Nevis","St Lucia","St Vincent","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad & Tobago","Tunisia","Turkey","Turkmenistan","Turks & Caicos","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States of America","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands (US)","Yemen","Zambia","Zimbabwe"];



</script>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "listaprojekti";
$date=date("Y-m-d");


// Luo yhteys
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

//Terveydenhuollon laitteen vastaanottotarkastuspöytäkirja


if(isset($_POST["etusivu"]))
{
  $laiteid = $_SESSION['laite'];

  $sql = "SELECT Sarjanumero FROM laite where LaiteID='$laiteid'";
  $result = mysqli_query($conn, $sql);
  //Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.
  if (mysqli_num_rows($result) > 0){
    $a=array();
    while($row = mysqli_fetch_assoc($result)) {
      array_push($a, $row["Sarjanumero"]);
      
    }
  
  ?>
  
  <script>
    
  // Access the array elements
  var passedArray = 
      <?php echo json_encode($a); ?>;
         
  
  
  autocomplete(document.getElementById("myInput"), passedArray);
  </script>
  
  
  <?php
  

  echo"<table>
  <!--Taulukon ensimmäinen rivi on lomakkeen otsikko-->
  <tr><th colspan=2 style='font-size:140%;'>THT Vastaanottotarkastajan tarkastuslista (huom. laitevalmistajan ohjeistukset)</th><label for='text'></label></th></tr>
  <!--Luodaan taulukon sisälle HTML-lomake-->
  <form action='./tarkastuslista_tarkastus.php' method='post'>" ;

  $sql = "SELECT LaiteID, Kauppanimike, Sarjanumero, Laitetunnus, Toimittaja FROM laite WHERE LaiteID='$laiteid'";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      echo "<tr><td><label for='text'><b>Laite:</b></label></td><td><input type='text' name='kauppanimike' value='".$row["Kauppanimike"]."' readonly></td></tr>";
      echo "<tr><td><label for='text'><b>Sarjanumero:</b></label></td><td><input type='text' name='sarjanumero' value='".$row["Sarjanumero"]."' readonly></td></tr>";
      echo "<tr><td><label for='text'><b>Laitetunnus:</b></label></td><td><input type='text' name='laitetunnus' value='".$row["Laitetunnus"]."' readonly></td></tr>";
      echo "<input type='hidden' value='".$row["LaiteID"]."' name = 'LaiteID'>";
      echo "</table><table>";
    }

  } else {
    echo "0 results";
  }

  }

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

?>

<script>
  
// Access the array elements
var passedArray = 
    <?php echo json_encode($a); ?>;
       


autocomplete(document.getElementById("myInput"), passedArray);
</script>


<?php

}

//Piilotetaan kaikki



  






echo " <!--Luodaan taulukko-->
  <table>
      <!--Taulukon ensimmäinen rivi on lomakkeen otsikko-->
      <tr><th colspan=2 style='font-size:140%;'>THT Vastaanottotarkastajan tarkastuslista (huom. laitevalmistajan ohjeistukset)</th><label for='text'></label></th></tr>
      <!--Luodaan taulukon sisälle HTML-lomake-->
      <form action='./tarkastuslista_tarkastus.php' method='post'>" ;
}

if(isset($_POST["sarjanumero"]) || isset($_POST["etusivu"]))
{



if(isset($_POST["sarjanumero"])){
  $sarjanumero=$_POST["sarjanumero"];
  
$sql = "SELECT LaiteID, Sarjanumero, Laitetunnus, Kauppanimike, Toimittaja FROM laite WHERE Sarjanumero='$sarjanumero'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<tr><td><label for='text'><b>Laite:</b></label></td><td><input type='text' name='kauppanimike'value='".$row["Kauppanimike"]."' readonly></td></tr>";
    echo "<tr><td><label for='text'><b>Sarjanumero:</b></label></td><td><input type='text' name='sarjanumero' value='".$row["Sarjanumero"]."' readonly></td></tr>";
    echo "<tr><td><label for='text'><b>Laitetunnus:</b></label></td><td><input type='text' name='laitetunnus' value='".$row["Laitetunnus"]."' readonly></td></tr>";
    echo "<input type='hidden' value='".$row["LaiteID"]."' name = 'LaiteID'>";
    echo "</table><table>";
  }
} else {
  echo "0 results";
}
}

$sql3 = "SELECT kysymys, tyyppiID, kysymysID FROM tarkastuslista_kysymykset";
$result3 = mysqli_query($conn, $sql3);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.
if (mysqli_num_rows($result3) > 0){
  $i=0;
  
  while($row = mysqli_fetch_assoc($result3)) {
    
    $kysy = $row["kysymys"];
    if($row["kysymysID"]==10){
      echo '</table><table>';
    }
    elseif($row["kysymysID"]==14 ){
      echo '</table><table>';
    }
    elseif($row["kysymysID"]==18){
      echo '</table><table><th colspan=2 style="font-size:115%;"">Lisäosa terveydenhuollon röntgenlaitteen tarkastukseen (huom. STUK:in ja laitevalmistajan ohjeistukset)';
    }
    elseif($row["kysymysID"]==20){
      echo '</table><table>';
      
    }
    
   
    $kysymysid = $row["kysymysID"];



    //ETUSIVULLE
    if(isset($_POST['etusivu'])){
      
      $vastaus = $_SESSION["vastaukset"][$i];
      
      
   
      
        if($row["tyyppiID"]==2){
          if($vastaus=="1"){
            
            echo "<tr><td colspan='2' class='vasen' style='font-size:115%;'><label class='container'><input type='hidden' name='vastaus[]' value='1'><input type='checkbox' checked onclick='this.previousSibling.value=1-this.previousSibling.value'>"; 
          }
          else{
            
          echo "<tr><td colspan='2' class='vasen' style='font-size:115%;'><label class='container'><input type='hidden' name='vastaus[]' value='0'><input type='checkbox' onclick='this.previousSibling.value=1-this.previousSibling.value'>";
        }echo"<span class='checkmark'></span></label><label for='checkbox'><b>$kysy</b></label></td></tr>";
    
             
        }
        
        elseif($row["tyyppiID"]==8){
         
          
            echo "<tr><td class='vasen' style='font-size:115%;'><label for='text'><b>$kysy</b></label></td></tr>";
    
          echo'</script>';
        
      }
      if($row["tyyppiID"]==2)
      {
        $i++;

      }
    

      $sql2 = "SELECT alikysymys, alikysymysID FROM tarkastuslista_alikysymykset WHERE kysymysID=$kysymysid";
$result2 = mysqli_query($conn, $sql2);


//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.
if (mysqli_num_rows($result2) > 0){
  
  while($row = mysqli_fetch_assoc($result2)) {
    $alikysy=$row["alikysymys"];
    $vastaus = $_SESSION["vastaukset"][$i];
   
    if($vastaus=="1"){
            
        echo "<tr class='sisennys'><td colspan='2' class='vasen'><label class='container'><input type='hidden' name='vastaus[]' value='1'><input type='checkbox' checked onclick='this.previousSibling.value=1-this.previousSibling.value'>"; 
       echo"<span class='checkmark'></span></label><label for='checkbox'>$alikysy</label></td></tr>";}
      else{
        
      echo "<tr class='sisennys'><td colspan='2' class='vasen'><label class='container'><input type='hidden' name='vastaus[]' value='0'><input type='checkbox' onclick='this.previousSibling.value=1-this.previousSibling.value'>";
      echo"<span class='checkmark'></span></label><label for='checkbox'>$alikysy</label></td></tr>";}
$i++;
  }


      //Loppuuu
    }}
    

    else{
    

    
    if($row["tyyppiID"]==2){
      echo "<tr><td colspan='2' class='vasen' style='font-size:115%;'><label class='container'><input type='hidden' name='vastaus[]' value='0'><input type='checkbox' onclick='this.previousSibling.value=1-this.previousSibling.value'>
      <span class='checkmark'></span></label><label for='checkbox'><b>$kysy</b></label></td></tr>";

         
    }
    
    elseif($row["tyyppiID"]==8){
        echo "<tr><td class='vasen' style='font-size:115%;'><label for='text'><b>$kysy</b></label></td></tr>";

      echo '</script>';
    
  }

  $sql = "SELECT alikysymys, alikysymysID FROM tarkastuslista_alikysymykset WHERE kysymysID=$kysymysid";
  $result = mysqli_query($conn, $sql);
  //Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.
  if (mysqli_num_rows($result) > 0){
    
    while($row = mysqli_fetch_assoc($result)) {
        $alikysy=$row["alikysymys"];
      echo "<tr class='sisennys'><td colspan='2' class='vasen'><label class='container'><input type='hidden' name='vastaus[]' value='0'><input type='checkbox' onclick='this.previousSibling.value=1-this.previousSibling.value'>
      <span class='checkmark'></span></label><label for='checkbox'>$alikysy</label></td></tr>";
      $i++;
    }
    
  
        //Loppuuu
      }}

  }
  
  echo "<tr><td><p>HUOM.<i>”Säteilylaitteen turvallinen toiminta on varmistettava merkittävän korjauksen,
  huollon tai ohjelmistopäivityksen jälkeen ja aina, kun on aihetta epäillä laitteen toiminnassa häiriöitä tai muutoksia.</i>
  Säteilyturvallisuuteen vaikuttavat viat ja puutteet on korjattava ennen käyttöä”<i>S/5/2019 kohta 24 §</i></p></td></tr></table>";
} 


else {
// Jos tuloksia 0, tulostetaan tieto.
  echo "0 results";
}

//Submit-nappi

echo '<input type="submit" id="siirry2" name="siirry" value="Seuraava"><br><br><br>';
  echo " </form>";

}

  

//Lopuksi tietokantayhteyden katkaiseminen.
$conn->close();


?>

</body>
</html>

 



