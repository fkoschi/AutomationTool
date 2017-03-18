<?php
	include_once('../../Controller/UPDATEDBclass.php');
	include_once('../../Controller/DBClass.php');
	/** 
	  * UPDATE 
	  **/
	if($_POST['function'] == 'update') {
		$Inhalt = array();
		foreach( $_POST as $key => $value){
			if($value != "") {
				$Inhalt[''.$key.''] = $value;
				
				if($value == "on") {
					$Inhalt[''.$key.''] = "";
				}
			}			
		}		
		array_pop($Inhalt);
		array_pop($Inhalt);
		$mand = "";
		foreach($Inhalt as $key => $value) {
			$mand .= $key . " " . $value . " ";
		}
		$com = "SCHTASKS /CHANGE ";
		$command = $com . $mand;				
		
		$output = shell_exec($command." 2>&1");
		$id = $_POST['/TN'];		
		header("Location: http://".$_SERVER['SERVER_NAME']."/Tool/php/jobs/job_info.php?id=" . urlencode($id) . "&page=change&info=" . urlencode($output) );
	}
	/** 
	  * TASK INFO CHANGE 
	  **/
	if($_POST['function'] == 'change_task_info'){
		
		$HostName = escapeshellarg($_POST['HostName']);
		$TaskToRun = escapeshellarg($_POST['TaskToRun']);
		$TaskName = escapeshellarg($_POST['TaskName']); 
		$RunAsUser = escapeshellarg($_POST['RunAsUser']);
		
		$command = "SCHTASKS /CHANGE  /S ". $HostName ." /TN ". $TaskName ." /TR ". $TaskToRun." /RU ".$RunAsUser." ";	
						
		$output = shell_exec($command." 2>&1"); 
		sleep(5);
		$UPDATE = new UPDATEDB;
		$UPDATE->_start();		
		exit;		
	}
	/** 
	  * ENABLE / DISABLE
	  **/
	if( $_POST['function'] == 'enable_disable_task' ){
		
		$HostName = $_POST['HostName'];
		$TaskName = $_POST['TaskName'];
		$Status = $_POST['Status'];
		
		/** 
		  * CHANGE STATUS ON HOST 
		   **/
		$command = "SCHTASKS /CHANGE /S ". $HostName ." /TN ". $TaskName ." /" . $Status ." ";						
		
		shell_exec($command." 2>&1");		
		sleep(5);
		/** 
		  * UPDATE DATENBANK
		  **/
		$UPDATE = new UPDATEDB;
		echo $UPDATE->_start();
				
		exit;		
		
	}	
	/**
	  * RUN TASK 
	  **/
	if( $_POST['function'] == 'run_task' ) {
		$TaskName = $_POST['TaskName'];
		$HostName = $_POST['HostName'];
		$command = "SCHTASKS /RUN /S ". $HostName ." /TN ". $TaskName ." "; 		
		shell_exec($command." 2>&1");
		sleep(5);
		$UPDATE = new UPDATEDB;
		echo $UPDATE->_start();
		exit;
	}
	/**
	  * LÖSCHEN 
	  **/
	if( $_POST['function'] == 'delete_task' ) {
		
		$HostName = $_POST['HostName'];
		$TaskName = $_POST['TaskName'];
		$TaskID = $_POST['TaskID'];
		/**
		   * TASK LÖSCHEN 
		   **/
		$command = "SCHTASKS /DELETE /S ". $HostName ." /TN ". $TaskName." /F ";
		
		echo shell_exec($command." 2>&1");		
		sleep(5);
		/** 
		  * UPDATE DATENBANK
		  **/
		$UPDATE = new UPDATEDB;
		echo $UPDATE->_start();		
		/** 
		  * ABTEILUNGEN / COMMENT / DOCUMENTS
		  * 		
		  *            LÖSCHEN 
		  **/
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "DELETE 
				FROM [Task_Abteilung]
				WHERE TaskName = '".$TaskName."' 
				
				DELETE 
				FROM [Comments]
				WHERE ID = '".$TaskName."'
				
				DELETE 
				FROM [Task_Documents]
				WHERE TaskID = ".$TaskID."
					
				DELETE 
				FROM [Task_InBearbeitung]
				WHERE TaskID = ".$TaskID."
				";	
		
		$DB->set_sql($sql);
		echo $DB->_query();
		$DB->_close();		
	}
?>