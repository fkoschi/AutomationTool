<?php
	require_once('/php/Controller/DBClass.php');
	
	/**
	  * 1) Wenn ein Task auf eine der beiden Stati umspringt ( could not start || running ) wird ein Eintrag in der Tabelle [Task_inStatus] erstellt
	  * 2) Verharrt der Task in einem der beiden Stati so wird das Feld, welches die Zeit für diesen Status erfasst, um 5 Minuten erhöht
	  *    - da der Cronjob nur alle 5 Minuten ausgeführt wird
	  * 3) Abarbeitung wird beendet
	  *    - Vergleich von zwei Arrays 
	  *    - stimmt die Anzahl der Einträge nicht überein, werden die gespeicherten Zeitwerte in eine weitere Tabelle übertragen,
	  *		 und die Einträge aus der Tabelle [Task_inStatus] gelöscht. 
	  * 
	  * @TODO 
	  * Tasks die eine Ausführungszeit unter 5 Minuten haben, können eventuell nicht vom Cronjob erfasst werden
	  **/
	$DB = new DB;
	$DB->set_database('Lindorff_DB');
	$DB->_connect();

	$query = "SELECT  	ID, 
						TaskName, 
						HostName, 
						NextRunTime, 
						Status
					FROM [Task] 
					WHERE Status = 'Running' 
					OR Status = 'Could not start' 
					";	
	
	$DB->set_sql($query);
	$DB->_query();
	
	$result = $DB->_fetch_array(1);
	$arrID = array();
	foreach( $result as $value ) {

		$ID = $value['ID'];
		$status = $value['Status'];
		$arrID[]['TID'] = $ID;

		setTimeInStatus( $ID, $status );  // Eintrag erstellen
		
	}		
	
	$DB->_close();
	
	deleteTimeInStatus( $arrID );  // Bearbeitung eines Task beendet, egal ob Could not start oder running -> Eintrag löschen

	function setTimeInStatus( $ID, $status ) {
		$DB = new DB;
		$DB->set_database('Lindorff_DB');
		$DB->_connect();
		$query = "IF NOT EXISTS
						(
							SELECT TID 
							FROM [Task_inStatus]
							WHERE  TID = " . $ID . "  
						)
					BEGIN 
						INSERT INTO [Task_inStatus]
						VALUES ( " . $ID . " , 5 , ' "  . $status .  "  ' , 1 )
					END
					
					ELSE
					BEGIN 
						UPDATE [Task_inStatus]
						SET TID = " . $ID . ",
							timeInStatus += 5,							
							Phase = 2
						WHERE TID = " . $ID . "
					END ";					
		$DB->set_sql( $query );
		$DB->_query();	

		$DB->_close();
	}
	
	/**
	  * Abgleichen der beiden Arrays ( [Task] und [Task_inStatus] )
	  *  - wenn also Tasks gerade nicht mehr abgearbeitet werden, egal ob im Status ( could not start oder running )
	  *  - werden diese an die Funktion setEstimatedTime übertragen und in die entsrepchende Tabelle eingetragen beziehungsweise gelöscht
	  **/	
	function deleteTimeInStatus( $array )
    {
    	$DB = new DB; 
		$DB->set_database('Lindorff_DB');
		$DB->_connect();

		// Wenn Task in Phase 2 		
		$query = "	SELECT TID 
					FROM [Task_inStatus]";
		$DB->set_sql( $query );
		$DB->_query();
		$result = $DB->_fetch_array(1);		
		
		$searchArray = array();

		foreach ($array as $value) 
		{
			$searchArray[] = $value['TID'];
		}
		
		$intersect = array_intersect_key($result, $array);		

		foreach ($result as $key => $value) 
		{			
			if( in_array($value['TID'], $searchArray) )
			{
				// Elemente in [Task] und [Task_inStatus]
			}
			else 
			{	
				// nicht im Array aus [Task]

				setEstimatedTime($value['TID']);
			}			
		}

		$DB->_close();
	}

	/**
	  *	timeInStatus in die Tabelle [Task_estimatedTime] übertragen
	  *  - Aufruf erfolgt aus der Funktion deleteTimeInStatus
	  *  - übertragen wird immer nur ein Wert aus dem Array
	  **/	
	function setEstimatedTime($intersect)
	{		
		$DB = new DB; 
		$DB->set_database('Lindorff_DB');
		$DB->_connect();

		$query = "SELECT timeInStatus, Status 
				  FROM [Task_inStatus] 
				  WHERE TID = " . $intersect ." ";
		$DB->set_sql( $query );
		$DB->_query();

		$result = $DB->_fetch_array(1);	
			
			if( substr_count($result[0]['Status'],'Running') == 1 ) 
			{
				$query = "
					UPDATE [Task_estimatedTime]
					SET estimatedTime += ".$result[0]['timeInStatus'].",
					counter += 1
					WHERE TaskID = " . $intersect . "	";
				$DB->set_sql($query);
				$DB->_query();
			}	

		$deleteSQL = "DELETE FROM [Task_inStatus] WHERE TID = " . $intersect . " ";
		$DB->set_sql($deleteSQL);
		$DB->_query();		

		$DB->_close();	
	}
	
?>