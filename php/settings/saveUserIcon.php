<?php
	/**
	  *
	  *
	**/
	require_once('../Controller/DBClass.php');	
	require_once('../validate.php');
	
if( !empty($_POST) )
{
	$DB = new DB; 
	$DB->set_database('Lindorff_DB');
	$DB->_connect();
	$sql = "UPDATE [User]
			SET Icon = ". $_POST['icon'] ."
			WHERE Kuerzel = '".$_SESSION['Username']."' ";
	$DB->set_sql($sql);
	$DB->_query();
	$DB->_close();
	
}

?>