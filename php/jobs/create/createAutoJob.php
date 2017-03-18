<?php
	/** 
		@TODO 
		- on event hinzufügen 
	**/

	include('../../validate.php');	
    
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];        
    
    $script_name = explode("/",$script, 6);

	if ( !empty($_GET) ) {
		echo '<div id="info"><p>'.$_GET['info'].'</p><i class="icon-info icon-2x"></i></div>';
	}
    
?>
<!DOCTYPE HTML5>
<html>
	<head>
		<title>Task erstellen</title>
	
<!-- Stylesheets -->
<!----------------->
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../../../css/reset.css">
<link rel="stylesheet" type="text/css" href="../../../css/index.css">
<link rel="stylesheet" type="text/css" href="../../../css/create/autojob.css">
<link rel="stylesheet" type="text/css" href="../../../css/tooltip/tooltip.css">
</head>

<body>
<?php 
	/** 
	  * FRONTEND ANPASSEN
	  **/
	require_once('../../../editFrontend/editFrontend.php');	 
?>
<!-- JQuery -->
<!------------>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"  type="text/javascript" ></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"  type="text/javascript" ></script>
<!-- Feldanzeige auf Schedule Type anpassen -->
<script src="../../../js/createjob/createAutoJob.js" type="text/javascript"></script>

<div id="loading"></div>

<!-- Lindorff Logo --> 
<!------------------->
<div id="logo" >
<img src="../../../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div> 

<!-- Navigationsleiste -->
<!----------------------->
<div id="navi">	
	<h1>Navigation</h1>
<ul>
	<a href="../../../index.php" ><li><i class="icon-home"></i> Home</li></a>
	<a href="../../../php/jobs.php" ><li class="active"><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="../../../php/bib.php" ><li><i class="icon-book "></i> Bibliothek</li></a>
	<a href="../../../php/user.php" ><li><i class="icon-user "></i> User</li></a>
	<a href="../../../php/settings.php" ><li><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="../../../php/monitoring.php?page=today" ><li><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> 

<!-- Logout Button --> 
<!------------------->
<acronym title="Logout ?">
<a class="btn" href="../../../logout.php" ><i class="icon-signout"></i></a>	
</acronym>

<!-- Content Bereich -->
<!--------------------->
<div id="content">
    
    <div id="content_header">
        <p><img src="../../../img/icons/schedule.png" name="task_img" /></p>
        <p class="header">Automatischer Job</p><br />
        <p>Neuen automatischen Job anlegen :</p><br />
    </div>
    
<form action="./ajax/CREATE.php" id="form" method="POST" >

<div id="sortable">

<div id="hide_show">
    <div id="row">
        <p><acronym title="Option: /S">System: </acronym>
			<select id="HostName" name="/S">
				<option value="fcap09.falcon.local">fcap09</option>
				<option value="fcap17.falcon.local">fcap17</option>
			</select>
		</p>
        <p class="info">Gibt das Remotesystem für die Verbindungsherstellung an. Bei fehlerhafte Angabe wird standardmäßig das lokale System als Systemparameter verwendet.</p>
	</div>
