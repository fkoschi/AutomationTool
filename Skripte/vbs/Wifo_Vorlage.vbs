
'----------------Vorlage für ein VBScript zum Abruf von Wifos----------------

'----------------Variablen------------
Dim WV_MandNrVon
Dim WV_MandnrBis
Dim WV_WVVon
Dim WV_WVBis
Dim WV_Anschreiben
Dim WV_MaxAnzahl
Dim WV_WVenErmittelt
Dim WV_AusschlussVorgangsListe

'---------------Auto WV--------------
Dim AutoWV

'--------------Hier wird die Mandanten-Bereichsgrenze gesetzt------------

'-------------- GROß -------------------------
'-------------- 1 bis 6718 -------------------
'-------------- 6772 bis 6790 ----------------
'-------------- 6800 bis 11999 ---------------

'-------------- KLEIN ------------------------
'-------------- 20000 bis - ------------------
'-------------- 13000 bis 18999 --------------
'-------------- 6792 bis 6798 ----------------
'-------------- 6758 bis 6770 ----------------

WV_MandNrVon = 6772
WV_MandNrBis = 6790

' --------------------------- Mandanten Bereichsgrenzen setzen ------------------------------
'
' ------------- Übergabe auch bei Aufruf der .vbs Datei möglich als Paramter ----------------

If WScript.Arguments.Count = 0 Then
 WScript.Echo("Keine Parameter gesetzt!")
Else If WScript.Arguments.Count = 1 Then
	param1 = WScript.Arguments(0)
	param2 = ""
Else 
	param1 = WScript.Arguments(0)
	param2 = WScript.Arguments(1)
End If
End If

WV_MandNrVon = param1
WV_MandNrBis = param2


' ------------------------ Hier wird der abzurufende Vorgang gesetzt --------------------------------

WV_Vorgang = "Alle"

'---------------------------- Hier wird die Datumsbereichsgrenze gesetzt ---------------------------------
'------------------------------- immer Mittwochs die komplette Spanne ------------------------------------
'----- 1 = Sonntag, 2 = Montag, 3 = Dienstag, 4 = Mittwoch, 5 = Donnerstag, 6 = Freitag, 7 = Samstag -----

If Weekday(Date) = 4 Then
	WV_WVVon = DateAdd("d",-18000,heute)
	WV_WVBis = DateAdd("d",0,heute)
Else
	WV_WVVon = DateAdd("d",-1,heute)
	WV_WVBis = DateAdd("d",0,heute)
End If

'-----------Hier wird das Vorgangsdatum der Schreiben/Vorgänge gesetzt---------------------------
'-----------bei Freitag wird Montag als Drucktag festegelegt, ansonsten immer der Folgetag-------

If Weekday(Date) = 6 Then
	WV_Anschreiben = DateAdd("d",3,heute)
Else
	WV_Anschreiben = DateAdd("d",1,heute)
End If

'-------Hier wird die maximale Anzahl an Vorgängen für den WV-Abruf gesetzt---
'-------Nur gültig wenn weiter unten die Verwendung dieser Funktion nicht-----
'-------auskommentiert ist (ist normalerweise deaktiviert)--------------------
WV_MaxAnzahl = 1


'----------Zugangsdaten für die Connection---------------

UID = "SD"
PW 	= "Neu7A//50UM"
DB 	= "IKA-BI"

'-----------------Pfad für die Protokolldatei-------------------------

ProtDatei   = "P:\Ikaros\Protokolle_AutoJobs\WV\" + wv_vorgang + "_" _
			+ CStr (WV_MandNrVon) + "-" + CStr (WV_MandNrBis) + "_"  _
			+ Cstr(heute) _
			+ ".txt"


'-----------------Protokolldatei erstellen----------------------------
Set FileSystemObjekt = CreateObject("Scripting.FileSystemObject")
Set Datei = FileSystemObjekt.CreateTextFile(ProtDatei, True)

