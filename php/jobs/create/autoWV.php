<?php
    clearstatcache(); 
	include('../../validate.php');
	include('../../active_link.php');
?>
<!DOCTYPE HTML5>
<html>
<head>
	<title>Auto WV erstellen</title>
<!-- JQuery -->

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"  type="text/javascript" async></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"  type="text/javascript" async></script>

<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../../../css/reset.css" media="screen">
<link rel="stylesheet" type="text/css" href="../../../css/index.css" media="screen">
<link rel="stylesheet" type="text/css" href="../../../css/home.css" media="screen">
<link rel="stylesheet" type="text/css" href="../../../css/create/autowv.css" media="screen">

</head>
<body>
<?php 
	/** 
	  * FRONTEND ANPASSEN
	  **/
	require_once('../../../editFrontend/editFrontend.php');	 
?>
<!-- INFOBOX -->
<?php 
	if(!empty($_GET['info'])) { 
		echo '<div id="infobox">'.$_GET['info'].'<i class="icon-level-up"></i></div>'; 
	} 
?>
<script src="../../../js/createjob/autoWV.js" type="text/javascript" async></script>


<!-- Lindorff Logo -->
<div id="logo" >
	<img src="../../../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div> 

<!-- Navigationsleiste -->
<div id="navi">	

<h1>Navigation</h1>
		
<ul>
	<a href="../../../index.php"><li><i class="icon-home"></i> Home</li></a>
	<a href="../../../php/jobs.php" ><li class="active"><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="../../../php/bib.php" ><li><i class="icon-book "></i> Bibliothek</li></a>
	<a href="../../../php/user.php" ><li><i class="icon-user "></i> User</li></a>	
	<a href="../../../settings.php" ><li><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="../../../php/monitoring.php?page=today" ><li><i class="icon-code "></i> Monitoring</li></a>
</ul>

</div> 

<!-- Logout Button -->

<acronym title="Logout ?">
<a class="btn" href="../../../logout.php" ><i class="icon-signout"></i></a>		
</acronym>

<!-- Content Bereich -->

