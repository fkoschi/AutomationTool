<?php
	//require_once('../php/Controller/DBclass.php');

	$datei_lesen = "C:\\xampp_1.8.2\htdocs\Tool\sql\TagesArbeit.txt";
	$datei_schreiben = "C:\\xampp_1.8.2\htdocs\Tool\sql\TagesArbeit_NEU.txt";
	$inhalt = array();

	function changeCSV($lesen, $schreiben, $inhalt)
	{
		if (!$handle = fopen($lesen, "r+")) 
		{
			print "Kann die Datei $lesen nicht öffnen";
			exit;
		} 
		else 
		{
			global $i;
			while(!feof($handle))
			{
				$zeile = fgets($handle	,1024);				
								
				
				$inhalt[] .= $i .";". $zeile;				
				

				$i++;
			}	
		}	
		
		$handle2 = fopen($schreiben, "w");	
	
		foreach($inhalt as $value)
		{
			fputs($handle2,$value);
		}
	
		fclose($handle);
		fclose($handle2);
	}

	//changeCSV($datei_lesen, $datei_schreiben, $inhalt);	
?>