</div>    
        
    <div id="row">
        <p><acronym title="Option: /U">Benutzername: </acronym><input type="text" name="/U" value="" /></p>
        <p class="info">Bestimmt den Benutzerkontext, unter dem "SchTasks.exe" ausgeführt wird.</p>
    </div>
    
    <div id="row">
        <p><acronym title="Option: /P">[Kennwort]: </acronym><input type="text" name="/P" value="" /></p>
        <p class="info">Bestimmt das Kennwort für den Benutzerkontext. Auslassung fordert zur Kennworteingabe auf. </p>
    </div>
	
	<div id="row">
        <acronym title="Pflichtfeld!"><i class="icon-star"></i></acronym>
        <p><acronym title="Option: /SC">Zeitplan: </acronym>
            <select id="zeitplan" name="/SC">
                <option value="MINUTE">Minute</option>
                <option value="HOURLY">Stündlich</option>
                <option value="DAILY">Täglich</option>
                <option value="WEEKLY">Wöchentlich</option>
                <option value="MONTHLY">Monatlich</option>
                <option value="ONCE">Einmal</option>
                <option value="ONSTART">Beim Start</option>
                <option value="ONLOGON">Bei Anmeldung</option>
                <option value="ONIDLE">Bei Leerlauf</option>
                <!--
				<option value="ONEVENT">Bei Ereignis</option>
				-->
            </select>
        </p>
        <p class="info">Legt fest, wie oft der Zeitplan ausgeführt wird.</p><br />
        
        <p><acronym title="Option: /MO">Parameter: </acronym><input type="text" name="/MO" value="" /></p>
        <p class="info">Gültige Werte pro Zeitplantyp:</p><i class="icon-chevron-down"></i><br />
		<div id="werte">
        <p class="info">Minute: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1 - 1439 Minuten.</p>
        <p class="info">Stündlich: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1 - 23 Stunden.</p>
        <p class="info">Täglich: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1 - 365 Tage.</p>
        <p class="info">Wöchentlich: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1 - 52 Wochen.</p>
        <p class="info">Beim Start: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Keine Parameter</p>
		<p class="info">Einmalig: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Keine Parameter </p>
        <p class="info">Bei Anmeldung: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Keine Parameter</p>
        <p class="info">Bei Leerlauf: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Keine Parameter</p>
        <p class="info">Monatlich: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1-12 <!-- oder ERSTER, ZWEITER, DRITTER, VIERTER, LETZTER, LETZTERTAG. --></p>                
		</div>
		<!-- wurde aus Zeitmangel ausgelassen -->
    </div>
	
	<div id="row">
        <acronym title="Pflichtfeld!"><i class="icon-star"></i></acronym>
        <p><acronym title="Option: /TN">Aufgabenname: </acronym><input type="text" name="/TN" value=""/></p>
        <p class="info">Gibt einen Namen an, der die geplante Aufgabe eindeutig indentifiziert.</p>
    </div>
    
    <div id="row">
        <acronym title="Pflichtfeld!"><i class="icon-star"></i></acronym>		
        <p><acronym title="Option: /TR">Auszuführende Aufgabe: </acronym><input type="text" name="/TR" value="" title="Bitte darauf achten, dass der angegebene Pfad auf dem auszuführenden Server erreichbar ist."/></p>
        <p class="info">Bestimmt den Pfad und den Dateinamen des Programms, das zur geplanten Zeit ausgeführt werden soll. Beispiel: C:\Windows\system32\calc.exe</p>
    </div>

<div id="RV">    
    <div id="row">
        <i class="icon-asterisk"></i>
        <p><acronym title="Option: /RU">Benutzername: </acronym><input type="text" name="/RU" value="" /></p>
        <p class="info">Gibt das Benutzerkonto für "Ausführen als" 'Benutzerkonto' an, unter dem die Aufgabe ausgeführt wird. Gültige Werte für das Systemkonto sind "", "NT-AUTORITÄT\SYSTEM" oder "SYSTEM". Für v2-Aufgaben sind "NT-AUTORITÄT\NETWORKSERVICE" auch verfügbar, sowie die bekannten SIDs für alle drei.</p>
    </div>
</div>

<div id="RP">    
    <div id="row">
        <i class="icon-asterisk"></i>
        <p><acronym title="Option: /RP">[Kennwort]: </acronym><input type="text" name="/RP" /></p>
        <p class="info">Gibt das Benutzerkennwort für "Ausführen als" an. Es muss entweder der Wert "*" oder kein Wert für die Kennwortaufforderung eingegeben werden. Dieses Kennwort wird für das Systemkonto ignoriert. Muss mit der Option /RV oder /XML kombiniert werden.</p>
    </div>
</div>

   
<div id="tage">  
    <div id="row">
        <p><acronym title="Option: /D">Tage: </acronym><input type="text" name="/D" value="" disabled="disabled" /></p>
		<div id="tage_check">
			<div id="left">
			<p><input type="checkbox" name="/D1" value="MO" /> Montag</p>
			<p><input type="checkbox" name="/D2" value="DI" /> Dienstag</p>
			<p><input type="checkbox" name="/D3" value="MI" /> Mittwoch</p>
			<p><input type="checkbox" name="/D4" value="DO" /> Donnerstag</p>
			</div>
			<div id="right">
			<p><input type="checkbox" name="/D5" value="FR" /> Freitag</p>
			<p><input type="checkbox" name="/D6" value="SA" /> Samstag</p>
			<p><input type="checkbox" name="/D7" value="SO" /> Sonntag</p>
			</div>
		</div>
        <p class="info">Bestimmt den Tag, an dem die Aufgabe ausgeführt wird. Gültige Werte: MO, DI, MI, DO, FR, SA, SO und für monatliche Zeitpläne 1 - 31 'Tage des Monats'. Der Platzhalter "*" steht für alle Tage.</p>
    </div>
