<?php
// Include config file
require_once "../config.php";
?>

<!DOCTYPE html>
<html lang="de">
<head> 
		 <meta charset="utf-8">
		 <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="icon" type="image/icon" sizes="16x16" href="favicon.ico">
			<link rel="stylesheet" href="quiz.css"> 
			<link rel="stylesheet" href="kontakte.css">
			
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
	  background-color: #bbbbbb;
	}
</style>

<?php
$benutzername = $id = $benutzernameERR = $idErr = "";
$vorname = $nachname = $email = $timestamp = $bild = $geschlecht = "";
$zaehler = 0;
$spaltennamen = array("ID","Vorname","Nachname","Benutzername","Email","Anmeldezeitpunkt","Bild","Geschlecht");
//select mit prepared statement:
$sql = "select * from benutzer where id = ? and benutzername = ?;";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, 'is', $id, $benutzername);
/*function test_input($data) {
	//entfernt alle ASCII außer Buchstaben,also auch Zahlen
	$data = trim($data,"\x00..\x2F\x3A..\x40\x7B..\x7F\x5B..\x60");
	return $data;
	*/
function test_input($data) {
	  $data = trim($data);	//entferne space, tab und newline
	  $data = stripslashes($data);	//entferne backslash (macht nur den Backslash vor einem Backslash weg weg
	  $data = htmlspecialchars($data); //maskiere < und >
	  return $data;
}

function begruessung($geschlecht, $vorname, $nachname){
	switch ($geschlecht) {
	  case "m":
		echo "<br>Hallo Herr ".$nachname;
		echo "<br>";
		break;
	  case "w":
		echo "<br>Hallo Frau ".$nachname;
		echo "<br>";
		break;
	  default:
		echo "<br>Hallo ".$vorname." ". $nachname;
		echo "<br>";
	}
}

function erstelle_Tabelle($spaltennamen,$ausgabe){
	echo "<br>Wir haben folgende Daten von Ihnen gespeichert: <br><br>";
	echo "<table>";
		echo "<tr>";
			for ($i=0; $i < count($spaltennamen);$i++){				
				echo "<th>". $spaltennamen[$i]."</th>";				
			}
		echo "</tr>";
		echo "<tr>";
			foreach ($ausgabe as $key => $value){
				if(!($key == 'bild' and $value)){
				echo "<td>". $value."</td>";
				}else{
					echo "<td><img src='". $value ."' style='width:120px;height:80px;border-radius: 0%;'></td>";
				}				
			}
		echo "</tr>";
	echo "</table>";
}
//hier wäre alternativ auch isset möglich:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["benutzername"])) {
		$benutzernameERR = "Benutzername ist erforderlich!";
	  } else {
			$benutzername = test_input($_POST["benutzername"]);					
				if (!preg_match("/^[ÄÜÖäüöa-zA-Z- ]*[0-9]*$/",$benutzername)) {
				  $benutzernameERR = "Es sind nur Buchstaben, Zahlen und Leerzeichen erlaubt";
				}else{
					$zaehler +=1;
					//speichere, dass Eingabe ok
				}
			
	  }
	if (empty($_POST["id"])) {
		$idErr = "Eingabe einer id ist erforderlich!";
	  } else {  
			$id = test_input($_POST["id"]);
							
				if (!preg_match("/^[0-9]*$/",$id)) {
					  $idErr = "Es sind nur Zahlen erlaubt";
				}else{
					$zaehler +=1;
					//speichere, dass Eingabe ok
				}
	  }

}

?>
       
<title>Der kleine FISI</title>
</head>
<body>
	<div><a href=" https://www.ctc-lohr.de/" class="button-imgeins"></a></div>
	<div><a href="https://www.w3schools.com/" class="button-imgzwei"></a></div>

<header>
	<nav>
	<ul>
		<li><a class="nav" href="index.html">Home</a></li>
		<li><a href="#">Themen</a>
		  <ul>
			<li><a href="wiso.html">WiSo</a></li>
			<li><a href="html.html">html,css,js</a></li>
			<li><a href="#">Windows</a>
			  <ul>
				<li><a href="quiz.html">WindowsServer</a></li>
				<li><a href="client.html">WindowsClient</a></li>
			  </ul>
			</li>
		  </ul>
		</li>
		<li><a href="newsletter.html">Newsletter</a>
		  <ul>
			<li><a href="impressum.html">Impressum</a></li>
			<li><a href="datenschutz.html">Datenschutz</a></li>
		  </ul>
		</li>
		<li><a href="quiz.html">Start</a></li>
		<li><a href="kontakte.html">Kontakte</a></li>
	 </ul>
	</nav>
	<div >
		<h2 class="text-dark">Quiz für kleine FISI's!</h2>
	</div>
	<div>
		<h2 class="text-dark"> Teste dein Wissen?</h2>	
	</div>
</header>
	<div class="body">
		<div  style="text-align:center; color:white;">
		<?php
		if ($zaehler == 2){
			if(mysqli_stmt_execute($stmt)){
				echo "<br>Abfrage hat geklappt.<br>";
				if ($ausgabe = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))){
					$vorname = $ausgabe['vorname'];
					$nachname = $ausgabe['nachname'];
					$email = $ausgabe['email'];
					$timestamp = $ausgabe['timestamp'];
					$bild = $ausgabe['bild'];
					$geschlecht = $ausgabe['geschlecht'];
					mysqli_stmt_close($stmt);
					echo "<br>";
					begruessung($geschlecht,$vorname,$nachname);
					echo "<br>";
					erstelle_Tabelle($spaltennamen,$ausgabe);
					echo "<br>";
				}else{
					echo "<br>Zu diesem Benutzernamen und dieser Id sind keine Daten vorhanden<br>"; 
				}
			}else{
				echo "<br>Abfrage hat nicht geklappt.";
			}
		}
		/*
		echo "<br>";
		echo $_POST['benutzername'];
		echo "<br>";
		echo $_POST['id'];
		echo "<br>";
		echo $benutzernameERR;
		echo "<br>";
		echo $idErr;
		echo "<br>";
		*/
		?>

				Kontakt:<br>
				Sandra Husseneder<br>
				Marlene-Dietrich-Straße 1<br>     	
				89231 Neu-Ulm<br><br>
				Telefon:0731 98491- <br>
				Fax: 0731 98491-88<br>
				E-Mail:SHusseneder@ctc-lohr.net
		 </div>
	</div>
</body>
	   
 </html>