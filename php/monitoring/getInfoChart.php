<?php
	require_once('../Controller/DBclass.php');
	
	switch ($_GET['option']) {
		case 'getRunErr':
			getRunErrTasks();
			break;
		case 'getPassed':
			getPassedTasks();
			break;
		case 'getCPUCapacity':
			getCPUCapacity($_GET['server']);
			break;
		case 'overviewData':
			getOverviewData($_GET['server'], $_GET['tag']);
			break;
		default:
			# code...
			break;
	}
	
	function getRunErrTasks(){
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT HostName,Status,COUNT(Status) as counter 
				FROM [Task]
				WHERE Status IN ('Running', 'Could not start')
				GROUP BY Status,HostName
				";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
				
		$header = array('HostName','Running','Could not start');
		$fcap09 = array('fcap09');
		$fcap17 = array('fcap17');		
		$cnsF17 = 0;
		$cnsF9 = 0;
		$runF17 = 0;
		$runF9 = 0;
		foreach( $result as $value ){
			
			if( $value['HostName'] == 'fcap09.falcon.local' && $value['Status'] == 'Could not start' ){
				$cnsF9 = $value['counter'];
			}
			if( $value['HostName'] == 'fcap09.falcon.local' && $value['Status'] == 'Running' ){
				$runF9 = $value['counter'];							
			}			
			if( $value['HostName'] == 'fcap17.falcon.local' && $value['Status'] == 'Running' ){				
				$runF17 = $value['counter'];				
			}
			if( $value['HostName'] == 'fcap17.falcon.local' && $value['Status'] == 'Could not start' ){			
				$cnsF17 = $value['counter'];				
			} 
			
		}
		array_push($fcap09, $runF9, $cnsF9);
		array_push($fcap17, $runF17, $cnsF17); // Array zusammenfügen 
		$Inhalt = array($header,$fcap09,$fcap17);
		echo json_encode($Inhalt);		
		$DB->_close();
	}
	function getPassedTasks(){
		$tstamp  = mktime(0, 0, 0, date("m"), date("d")-5, date("Y"));
		$bis = date("Y-m-d", $tstamp);
		$von = date("Y-m-d");
		
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT HostName, DATENAME(dw, PARSE(LastRunTime AS datetime USING 'de-DE') ) AS date, COUNT(LastRunTime) as counter 
				FROM [Task]
				WHERE CONVERT(DATE,PARSE(LastRunTime AS date USING 'de-DE')) <= '".$von."'
				AND CONVERT(DATE,PARSE(LastRunTime AS date USING 'de-DE')) >= '".$bis."'	
				AND LastRunTime != 'Disabled' AND LastRunTime != 'N/A'
				GROUP BY DATENAME(dw, PARSE(LastRunTime AS datetime USING 'de-DE') ), HostName
				";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		$header = array('Wochentag', 'fcap09', 'fcap17');
		$monday = array('Montag',0,0);
		$tuesday = array('Dienstag',0,0);
		$wednesday = array('Mittwoch',0,0);
		$thursday = array('Donnerstag',0,0);
		$friday = array('Freitag',0,0);
		$saturday = array('Samstag',0,0);
		$sunday = array('Sonntag',0,0);
		
		foreach( $result as $value ){
		
			switch($value['date'])
			{
				case "Monday":
					if($value['HostName'] == 'fcap09.falcon.local'){
						$monday[1] = $value['counter'];
					} else if($value['HostName'] == 'fcap17.falcon.local'){
						$monday[2] = $value['counter'];
					}
					break;
				case "Tuesday";
					if($value['HostName'] == 'fcap09.falcon.local'){
						$tuesday[1] = $value['counter'];
					} else if($value['HostName'] == 'fcap17.falcon.local'){
						$tuesday[2] = $value['counter'];
					}
					break;				
				case "Wednesday";
					if($value['HostName'] == 'fcap09.falcon.local'){
						$wednesday[1] = $value['counter'];
					} else if($value['HostName'] == 'fcap17.falcon.local'){
						$wednesday[2] = $value['counter'];
					}
					break;
				case "Thursday";
					if($value['HostName'] == 'fcap09.falcon.local'){
						$thursday[1] = $value['counter'];
					} else if($value['HostName'] == 'fcap17.falcon.local'){
						$thursday[2] = $value['counter'];
					}
					break;
				case "Friday";
					if($value['HostName'] == 'fcap09.falcon.local'){
						$friday[1] = $value['counter'];
					} else if($value['HostName'] == 'fcap17.falcon.local'){
						$friday[2] = $value['counter'];
					}
					break;
				case "Saturday";
					if($value['HostName'] == 'fcap09.falcon.local'){
						$saturday[1] = $value['counter'];
					} else if($value['HostName'] == 'fcap17.falcon.local'){
						$saturday[2] = $value['counter'];
					}
					break;
				case "Sunday";
					if($value['HostName'] == 'fcap09.falcon.local'){
						$sunday[1] = $value['counter'];
					} else if($value['HostName'] == 'fcap17.falcon.local'){
						$sunday[2] = $value['counter'];
					}
					break;
			}
		}	
		$array = array($header, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday);
		echo json_encode($array);
		$DB->_close();
	}
	function getCPUCapacity($server){
		
		$output = shell_exec('wmic /node:'.$server.' cpu get loadpercentage');

		$exp = explode('LoadPercentage', $output); // String zerlegen -> LoadPercentage entfernen
		$percentage = $exp[1];
				$values = explode(' ', $percentage);	// Werte zerlegen
		$arr = array();
		foreach( $values as $strings ){
			if( strlen($strings) > 0){	
				$arr[] = $strings;		// Werte dem Array hinzufügen
			}
		}
		array_pop($arr); // Letztes Element löschen, da leer
	
		$header = array('Label', 'Value');
		$values_cpu_1 = array( 'CPU1', (int)(ltrim($arr[0])) );
		$values_cpu_2 = array( 'CPU2', (int)(ltrim($arr[1])) );
		$values_cpu_3 = array( 'CPU3', (int)(ltrim($arr[2])) );
		$values_cpu_4 = array( 'CPU4', (int)(ltrim($arr[3])) );
		$array = array();
		
		array_push($array, $header, $values_cpu_1, $values_cpu_2, $values_cpu_3, $values_cpu_4);
				
		echo json_encode($array);		
	}
	function getOverviewData($server, $tag) {
		$DB = new DB;
		$DB->set_database('Lindorff_DB');
		$DB->_connect();
		$date = date("Y-m-d", time() - (60*60*24) ); // gestern
		if( $server == '*' ) {
			$sql = "	SELECT ID, TaskName, LastResult, HostName
 					 	FROM [Lindorff_DB].[dbo].[Task]
  						WHERE CONVERT(DATE,PARSE(LastRunTime AS date USING 'de-DE')) = '".$date."' 
  						AND LastRunTime != 'N/A' ";
  		} 
  		else {
  			$sql = "	SELECT ID, TaskName, LastResult, HostName
 					 	FROM [Lindorff_DB].[dbo].[Task]
  						WHERE CONVERT(DATE,PARSE(LastRunTime AS date USING 'de-DE')) = '".$date."' 
  						AND HostName = '".$server."' 
  						AND LastRunTime != 'N/A' ";	
  		} 
  		$DB->set_sql( $sql );
  		$DB->_query();

  		$result = $DB->_fetch_array(1);
  		
  		$header = array('LastResult','Anzahl');
  		$success = array('Erfolg');
  		$fail = array('Fehler');
  		$unknown = array('Unbekannt');

  		$counterSuccess = 0;		// Zählervariablen
  		$counterFail = 0;
  		$counterUnknown = 0;

  		foreach ($result as $value ) {
  			
  			if( $value['LastResult'] == '0' ) 
  			{
  				$counterSuccess += 1;
  			}
  			else if ( $value['LastResult'] == '1' )
  			{
  				$counterFail += 1;
  			}
  			else
  			{
  				$counterUnknown += 1;
  			}
  		}
  		array_push($success, $counterSuccess);
  		array_push($fail, $counterFail);
  		array_push($unknown, $counterUnknown);
  		$counterTotal = $counterUnknown + $counterSuccess + $counterFail;
  		$inhalt = array($header, $success, $fail, $unknown);  		
  		
  		if ( $tag == 'one' ) {
  			echo json_encode($inhalt);  		
  		}
  		else if ( $tag == 'two' ) {
  			echo json_encode($counterTotal);	// Nur den Zähler zurückgeben
  		}
  		
  		$DB->_close();

	}
?>