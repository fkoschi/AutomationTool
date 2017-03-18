<?php
	require_once('./php/Controller/DBclass.php');
	require ('./php/PHPMailer/PHPMailerAutoload.php');
	include ('./php/PHPMailer/class.smtp.php');
	/**
	  * @TODO :
	  * FALL ABDECKEN, DASS ZWEI EMAILS GEFUNDEN WORDEN SIND 
	  **/
	  	  
if($_POST['function'] == 'login' ) {
	$Username = $_POST['Username'];
	$Password = $_POST['Password'];		
    $DB = new DB;
	$DB->set_database("Lindorff_DB");
	$DB->_connect();
	$sql = "SELECT COUNT(*) AS treffer 
			FROM [User] 
			WHERE BINARY_CHECKSUM([User].Kuerzel) = BINARY_CHECKSUM('" . $Username . "') 
			AND [User].Passwort = HASHBYTES('SHA1', '" . $Password . "') ";
	
	$DB->set_sql($sql);	
	$DB->_query();
	$result = $DB->_fetch_array(1);
	
	echo $result[0]['treffer'];
	
	if( $result[0]['treffer'] != 0 ) {
		/**
		  * SESSION STARTEN
		  **/
		session_start();
		$_SESSION['angemeldet'] = true;
		$_SESSION['Username'] = $_POST['Username'];
	}
	
	$DB->_close();	
}	
/** 
  * PASSWORT ZURÜCKSETZEN
   **/
else if($_POST['function'] == 'resetpw') {
	
	$Email = $_POST['Email'];	
	$newPW = "Lindorff" . mt_rand(0,1000) . "!";
	
	$DB = new DB;
	$DB->set_database("Lindorff_DB");
	$DB->_connect();
	$sql = "SELECT COUNT(UID) AS Treffer 
			FROM [User]
			WHERE Email = '".$Email."' ";
	$DB->set_sql($sql);
	$DB->_query();
	$result = $DB->_fetch_array(1);

	/** 
	  * BENUTZER GEFUNDEN 
	  **/
	if ($result[0]['Treffer'] == 1 ) {
		
		$sql = "UPDATE [User]
				SET Passwort = HASHBYTES('SHA1', '".$newPW."')
				WHERE Email = '".$Email."' ";
		$DB->set_sql($sql);
		$DB->_query();
		
		$body = "Hallo, <br /><br />
				Ihr neu gesetztes Passwort lautet: <br />
				".$newPW." <br /><br />
				Zum <a href='http://".$_SERVER['SERVER_NAME']."/Tool/UserLogin.php'> Login </a> <br /><br />
				Gru&szlig;, <br /> IT Abteilung";
	
		$mail = new PHPMailer();
		$mail->IsSMTP();	
		$mail->Host = "smtp-relay.groupad1.com";
		$mail->SMTPDebug = 2;	
		$mail->setFrom('DL_DE_EDV@lindorff.com', 'IT Abteilung');
		$mail->addReplyTo('DL_DE_EDV@lindorff.com', 'IT Abteilung');
		$mail->addAddress(''.$Email.'');
		$mail->Subject = 'Ihr neues Passwort';
		$mail->msgHTML($body);

		$mail->AltBody = 'This is a plain-text message body';	

		if (!$mail->send()) {
			echo $mail->ErrorInfo;
		} else {
			
		}
	} 
	/** 
	  * KEINEN BENUTZER GEFUNDEN 
	  **/
	else {
		echo "Fehler";
	}
	$DB->_close();
} 

?>