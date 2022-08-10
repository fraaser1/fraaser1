<?php
// Include config file
require_once "config.php";

//Variablen-Initialisierung
$benutzername = $id = $benutzernameERR = $idErr =  $id_score = $scoreErr = "";
$zaehler = $id_score_int = 0;
//Definition des Arrays mit den Überschriften für die Benutzer-Tabelle
$spaltennamen = array("ID","Vorname","Nachname","Benutzername","Email","Anmeldezeitpunkt","Bild","Geschlecht");

//select mit prepared statement:
//Prepare-Statement für Tabelle benutzer
$sql = "select * from benutzer where id = ? and benutzername = ?;";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, 'is', $id, $benutzername);

//Prepare-Statement für Tabelle score
$sql_score = "select spruch from score where id = ?;";
$stmt_score = mysqli_prepare($link, $sql_score);
mysqli_stmt_bind_param($stmt_score, 'i', $id_score_int);

//entfernt alle ASCII außer Buchstaben und Zahlen
function test_input($data) {	
	$data = trim($data,"\x00..\x2F\x3A..\x40\x5B..\x60\x7B..\x7F");
	return $data;
}

//gibt eine Begrüßung mit der richtigen Anrede aus
function begruessung($ausgabe){
	switch ($ausgabe['geschlecht']) {
	  case "m":
		echo "<br>Hallo Herr ".$ausgabe['nachname'];
		echo "<br>";
		break;
	  case "w":
		echo "<br>Hallo Frau ".$ausgabe['nachname'];
		echo "<br>";
		break;
	  default:
		echo "<br>Hallo ".$ausgabe['vorname']." ". $ausgabe['nachname'];
		echo "<br>";
	}
}

//erstellt die Tabelle mit den in der Datenbank gespeicherten Daten des Benutzers
function erstelle_Tabelle($spaltennamen,$ausgabe){
	echo "<br>Wir haben folgende Daten von Ihnen gespeichert: <br><br>";
	echo "<table>";
		echo "<tr>";
			//Ausgabe der Überschriften
			for ($i=0; $i < count($spaltennamen);$i++){				
				echo "<th>". $spaltennamen[$i]."</th>";				
			}
		echo "</tr>";
		echo "<tr>";
			//Ausgabe der Zeile mit den Werten
			foreach ($ausgabe as $key => $value){
				if(!($key == 'bild' and $value)){
				echo "<td>". $value."</td>";
				}else{
					echo "<td><img src='". $value ."' style='width:120px;height:120px;border-radius: 0%;'></td>";
				}				
			}
		echo "</tr>";
	echo "</table>";
}
//hier wäre alternativ auch isset möglich:
//Abfrage ob der Submit-Button gedrückt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//Abfrage ob Benutzername eingegeben wurde
	if (empty($_POST["benutzername"])) {
		$benutzernameERR = "Eingabe eines Benutzernamens ist erforderlich!";
	  } else {
			//hier werden alle Eingaben außer Buchstaben entfernt
			$benutzername = test_input($_POST["benutzername"]);	
				//hier wird nochmal abgefragt, dass nur Buchstaben und Zahlen erlaubt sind
				if (!preg_match("/^[ÄÜÖäüößa-zA-Z-]*[0-9]*$/",$benutzername)) {
				  $benutzernameERR = "Es sind nur Buchstaben und Zahlen erlaubt";
				}else{
					$zaehler +=1;
					//speichere, dass Eingabe ok
				}
			
		}
	//Abfrage ob ID eingegeben wurde	
	if (empty($_POST["id"])) {
		$idErr = "Eingabe einer ID ist erforderlich!";
	  } else {
			//hier braucht es keinen test_input weil nur Zahlen erlaubt sind (preg_match und Nummern-Eingabefeld bei html)
			$id = $_POST["id"];							
				//hier wird überprüft, dass nur Zahlen enthalten sind
				if (!preg_match("/^[0-9]*$/",$id)) {
					  $idErr = "Es sind nur Zahlen erlaubt";
				}else{
					//speichere, dass Eingabe ok war
					$zaehler +=1;					
				}
	  }
	  //hier braucht es eine andere Abfrage, weil 0 auch als empty gewertet wird
	if (strlen($_POST["id_score"]) == 0) {
		$scoreErr = "Eingabe eines Scores (0-5) ist erforderlich!";
	  } else {
			//hier braucht es keinen test_input weil nur Zahlen erlaubt sind (preg_match)
			$id_score = $_POST['id_score'];
			//hier wird überprüft, dass nur Zahlen enthalten sind
			if (!preg_match("/^[0-9]*$/",$id_score)) {
			  $scoreErr = "Es sind nur Zahlen erlaubt";
			}else{
				$zaehler +=1; //speichere, dass Eingabe ok				
				$id_score_int = intval($id_score); //explizite Umwandlung von String in Zahl				
			}			
		}

}

?>

<!DOCTYPE html>
<html lang="de">
<head> 
		 <meta charset="utf-8">
		 <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="icon" type="image/icon" sizes="16x16" href="../bilder/favicon.ico">
			<link rel="stylesheet" href="../css/quiz.css"> 
			<link rel="stylesheet" href="../css/kontakte.css">
			
	<!-- hier der Tabellenstil in css -->			
	<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background-color: #aaaaaa;
		}
	</style>
	

 
