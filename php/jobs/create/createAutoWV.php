<?php
	
    $dateiname = $_POST['dateiname'];  
	
    $speicherpfad = $_SERVER['DOCUMENT_ROOT'] . "/Tool/Skripte/AutoWV/erstellt/";		
	$save_as = $speicherpfad  . $dateiname . ".vbs";	
	
    // WV Vorgang
    if(isset($_POST['WV_Vorgang'])){
        if( $_POST['WV_Vorgang'] == "" ) {
            $_POST['WV_Vorgang'] = "WV_Vorgang = \"\"";
        } else {
            $_POST['WV_Vorgang'] = "WV_Vorgang = \"" . $_POST['WV_Vorgang'] . "\"" ;
        }
     } else {
        $_POST['WV_Vorgang'] = "WV_Vorgang = \"\"";
     }
    
    // WV_Vorgangsliste 
    if(isset($_POST['Vorgangsliste'])){
        if ( $_POST['Vorgangsliste'] == "" ){
            $_POST['Vorgangsliste'] = "WV_Vorgangsliste = \"\"";
        } else {
            $_POST['Vorgangsliste'] = "WV_Vorgangsliste = Array(" . $_POST['Vorgangsliste'] . ")"; 
        }
    } else {
		$_POST['Vorgangsliste'] = "WV_Vorgangsliste = \"\"";
	}
    
    // AusschlussVorgang 
    if(isset($_POST['Ausschlussvorgang'])){
        if($_POST['Ausschlussvorgang'] == ""){
            $_POST['Ausschlussvorgang'] = "WV_AusschlussVorgang = \"\"";
        }else {
            $_POST['Ausschlussvorgang'] = "WV_AusschlussVorgang = " . $_POST['Ausschlussvorgang'] . ""; 
        }
    } else {
		$_POST['Ausschlussvorgang'] = "WV_AusschlussVorgang = \"\"";
	}
    
    // AusschlussVorgangsListe
    if(isset($_POST['Ausschlussvorgangsliste'])){
        if( $_POST['Ausschlussvorgangsliste'] == "" ){
            $_POST['Ausschlussvorgangsliste'] = "WV_AusschlussVorgangsListe = \"\" ";     
        } else {
            $_POST['Ausschlussvorgangsliste'] = "WV_AusschlussVorgangsListe = Array(" .  $_POST['Ausschlussvorgangsliste'] . ")";
        }
    }

    // MandantNrVon
    if( $_POST['MandantNrVon'] == "" ) {
        $_POST['MandantNrVon'] = "WV_MandNrVon = \"\" ";
    } else {
        $_POST['MandantNrVon'] = "WV_MandNrVon = " . $_POST['MandantNrVon'] ;
    } 
    
    // MandantNrBis
    if ( $_POST['MandantNrBis'] == "" )  {
        $_POST['MandantNrBis'] = "WV_MandNrBis = \"\"";
    } else {
        $_POST['MandantNrBis'] = "WV_MandNrBis = " . $_POST['MandantNrBis'] ;    
    }   
    
    //WVVon
    if( $_POST['WVVon'] == ""){
        $_POST['WVVon'] = "WV_WVVon = \"\" ";
    } else {
        $_POST['WVVon'] = "WV_WVVon = CDate(\" " . $_POST['WVVon'] . " \" )";
    }
    
    //WVBis
    if( $_POST['WVBis'] == ""){
        $_POST['WVBis'] = "WV_WVBis = \"\" ";
    } else {
        $_POST['WVBis'] = "WV_WVBis = CDate(\" " . $_POST['WVBis'] . " \" )";
    }
    
    //UID
    $_POST['UID'] = "UID = \" " . $_POST['UID'] . " \" ";
    //PW
    $_POST['PW'] = "PW = \" " . $_POST['PW'] . " \" ";
    //DB   
    $_POST['DB'] = "DB = \" " . $_POST['DB'] . " \" ";
    
    
    // Anschreiben
    if(isset($_POST['Anschreiben'])){  
        if($_POST['Anschreiben'] == "0"){
            $_POST['Anschreiben'] = "WV_Anschreiben = DateAdd(\"d\", 0, heute) ";
        } 
		else if ($_POST['Anschreiben'] == "1"){
            $_POST['Anschreiben'] = "WV_Anschreiben = DateAdd(\"d\" , 1 , heute) ";
        }        
     } else {
        $_POST['Anschreiben'] = "WV_Anschreiben = \"\" ";
     }   
     
    // MaxAnzahl 
    if(isset($_POST['MaxAnzahl'])){
        if($_POST['MaxAnzahl'] == ""){
            $_POST['MaxAnzahl'] = "WV_MaxAnzahl = \"\"";
        }else {
            $_POST['MaxAnzahl'] = "WV_MaxAnzahl = " . $_POST['MaxAnzahl'] . "";
        } 
    }
	// Datei öffnen 
    $datei = fopen( $save_as,"w+");    	
	
    // Variablen die nicht übermittelt werden sollen:    
    unset($_POST['dateiname']);
    unset($_POST['ausschluss']);
    unset($_POST['WV_Vorgang_Checkbox']);
    unset($_POST['MaxAnzahl_checkbox']);
    
    foreach ( $_POST as $value ) {                 		
		fwrite($datei, $value . "\r\n\n");           
    }	
    
    fclose($datei);   
	$info = "Skripte liegen unter <br />". $speicherpfad . " ";
	$info_file = "file:///" . $speicherpfad;
	header("Location: http://localhost/Tool/php/jobs/create/autoWV.php?info=". urlencode($info . '<a href=' . $info_file . '></a>'));

?>