'-----------------Applikation starten---------------------------------
Set App = CreateObject("IKAROSsql.Application")

'-----------------Anmelden ohne Workflow-Editor -> "N" an Index 5-----
AnmeldungOK = App.ConnectAsActiveX(UID, PW, DB,"DE","N")

If AnmeldungOK Then   '----------------------------Anmeldung gelungen?
	Datei.WriteLine ("Anmeldung gelungen")
  'Set PROSASkript = App.gibActiveXSkript()
  Set AutoWV = App.gibActiveXAutoWV()
  
  
'-------------------------Vorgaben setzen----------------------------
  With AutoWV
    '.Vorgang = WV_Vorgang
    'alternativ: .Vorgangsliste = Array("S02", "M001")
    .WVVon = WV_WVVon 
    .WVBis = WV_WVBis 
    .MandNrVon = WV_MandNrVon
    .MandNrBis = WV_MandNrBis
    .Anschreiben = WV_Anschreiben

    .MaxAnzahl = WV_MaxAnzahl

    '---------------------- setzen der AusschlussVorgangsliste ----------------------------
    '-----------------------  keine Wildcard Angaben möglich ------------------------------
    .AusschlussVorgangsListe = Array("MA00", "B", "SI101", "SI0", "PI", "P999", "SI701", "SI771","SI781","NAUNT","GI099","I0","SI46","T;","SI485","SI420","SI438","SD070","SD071","SD072","SD073","SD074","SD076","SD077","SD079","SD083","SI130","SI131","SI132","SI133","SD086","SI314","UN819")
    
    '-------------------------- AusschlussVorgang ----------------------------
    '---------------------- Wildcard Angaben möglich -------------------------
     .AusschlussVorgang = "MA00;B;SI101;SI0;PI;P999;SI701;SI771;SI781;NAUNT;GI099;I0;SI46;T;SI485;SI420;SI438;SD070;SD071;SD072;SD073;SD074,SD076;SD077;SD079;SD083;SI130;SI131;SI132;SI133;SD086;SI314;UN819"
                
    .Druckerspooler = False
    '.AzVon = "M"
    '.AzBis = "N"
  End With
  
  WV_WVenErmittelt = AutoWV.ermittleAnzWVGesamt()


'------------AutoWV starten, wenn etwas zu tun ist---------------------
  If WV_WVenErmittelt > 0 Then
    AutoWV.starte
    '------------Ergebnisse holen
    Protokoll = AutoWV.Protokolldatei
    Fehler = AutoWV.AnzFehler
    Bearbeitet = AutoWV.AnzWVBearbeitet
  End If

    Datei.WriteLine ("----------------------------------------------")
    Datei.WriteLine ("Meldungen: Mand "+ CStr (WV_MandNrVon) + " bis " + CStr (WV_MandNrBis))
    Datei.WriteLine ("----------------------------------------------")    
    Datei.WriteLine ("VorgangsKürzel              : " + WV_Vorgang )
    Datei.WriteLine ("WV Datum                    : " + Cstr (wv_wvvon) + " - " + Cstr (wv_wvbis) )
    Datei.WriteLine ("Anschreiben Datum           : " + Cstr (wv_Anschreiben))
    Datei.WriteLine ("Protokollmeldungen          : " + Protokoll)
    Datei.WriteLine ("WVen ermittelt              : " + CStr(WV_WVenErmittelt))
    Datei.WriteLine ("WVen mit Fehlern bearbeitet : " + CStr(Fehler) )
    Datei.WriteLine ("WVen insgesamt bearbeitet   : " + CStr(Bearbeitet))
    Datei.WriteLine ("----------------------------------------------")


  '----------Ergebnisse anzeigen--------------------------

Else
  Datei.WriteLine ("Anmeldung misslungen!!!")
End If  '-----------------Anmeldung gelungen?-------------

'-------------------------Applikation schließen-----------
App.Quit

'-------------------------Protokolldatei schließen--------
Datei.Close