</div>
<div id="monate">
    <div id="row">
        <p><acronym title="Option: /M">Monate: </acronym><input type="text" name="/M" value="" /></p>
		<div id="monate_check">
			<div id="left">
			<p><input type="checkbox" name="/M1" value="JAN" /> Januar</p>
			<p><input type="checkbox" name="/M2" value="FEB" /> Feburar</p>
			<p><input type="checkbox" name="/M3" value="MÄR" /> März</p>
			<p><input type="checkbox" name="/M4" value="APR" /> April</p>
			<p><input type="checkbox" name="/M5" value="MAI" /> Mai</p>
			<p><input type="checkbox" name="/M6" value="JUN" /> Juni</p>
			</div>
			<div id="right">			
			<p><input type="checkbox" name="/M7" value="JUL" /> Juli</p>
			<p><input type="checkbox" name="/M8" value="AUG" /> August</p>
			<p><input type="checkbox" name="/M9" value="SEP" /> September</p>
			<p><input type="checkbox" name="/M10" value="OKT" /> Oktober</p>
			<p><input type="checkbox" name="/M11" value="NOV" /> November</p>
			<p><input type="checkbox" name="/M12" value="DEZ" /> Dezember</p>
			</div>
		</div>
		<p class="info">Bestimmt die Monate des Jahres. Standardmäßig wird der erste Tag des Monats verwendet. Gültige Werte: JAN, FEB, MÄR, APR, MAI, JUN, JUL, AUG, SEP, OKT, NOV, DEZ. Der Platzhalter "*" steht für alle Monate.</p>
    </div>
</div>
 
    <div id="row">
        <p><acronym title="Option: /I">Leerlaufzeit: </acronym><input type="text" name="/I" value="" placeholder="1 - 999" /></p>
        <p class="info">Legt den Leerlauf-Wartezeitraum fest, bevor die geplante Aufgabe BEILEERLAUF ausgeführt wird. Gültiger Bereich: 1 - 999 Minuten.</p>
    </div>    

<div id="Startzeit"> 
    <div id="row" class="startzeit">
        <p style="text-align: center;"><span class="Startzeit" style="color: red; font-size:12px;">Wert muss bei Option 'Einmal' angegeben werden.</span></p>
        <p><acronym title="Option: /ST">Startzeit: </acronym><input type="text" name="/ST" value=""/></p>
        <p class="info">Bestimmt die Startzeit der Aufgabe. Zeitformat: HH:mm '24 Stunden', z.B. steht 14:30 für 2:30 PM. Wenn /ST nicht angegeben ist, wird standardmäßig die aktuelle Zeit verwendet. Diese Option ist erforderlich für /SC EINMAL.</p>
    </div>
</div>

<div id="intervall">
    <div id="row">
        <p><acronym title="Option: /RI">Intervall: </acronym><input type="text" name="/RI" value=""/></p>
        <p class="info">Bestimmt das Wiederholungsintervall in Minuten. Diese Option kann nicht für folgende Zeitplantypen verwendet werden: MINUTE, STÜNDLICH, BEIMSTART, BEIANMELDUNG, BEILEERLAUF, BEIEREIGNIS. Gültiger Bereich: 1 - 599940 Minuten. Wenn entweder /ET oder /DU angegebn ist, wird standardmäßig 10 Minuten verwendet.</p>
    </div>
</div>


<div id="endzeit">
    <div id="row">
        <p><acronym title="Option: /ET">Endzeit: </acronym><input type="text" name="/ET" value="" /></p>
        <p class="info">Bestimmt die Endzeit der Aufgabe. Zeitformat: HH:mm. Dies gilt nicht für die Option /ET und für folgende Zeitplantypen: BEIMSTART, BEIANMELDUNG, BEILEERLAUF, BEIEREIGNIS.</p>
    </div>
</div>
    
<div id="dauer">    
    <div id="row">
        <p><acronym title="Option: /DU">Dauer: </acronym><input type="text" name="/DU" value="" /></p>
        <p class="info">Gibt die Ausführungsdauer der Aufgabe an. Zeitformat: HH:mm. Dies gilt nicht für die Option /ET und für folgende Zeitplantype: BEIMSTART, BEIANMELDUNG, BEILEERLAUF, BEIEREIGNIS. Bei /V1-Aufgaben wird bei Angabe von /RI standardmäßig eine Dauer von 1 Stunde eingestellt. </p>
    </div>
</div>    

<div id="beenden">
    <div id="row">
        <p><acronym title="Option: /K">Beenden ? </acronym><input type="checkbox" name="/K" /></p>
        <p class="info">Beendet die Aufgabe zur Endzeit oder nach Ablauf der Dauer. Dies gilt nicht für die folgenden Zeitplantypen: BEIMSTART, BEIANMELDUNG, BEILEERLAUF, BEIEREIGNIS. Die Optionen /ET oder /DU müssen angegeben werden.</p>
    </div>
</div>

<div id="startdatum">   
    <div id="row">
        <p><acronym title="Option: /SD">Startdatum: </acronym><input type="date" id="datepicker_start" name="/SD" value="" /></p>
        <p class="info">Gibt das erste Datum an, an dem die Aufgabe ausgeführt wird. Zeitformat: dd/mm/yyyy. Als Standardwert wird das aktuelle Datum verwendet. Dies gilt nicht für folgende Zeitplantypen: EINMAL, BEIMSTART, BEIANMELDUNG, BEILEERLAUF, BEIEREIGNIS.</p>
    </div>
