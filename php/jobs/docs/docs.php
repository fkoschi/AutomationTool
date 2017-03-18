<?php 	
	require_once('../../Controller/DBClass.php');
	
	if( !empty($_FILES) ){
		// Ordner anlegen an dem die Datei gespeichert wird
		$dir = "C:\\xampp_1.8.2\htdocs\Tool\Dokumentationen" . $_POST['job'];
		$savedir = escapeshellarg($dir);
		$command = "mkdir " . $savedir;
		shell_exec($command ." 2>&1");	
		
		$datei = $dir . "\\" . $_FILES['file']['name'];
		//$datei = escapeshellarg($datei);
	
		if (move_uploaded_file($_FILES['file']['tmp_name'], $datei) ) {
			// mach garnix
		} else {
			echo'Fehler: <br /> Datei konnte nicht nach ' . $dir . ' verschoben werden. ';
		}		
	
		$DB	= new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT MAX(ID) AS counter FROM [Task_Documents]";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		$ID = $result[0]['counter'];
		if( $ID != NULL ) {
			$ID += 1;
		} else {
			$ID = 1;
		}
		$sql = "IF NOT EXISTS ( 
					SELECT ID
					FROM [Task_Documents]
					WHERE ID = ".$ID." 
					AND Name = '".$_FILES['file']['name']."'
				)
				INSERT INTO [Task_Documents]
				VALUES ( ".$ID." , ".$_POST['id']." , '".$_FILES['file']['name']."' , '".$datei."' ) ";
		$DB->set_sql($sql);
		$DB->_query();
		var_dump($sql);
	}
	else if ( !empty($_GET) ) {
		$pfad = $_GET['pfad'];
		$command = "del /Q " . $pfad;
		shell_exec($command ." 2>&1");
		
		$DB = new DB; 
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "DELETE FROM [Task_Documents]
				WHERE ID = ".$_GET['id']." ";
		$DB->set_sql($sql);
		$DB->_query();
		$DB->_close();
	}	
?>