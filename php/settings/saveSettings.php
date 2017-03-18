<?php
	/**
	  * @ TODO : Benutzerüberprüfung ausbauen auf Benutzername 
	  *			 oder andere Felder. 
	**/
	require_once('../Controller/DBClass.php');	
	
if(!empty($_POST)){
	$DB = new DB; 
	$DB->set_database("Lindorff_DB");
	$DB->_connect();
	
	$sql = "SELECT COUNT(*) AS Treffer 
			FROM [User]
			WHERE Passwort = HASHBYTES('SHA1' , '".$_POST['oldPW']."')
			AND Kuerzel = '".$_POST['Username']."' ";	
	$DB->set_sql($sql);

	$DB->_query();
	$result = $DB->_fetch_array(1);
	
	if( $result[0]['Treffer'] == 1 ) 
	{ 	
		$sql = "UPDATE [User]
				SET Passwort = HASHBYTES('SHA1' , '".$_POST['newPW']."' )
				WHERE Kuerzel = '".$_POST['Username']."' ";
		$DB->set_sql($sql);
		$DB->_query();
		$DB->_close();
		echo json_encode("Fertig");		
		exit;
	}
	else if ( $result[0]['Treffer'] == 0 ) 
	{	
		echo json_encode("Kein Treffer");		
		exit;
	}
	else 
	{
		echo json_encode("Mehr als ein Treffer");
		exit;
	}
	
}
?>