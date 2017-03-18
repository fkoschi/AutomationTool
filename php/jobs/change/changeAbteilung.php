<?php
	require_once('../../Controller/DBclass.php');
	
	$TaskName = $_GET['TaskName'];	
	var_dump($_GET);
	
	if( $_GET['function'] == 'saveChanges' ){ 
		foreach($_GET['Abteilungen'] as $index ) {
		
			$DB = new DB;
			$DB->set_database("Lindorff_DB");
			$DB->_connect();
			$sql = "SELECT COUNT(ID) AS cnt FROM [Task_Abteilung]";		
			$DB->set_sql($sql);
			$DB->_query();		
			$result = $DB->_fetch_array(1);
			$ID = $result[0]['cnt'] + 1;
			$sql = "INSERT INTO [Task_Abteilung]
					VALUES (" . $ID . " , '". $TaskName ."' ,'" . $index . "' )";
			$DB->set_sql($sql);
			$DB->_query();		
			$DB->_close();	
		}
	}
	if( $_GET['function'] == 'DeleteFromList' ) {
			$DB = new DB;
			$DB->set_database("Lindorff_DB");
			$DB->_connect();
			$sql = "DELETE FROM [Task_Abteilung]
					WHERE TaskName = '".$TaskName."' 
					AND Abteilung = '".$_GET['Abteilung']."' ";
			$DB->set_sql($sql);
			$DB->_query();			
			$DB->_close();
	}
	if( $_GET['function'] == 'deleteALLabteilungen' ) {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "DELETE FROM [Task_Abteilung]
				WHERE TaskName = '".$TaskName."' ";
		$DB->set_sql($sql);
		$DB->_query();
		$DB->_close();	
	}
?>