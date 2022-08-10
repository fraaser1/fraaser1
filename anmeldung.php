<?ph
require_once "config.php";   

$geschlecht = $vorname = $nachname = $begruessung = $benutzername = $email = $localFileName =  $id =$emailErr = $benutzernameErr = $fileErr = $vornameErr = $nachnameErr = $geschlechtErr = $anrede = "";
$zaehler = 0;

$allowed_files = [
	'image/jpeg' => 'jpg',
	'image/gif' => 'gif',
	'image/png' => 'png',
	'application/pdf' => 'pdf'];

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){			
			
	//Benutzer anlegen insert into 
	$sql = "INSERT INTO benutzer (vorname, nachname, benutzername, email, bild, geschlecht) VALUES (?,?,?,?,?,?) ;";
	//Prepared-Statement
	$stmt = mysqli_prepare($link, $sql);
	mysqli_stmt_bind_param($stmt, 'ssssss', $vorname, $nachname, $benutzername, $email, $localFileName, $geschlecht);

	//Bildvariable wird verarbeitet 
	// Es wurde eine Datei hochgeladen und dabei sind keine Fehler aufgetreten 
	if(!empty($_FILES) && $_FILES['bild']['error'] == UPLOAD_ERR_OK) { 
			$type = mime_content_type($_FILES['bild']['tmp_name']);		 
			//Prüfung der Dateiendung 
			$extension = strtolower(pathinfo($_FILES['bild']['name'], PATHINFO_EXTENSION)); 
			$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif', 'pdf'); 
			//prüfung ob die extension nicht in dem extension-array vorkommt 		
			if(!in_array($extension, $allowed_extensions)) { 
				die("Ungültige Dateiendung"); 
			}
			//Prüfung der Dateigröße?
			if(filesize($_FILES['bild']['tmp_name']) > 2000000) {
				$fileErr  = "Die Datei ist zu gross.";	 
				//Achtung, in der php.ini gibt es den Eintrag upload_max_filesize, der war auf 2M gestetzt, deshalb hat das vorher nicht geklappt mit der Fehlermeldung!!		
			} else { 
				//Dateityp überprüfen (array wurde oben angelegt; auch wenn Endung ok sein sollte...)			
				if(isset($allowed_files[$type])) {
					//Vergabe des Dateinamens mit Pfad
					$localFileName = "../../uploads/files/" . $_FILES['bild']['name'];
					//Verschieben der Datei aus .tmp nach geplantem Ort
					move_uploaded_file($_FILES['bild']['tmp_name'], $localFileName) ;
					$zaehler++;
					$fileErr  = "Alles ok., " . $_FILES['bild']['name'] . " wurde hochgeladen!";
				} 
				else { 
					$fileErr = "Dieser Dateityp ist nicht zulässig."; 
				}				
			}		
	}
			
	//ueberprüfen email
	 if (empty($_POST["email"])) {
			$emailErr = "Email-Adresse ist erforderlich";
	} else {
		$email = test_input($_POST["email"]);
		//  nun testen wir mit der Funktion filter_var() und FILTER_VALIDATE_EMAIL ob Email
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		  $emailErr = "Bitte echte Email-Adresse angeben!";
		  echo "FILTER_VALIDATE_EMAIL: ".FILTER_VALIDATE_EMAIL;
		}else{
				$zaehler +=1;
				//speichere, dass Eingabe ok
			}
	}

	//Benutzername überprüfen	  
	if (empty($_POST["benutzername"])) {
		$benutzernameErr = "Eingabe eines Benutzernamens ist erforderlich!";
	} else {
			//hier werden alle Eingaben außer Buchstaben und Zahlen entfernt
			$benutzername = test_input($_POST["benutzername"]);	
			//hier wird nochmal abgefragt, dass nur Buchstaben und Zahlen erlaubt sind
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
			//hier werden alle Eingaben außer Buchstaben entfernt
			$vorname = test_input($_POST["vorname"]);	
			//hier wird nochmal abgefragt, dass nur Buchstaben und Zahlen erlaubt sind
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
		//hier werden alle Eingaben außer Buchstaben entfernt
		$nachname = test_input($_POST["nachname"]);	
		//hier wird nochmal abgefragt, dass nur Buchstaben und Zahlen erlaubt sind
		if (!preg_match("/^[ÄÜÖäüößa-zA-Z-]*[0-9]*$/",$nachname)) {
		  $nachnameErr = "Es sind nur Buchstaben und Zahlen erlaubt";
		}else{
			$zaehler +=1;
			//speichere, dass Eingabe ok
		}

}

	//Geschlecht überprüfen	  
	if (empty($_POST["geschlecht"])) {
		$geschlechtErr = "Eingabe einer Auswahl ist erforderlich!";
	} else {
			//hier werden alle Eingaben außer Buchstaben und Zahlen entfernt
			$geschlecht = test_input($_POST["geschlecht"]);			
			//hier wird nochmal abgefragt, dass nur die Auswahl-Values drin sind
			if (!preg_match("/^[mwd-]*$/",$geschlecht)) {
			  $geschlechtErr = "Es sind nur die Auswahlmöglichkeiten erlaubt";
			}else{
				$zaehler +=1;
				//speichere, dass Eingabe ok
				switch($geschlecht) {
				case "m": $anrede = "Herr"; break;
				case "w": $anrede = "Frau"; break;
				default: $anrede = $vorname;
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
<div class="body">
	
	<form method = "POST"  action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  enctype="multipart/form-data">
		<p> Um sich Registrieren zu können füllen Sie bitte die Felder vollständig aus:<br><br></p>
		 		 
			<label for="geschlecht"  style="color:white;">Bitte wählen Sie: <b class="error" style="color:red;"> *<?php echo $geschlechtErr;?></b> </label>		
			<select name="geschlecht" id="geschlecht" class="feedback-input">
				<option value="<?php echo $geschlecht;?>"><?php echo $geschlecht;?></option>
				<option value="m">männlich</option>
				<option value="w">weiblich</option>
				<option value="d">divers</option>
				<option value="-">keine Angabe</option>
			</select>   

		
			<label for="vorname" style="color:white;" >Vorname<b class="error" style="color:red;"> *<?php echo $vornameErr;?></b></label>
			<input type="text" name="vorname" id="vorname" class="feedback-input" placeholder="Vorname" value="<?php echo $vorname;?>">		
		
			<label for="nachname"  style="color:white;">Nachname<b class="error" style="color:red;"> *<?php echo $nachnameErr;?></b></label>
			<input type="text" name="nachname" id="nachname"  class="feedback-input" placeholder="Nachname" value="<?php echo $nachname;?>">		
		
			<label for="benutzername" style="color:white;">Benutzername<b class="error" style="color:red;"> *<?php echo $benutzernameErr;?></b></label>
			<input type="text" name="benutzername" id="benutzername"  class="feedback-input" placeholder="Benutzername" value="<?php echo $benutzername;?>">		
		
			<label for="email"  style="color:white;">Bitte geben Sie Ihre Email-Adresse ein:<b class="error" style="color:red;"> *<?php echo $emailErr;?></b> </label>
			<input type="text" name="email" id="email"  class="feedback-input"placeholder="Email-Adresse" value="<?php echo $email;?>">
    			
			<!--<input type="hidden" name="MAX_FILE_SIZE" value="2000000">-->
			<label  style="color:white;">Bitte wählen Sie ein Bild (*.jpg, *.png, *.gif oder *.pdf) zum Hochladen aus.</label>
			<input name="bild" type="file" style="color:white;" accept="image/gif,image/jpeg,image/png,application/pdf"  value="<?php echo $localFileName;?>"> 
			<b class="error"  style="color:red;"><?php echo $fileErr;?></b>
			
			<button type="submit" name = "speichern" id="speichern">Anmelden</button>
		</p>
		<b class="error"  style="color:red;">* Pflichtfeld</b>
	
	</form>
</div>		
<div style="text-align:center;color:white;font-size:1.1em;">
	<?php
		if($zaehler >= 5){
			if(mysqli_stmt_execute($stmt)){ 					
					echo "Speichern hat geklappt.";
					echo "<br>";
					$last_id = mysqli_insert_id($link);
					echo "Hallo " . $anrede . " " . $nachname . ",<br>Vielen Dank für die Registrierung!<br>Bitte notieren Sie die folgenden Infos für den Abruf Ihrer Daten:<br> Ihr Benutzername ist " . $benutzername . "<br>und ihre ID ist " . $last_id .".<br>Viel Spaß";
					echo "<br>";
					
			
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