</div> 

<div id="enddatum">
    <div id="row">
        <p><acronym title="Option: /ED">Enddatum: </acronym><input type="date" id="datepicker_end" name="/ED"/></p>
        <p class="info">Gibt das letzte Datum an, an dem die Aufgabe ausgeführt wird. Zeitformat: dd/mm/yyyy. Dies gilt nicht für folgende Zeitplantypen: EINMAL, BEIMSTART, BEIANMELDUNG, BEILEERLAUF, BEIEREIGNIS.</p>
    </div>
</div>    
    
    <div id="row">
        <p><acronym title="Option: /EC">Kanalname: </acronym><input type="text" name="/EC" value="" /></p>
        <p class="info">Gibt das Kanalereignis für OnEvent-Trigger an.</p>
    </div>
    
    <div id="row">
        <p><acronym title="Option: /IT">Interaktive Ausführung ? </acronym><input type="checkbox" name="/IT" /></p>
        <p class="info">Ermöglicht das interaktive Ausführen der Aufgaben nur, wenn der Benutzer /RV zum Zeitpunkt der Auftragsausführung angemeldet ist. Diese Aufgabe wird nur ausgeführt, wenn der Benutzer angemeldet ist.</p>
    </div>
    
    <div id="row">
        <p><acronym title="Option: /NP">Kein Passwort ? </acronym><input type="checkbox" name="/NP" /></p>
        <p class="info">Es wird kein Kennwort gespeichert. Die Aufgabe wird nicht interaktiv unter dem jeweiligen Benutzerkonto ausgeführt. Nur lokale Resourcen sind verfügbar.</p>
    </div>
    
    <div id="row">
        <p><acronym title="Option: /Z">Löschen nach Ausführung ? </acronym><input type="checkbox" name="/Z"/></p>
        <p class="info">Kennzeichnet die Aufgabe zum Löschen nach der letzten Ausführung.</p>
    </div>
    
    <div id="row">
        <i class="icon-asterisk"></i>
        <p><acronym title="Option: /XML">XML-Datei: </acronym><input type="text" name="/XML" value="" /></p>
        <p class="info">Erstellt eine Aufgabe aus der Aufgaben-XML, die in einer Datei angegebn ist. Kann mit den Optionen /RV und /RP kombiniert, oder nur mit /RP verwendet werden, wenn das Aufgaben-XML bereits den Prinzipal enthält.</p>
    </div>

<div id="vista"> 
    <div id="row">
        <p><acronym title="Option: /V1">Vor Vista ? </acronym><input type="checkbox" name="/V1"/></p>
        <p class="info">Erstellt eine Aufgabe, die für Plattformen vor Vista sichtbar ist. Nicht kampatibel mit /XML</p>
    </div>
</div>

    <div id="row">
        <p><acronym title="Option: /F">Unterdrücken ? </acronym><input type="checkbox" name="/F" /></p>
        <p class="info">Erzwingt das Erstellen der Aufgabe und unterdrückt Warnungen, falls die angegebene Aufgabe bereits vorhanden ist.</p>
    </div>
   
<div id="ebene">
    <div id="row">
        <p><acronym title="Option: /RL">Ebene: </acronym>
		<select id="" name="/RL">
			<option value="LIMITED" selected>Eingeschränkt</option>
			<option value="HIGHEST">Höchste</option>
		</select>
        <p class="info">Legt die Ausführungsebene für den Auftrag fest. Gültige Werte sind EINGESCHRÄNKT und HÖCHSTE. Der Standardwert ist EINGESCHRÄNKT.</p>
    </div>
</div>
<div id="delay">    
    <div id="row">
        <p><acronym title="Option: /DELAY">Verzögerungszeit: </acronym><input type="text" name="/DELAY" value=""/></p>
        <p class="info">Gibt an, wie lange gewartet wird, bis die Aufgabe nach dem Auslösen des Triggers ausgeführt wird. Zeitformat: mmmm:ss <br />Diese Option ist nur gültig für die Zeitplantypen BEIMSTART, BEIANMELDUNG, BEIEREIGNIS.</p>
    </div>
</div>

</div> <!-- SORTABLE  -->

    <!-- SUBMIT -->
	<div id="submit">
		<input type="submit" name="submit" value="Speichern" />
	</div>
</form>
</div>

<!-- Background picture -->
<!------------------------>
<div id="background-pic">
	<img src="../../../img/icons/schedule.png" name="schedule_pic" class="schedule_pic" />
</div>

	

<!-- Footer --> 
<!------------>
<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>
</body>
</html>