<?php 
	include('../../Controller/DBClass.php');
	/**
	  * BOOKMARK SETZEN 
	  **/
	if( !empty($_GET) ) {
		if( $_GET['function'] == 'setStatus' ) {
			
			$DB = new DB; 
			$DB->set_database("Lindorff_DB");
			$DB->_connect();
			$sql = "SELECT UID FROM [User] WHERE BINARY_CHECKSUM(Kuerzel) = BINARY_CHECKSUM('".$_GET['user']."')";
			$DB->set_sql($sql);
			$DB->_query();
			$result = $DB->_fetch_array(1);
			$UserID = $result[0]['UID'];
			$sql = "SELECT MAX(ID) + 1 AS ID FROM [Task_InBearbeitung]";
			$DB->set_sql($sql);
			$DB->_query();
			$result = $DB->_fetch_array(1);
			$ID = $result[0]['ID'];
		/**
		  * NUR DER BENUTZER WELCHEN DEN
		    STATUS GESETZT HAT, KANN 
			DIESEN AUCH WIEDER LÖSCHEN
			**/
		$sql = "IF EXISTS ( 
					SELECT TIB.ID
					FROM [Task_InBearbeitung] as TIB						
					JOIN [Task] as T ON T.ID = TIB.TaskID					
					JOIN [User] as U ON U.UID = TIB.UserID	
					WHERE T.ID = " .$_GET['id']. "
					AND TIB.UserID = " .$UserID. "
				) 
				DELETE FROM [Task_InBearbeitung]
				WHERE TaskID = ".$_GET['id']."
				ELSE
					IF NOT EXISTS (
						SELECT TIB.ID
						FROM [Task_InBearbeitung] as TIB						
						JOIN [Task] as T ON T.ID = TIB.TaskID							
						WHERE T.ID = ".$_GET['id']." 
					)
					INSERT INTO [Task_InBearbeitung] (
						ID,
						TaskID,
						UserID	
						) SELECT 
							".$ID.",
							".$_GET['id'].",
							[User].UID	
							FROM [User]
							WHERE [User].Kuerzel = '".$_GET['user']."' ";

			$DB->set_sql($sql);
			$DB->_query();
			$sql = "SELECT U.Kuerzel , TIB.TaskID 
					FROM [User] AS U
					JOIN [Task_InBearbeitung] AS TIB ON TIB.UserID = U.UID
					WHERE TIB.TaskID = ".$_GET['id']." ";
			$DB->set_sql($sql);
			$DB->_query();
			$result = $DB->_fetch_array(1);
			
			echo json_encode($result);
			
			$DB->_close();
		}
	}
	
?>