<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://www.istekki.fi/istekki-theme-v2/images/favicon.ico" type="image/x-icon" rel="icon">
<link rel="stylesheet" href="lomake.css"> 
<title>Vastaanottotarkastus</title>

<script>

window.onload=function()
  { document.getElementById("search").disabled = true;
  }
 
function compare()
{
    var startDt = document.getElementById("startDate").value;
    var endDt = document.getElementById("endDate").value;

    if( (new Date(startDt).getTime() > new Date(endDt).getTime()))
    {
       document.getElementById("endDate").value = " ";
       alert("Takuuajan päättymispäivä ei voi olla ennen alkamispäivää.");
    }
}

function compare1()
{
    var startDt1 = document.getElementById("startDate1").value;
    var endDt1 = document.getElementById("endDate1").value;

    if( (new Date(startDt1).getTime() > new Date(endDt1).getTime()))
    {
       document.getElementById("endDate1").value = " ";
       alert("Takuuajan päättymispäivä ei voi olla ennen alkamispäivää.");
    }
}
</script>

</head>
<body>
  <?php
  session_start();
  ?>






<form autocomplete="off" action="./vastaanottotarkastus.php" method="post">
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
  
  

//Sarjanumeron valinta
  

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
  <tr><th colspan=2 style='font-size:140%;'>Terveydenhuollon laitteen Vastaanottotarkastuspöytäkirja</th><label for='text'></label></th></tr>
  <!--Luodaan taulukon sisälle HTML-lomake-->
  <form action='./vastaanottotarkastus_tarkastus.php' method='post'>" ;

  $sql = "SELECT LaiteID, Kauppanimike, Laitetunnus, Sarjanumero, Toimittaja FROM laite WHERE LaiteID='$laiteid'";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    // output data of each row
    $laitetunnus=$_SESSION["laitetunnus"];
    $sarjanumero=$_SESSION["sarjanumero"];
    $laite=$_SESSION["kauppanimike"];
    $toimittaja=$_SESSION["toimittaja"];

    while($row = $result->fetch_assoc()) {
      echo "<tr><td><label for='text'><b>Laite:</b></label></td><td><input type='text' name='kauppanimike' value='".$laite."' readonly></td></tr>";
      echo "<tr><td><label for='text'><b>Laitetoimittaja:</b></label></td><td><input type='text' name='toimittaja' value='".$toimittaja."' readonly></td></tr>";
      echo "<tr><td><label for='text'><b>Sarjanumero:</b></label></td><td><input type='text' name='sarjanumero' value='".$sarjanumero."' readonly></td></tr>";
      echo "<tr><td><label for='text'><b>Laitetunnus:</b></label></td><td><input type='text' name='laitetunnus' value='".$laitetunnus."' required></td></tr>";
      echo "<input type='hidden' value='".$row["LaiteID"]."' name = 'LaiteID'>";
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
echo " <!--Luodaan taulukko-->
  <table>
      <!--Taulukon ensimmäinen rivi on lomakkeen otsikko-->
      <tr><th colspan=2 style='font-size:140%'>Terveydenhuollon laitteen vastaanottotarkastuspöytäkirja</th><label for='text'></label></th></tr>
      <!--Luodaan taulukon sisälle HTML-lomake-->
      <form action='./vastaanottotarkastus_tarkastus.php' method='post'>" ;
}

if(isset($_POST["sarjanumero"]) || isset($_POST["etusivu"]))
{


if(isset($_POST["sarjanumero"])){
  
  $sarjanumero=$_POST["sarjanumero"];
$sql = "SELECT LaiteID, Kauppanimike, Toimittaja, Sarjanumero FROM laite WHERE Sarjanumero='$sarjanumero'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<tr><td><label for='text'><b>Laite:</b></label></td><td><input type='text' name='kauppanimike' value='".$row["Kauppanimike"]."' readonly></td></tr>";
    echo "<tr><td><label for='text'><b>Laitetoimittaja:</b></label></td><td><input type='text' name='toimittaja' value='".$row["Toimittaja"]."'readonly></td></tr>";
    echo "<tr><td><label for='text'><b>Sarjanumero:</b></label></td><td><input type='text' name='sarjanumero' value='".$row["Sarjanumero"]."'readonly></td></tr>";
    echo "<tr><td><label for='text'><b>Laitetunnus:</b></label></td><td><input type='text' name='laitetunnus' pattern='[A-Öa-ö0-9-,.\s]{1,}' required></td></tr>";
    echo "<input type='hidden' value='".$row["LaiteID"]."' name = 'LaiteID'>";
  }
} else {
  echo "0 results";
}
}