<div id="content">
    
    <div id="header_img">
        <img src="../../../img/icons/WV.png" name="content_header_pic" class="content_header_pic" />
    </div>
    <p name="header">Auto WV</p><br />
    <p style="text-align: center;">Hier besteht die Möglichkeit zum Einrichten einer neuen Auto WV</p><br />
    
    <div id="autowv">
        
    
    <!-- FORM -->
    <form action="./createAutoWV.php" method="POST">
        
        <!-- Variablen -->
        <input type="hidden" value="Dim WV_MandNrVon" name="Variable1" />
        <input type="hidden" value="Dim WV_MandNrBis" name="Variable2" />
        <input type="hidden" value="Dim WV_WVVon" name="Variable3" />
        <input type="hidden" value="Dim WV_WVBis" name="Variable4" />
        <input type="hidden" value="Dim WV_Anschreiben" name="Variable5" />
        <input type="hidden" value="Dim WV_MaxAnzahl" name="Variable6" />
        <input type="hidden" value="Dim WV_WVenErmittelt" name="Variable7" />
        <input type="hidden" value="Dim WV_AusschlussVorgangsListe" name="Variable8" />
        <input type="hidden" value="Dim WV_AutoWV" name="Variable9" />        
        
        <div id="infos_row_one">
            <div id="left">                
                <!-- DateiName -->                
                <p>Dateiname: <input type="text" name="dateiname" value="" placeholder="Bsp. AutoWV" /></p>
                <p style="color: gray;">Wie soll die Datei benannt werden ?</p>                
            </div>
            <div id="right">
             <!-- WV Vorgang -->
             <input type="checkbox" name="WV_Vorgang_Checkbox" checked />
                <p>WV_Vorgang: <input type="text" name="WV_Vorgang" value="" /></p>
                <p style="color: gray; font-size:12px;">Für welchen Vorgang soll die Auto-WV durchgeführt werden ?</p><br />
                <p style="color: gray; font-size:12px;">Wird weder diese Eigenschaft, noch die Eigenschaft "Vorgangsliste" übergeben, wird die Auto-WV für alle Vorgänge durchgeführt.</p>
            </div>
        </div>
                
        <br /><br />        
        
       <!-- Vorgangsliste -->
       <p class="ausschluss">Vorgangsliste: <input type="text" name="Vorgangsliste" value="" placeholder="Bsp. &quot;SI01&quot;, &quot;MI001&quot;" disabled="disabled"/></p>
       
        <!-- Mandantengrenzen -->
        
           <div id="mandantengrenzen"> 
                <div id="mandant_von">
                    <p>MandantNrVon: <input type="number" name="MandantNrVon" /></p>  
                    <p style="color: gray; font-size: 12px;">Gläubigernummer, ab der für alle folgenden Gläubiger die Auto-WV durchgeführt werden soll (inkl. diesem Gläubiger).</p>
                </div>
                <div id="mandant_bis">
                    <p>MandantNrBis: <input type="number" name="MandantNrBis" /></p>
                    <p style="color: gray; font-size: 12px;">Gläubigernummer, bis zu der für alle Gläubiger mit kleinerer Gläubigernummer die Auto-WV durchgeführt werden soll (inkl. diesem Gläubiger).</p>
                </div>
                <p style="color: gray; font-size: 12px;">Mit dieser Eigenschaft kann dem AutoWV-Objekt mitgeteilt werden, für welche Gläubiger die Auto-WV durchgeführt werden soll. Wird diese Eigenschaft gesetzt, werden in der Auto-WV alle Vorgänge abgearbeitet, deren Akten einem Gläubiger zugeordnet sind, dessen Gläubigernummer dieser Angabe entspricht oder kleiner ist. Wird keine Angabe gemacht, werden die Wiedervorlagen aller Akten berücksichtigt, die einem Gläubiger zugeordnet sind, dessen Gläubigernummer größer oder gleich der ggf. gesetzten Eigenschaft "MandNrVon" / "MandNrBis" ist.</p>
           </div>
          

        <!-- Zugangsdaten Connection -->
        <div id="connection">
        <p>Zugangsdaten für die Connection: </p><br />
        <p>UID: <input type="text" name="UID" value="" placeholder="SD" /></p>	
        <p>PW: <input type="text" name="PW" value="" placeholder="NEU7A" /></p>
        <p>DB: <input type="text" name="DB" value="" placeholder="IKA-BI"/></p>
        <p style="color: gray;"></p>
        </div>
        <br /><br />
        
        <div id="von_bis">
            <br />
            <p style="text-align: center;">WVVon :<input type="date" id="datepicker_von" name="WVVon" /> WVBis :<input type="date" id="datepicker_bis" name="WVBis" /></p>
            <p style="text-align: center; color: gray; font-size: 12px;">Mit dieser Eigenschaft kann dem AutoWV-Objekt mitgeteilt werden, bis zu welchem Wiedervorlage-Datum die Vorgänge selektiert werden sollen; das bedeutet, dass alle Wiedervorlage-Vorgänge, deren Datum diesem Wert entspricht bzw. zeitlich vor diesem angegebnen Datum liegt, durch die Auto-WV abgearbeitet werden. Wird weder ein Datum noch "NULL" angegeben, werden alle Wiedervorlagen berücksichtigt, deren WV-Datum zwischen dem "WVVon"-Datum und dem Tagesdatum liegt.</p>
            <br />
        </div>   
        <input type="hidden" value="heute = Date()" name="heute" /> 
         <!-- Anschreiben -->
        <div id="Anschreiben">
            <p style="text-align: center;">Anschreiben: 
			<select name="Anschreiben">
				<option value="0">Heute</option>
				<option value="1">Morgen</option>
			</select>
			</p>
            <p style="color: gray; font-size: 12px;">Mit dieser Eigenschaft kann dem AutoWV-Objekt mitgeteilt werden, welches Datum in den zu erstellenden Anschreiben gesetzt werden soll. Wird diese Eigenschaft nicht gesetzt, wird das aktuelle Tagesdatum verwendet.</p>
        </div>
        
        <input type="checkbox" name="MaxAnzahl_checkbox" />
        <p style="text-align: center;">MaxAnzahl: <input type="number" name="MaxAnzahl" style="width: 200px;" disabled="disabled" /></p>
        <br /><br />
       
        <div id="ausschluss">
        <!-- Ausschlussvorgangsliste -->
        <p class="ausschluss">Ausschlussvorgangsliste: <input type="radio" name="ausschluss" value="one" checked/><input type="text" placeholder="keine Wildcard Angaben möglich !" name="Ausschlussvorgangsliste" value="" /></p>
        <p class="bsp">Bsp. &quot;MA00&quot;,&quot;P999&quot;,&quot;SI771&quot;</p>
        <!-- Ausschlussvorgang -->
        <p class="ausschluss">Ausschlussvorgang: <input type="radio" name="ausschluss" value="two" /><input type="text" placeholder="Wildcard Angaben möglich !" value="" name="Ausschlussvorgang" disabled="disabled"/></p>
        <p class="bsp">Bsp. &quot;MA00,B;SI101;PI;P999&quot;</p><br />
        <p style="color: gray; font-size: 12px;">Welche Vorgänge sollen von der Auto-WV ignoriert werden ?</p>
        <br />
        <p style="color: gray; font-size: 12px;"><i class="icon-info" style="color: red;"></i> Wird keine der beiden Eigenschaften gesetzt, werden alle Vorgänge gemäß Selektion berücksichtigt.</p>
        </div>
        
        <br /><br />
        
        <!-- Pfad Protokolldatei -->
        <input type="hidden" value="ProtDatei = &quot;P:\Ikaros\Protokolle_AutoJobs\WV&quot; + WV_Vorgang + &quot;_&quot; + CStr(WV_MandNrVon) + &quot;-&quot; + CStr (WV_MandNrBis) + &quot;_&quot; + &quot;.txt" name="ProtDatei" />
        
        <!-- Protokolldatei erstellen -->
        <input type="hidden" value="Set FileSystemObjekt = CreateObject(&quot;Scripting.FileSystemObject&quot;)" name="FileSystemObjekt" />
        <input type="hidden" value="Set Datei = " />
        
        <!-- Application starten -->
        <input type="hidden" value="Set App = CreateObject(&quot;IKAROSsql.Application&quot)" name="start_application" />
        
        <!-- Anmelden -->
        <input type="hidden" value="AnmeldungOK = App.ConnectAsActiveX(UID, PW, DB, &quot;DE&quot;, &quot;N&quot;)" name="Anmeldung" />
        <input type="hidden" value="If AnmeldungOK Then" name="Anmeldung1" />
        <input type="hidden" value="Datei.WriteLine (&quot;Anmeldung gelungen&quot;) " name="Anmeldung2" />
        <input type="hidden" value="Set AutoWV = App.gibActiveXAutoWV() " name="Anmeldung3" />     
       
        
        <!-- Vorgaben setzen -->
        <input type="hidden" value="With AutoWV" name="With AutoWV" />
        <input type="hidden" value=".Vorgang = WV_Vorgang" name=".Vorgang" />                   
        <input type="hidden" value=".WVVon = WV_WVVon" name=".WVVon" />
        <input type="hidden" value=".WVBis = WV_WVBis" name=".WVBis" />        
        <input type="hidden" value=".MandNrVon = WV_MandNrVon" name=".MandNrVon" /> 
        <input type="hidden" value=".MandNrBis = WV_MandNrBis" name=".MandNrBis" /> 
        <input type="hidden" value=".Anschreiben = WV_Anschreiben" name=".Anschreiben" />  
        <input type="hidden" value=".MaxAnzahl = WV_MaxAnzahl" name=".MaxAnzahl" />   
        <input type="hidden" value=".AusschlussVorgangsListe = WV_AusschlussVorgangsListe" name=".AusschlussVorgangsListe" />   
        <input type="hidden" value=".AusschlussVorgang = WV_AusschlussVorgang" name=".AusschlussVorgang" />
        
        <div id="druckerspooler">
        <p>An den Druckerspooler schicken ?</p>
        <select name="Druckerspooler" style="width: 80px;">
            <option value=".Druckerspooler = True">JA</option>
            <option value=".Druckerspooler = False">NEIN</option>
        </select>
        </div>
        
        <input type="hidden" name="end with" value="End With" />
        
        <input type="hidden" name="WVermittelt" value="WV_WVenErmittelt = AutoWV.ermittleAnzWVGesamt()" />
        <input type="hidden" name="If WV_WVenErmittelt" value="If WV_WVenErmittelt > 0 Then" />
        <input type="hidden" name="AutoWV.starte" value="AutoWV.starte" />
        <input type="hidden" name="Protokoll = AutoWV.Protokolldatei" value="Protokoll = AutoWV.Protokolldatei" />
        <input type="hidden" name="Fehler = AutoWV.AnzFehler" value="Fehler = AutoWV.AnzFehler" />
        <input type="hidden" name="Bearbeitet = AutoWV.AnzWVBearbeitet" value="Bearbeitet = AutoWV.AnzWVBearbeitet" />
        <input type="hidden" name="End If" value="End If" />
        
        <input type="hidden" name="protokoll_1" value="Datei.WriteLine (&quot;----------------------------------------------&quot;)" />
        <input type="hidden" name="protokoll_2" value=" Datei.WriteLine (&quot;Meldungen: Mand &quot;+ CStr (WV_MandNrVon) + &quot; bis &quot; + CStr (WV_MandNrBis))" />
        <input type="hidden" name="protokoll_3" value="Datei.WriteLine (&quot;----------------------------------------------&quot;)" />
        <input type="hidden" name="protokoll_4" value="Datei.WriteLine (&quot;VorgangsKürzel              : &quot; + WV_Vorgang )" />
        <input type="hidden" name="protokoll_5" value="Datei.WriteLine (&quot;WV Datum                    : &quot; + Cstr (WV_WVVon) + &quot; - &quot; + Cstr (WV_WVBis) )" />
        <input type="hidden" name="protokoll_6" value="Datei.WriteLine (&quot;Anschreiben Datum           : &quot; + Cstr (WV_Anschreiben))" />
        <input type="hidden" name="protokoll_7" value="Datei.WriteLine (&quot;Protokollmeldungen          : &quot; + Protokoll) " /> 
        <input type="hidden" name="protokoll_8" value="Datei.WriteLine (&quot;WVen ermittelt              : &quot; + CStr(WV_WVenErmittelt))" />        
        <input type="hidden" name="protokoll_9" value="Datei.WriteLine (&quot;WVen mit Fehlern bearbeitet : &quot; + CStr(Fehler) )" />
        <input type="hidden" name="protokoll_10" value="Datei.WriteLine (&quot;WVen insgesamt bearbeitet   : &quot; + CStr(Bearbeitet))" />
        <input type="hidden" name="protokoll_11" value="Datei.WriteLine (&quot;----------------------------------------------&quot;)" />
        
        <input type="hidden" name="else" value="Else"/>
        <input type="hidden" name="else_" value="Datei.WriteLine (&quot;Anmeldung misslungen!!!&quot;)" />
        <input type="hidden" name="end if" value="End If" />
        
        <!-- Quit App -->
        <input type="hidden" name="quit app" value="App.Quit" />
        <!-- Datei Close -->
        <input type="hidden" name="datei close" value="Datei.Close" />
        
        <input type="submit" value="AutoWV erstellen" />
    
    </form> <!-- POST -->
    
    </div> <!-- DIV AUTOWV -->

</div>


<!-- Background picture -->

<div id="background-pic">
    <img src="../../../img/icons/WV.png" name="autowv_pic" class="autowv_pic" />
</div>

<!-- Footer -->

<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>


</body>
</html>