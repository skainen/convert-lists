<!DOCTYPE html>
<head>
  <script>
window.onload=function()
  { document.getElementById("search").disabled = true;
    document.getElementById("search1").disabled = true;
  }
</script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://www.istekki.fi/istekki-theme-v2/images/favicon.ico" type="image/x-icon" rel="icon">
<link rel="stylesheet" href="lomake.css"> 
<title>Arkisto</title>
</head>
<body>
  <?php
  session_start();
  ?>


<form autocomplete="off" action="./arkisto.php" method="post">
  <div class="autocomplete" style="width:300px;">
    <input id="myInput" type="text" name="tilausnro" pattern="[A-Öa-ö0-9-,.\s]{1,}" placeholder="Tilausnumero">
  </div>
  <label class="laite"><b>Valitse tilausnumero</b></label>
  <input type="submit" id="search" value="Näytä">
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


$sql = "SELECT Tilausnro FROM laite";
$result = mysqli_query($conn, $sql);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.
if (mysqli_num_rows($result) > 0){
  $a=array();
  while($row = mysqli_fetch_assoc($result)) {
    array_push($a, $row["Tilausnro"]);
    
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
if(isset($_POST["tilausnro"])||isset($_POST["sarjanumero"]))
{
  $tilausnro=$_POST["tilausnro"];
  $_SESSION["tilausnro"]=$tilausnro;
  echo '<div class="valittulaite" <h2>#'.$tilausnro.'</h2></div>';
  

echo '<form autocomplete="off" action="./arkisto.php" method="post">
  <div class="autocomplete" style="width:300px;">
    <input id="myInput1" type="text" name="sarjanumero" pattern="[A-Öa-ö0-9-,.\s]{1,}" placeholder="Sarjanumero">
  </div>
  <label class="tilausnumero"><b>Valitse sarjanumero</b></label>
  <input type="submit" id="search1" value="Näytä">
  <input type="hidden" name="tilausnro" value="'.$tilausnro.'">
  
  
</form>';



?>

<script>
function autocomplete(inp, arr) {

  var currentFocus;
  
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      document.getElementById("search1").disabled = true;
     
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
    
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items1");
     
      this.parentNode.appendChild(a);
    
      for (i = 0; i < arr.length; i++) {
        
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
         
          b = document.createElement("DIV");
     
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
       
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
        
          b.addEventListener("click", function(e) {
          
              inp.value = this.getElementsByTagName("input")[0].value;
              document.getElementById("search1").disabled = false;

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
  
    x[currentFocus].classList.add("autocomplete-active1");
  }
  function removeActive(x) {

    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active1");
    }
  }
  function closeAllLists(elmnt) {

    var x = document.getElementsByClassName("autocomplete-items1");
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


</script>



<?php


$sql = "SELECT Sarjanumero FROM laite WHERE Tilausnro='$tilausnro'";

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
  
var passedArray1 = 
    <?php echo json_encode($a); ?>;
      
autocomplete(document.getElementById("myInput1"), passedArray1);
</script>


<?php


}


if(isset($_POST["sarjanumero"])){
  $sarjanumero=$_POST["sarjanumero"];
  $_SESSION["sarjanumero"]=$sarjanumero;

  echo '<div class="valittulaite"><b>'.$sarjanumero.'</b></div>';
  echo '<div class="tablecontainer">';

  $sql="SELECT esitietolomake.EsitietoID, esitietolomake.Pvm FROM esitietolomake INNER JOIN laite ON esitietolomake.LaiteID=laite.LaiteID WHERE Sarjanumero='$sarjanumero'";

  $result = mysqli_query($conn, $sql);

  //Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.

  if (mysqli_num_rows($result) > 0){
    echo '<table>';
    echo '<tr><th>Esitietolomake</th></tr>';
    while($row = mysqli_fetch_assoc($result)) {
     
      echo '<tr><td class="vasen"><a href="esitietolomakkeet.php?EsitietoID='.$row["EsitietoID"].'">'.$row["Pvm"].'</a></td></tr>';
      
    }
    echo '</table>';
}





$sql="SELECT vastaanotto.PoytakirjaID, vastaanotto.PVM FROM vastaanotto INNER JOIN laite ON vastaanotto.LaiteID=laite.LaiteID WHERE Sarjanumero='$sarjanumero'";

$result = mysqli_query($conn, $sql);

//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.

if (mysqli_num_rows($result) > 0){
  echo '<table>';
  echo '<tr><th>Vastaanottopöytäkirja</th></tr>';
  while($row = mysqli_fetch_assoc($result)) {
   
    echo '<tr><td class="vasen"><a href="vastaanottopoytakirjat.php?PoytakirjaID='.$row["PoytakirjaID"].'">'.$row["PVM"].'</a></td></tr>';
    
  }
  echo '</table>';
}





$sql="SELECT tarkastuslista.tarkastuslistaID, tarkastuslista.PVM FROM tarkastuslista INNER JOIN laite ON tarkastuslista.LaiteID=laite.LaiteID WHERE Sarjanumero='$sarjanumero'";

$result = mysqli_query($conn, $sql);

//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.

if (mysqli_num_rows($result) > 0){
  echo '<table>';
  echo '<tr><th>Vastaanottotarkastajan tarkastuslista</th></tr>';
  while($row = mysqli_fetch_assoc($result)) {
   
    echo '<tr><td class="vasen"><a href="tarkastuslistat.php?tarkastuslistaID='.$row["tarkastuslistaID"].'">'.$row["PVM"].'</a></td></tr>';
    
  }
  echo '</table>';
}
echo '</div>';
}



echo '<form action="etusivu.php" method="post">
      <input type="submit" id="etusivu" value="Etusivulle">
      </form>';


$conn->close();


?>

</body>
</html>
