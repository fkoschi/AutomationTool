<?php 
	require_once('../Controller/DBclass.php');
	
	
		$time = date('H:m');
		$datum = date('Y-m-d');
	
		$aktZeit = explode(":",$time);
	
	if($_GET['option'] == 'today'){
		if($_GET['Von'] != "" && $_GET['Bis'] != ""){
			//$_von = explode(":", $_GET['Von']);
			$_bis = explode(":", $_GET['Bis']);
			//$von = $_von[0] . ":" . $_von[1] . ":00";
			$von = $aktZeit[0]. ":" . $aktZeit[1] . ":00";
			$bis = $_bis[0] . ":" . $_bis[1] . ":00";
			getInfoTimeline($datum, $von, $bis);
		} else {
			$von = $aktZeit[0]. ":" . $aktZeit[1] . ":00";
			$bis = $aktZeit[0] + 3 . ":" .$aktZeit[1]. ":00";			
			getInfoTimeline($datum, $von, $bis);
		}
	}		
	
	function getInfoTimeline($datum, $von, $bis) {
	
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT StartTime, TaskName, NextRunTime, HostName, estimatedTime, counter
				FROM [Lindorff_DB].[dbo].[Task] AS Task
				JOIN [Lindorff_DB].[dbo].[Task_estimatedTime] AS ET ON ET.TaskID = Task.ID
				WHERE CONVERT(DATE,PARSE(NextRunTime AS date USING 'de-DE')) = '".$datum."'
				AND CONVERT(time, StartTime) >= '".$von."' AND CONVERT(time, StartTime) <= '".$bis."'
				AND NextRunTime != 'Disabled' AND NextRunTime != 'N/A'
				";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		if(!$result){
			echo "Fehler";
		} else {
			echo json_encode($result);
		}
	
		$DB->_close();
	}
?>