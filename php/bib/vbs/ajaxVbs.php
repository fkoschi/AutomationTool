<?php
	
	require_once('../../Controller/DBclass.php');
		
	
	if( $_POST['function'] == 'insert' ) {
		
		$savedir = 'C:\xampp_1.8.2\htdocs\Tool\Skripte\vbs\\' . $_FILES['file']['name'];
		$uploaddir  = '../../../Skripte/vbs/';
		$Pfad = $uploaddir . $_FILES['file']['name'];
		
		$Zeit = date('Y.m.d');	
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT MAX(ID) AS cnt FROM [VBSJob]";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		$ID = $result[0]['cnt'] + 1;		
		$sql = "INSERT INTO [VBSJob]
				VALUES (".$ID.", '".$_POST['Name']."' , '".$savedir."', '".$_POST['create_textarea']."', '".$_POST['User']."' , '".$Zeit."') ";
		$DB->set_sql($sql);	
		$return = $DB->_query();									
		echo $return; 
		$DB->_close();	
		if (move_uploaded_file($_FILES['file']['tmp_name'], $Pfad) ) {
			header('Location: vbscript.php');
		} else {
			echo'Fehler: <br /> Datei konnte nicht nach ' . $Pfad . ' verschoben werden. ';
		}
	}
	/** 
	  * LÖSCHEN 
	  **/
	if( $_GET['function'] == 'delete' ) {
		$pfad = escapeshellarg($_GET['Pfad']);
		$delete = "del ".$pfad." ";
		shell_exec($delete ." 2>&1");	
		
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "DELETE FROM [VBSJob] WHERE ID = ".$_GET['ID']." ";		
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		echo $result;
		$DB->_close();
	}
?>