<title>Der kleine FISI</title>

</head>

<body>
	<div><a href=" https://www.ctc-lohr.de/" class="button-imgeins"></a></div>
	<div><a href="https://www.w3schools.com/" class="button-imgzwei"></a></div>

<header>
	<nav>
	<ul>
		<li><a class="nav" href="../index.html">Home</a></li>
		<li><a href="#">Themen</a>
		  <ul>
			<li><a href="../html/wiso.html">WiSo</a></li>
			<li><a href="../html/html.html">html,css,js</a></li>
			<li><a href="#">Windows</a>
			  <ul>
				<li><a href="../html/quiz.html">WindowsServer</a></li>
				<li><a href="../html/client.html">WindowsClient</a></li>
			  </ul>
			</li>
		  </ul>
		</li>
		<li><a href="../php/anmeldung.php">Anmeldung</a>
		  <ul>
			<li><a href="../html/impressum.html">Impressum</a></li>
			<li><a href="../html/datenschutz.html">Datenschutz</a></li>
		  </ul>
		</li>
		<li><a href="../html/quiz.html">Start</a></li>
		<li><a href="../php/mein_konto.php">Mein Konto</a></li>
	 </ul>
	</nav>
	<div >
		<h2 class="text-dark">Quiz für kleine FISI's!</h2>
	</div>
	<div>
		<h2 class="text-dark"> Teste dein Wissen!</h2>	
	</div>
</header>

<div style="color:white;font-size:1.1em;">
		<!-- Formular für die Eingabe von Benutzername, ID und Score 
			bei action bleiben wir auf dergleichen Seite und wollen XSS verhindern mit htmlspecialchars-->
		<form method=POST action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<label for="benutzername" >Bitte geben Sie Ihren Benutzernamen ein:
			<b style="color:red;"> * <br><?php echo $benutzernameERR;?></b></label>
			<!-- hier wird php verwendet um eine Eingabe einzufordern, Abfrage im oberen php-Teil, dort auch Zuweisung der Fehlerausgabe -> $benutzernameERR -->
			<input name="benutzername" type="text" class="feedback-input" placeholder="Benutzername"  value="<?php echo $benutzername;?>">
			<!-- durch die Eingabe des php-Values wird die Eingabe auch nach Submit beibehalten -->
			
			<label for="id" >Bitte geben Sie Ihre ID ein: 
			<b  style="color:red;"> * <br><?php echo $idErr;?></b></label>		
			<input name="id" type="number" class="feedback-input" placeholder="ID"  min="1" max="1000" value="<?php echo $id;?>">
			
			
			<label for="id_score">Bitte wählen Sie Ihren erzielten Score aus: <b style="color:red;"> * <?php echo $scoreErr;?></b></label>		
			<select name="id_score" id="id_score" style = "font-size: 1em">
				<option value="<?php echo $id_score;?>"><?php echo $id_score;?></option>
				
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			      
			</select>
			<br>				
			<p>
			<input  type="submit" value="Absenden">
			</p>
			<b id="ausgabe" style="color:red;">* Pflichtfeld</b>
			
		</form>
	<div style="text-align:center;color:white;font-size:1.1em;">	
		<?php
			//Abfrage, dass alle Eingaben erfolgt sind
			if ($zaehler == 3){
				//Ausführung des prepared statement zur Benutzer-Tabelle und Abfrage ob es geklappt hat, denn nur dann können wie weitermachen
				if(mysqli_stmt_execute($stmt)){
					echo "<br>Die Datenabfrage hat geklappt.<br>";
					//Zuweisung und Umwandlung des Rückgabe-Objekts in ein ass-Array, gleichzeitig Abfrage ob die Rückgabe nicht leer war
					if ($ausgabe = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))){
						mysqli_stmt_close($stmt);						
						echo "<br>";
						begruessung($ausgabe);						
						erstelle_Tabelle($spaltennamen,$ausgabe);
						//Sprung zur Ausgabe
						echo '<meta http-equiv="refresh" content="1; URL=#ausgabe"> ';
						echo "<br>";						
					}else{
						echo '<meta http-equiv="refresh" content="1; URL=#ausgabe"> ';
						echo "<br>Zu diesem Benutzernamen und dieser ID sind keine Daten vorhanden<br><br>"; 
					}
				}else{
					echo "<br>Die Datenabfrage hat nicht geklappt.<br>";
				}
				//Ausführung des prepared statement zur Score-Tabelle und Abfrage ob es geklappt hat, denn nur dann können wie weitermachen
			    if(mysqli_stmt_execute($stmt_score)){
					echo "Ihr Score ist ". $id_score_int . "<br>";
					//Zuweisung und Umwandlung des Rückgabe-Objekts in ein ass-Array, gleichzeitig Abfrage ob die Rückgabe nicht leer war
					if ($spruch_ausgabe = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_score))){
						mysqli_stmt_close($stmt_score);
						echo $spruch_ausgabe['spruch'];
						echo "<br><br>";
					}else {echo "Kein Spruch zu Ihrem Score vorhanden";};
				}else{
					echo "<br>Die Datenabfrage score hat nicht geklappt.<br>";
				}
			}

		?>
	</div>
</div>


</body>
	   
 </html>