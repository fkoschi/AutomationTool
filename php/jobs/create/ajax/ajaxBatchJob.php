<?php
	/**
	* SAVE FILE
	**/
	function saveFile( $Pfad , $Text ) {
		
		// Datei anlegen
		$wf = fopen( $Pfad , 'x' );
		if ( $wf == FALSE ) {
			echo "File already exists";
		} else {
			// in Datei schreiben
		if ( fwrite( $wf , "\r\n" . $Text ) === FALSE ) {
				echo "Cannot write to file";
				exit;
			}
			echo "Success";
		}
		
	}
	
	if( !empty($_GET) ) {		
		
		$inhalt = $_GET['text'];
		$dateiname = $_GET['dateiname'] . $_GET['dateityp'];
		
		// Speicherpfad 
		$dir = 'C:\xampp_1.8.2\htdocs\Tool\Skripte\erstellt\\';
		$savedir = $dir . $dateiname;
		
		// Datei abspeichern 
		saveFile( $savedir , $inhalt );			
		
	}	

?>