<?php
require_once "config.php";
 
		
          
        
 
        $geschlecht = $vorname = $nachname = $begruessung = $benutzername = $email = $localFileName =  $id =$emailErr = $benutzernameErr = $vornameErr = $nachnameErr = "";

		$allowed_files = [
			'image/jpeg' => 'jpg',
			'image/gif' => 'gif',
			'image/png' => 'png',
			'application/pdf' => 'pdf'];

		function test_input($data) {
			$data = trim($data,"\x00..\x2F\x3A..\x40\x5B..\x60\x7B..\x7F");
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
	}

		if($_SERVER["REQUEST_METHOD"] == "POST")
 {
			
			$vorname = test_input($_POST['vorname']);
			$nachname = test_input($_POST['nachname']);
			$benutzername = test_input($_POST['benutzername']);
			$email = test_input($_POST['email']);
			$geschlecht = test_input($_POST['geschlecht']);
			
			
	//var_dump($_SERVER);

			$anrede = "";

			switch($geschlecht) {
			case "m": $anrede = "Herr"; break;
			case "w": $anrede = "Frau"; break;
			default: $anrede = $vorname;
			}
			
//Benutzer anlegen insert into

		$sql = "INSERT INTO benutzer (vorname, nachname, benutzername, email, timestamp, bild, geschlecht) VALUES (?,?,?,?,?,?,?) ;";

		
		
		/*if (mysqli_query($link, $sql)) {
		echo "Neuen Eintrag erfolgreich gespeichert. <br>";
		} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($link). "<br>";
		}*/
		
		$stmt = mysqli_prepare($link, $sql);
		mysqli_stmt_bind_param($stmt, 'sssssss', $vorname, $nachname, $benutzername, $email, $timestamp, $localFileName, $geschlecht);

// hier  braucheb wir die Benutzereingaben , die aus dem Formular ausgelesen und gestestet werden

//Bildvariable wird verarbeitet 
// Es wurde eine Datei hochgeladen und dabei sind keine Fehler aufgetreten 

	if(!empty($_FILES) && $_FILES['bild']['error'] == UPLOAD_ERR_OK) { 
		$type = mime_content_type($_FILES['bild']['tmp_name']); 
		$localFileName = "./uploads/files/" . $_FILES['bild']['name']; 
 //Prüfung der Dateiendung 
			$extension = strtolower(pathinfo($_FILES['bild']['name'], 
			PATHINFO_EXTENSION)); 
			$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif', 'pdf'); 
 
				if(!in_array($extension, $allowed_extensions)) { 
				die("Ungültige Dateiendung"); 
					} 
 //var_dump($_FILES['bild']['tmp_name']);
 //var_dump( $localFileName);
	if (move_uploaded_file($_FILES['bild']['tmp_name'], $localFileName)) { 
			//Dateityp überprüfen 
			if(isset($allowed_files[$type])) { 
			// Größe überprüfen 
			if(filesize($_FILES['bild']['tmp_name']) <= 2000000) { 
				echo "Alles ok."; 
					} else { 
						echo "Die Datei ist zu gross."; 
							} 
					} else { 
						echo "Dieser Dateityp ist nicht zulässig."; 
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
          }
      }
	  
	  if (empty($_POST["benutzername"])) {
		$benutzernameErr = "Eingabe eines Benutzernamens ist erforderlich!";
	  } else {
			//hier werden alle Eingaben außer Buchstaben entfernt
			$benutzername = test_input($_POST["benutzername"]);	
				//hier wird nochmal abgefragt, dass nur Buchstaben und Zahlen erlaubt sind
				if (!preg_match("/^[ÄÜÖäüößa-zA-Z-]*[0-9]*$/",$benutzername)) {
				  $benutzernameErr = "Es sind nur Buchstaben und Zahlen erlaubt";
				}
			
		}
		
		if (empty($_POST["vornamen"])) {
		$vornameErr = "Eingabe eines Vornamens ist erforderlich!";
	  } else {
			//hier werden alle Eingaben außer Buchstaben entfernt
			$vorname = test_input($_POST["vorname"]);	
				//hier wird nochmal abgefragt, dass nur Buchstaben erlaubt sind
				$vorname = test_input($_POST["name"]);
					if (!preg_match("/^[a-zA-Z ]*$/",$vorname)) {
					$vornameErr = "Bitte nur Buchstaben eingeben";
					}
			
		}
		if (empty($_POST["nachname"])) {
		$nachnameErr = "Eingabe eines Nachnamens ist erforderlich!";
	  } else {
			//hier werden alle Eingaben außer Buchstaben entfernt
			$nachname = test_input($_POST["nachname"]);	
				//hier wird nochmal abgefragt, dass nur Buchstaben erlaubt sind
				if (!preg_match("/^[ÄÜÖäüößa-zA-Z-]*$/",$nachname)) {
				  $nachnameErr = "Es sind nur Buchstaben und Zahlen erlaubt";
				}
			
			}
			
			
		if(mysqli_stmt_execute($stmt)){ 
			
					echo "speichern hat geklappt.";
					mysqli_stmt_close($stmt);
				}	else{
					echo "Speichern hat nicht geklappt.";
				}
				// id zur ausgabe vorbereiten
				$last_id = mysqli_insert_id($link);
		//Ausgabe von anrede und id
$begruessung = "Hallo " . $anrede . " " . $nachname . " vielen Dank für die Registrierung! Ihr Benutzername ist " . $benutzername . " und deine ID ist " . $last_id .". Viel Spaß";


	
 }
					 mysqli_close($link);

?>


<!DOCTYPE html>
<html lang="de">
	 <head> 
		 <meta charset="utf-8">
		 <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="icon" type="image/icon" sizes="16x16" href="favicon.ico">
			<link rel="stylesheet" href="quiz.css"> 
     		<link rel="stylesheet" href="newsletter.css"> 

       
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
    <li><a href="anmeldung.php">Anmeldung</a>
      <ul>
        <li><a href="impressum.html">Impressum</a></li>
        <li><a href="datenschutz.html">Datenschutz</a></li>
      </ul>
    </li>
    <li><a href="quiz.html">Start</a></li>
	<li><a href="kontakte.html">Kontakte</a></li>
  </ul>
</nav>
<div>
	<h2 class="text-dark">Um deinen Score zu speichern mußt du dich Anmelden oder Registrieren?</h2>
</div>

</header>
<div class="body">
	
		<form method = "POST"  action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  enctype="multipart/form-data">
		<p> Um sich Registriern zu können füllen Sie bitte die Felder vollständig aus:<br><br><?php echo $begruessung; ?></p>
		 
		 
        <label for="geschlecht"  style="color:white;">Bitte wählen Sie: </label>
		
			<select name="geschlecht" id="geschlecht" class="feedback-input">
			  <option value="<?php echo $geschlecht;?>"></option>
				<option value="m">männlich</option>
				<option value="w">weiblich</option>
				<option value="d">divers</option>
				<option value="k">keine Angabe</option>
			</select>
		
       

		
			<label for="vorname" style="color:white;" >Vorname<b class="error" style="color:red;"> *<?php echo $vornameErr;?></b></label>
			<input type="text" name="vorname" id="vorname" class="feedback-input" placeholder="Vorname" value="<?php echo $vorname;?>">
		
		
		<label for="nachname"  style="color:white;">Nachname<b class="error" style="color:red;"> *<?php echo $nachnameErr;?></b></label>
			<input type="text" name="nachname" id="nachname"  class="feedback-input" placeholder="Nachname" value="<?php echo $nachname;?>">
		
		
			<label for="benutzername" style="color:white;">Benutzername<b class="error" style="color:red;"> *<?php echo $benutzernameErr;?></b></label>
			<input type="text" name="benutzername" id="benutzername"  class="feedback-input" placeholder="Benutzername" value="<?php echo $benutzername;?>">
		
		
		<label for="email"  style="color:white;">Bitte geben Sie Ihre Email-Adresse ein:<b class="error" style="color:red;"> *<?php echo $emailErr;?></b> </label>
			<input type="text" name="email" id="email"  class="feedback-input"placeholder="Email-Adresse" value="<?php echo $email;?>">
				
        
	
		
			<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
			<label  style="color:white;">Bitte wählen Sie ein Bild (*.jpg, *.png, *.gif oder *.pdf) zum Hochladen aus.</label>
			<input name="bild" type="file" accept="image/gif,image/jpeg,image/png,application/pdf"> 
			</p>

			<button type="submit" name = "speichern" id="speichern">Anmelden</button>
		</p>
		<b class="error" style="color:red;">* Pflichtfeld</b>
			<div style="text-align:center; color:white; font-size:1.1em;"><?php
			
				
	
				
		?></div>
	</form>
		</div>		
			


	<div class="robbi"></div>
			
	   </body>
	   
	   </html>