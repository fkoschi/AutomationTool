' ----------------------------------------------------------
' 		Regis Datei auf AKTE und ADRESSMELD durchsuchen 
'
'--> folgt auf eine AKTE eine ADRESSMELD werden diese 
' 	 Daten in eine neue Datei hinzugefügt und gespei-
'		 chert. Der Dateiname ist von der Original-Datei
' 	 dadurch zu unterscheiden, das er die Endung 
' 	 _BEARBEITET enthält. 
'
'			erstellt am: 29-10-2013	
'			von: Felix Koschmidder 
' ----------------------------------------------------------

Const ForReading = 1
Const ForWriting = 2
Dim Dateiname 
Dim sDateiname

' -----------------------------------------
' Eingabedialog für die Auswahl einer Datei
' -> läuft nur unter Windows Server !!! 
Set objDialog = CreateObject( "SAFRCFileDlg.FileOpen" )

If objDialog.OpenFileOpenDlg Then
    Dateiname = objDialog.FileName
Else 
	WScript.Echo "Bitte Datei auswählen!"
	WScript.Quit
End If

Set fso = CreateObject("Scripting.FileSystemObject")

' ---------------------------------
' ---> ****** SCHRITT 1 ***********
' ---------------------------------

' ---------------------------------
' Dateiname anpassen für Änderung :
sDateiname = Replace(Dateiname, ".txt", "_BEARBEITET.txt")

' ----------------------------------------------
' Datei auswählen, welche bearbeitet werden soll 
Set objFile = fso.OpenTextFile( CStr(Dateiname) , ForReading)

nZeile = ""

Do Until objFile.AtEndOfStream 
	oZeile = objFile.ReadLine	
		If InStr(oZeile, "KOPF") = 1 Then 
			nZeile = nZeile + oZeile + vbCrLf 
		Else If InStr(oZeile , "AKTE") = 1 Or InStr(oZeile, "ADRESSMELD") = 1 Then 
			nZeile = nZeile + oZeile + vbCrLf				
		Else
			nZeile = nZeile + Replace(oZeile, oZeile, ""	)			
		End If
		End If 			 	
Loop
' ------------------
' Text File erzeugen 
Set objnFile = fso.CreateTextFile( CStr(sDateiname), true)
' ------------------------------------------
' Ergebnisse in die erzeugte Datei schreiben
objnFile.WriteLine nZeile
objnFile.Close

' ---------------------------------
' -----> ******* SCHRITT 2 ********
' ---------------------------------

Set File = fso.OpenTextFile( CStr(sDateiname) , ForReading)

zADRESSE = 0
zAKTE = 0
treffer = 0
del = 0 
nZeile = ""

Do Until File.AtEndOfStream	' ----- Beginn Loop 
		'---------------
		' Zeile einlesen 		
		oZeile = File.ReadLine
		
		' -----------------
		' Wert zurücksetzen 		
		tmpADR = ""
	
	' ------------------
	' Kopfzeile einfügen 	
	If InStr(oZeile, "KOPF") = 1 Then 
			nZeile = nZeile + oZeile  
	End If 				
	
	' ----------------------
	' Akte zwischenspeichern 
	If InStr(oZeile, "AKTE") = 1 Then 
		zAKTE = File.Line
		tmpAKTE = oZeile		
	End If	
	
	' -------------------------------
	' Adressmeldung zwischenspeichern 
	If InStr(oZeile, "ADRESSMELD") = 1 Then 
		zADRESSE = File.Line
		tmpADR = tmpADR + oZeile
	End If 
	
	' -----------------------------------
	' Folgt auf AKTE eine Adressmeldung ?
	If zADRESSE = zAKTE + 1 Then 
		treffer = treffer + 1
		nZeile = nZeile + vbCrLf + tmpAKTE + vbCrLf + tmpADR
		tmpAKTE = ""			
	End If   	
	
	
Loop ' ------ Ende Loop Schleife 


Set File = fso.OpenTextFile( CStr(sDateiname), ForWriting)

File.WriteLine nZeile

' ---------------
' Datei schließen 
File.Close

' -----------------------------
' ist der File erzeugt worden ? 
If fso.FileExists( CStr(sDateiname) ) Then 
	WScript.Echo "Datei erstellt!"
Else 
	WScript.Echo "Konnte erstellte Datei nicht finden!"
End If 
