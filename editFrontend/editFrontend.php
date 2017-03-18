<?php	
	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/Tool/php/Controller/DBClass.php');
	
	//localhost
	$server = $_SERVER['SERVER_NAME'];
	// C:/xampp_1.8.2/htdocs 
	$document_root = $_SERVER['DOCUMENT_ROOT'];
	/** 
	  *** FRONTEND ANPASSEN
	**/
	$CSS_Pfad = "/Tool/editFrontend/editFrontend.css";	
	$JS_Pfad = "/Tool/editFrontend/editFrontend.js";
	
	// MAIN CSS
	$MAIN_CSS = "/Tool/editFrontend/_CSS.css";
	/** 
	  * Rechte des Benutzer auslesen 
	  **/
	function getRechte($User)   {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT U.Vorname, U.Nachname , U.Geschlecht , A.Name , R.*
				FROM [User] AS U
				JOIN [Abteilung] AS A ON A.ID = U.AbteilungsID
				JOIN [Rechte] AS R ON R.ID = U.UID
				WHERE U.Kuerzel = '" . $User ."' ";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		return $result;
		$DB->_close();
	}
	function getUserIcon( $User )
	{
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT Icon
				FROM [User]
				WHERE Kuerzel = '" . $User . "' ";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);		
		return $result;
		$DB->_close();	
	}
	$return = getRechte( $_SESSION['Username'] );	
	/**
	  * HAUPT CSS DATEI 
	  **/
	echo '<link rel="stylesheet" type="text/css" href="'.$MAIN_CSS.'" >';
	
	/** 
	  * WER DARF WAS SEHEN ?
	  **/
	if ( substr_count($return[0]['Name'] , 'DL_DE_EDV' ) != 1 ) {
		echo '<link rel="stylesheet" type="text/css" href="'.$CSS_Pfad.'">';		
		echo '<script src="'.$JS_Pfad.'" type="text/javascript" ></script>';
	}
	// Aufgabenverwaltung (Christoph Pleger)
	else if ( $_SESSION['Username'] == 'admin-fkos' || $_SESSION['Username'] == 'it_prinzessin' )
	{
		echo '<script src="/Tool/editFrontend/enableAufgabenverwaltung/enableAufgabenverwaltung.js" ></script>';
	}
	/** 
	  * Icon einbinden 
	  **/
	$icon = getUserIcon( $_SESSION['Username'] );
	
	echo '<div id="user_logo">';	
	switch ($icon[0]['Icon']) 
	{
		  	case '1':
				echo '<img src="/Tool/img/icons/User/user_one.png" name="user_icon" />';
		  		break;
		  	case '2':		  		
				echo '<img src="/Tool/img/icons/User/user_two.png" name="user_icon" />';				
		  		break;
		  	case '3':		  		
				echo '<img src="/Tool/img/icons/User/user_three.png" name="user_icon" />';				
		  		break;
		  	case '4':		  		
				echo '<img src="/Tool/img/icons/User/user_four.png" name="user_icon" />';				
		  		break;
		  	case '5':		  		
				echo '<img src="/Tool/img/icons/User/user_five.png" name="user_icon" />';				
		  		break;
		  	case '6':		  		
				echo '<img src="/Tool/img/icons/User/user_six.png" name="user_icon" />';				
		  		break;
		  	case '11':		  		
				echo '<img src="/Tool/img/icons/User/user_female_one.png" name="user_icon" />';				
		  		break;			
		  	case '12':		  		
				echo '<img src="/Tool/img/icons/User/user_female_two.png" name="user_icon" />';				
		  		break;
		  	case '13':		  		
				echo '<img src="/Tool/img/icons/User/user_female_three.png" name="user_icon" />';				
		  		break;
		  	case '14':		  		
				echo '<img src="/Tool/img/icons/User/user_female_four.png" name="user_icon" />';				
		  		break;
		  	default:		  		
				echo '<img src="/Tool/img/icons/User/user_one.png" name="user_icon" />';						
		  		break;
	}	  
	echo '</div>';  
	
?>