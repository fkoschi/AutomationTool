<?php	
    require_once('../Controller/DBclass.php');
	require '../../php/PHPMailer/PHPMailerAutoload.php';
	include ('../../php/PHPMailer/class.smtp.php');
    
	if(isset($_POST)){
		if($_POST['function'] == 'updateRechte'){
			updateRechte();			
		}
		if($_POST['function'] == 'updateInfos'){
			$_POST['function'] = "";
			updateInfos();
		}
		if($_POST['function'] == 'create'){
			saveUser();	
		}
	}	
		
	function saveUser() {		 		 
         
		$DB = new DB;
        $DB->set_database("Lindorff_DB");
        $DB->_connect();
        $DB->set_sql("SELECT MAX(UID) AS count FROM [User]");
        $DB->_query();
		$row_count = $DB->_fetch_array(1);	 		 
		$newUID = $row_count[0]['count'] + 1;
		$passwort = setPassword();
		$DB->set_sql("INSERT INTO [User] VALUES (" . $newUID . " , " . $_POST['Abteilung'] . " , '" . $_POST['Geschlecht'] . "' , '" . $_POST['Kuerzel'] . "' , HashBytes('SHA1' ,'" . $passwort . "') , '" . $_POST['Vorname'] . "' , '" . $_POST['Nachname'] . "' , '" . $_POST['Email'] . "' , 1 )");
		$DB->_query();		 
		$DB->set_sql("INSERT INTO [Rechte] VALUES (" . $newUID . " , " . $_POST['createAutoJob'] . " , " . $_POST['createSQLJob'] . " , " . $_POST['createBatchJob'] . " , " . $_POST['createAutoWV'] . " , " . $_POST['editUser'] . ") ");
		$DB->_query();
		$DB->_close();
		
		$body = "Hallo <b>".$_POST['Vorname']." ".$_POST['Nachname']."</b>, <br /><br />
				Es wurde erfolgreich ein Benutzerkonto f&uuml;r Sie angelegt. 
				<br /> Unter folgendem <a href='http:\\localhost\Tool\UserLogin.php'>Link</a> k&ouml;nnen Sie sich mit diesen Anmeldedaten einloggen: <br /><br />
				Username (Kuerzel): ".$_POST['Kuerzel']." <br />
				Passwort: ".$passwort." <br /><br />
				
				Gru&szlig;,<br />
				IT-Abteilung
				";					
		$mail = new PHPMailer();
		$mail->IsSMTP();	
		$mail->Host = "smtp-relay.groupad1.com";
		$mail->SMTPDebug = 2;	
		$mail->setFrom('DL_DE_EDV@lindorff.com', 'IT Abteilung');
		$mail->addReplyTo('DL_DE_EDV@lindorff.com', 'IT Abteilung');
		$mail->addAddress(' '.$_POST['Email'].' ', ' '.$_POST['Vorname'].' '.$_POST['Nachname'].' ');
		$mail->Subject = 'Zugangsdaten Tool';
		$mail->msgHTML($body);

		$mail->AltBody = 'This is a plain-text message body';	

		if (!$mail->send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			echo "Message sent!";
		}
		
    }
	function updateInfos(){
		$DB = new DB;
        $DB->set_database("Lindorff_DB");
        $DB->_connect();		
		$inhalt = array();
		$s = "UPDATE [User] ";
		$l = "WHERE UID = ".$_POST['ID']." ";
		$q = "";
		foreach($_POST as $key => $value){
			if($value != ""){			
					$inhalt[$key] = $value; 				
			}
		}	
		/** 
		  * ID AUS ARRAY LÖSCHEN 
		  **/
		unset($inhalt['ID']);		
		/** 
		  * STRING ZUSAMMENFÜHREN 
		  **/		
		foreach( $inhalt as $key => $value ) {
			if ( $key == 'Abteilung' ) {
				$q .= "AbteilungsID = " . $value . " , ";
			}
			else {
				$q .= $key . " = '" . $value . "' , ";
			}
		}		
		$q = "SET " . $q;			
		$q{strlen($q) - 2 } = " ";
		$sql = $s . $q . $l;
		
		$DB->set_sql($sql);
		$DB->_query();
		$DB->_close();
	}
	function updateRechte() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "UPDATE [Rechte]
				SET createAutoJobs = ".$_POST['createAutoJob']." , 
					createSQLJobs = ".$_POST['createSQLJob']." ,
					createBatchJobs = ".$_POST['createBatchJob']." ,
					createAutoWV = ".$_POST['createAutoWV']." ,
					editUser = ".$_POST['editUser']."
				WHERE ID = ".$_POST['ID']."
				";		
		$DB->set_sql($sql);
		$DB->_query();
		$DB->_close();				
	}
	function setPassword() {		
		$mt_rand = mt_rand();
		$chars = array("!","?","%","-","<",">","/");
		$index = array_rand($chars);
		$password = "Lindorff_" . $mt_rand . $chars[$index];		
		return $password;
	}
?>