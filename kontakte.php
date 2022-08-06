<!DOCTYPE html>
<html lang="de">
<head> 
		 <meta charset="utf-8">
		 <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="icon" type="image/icon" sizes="16x16" href="favicon.ico">
			<link rel="stylesheet" href="quiz.css"> 
			<link rel="stylesheet" href="kontakte.css">
       
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
	
		<form method=POST action="ausgabe.php">
			<label for="benutzername" style="color:white;">Bitte geben Sie Ihren Benutzernamen ein: </label>
			<input name="benutzername" type="text" class="feedback-input" placeholder="Benutzername" >
			<label for="id" style="color:white;">Bitte geben Sie Ihre ID ein: </label>
			<input name="id" type="number" class="feedback-input" placeholder="ID"  min="1" max="1000" >
			
			<input type="submit" value="Absenden">
		</form>
		 <p  style="text-align:center; color:white;" >
				Kontakt:<br>
				Sepp Hase<br>
				Marlene-Dietrich-Straße 1<br>     	
				89231 Neu-Ulm<br><br>
				Telefon:0731 98491- <br>
				Fax: 0731 98491-88<br>
				E-Mail:SHase@ctc-lohr.net
		 </p>
	</div>
</body>
	   
 </html>
