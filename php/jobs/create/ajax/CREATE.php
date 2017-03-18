<?php	
	include_once('../../../Controller/UPDATEDBClass.php');
		
	$Inhalt = array("/S"=>"", "/U"=>"", "/P"=>"", "/SC"=>"", "/MO"=>"", "/TN"=>"", "/TR"=>"", "/RU"=>"", "/RP"=>"", "/D"=>"", "/M"=>"", "/I"=>"", "/ST"=>"", "RI"=>"", "/ET"=>"", "/DU"=>"", "/SD"=>"", "/ED"=>"", "/EC"=>"", "/XML"=>"", "/RL"=>"", "/DELAY"=>"");	
	
	foreach($_POST as $key => $value){
		
		if($value != ""){
			$Inhalt[''.$key.''] = $value;
		
		if( $key == "submit" ) {
			unset( $Inhalt['' . $key . ''] );			
		}
	
		if( $key == "/M1" || $key == "/M2" || $key == "/M3" || $key == "/M4" || $key == "/M5" || $key == "/M6" || $key == "/M7" || $key == "/M8" || $key == "/M9" || $key == "/M10" || $key == "/M11" || $key == "/M12" ) {				
			$Inhalt['/M'] .= $value . "," ;
			unset( $Inhalt['' .$key. '']);						
		}		
	
		if ( $key == "/D1" || $key == "/D2" || $key == "/D3" || $key == "/D4" || $key == "/D5" || $key == "/D6" || $key == "/D7" ) {
			$Inhalt['/D'] .= $value . "," ;
			unset( $Inhalt['' .$key. '']);				
		}
		
		}		
	}
	if( !empty($Inhalt['/M']) ) {
		$Inhalt['/M'] = substr_replace( $Inhalt['/M'] , "" , strlen( $Inhalt['/M'] ) - 1 );	
	}
	if( !empty($Inhalt['/D']) ) {
		$Inhalt['/D'] = substr_replace( $Inhalt['/D'] , "" , strlen( $Inhalt['/D'] ) - 1 );
	}
	if( !empty($Inhalt['/TR']) ) {
		$Inhalt['/TR'] = escapeshellarg($Inhalt['/TR']);
	}

	/**
      *	letztes Element aus dem Array entfernen 
	   **/
	array_pop($Inhalt);
	
	$com="SCHTASKS /Create";
	$mand = "";	
	
	foreach($Inhalt as $key => $value){
		if($value == "on"){
			$value = "";
		}
		if( $value != "" ) {				
			$mand .= " " . $key . " " . $value;
		}
	}
	
	$command = $com . " " . $mand;		
	
	$output=shell_exec($command." 2>&1");
	$UPDATE = new UPDATEDB;
	$UPDATE->_start();	
	header("Location: http://localhost/Tool/php/jobs/create/createAutoJob.php?info=" . urlencode($output) );
		
?>