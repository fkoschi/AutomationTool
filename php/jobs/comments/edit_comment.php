<?php 
	include('../../validate.php');
	require_once('../../Controller/DBclass.php');

	if(isset($_POST)){
		if($_POST['function'] == 'add_comment'){
			addComment();
		}
		if($_POST['function'] == 'delete_comment'){
			deleteComment();
		}
	}
	function addComment() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$query = "SELECT COUNT(Nr) AS counter FROM [Comments] WHERE ID = '".$_POST['ID']."' ";
		$DB->set_sql($query);
		$DB->_query();		
		$count = $DB->_fetch_array(1);
		$Nr = $count[0]['counter'] + 1;
		$sql = "INSERT INTO [Comments] VALUES ( '".$_POST['ID']."', ".$Nr." , '".date('d-m-Y H:m:s')."' , '".$_SESSION['Username']."' , '".$_POST['Text']."') ";		
		$DB->set_sql($sql);
		$DB->_query();
		$DB->_close();
	}
	function deleteComment() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();		
		$sql = "IF EXISTS (
					SELECT U.UID
					FROM [User] AS U
					JOIN [Comments] AS C ON C.createdFROM = U.Kuerzel
					WHERE U.Kuerzel = '".$_SESSION['Username']."'
					AND C.Nr = ".$_POST['Nr']."
					)
					DELETE 
					FROM [Comments] 
					WHERE BINARY_CHECKSUM(ID) = BINARY_CHECKSUM('".$_POST['ID']."')
					AND Nr = ".$_POST['Nr']." 				
				ELSE
					SELECT UID
					FROM [User]
					WHERE Kuerzel = '".$_SESSION['Username']."'
				";
		$DB->set_sql($sql);		
		$DB->_query();
		$result = $DB->_fetch_array(1);
		echo json_encode($result);
		$DB->_close();
	}
?>