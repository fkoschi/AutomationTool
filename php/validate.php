 <?php 
	session_start();
	$hostname = $_SERVER['HTTP_HOST'];
	$path = dirname($_SERVER['PHP_SELF']);
	$server = "localhost";
	
	$path_time_img = 'http://' . $server. '/Tool/img/icons/time.png';	
	
	if (!isset($_SESSION['angemeldet']) or !$_SESSION['angemeldet']){
		
		header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/UserLogin.php');
		exit;
	} 
	else {
		echo '<a class="username" href="http://'.$server.'/Tool/php/settings.php"><p class="Username">' . $_SESSION['Username'] . '</p></a>'; 
	}
	
	
	$timestamp = time();
	$h = date("H", $timestamp);
	$m = date("i", $timestamp);
	// Ausgabe serverseitig 
	echo 	"<div id='clock_time'>	
				<p> " . date('D') . "  				
					<img src='". $path_time_img ."'>
				".$h . ":<span style='color:#3F88FF;'>" . $m . "</span></p>
			</div>";
	echo "<canvas id='canvas'></canvas>";	
	
	$path_clock_img = 'http://' . $server . '/Tool/img/icons/index.png';
	echo "<img name='clock_stroke' src=" . $path_clock_img . ">";

	// Favicon
	echo "<link rel='shortcut icon' type='image/x-icon' href='http://".$server."/Tool/img/favicon/faviconNEU.ico'>";
	// Title 
	echo "<title>TIME</title>";
?>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	// Uhrzeit jede Minute aktualisieren
	setInterval(function() {
		d = new Date ();
		var weekdays = new Array ("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
		h = (d.getHours () < 10 ? '0' + d.getHours () : d.getHours ());
		m = (d.getMinutes () < 10 ? '0' + d.getMinutes () : d.getMinutes ());
		s = (d.getSeconds () < 10 ? '0' + d.getSeconds () : d.getSeconds ());
		
		path = "<img src='http://localhost/Tool/img/icons/time.png'>";
		
		$("#clock_time p").replaceWith('<p>' + weekdays[d.getDay ()] + path + h + ':<span style="color:#3F88FF;"">' + m + '</span></p>');
			
	}, 60000 );	// alle 60 Sekunden	
	
	var c = document.getElementById('canvas');
	
	AnalogClock(c);	
	
function AnalogClock(objCanvas) {
  // Neues Datumsobjekt
  var objDate = new Date();
  var intSek = objDate.getSeconds();     // Sekunden 0..59
  var intMin = objDate.getMinutes();     // Minuten 0..59
  var intHours = objDate.getHours()%12;  // Stunden 0..11
  // Kontext-Objekt
  var objContext = objCanvas.getContext("2d");

  objContext.clearRect(0, 0, 150, 150);  // Anzeigebereich leeren  

  objContext.save();                     // Ausgangszustand speichern
  objContext.translate(75, 75);          // Koordinatensystem in Mittelpkt des Ziffernblatts verschieben

  // Stunden
  objContext.save();
  // Aktuelle Stunde zzgl. Minutenanteil über Drehung des Koordinatensystems
  // (kontinuierlicher Übergang zwischen zwei Stunden gewünscht, keine Sprung)
  objContext.rotate(intHours*Math.PI/6+intMin*Math.PI/360);
  objContext.beginPath();                // Neuen Pfad anlegen
  objContext.moveTo(0, 1);              // Zeiger über Mitte hinaus zeichnen
  objContext.lineTo(0, -20);             // Stundenzeiger im gedrehten Koord-Sys. um 38 Einheiten nach oben zeichnen
  // Linienstyle festlegen und zeichnen
  objContext.lineWidth = 1;
  objContext.strokeStyle = "black";
  objContext.stroke();
  objContext.restore();

  // Minuten
  objContext.save();
  objContext.rotate(intMin*Math.PI/30);
  objContext.beginPath();
  objContext.moveTo(0, 1);
  objContext.lineTo(0, -19);
  objContext.lineWidth = 1;
  objContext.strokeStyle = "#3381FF";
  objContext.stroke();
  objContext.restore();		
	
  objContext.restore();

  hTimer = window.setTimeout(function(){ AnalogClock(objCanvas);}, 1000);
}
	
	
});
</script>