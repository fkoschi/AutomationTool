<?php
	
	require_once('../../Controller/DBclass.php');
if( isset($_POST) ) {			
	if( $_POST['function'] == 'insert' ) {	
		
		$savedir = 'C:\xampp_1.8.2\htdocs\Tool\Skripte\batch\\' . $_FILES['file']['name'];
		$uploaddir  = '../../../Skripte/batch/';
		$Pfad = $uploaddir . $_FILES['file']['name'];
				
		$Zeit = date('Y.m.d');
	
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT MAX(ID) AS cnt FROM [BatchJob]";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		$ID = $result[0]['cnt'] + 1;
		
		$sql = "INSERT INTO [BatchJob]
				VALUES (".$ID.", '".$_POST['Name']."' , '".$savedir."', '".$_POST['create_textarea']."', '".$Zeit."' , '".$_POST['User']."' ) ";
		$DB->set_sql($sql);	
		$return = $DB->_query();									
		echo $return; 
		$DB->_close();	
		if (move_uploaded_file($_FILES['file']['tmp_name'], $Pfad) ) {
			header('Location: batch.php');
		} else {
			echo'Fehler: <br /> Datei konnte nicht nach ' . $Pfad . ' verschoben werden. ';
		}
	}
}
if( isset($_GET) ) {
	if( $_GET['function'] == 'delete' ) {
		
		$pfad = escapeshellarg($_GET['Pfad']);
		$delete = "del ".$pfad." ";
		shell_exec($delete ." 2>&1");		
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "DELETE FROM [BatchJob] WHERE ID = ".$_GET['ID']." ";		
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);			
		print_r($result);
		$DB->_close();
	}
}
?>