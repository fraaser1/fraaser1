<?php
require_once "config.php";   
//Definition der verwendeten Variablen, die nicht gleich Initialisiert werden
$geschlecht = $vorname = $nachname = $begruessung = $benutzername = $email = $localFileName =  $id =$emailErr = $benutzernameErr = $fileErr = $vornameErr = $nachnameErr = $geschlechtErr = $anrede = "";
$bildupload = 1;
$zaehler = 0;

//Definition der erlaubten Dateierweiterung beim Bildupload
$allowed_files = [
	'image/jpeg' => 'jpg',
	'image/gif' => 'gif',
	'image/png' => 'png',
	'application/pdf' => 'pdf'];
	
//Bearbeitung der eingegebenen Strings um XSS zu verhindern
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
//Das passiert wenn das Formular abgeschickt wurde:
if($_SERVER["REQUEST_METHOD"] == "POST"){			
			
	//Benutzer anlegen insert into vorbereiten (Prepared-Statement)
	$sql = "INSERT INTO benutzer (vorname, nachname, benutzername, email, bild, geschlecht) VALUES (?,?,?,?,?,?) ;";
	//Definition des Prepared-Statement
	$stmt = mysqli_prepare($link, $sql);
	//Verknüpfung des Prepared-Statement mit den Variablen für die Platzhalter
	mysqli_stmt_bind_param($stmt, 'ssssss', $vorname, $nachname, $benutzername, $email, $localFileName, $geschlecht);

	//Überprüfung ob ein Bild hochgeladen wurde und dieses Bild den Vorgaben entspricht
	
			
	//Überprüfung Email
	 if (empty($_POST["email"])) {
			$emailErr = "Email-Adresse ist erforderlich";
	} else {
		$email = test_input($_POST["email"]);
		//  nun testen wir mit der Funktion filter_var() und FILTER_VALIDATE_EMAIL ob Email
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr = "Bitte echte Email-Adresse angeben!";		  
		}else{
			//speichere, dass Eingabe ok war
			$zaehler +=1;			
		}
	}

	//Benutzername überprüfen	  
	if (empty($_POST["benutzername"])) {
		$benutzernameErr = "Eingabe eines Benutzernamens ist erforderlich!";
	} else {
			$benutzername = test_input($_POST["benutzername"]);	
			//hier wird abgefragt, ob nur Buchstaben und Zahlen drin sind
			if (!preg_match("/^[ÄÜÖäüößa-zA-Z-]*[0-9]*$/",$benutzername)) {
			  $benutzernameErr = "Es sind nur Buchstaben und Zahlen erlaubt";
			}else{
				$zaehler +=1;
				//speichere, dass Eingabe ok
			}
	}
	
	//Überprüfung Vorname
	if (empty($_POST["vorname"])) {
		$vornameErr = "Eingabe eines Vornamens ist erforderlich!";
	} else {
			$vorname = test_input($_POST["vorname"]);	
			//hier wird abgefragt, ob nur Buchstaben drin sind
			if (!preg_match("/^[ÄÜÖäüößa-zA-Z-]*$/",$vorname)) {
				$vornameErr = "Es sind nur Buchstaben erlaubt";
			}else{
				$zaehler +=1;
				//speichere, dass Eingabe ok
			}
	}

	//Überprüfung Nachname
	if (empty($_POST["nachname"])) {
		$nachnameErr = "Eingabe eines Nachnamens ist erforderlich!";
	} else {
			$nachname = test_input($_POST["nachname"]);	
			//hier wird abgefragt, ob nur Buchstaben drin sind
			if (!preg_match("/^[ÄÜÖäüößa-zA-Z-]*$/",$nachname)) {
			  $nachnameErr = "Es sind nur Buchstaben erlaubt";
			}else{
				$zaehler +=1;
				//speichere, dass Eingabe ok
			}

	}

	//Geschlecht überprüfen	  
	if (empty($_POST["geschlecht"])) {
		$geschlechtErr = "Eingabe einer Auswahl ist erforderlich!";
	} else {
			$geschlecht = test_input($_POST["geschlecht"]);			
			//hier wird abgefragt, dass nur die Auswahl-Values drin sind
			if (!preg_match("/^[mwd-]*$/",$geschlecht)) {
			  $geschlechtErr = "Es sind nur die Auswahlmöglichkeiten erlaubt";
			}else{
				//speichere, dass Eingabe ok
				$zaehler +=1;				
				//Auswahl der Anrede je nach eingegebenem Geschlecht
				switch($geschlecht) {
				case "m": $anrede = "Herr"; break;
				case "w": $anrede = "Frau"; break;
				default: $anrede = $vorname;
				}
			}
	}
	
	if(!empty($_FILES) && $_FILES['bild']['error'] == UPLOAD_ERR_OK) {				
			//Prüfung der Dateiendung
			$extension = strtolower(pathinfo($_FILES['bild']['name'], PATHINFO_EXTENSION)); 
			$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif', 'pdf'); 
			//Prüfung ob die Extension nicht in dem extension-array vorkommt 		
			if(!in_array($extension, $allowed_extensions)) {
				//Abbruch des Scripts mit Fehlermeldung falls keine erlaubte Extension
				die("Ungültige Dateiendung"); 
			}
			//Prüfung der Dateigröße 
			if(filesize($_FILES['bild']['tmp_name']) > 2000000) {
				$bildupload = 0;
				$fileErr  = "Die Datei ist zu gross.";	 
				/*Achtung, in der php.ini gibt es den Eintrag upload_max_filesize, der ist standardmßig wohl auf 2M gesetzt und verhindert einen Upload von Dateien, die größer sind, da kommt dann aber keine Fehlermeldung...*/
			} else { 
				//Dateityp überprüfen (array wurde oben angelegt; auch wenn Endung ok sein sollte, wird hier der Inhalt überprüft)			
				$type = mime_content_type($_FILES['bild']['tmp_name']); 			
				if(isset($allowed_files[$type])) {
					//Vergabe des Dateinamens mit Pfad
					$localFileName = "../../uploads/files/" . $_FILES['bild']['name'];
					if ($zaehler == 5){
					//Verschieben des Bildes von tmp nach /update/files
					move_uploaded_file($_FILES['bild']['tmp_name'], $localFileName);
					//Ausgabe, dass alles geklappt hat
					$fileErr  = "Alles ok., " . $_FILES['bild']['name'] . " wurde hochgeladen!";
					} else {
						$bildupload = 0;
						$fileErr = "Bilder können erst hochgeladen werden, wenn alle Pflichtfelder ausgefüllt wurden!";
					} 									
				} 
				else {
					$bildupload = 0;
					$fileErr = "Dieser Dateityp ist nicht zulässig."; 
				}				
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
     		<link rel="stylesheet" href="../css/newsletter.css"> 

       
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
			<li><a href="anmeldung.php">Anmeldung</a>
			  <ul>
				<li><a href="../html/impressum.html">Impressum</a></li>
				<li><a href="../html/datenschutz.html">Datenschutz</a></li>
			  </ul>
			</li>
			<li><a href="../html/quiz.html">Start</a></li>
			<li><a href="mein_konto.php">Mein Konto</a></li>
		  </ul>
		</nav>
		<div>
			<h2 class="text-dark">Um deinen Score zu speichern musst du dich Anmelden oder Registrieren!</h2>
		</div>

	</header>
	
	<div  style="color:white;">
	
		<form method = "POST"  action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  enctype="multipart/form-data">
			<p> Um sich Registrieren zu können füllen Sie bitte die Felder vollständig aus:<br><br></p>
					 
				<label for="geschlecht"  >Bitte wählen Sie: <b style="color:red;"> * <br><?php echo $geschlechtErr;?></b> </label>		
				<select name="geschlecht" id="geschlecht" class="feedback-input">
					<option value="<?php echo $geschlecht;?>"><?php echo $geschlecht;?></option>
					<option value="m">männlich</option>
					<option value="w">weiblich</option>
					<option value="d">divers</option>
					<option value="-">keine Angabe</option>
				</select>   

			
				<label for="vorname"  >Vorname<b style="color:red;"> * <br><?php echo $vornameErr;?></b></label>
				<input type="text" name="vorname" id="vorname" class="feedback-input" placeholder="Vorname" value="<?php echo $vorname;?>">		
			
				<label for="nachname" >Nachname<b style="color:red;"> * <br><?php echo $nachnameErr;?></b></label>
				<input type="text" name="nachname" id="nachname"  class="feedback-input" placeholder="Nachname" value="<?php echo $nachname;?>">		
			
				<label for="benutzername" >Benutzername<b style="color:red;"> * <br><?php echo $benutzernameErr;?></b></label>
				<input type="text" name="benutzername" id="benutzername"  class="feedback-input" placeholder="Benutzername" value="<?php echo $benutzername;?>">		
			
				<label for="email" >Bitte geben Sie Ihre Email-Adresse ein:<b style="color:red;"> *<br><?php echo $emailErr;?></b> </label>
				<input type="text" name="email" id="email"  class="feedback-input"placeholder="Email-Adresse" value="<?php echo $email;?>">
					
				<!--<input type="hidden" name="MAX_FILE_SIZE" value="2000000">-->
				<label >Bitte wählen Sie ein Bild (*.jpg, *.png, *.gif oder *.pdf) zum Hochladen aus.</label>
				<input name="bild" type="file" accept="image/gif,image/jpeg,image/png,application/pdf"  value="<?php echo $localFileName;?>"> 
				<div><b  style="color:red;"><?php echo $fileErr;?></b></div>
				<br>
				<button id="ausgabe" type="submit" name = "speichern" id="speichern">Anmelden</button>
			</p>
			<b style="color:red;">* Pflichtfeld</b>
		
		</form>
	</div>	
	<br >
	<div  style="text-align:center;color:white;font-size:1.1em;">
		<?php
			if($zaehler == 5 and $bildupload = 1){
				if(mysqli_stmt_execute($stmt)){						
						echo "Speichern hat geklappt.";
						echo "<br>";
						//Abfrage der vergebenen ID des Benutzers
						$last_id = mysqli_insert_id($link);
						//Begrüßung
						echo "Hallo " . $anrede . " " . $nachname . ",<br>Vielen Dank für die Registrierung!<br>Bitte notieren Sie die folgenden Infos für den Abruf Ihrer Daten:<br> Ihr Benutzername ist: <b>" . $benutzername . "</b><br>und ihre ID ist: <b>" . $last_id ."</b>.<br>Viel Spaß";
						echo "<br>";
						//Sprung zur Ausgabe
						echo '<meta http-equiv="refresh" content="1; URL=#ausgabe"> ';
						//schließen der Verbindung und der Datenbankabfrage
						mysqli_close($link);
						mysqli_stmt_close($stmt);
				}else{
					echo "Speichern hat nicht geklappt.";
				}
			}
		?>
	</div>
	
<div class="robbi"></div>
			
</body>
	   
</html>