$sql = "SELECT kysymys, tyyppiID, kysymysID FROM vastaanotto_kysymykset";
$result = mysqli_query($conn, $sql);
//Jos rivejä on enemmän kuin 0, tulostetaan rivit while –silmukassa allekkain.
if (mysqli_num_rows($result) > 0){
  $i=0;
  $k=0;
  while($row = mysqli_fetch_assoc($result)) {
    $kysy = $row["kysymys"];
    if($row["kysymysID"]==4){
      echo '</table><table><th colspan=2 style="font-size:115%;">Tarkastuslista</th>';
    }
    elseif($row["kysymysID"]==13 ){
      echo '</table><table style="font-size:115%;">';
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
    
    $kysymysid = $row["kysymysID"];
    if(isset($_POST['etusivu']))
    {
      $vastaus = $_SESSION["vastaukset"][$i];
      
   
      if($row["tyyppiID"]==1){
        
        echo "<tr><td><label for='text'><b>$kysy</b></label></td><td><input type='text' name='vastaus[]' pattern='[A-Öa-ö0-9-,.\s]{1,}' value='$vastaus' required></td></tr>";
        }
        elseif($row["tyyppiID"]==2){
          if($vastaus=="1"){
            
            echo "<tr><td class='vasen' style='font-size:120%;'><label class='container'><input type='hidden' name='vastaus[]' pattern='[A-Öa-ö0-9-,.\s]{1,}' value='1'><input type='checkbox' checked onclick='this.previousSibling.value=1-this.previousSibling.value'>"; 
          }
          else{
            
          echo "<tr><td class='vasen' style='font-size:120%;'><label class='container'><input type='hidden' name='vastaus[]' pattern='[A-Öa-ö0-9-,.\s]{1,}' value='0'><input type='checkbox' onclick='this.previousSibling.value=1-this.previousSibling.value'>";
        }
        echo"<span class='checkmark'></span></label><label for='checkbox'>$kysy</label></td></tr>";
    
             
        }
        elseif($row["tyyppiID"]==3){
          
          if($vastaus=="1"){
            
            echo "<tr><td colspan='2' class='vasen'><label class='container'><input type='hidden' name='vastaus[]' pattern='[A-Öa-ö0-9-,.\s]{1,}' value='1'><input type='checkbox' checked onclick='this.previousSibling.value=1-this.previousSibling.value'>"; 
          }
          else{
            
          echo "<tr><td colspan='2' class='vasen'><label class='container'><input type='hidden' name='vastaus[]' pattern='[A-Öa-ö0-9-,.\s]{1,}' value='0'><input type='checkbox' onclick='this.previousSibling.value=1-this.previousSibling.value'>";
        }
          echo "<span class='checkmark'></span></label><label for='checkbox' style='font-size:120%;'>$kysy</label></td></tr>";
          echo "<tr><td colspan='2' class='vasen'><label for='text'><b>Huom:</b></label><input type='text' maxlength='150' name='".$kysymysid."' pattern='[A-Öa-ö0-9-,.\s]{0,}' value='".$_POST["$kysymysid"]."'></td></tr>";
        
    
        
        }
        elseif($row["tyyppiID"]==4){
          echo "<tr><th colspan=2><label for='textarea'>$kysy</label></th></tr><tr><td><textarea rows='10' maxlength='1000' name='vastaus[]'  placeholder='Enter text'>$vastaus</textarea></td></tr>";
          
        }
        elseif($row["tyyppiID"]==5){
          
          if($k==0){
            
        
            echo "<tr><td><label for='date'><b>$kysy</b></label></td><td><input type='date' id='startDate' onblur='compare()' name ='vastaus[]' placeholder='YYYY-MM-DD' value='$vastaus' required></td></tr>";
        
          }
          else{
            echo "<tr><td><label for='date'><b>$kysy</b></label></td><td><input type='date' id='endDate' onblur='compare()' name ='vastaus[]' placeholder='YYYY-MM-DD' value='$vastaus' required></td></tr>";
          }
          $k++;
        }
        elseif($row["tyyppiID"]==6){
         
          if($vastaus=="1"){
            
            echo "<tr><td colspan='2' class='vasen'><label class='container'><input type='hidden' name='vastaus[]' pattern='[A-Öa-ö0-9-,.\s]{1,}' value='1'><input type='checkbox' checked onclick='this.previousSibling.value=1-this.previousSibling.value'>"; 
          }
          else{
            
          echo "<tr><td colspan='2' class='vasen'><label class='container'><input type='hidden' name='vastaus[]' pattern='[A-Öa-ö0-9-,.\s]{1,}' value='0'><input type='checkbox' onclick='this.previousSibling.value=1-this.previousSibling.value'>";
        }
          echo"<span class='checkmark'></span></label><label for='checkbox' style='font-size:120%;'>$kysy</label></td></tr>";
          echo "<tr><td><input type='text' class='lyhytext' maxlength='200' name='e".$kysymysid."' pattern='[A-Öa-ö0-9-,.\s]{0,150}' value='".$_POST["$kysymysid"]."'></td></tr>";
    
          echo '</script>';
        
      }


      //Loppuuu
    }


    else{
    

    if($row["tyyppiID"]==1){
    echo "<tr><td><label for='text'><b>$kysy</b></label></td><td><input type='text' name='vastaus[]' pattern='[A-Öa-ö0-9-,.\s]{1,}' required></td></tr>";
    }
    elseif($row["tyyppiID"]==2){
      echo "<tr><td colspan='2' class='vasen' style='font-size:120%;'><label class='container'><input type='hidden' name='vastaus[]' pattern='[A-Öa-ö0-9-,.\s]{1,}' value='0'><input type='checkbox' onclick='this.previousSibling.value=1-this.previousSibling.value'>
      <span class='checkmark'></span></label><label for='checkbox'>$kysy</label></td></tr>";

         
    }
    elseif($row["tyyppiID"]==3){
      echo "<tr><td colspan='2' class='vasen' style='font-size:120%;'><label class='container'><input type='hidden' name='vastaus[]' value='0'><input type='checkbox' onclick='this.previousSibling.value=1-this.previousSibling.value'>
      <span class='checkmark'></span></label><label for='checkbox'>$kysy</label></td></tr>";
      echo "<tr><td colspan='2' class='vasen'><label for='text'><b>Huom:</b></label><input type='text' name='".$kysymysid."' pattern='[A-Öa-ö0-9-,.\s]{1,}'></td></tr>";
    

    
    }
    elseif($row["tyyppiID"]==4){
      echo "<tr><th colspan=2><label for='textarea'>$kysy</label></th></tr><tr><td><textarea rows='10' maxlength='1000' name='vastaus[]' placeholder='Enter text'></textarea></td></tr>";
      
    }
    elseif($row["tyyppiID"]==5){
      if($k==0){
            
        echo "<tr><td><label for='date'><b>$kysy</b></label></td><td><input type='date' id='startDate1' onblur='compare1()' name ='vastaus[]' placeholder='YYYY-MM-DD'  required></td></tr>";
    
      }
      else{
        echo "<tr><td><label for='date'><b>$kysy</b></label></td><td><input type='date' id='endDate1' onblur='compare1()' name ='vastaus[]' placeholder='YYYY-MM-DD'  required></td></tr>";
      }
      $k++;
    
  

    }
    elseif($row["tyyppiID"]==6){
      echo "<tr><td class='vasen' style='font-size:120%;'><label class='container'><input type='hidden' name='vastaus[]' value='0'><input type='checkbox' onclick='this.previousSibling.value=1-this.previousSibling.value'>
      <span class='checkmark'></span></label><label for='checkbox'>$kysy</label></td></tr>";
      echo "<tr><td><input type='text' class='lyhytext' maxlength='200' pattern='[A-Öa-ö0-9-,.\s]{1,}' name='e".$kysymysid."'></td></tr>";

      echo'
    </script>';
    
  }}
$i++;
  }
  echo "</table>";
} 

else {
// Jos tuloksia 0, tulostetaan tieto.
  echo "0 results";
}

//Submit-nappi

echo '<input type="submit" id="siirry1" name="siirry" value="Siirry"><br><br><br>';
  echo " </form>";


}

  

//Lopuksi tietokantayhteyden katkaiseminen.
$conn->close();

?>




</body>
</html>





