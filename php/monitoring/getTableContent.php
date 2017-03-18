<?php 
	require_once('../Controller/DBclass.php');	
	
		$time = date('H:m');
		$datum = date('Y-m-d');	
		$aktZeit = explode(":", $time);
	
	if($_GET['function'] == 'tbody')
	{
		if( $_GET['bis'] != '' )
		{	
			$von =  $aktZeit[0] . ":" . $aktZeit[1] . ":00";		
			$_bis = explode(":", $_GET['bis']);					
			$bis = $_bis[0] . ":" . $_bis[1] . ":00";
			getTableContent($datum, $von, $bis);
		} 
		else 
		{			
			$von = $aktZeit[0]. ":" . $aktZeit[1] . ":00"; 
			$bis = $aktZeit[0] + 3 . ":" .$aktZeit[1]. ":00";			
			getTableContent($datum, $von, $bis);
		}
	}
	if($_GET['function'] == 'getRunErr')
	{
		getRunnningStoppedTasks();
	}
	if($_GET['function'] == 'getPassed')
	{
		if($_GET['von'] == '' && $_GET['bis'] == '')
		{
			$von = date('Y-m-d', time() - (60*60*24));
			$bis = date('Y-m-d', time() - (60*60*24));
			getPassedTasks($von, $bis);
		} 
		else if($_GET['von'] != '' && $_GET['bis'] == '')
		{
			$von = $_GET['von'];
			$bis = date('Y-m-d', time() - (60*60*24));
			getPassedTasks($von, $bis);
		}
		else if($_GET['von'] != '' && $_GET['bis'] != '')
		{
			$von = $_GET['von']; 
			$bis = $_GET['bis'];
			getPassedTasks($von, $bis);
		}		
	}
	/** 
	  * TABELLENINHALT 
	  *
	  * bevorstehenden Tasks am aktuellen Tag
	  **/ 
	function getTableContent($datum, $von, $bis){
	
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT TaskName, NextRunTime, Status, HostName , ID
				FROM [Task] 
				WHERE CONVERT(DATE,PARSE(NextRunTime AS date USING 'de-DE')) = '".$datum."'
				AND CONVERT(time, StartTime) >= ' " . $von . " ' AND CONVERT(time, StartTime) <= ' ".$bis." '	
				AND NextRunTime != 'Disabled' AND NextRunTime != 'N/A'
				";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		
	if( !empty($result) ) {
		foreach($result as $value){
			
			$estimatedTime = getEstimatedTime($value['ID']);

			$Host = explode(".", $value['HostName'] );	 // fcap17.falcon.local in Einzelteile trennen
			$NextRunTime = explode(" ", $value['NextRunTime'] );	// Nur Uhrzeit ohne Datum

			echo "<tr>";
				
			if($value['NextRunTime'] == "Disabled")
			{ 
				echo "<td><img src='../img/icons/stop.png' class='symbol' /></td>";				
			} 
			else 
			{
				echo "<td><img src='../img/icons/task.png' class='symbol' /></td>";				
			}			
		
			if(substr_count($value['Status'],'Could not start') == 1)
			{			
				echo "<td><a style='color:red;' href='./jobs/job_info.php?id=" . urlencode($value['TaskName']) ."&page=task'>" . htmlentities($value['TaskName'], ENT_QUOTES ,'ISO-8859-1') . "</a></td>";
				echo "<td>".$Host[0]."</td>";
				echo "<td style='color:red;' class='NextRunTime'>" . $NextRunTime[1] . "</td>";
				echo "<td style='color:red;'>" . $value['Status'] . "</td>";
				echo "<td class='light'><img src='../img/icons/giff/ampel_red.gif' class='ampel' /></td>";				
			} 
			else 
			{
				echo "<td><a href='./jobs/job_info.php?id=" . urlencode($value['TaskName']) ."&page=task'>" . htmlentities($value['TaskName'], ENT_QUOTES ,'ISO-8859-1') . "</a></td>";
				echo "<td>".$Host[0]."</td>";
				echo "<td class='NextRunTime'>" . $NextRunTime[1] . "</td>";
				echo "<td>" . $value['Status'] . "</td>";
					if( substr_count($value['Status'],'Ready') == 1 || substr_count($value['Status'],'Unknown') == 1) 
					{
						echo "<td class='light'><img src='../img/icons/ampel_yellow.png' class='ampel' /></td>";					
					} 
					else 
					{
						echo "<td class='light'><img src='../img/icons/ampel_green.png' class='ampel' /></td>";						
					}
				
				if( !empty($estimatedTime) ) // Estimated Time 
				{
					$estimatedTimeValue = $estimatedTime[0]['estimatedTime'] / $estimatedTime[0]['counter'];
					if ( $estimatedTimeValue == 0 )
					{
						echo "<td class='estimatedTime'> < 5 min </td>";
					}
					else
					{
						echo "<td class='estimatedTime'>" . floor($estimatedTimeValue / 60 ) . "h " . floor($estimatedTimeValue % 60). "m</td>";		
					}
				}
			}
			echo "</tr>";
		}
	} 
	else 
		{
			echo '<div id="NoResult">';
			echo '<img src="../img/icons/could_not_start.png" name="image" />';
			echo '<p>Keine Treffer gefunden für die Eingrenzung</p>';
			echo '</div>';
		}
		$DB->_close();
	}
	/** 
	  * RUNNING / ERROR 
	  **/
	function getRunnningStoppedTasks(){	
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT T.HostName, T.TaskName, T.Status, TIN.timeInStatus
				FROM [Task] AS T
				JOIN [Task_InStatus] AS TIN
				ON T.ID = TIN.TID 
				WHERE T.Status = 'Could not start' 
				OR T.Status = 'Running'
				";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		
		foreach($result as $value){
						
			$Host = explode(".", $value['HostName'] );

			echo "<tr>";			
					
			if(substr_count($value['Status'],'Could not start') == 1){			
				echo "<td class='img'><img src='../img/icons/task.png' width=24 height=24 /></td>";
				echo "<td><a href='./jobs/job_info.php?id=" . urlencode($value['TaskName']) ."&page=task'>" . htmlentities($value['TaskName'], ENT_QUOTES ,'ISO-8859-1') . "</a></td>";	
				echo "<td>" . $Host[0] . "</td>";			
				echo "<td>" . $value['Status'] . "</td>";
				echo "<td class='light'><img src='../img/icons/giff/ampel_red.gif' class='ampel' /></td>";
				echo "<td style='text-align: center;'>" . floor($value['timeInStatus'] / 60 ) . " h " . floor($value['timeInStatus'] % 60 ) . " m</td>";				
			} else {
				echo "<td class='img'><img src='../img/icons/task.png' width=24 height=24 /></td>";
				echo "<td><a href='./jobs/job_info.php?id=" . urlencode($value['TaskName']) ."&page=task'>" . htmlentities($value['TaskName'], ENT_QUOTES ,'ISO-8859-1') . "</a></td>";				
				echo "<td>" . $Host[0] . "</td>";
				echo "<td>" . $value['Status'] . "</td>";			
				echo "<td class='light'><img src='../img/icons/ampel_green.png' class='ampel' /></td>";						
				echo "<td style='text-align: center;' >" . floor($value['timeInStatus'] / 60 ) . " h " . floor($value['timeInStatus'] % 60 ) . " m</td>";			
			}
			echo "</tr>";
		}	
		$DB->_close();
	}
	/**
	  * ESTIMATED TIME 
	  * falls vorhanden, estimated Time aus der DB auslesen und an in die Tabelle übertragen
	  **/
	function getEstimatedTime($ID){
		$DB = new DB;
		$DB->set_database('Lindorff_DB');
		$DB->_connect();
		$sql = "SELECT estimatedTime, counter
				FROM [Task_estimatedTime] 
				WHERE TaskID = " . $ID . " ";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		return $result;				
	}
	/** 
	  * BEREITS GELAUFEN 
	  **/
	function getPassedTasks($von, $bis){
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT TaskName, LastRunTime, LastResult, Status, HostName
				FROM [Task]
				WHERE CONVERT(DATE,PARSE(LastRunTime AS date USING 'de-DE')) <= '".$bis."'
				AND CONVERT(DATE,PARSE(LastRunTime AS date USING 'de-DE')) >= '".$von."'
				AND LastRunTime != 'Disabled' AND LastRunTime != 'N/A'
				";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		
		foreach($result as $value)
		{
			switch ($value['LastResult']) {
				case '0':
					$result = "<strong style='color: green;'>Erfolg</strong>";
					break;
				case '1':
					$result = "<strong style='color: red;'>Fehler</strong>";
					break;
				default:
					$result = "<strong style='color: #FFC13B;'>Unbekannt</strong>";
					break;
			}
			$Host = explode('.', $value['HostName']);	
			echo "<tr>";			
				echo "<td class='img'><img src='../img/icons/task.png' width=28 height=28 /></td>";
				echo "<td><a href='./jobs/job_info.php?id=" . urlencode($value['TaskName']) ."&page=task'>" . htmlentities($value['TaskName'], ENT_QUOTES, 'ISO-8859-1') . "</a></td>";				
				echo "<td>" . $Host[0] . "</td>";
				echo "<td>" . $value['LastRunTime'] . "</td>";		
				echo "<td>" . $result . "</td>";				
				echo "<td>" . $value['Status'] . "</td>";			
				if($value['Status'] == 'Running'){
					echo "<td class='light'><img src='../img/icons/ampel_green.png' class='ampel' /></td>";						
				} else {
					echo "<td class='light'><img src='../img/icons/ampel_yellow.png' class='ampel' /></td>";
				}
			echo "</tr>";
		}	
		$DB->_close();
	}
	
	?>