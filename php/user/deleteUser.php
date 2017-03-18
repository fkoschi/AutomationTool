<?php	
    require_once('../Controller/DBclass.php');
    
	if(isset($_GET)){
		
		if($_GET['function'] == 'delete'){
			
			deleteUser($_GET['userid']);
			
		}
	
	}
	
	function deleteUser($ID){
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$DB->set_sql("DELETE FROM [User] 					  					  
					  WHERE [User].UID = " . $ID ."");
		$DB->_query();
		$DB->set_sql("DELETE FROM [Rechte]
					  WHERE [Rechte].ID = " . $ID . "");
		$DB->_query();
		$DB->_close();
	}
	
?>