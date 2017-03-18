<?php 
	
	require_once('../Controller/DBclass.php');
	
if ( !empty($_GET['page']) ) {	
	if( $_GET['option'] == 'enable' && $_GET['sortbydate'] == 'undefined' && $_GET['search'] == ""){
		$where = "";
		$sortbydate = "";
		$search = "";
		getAllTasks($where, $sortbydate, $search);
	}
	else if( $_GET['option'] == 'enable' && $_GET['sortbydate'] == 'undefined' && $_GET['search'] != ''){
		$where = "";
		$sortbydate = "";
		$search = "AND [Task].TaskName LIKE '%".$_GET['search']."%' ";
		getAllTasks($where, $sortbydate, $search);
	}
	else if( $_GET['option'] == 'disable' && $_GET['sortbydate'] == 'undefined' && $_GET['search'] != ''){
		$where = "AND [Task].NextRunTime != 'Disabled'";
		$sortbydate = "";
		$search = "AND [Task].TaskName LIKE '%".$_GET['search']."%' ";
		getAllTasks($where, $sortbydate, $search);
	}
	else if( $_GET['option'] == 'disable' && $_GET['sortbydate'] == 'undefined' && $_GET['search'] == ''){
		$where = "AND [Task].NextRunTime != 'Disabled'";
		$sortbydate = "";
		$search = "";
		getAllTasks($where, $sortbydate, $search);
	} 	
	else if ($_GET['option'] == 'enable' && $_GET['sortbydate'] == 'on' && $_GET['search'] == "") {
		$where = "";
		$sortbydate = "ORDER BY [Task].NextRunTime";
		$search = "";
		getAllTasks($where, $sortbydate, $search);
	}	
	else if( $_GET['option'] == 'enable' && $_GET['sortbydate'] == 'off' && $_GET['search'] != ""){
		$where = "";
		$sortbydate = "";
		$search = "AND [Task].TaskName LIKE '%" .$_GET['search']. "%' ";
		getAllTasks($where, $sortbydate, $search);
	}
	else if($_GET['option'] == 'disable' && $_GET['sortbydate'] == 'off' && $_GET['search'] != '' ) {
		$where = "AND [Task].NextRunTime != 'Disabled'";
		$sortbydate = "";
		$search = "AND [Task].TaskName LIKE '%". $_GET['search'] ."%' ";
		getAllTasks($where, $sortbydate, $search);
	}
	else if ($_GET['option'] == 'disable' && $_GET['sortbydate'] == 'on' && $_GET['search'] != '' ) {
		$where = "AND [Task].NextRunTime != 'Disabled'";
		$sortbydate = "ORDER BY [Task].NextRunTime";
		$search = "AND [Task].TaskName LIKE '%". $_GET['search'] ."%' ";
		getAllTasks($where, $sortbydate, $search);
	}
	else if($_GET['option'] == 'disable' && $_GET['sortbydate'] == 'on' && $_GET['search'] == ''){
		$where = "AND [Task].NextRunTime != 'Disabled'";
		$sortbydate = "ORDER BY [Task].NextRunTime";
		$search = "";
		getAllTasks($where, $sortbydate, $search);
	}
	else if($_GET['option'] == 'disable' && $_GET['sortbydate'] == 'off' && $_GET['search'] == ''){
		$where = "AND [Task].NextRunTime != 'Disabled'";
		$sortbydate = "";
		$search = "";
		getAllTasks($where, $sortbydate, $search);
	}
	else {
		$where = "";
		$sortbydate = "";
		$search = "";
		getAllTasks($where, $sortbydate, $search);
	}
}	
	function getStatus() {
		$DB = new DB; 
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT TK.TaskName
				FROM [Task] AS TK
				JOIN [Task_InBearbeitung] AS TB ON TB.TaskID = TK.ID";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		$array = array();
		foreach( $result as $index => $value ) {
			$array[] = $value;
		}
		return $array;		
		$DB->_close();
	}
	function getAllTasks($where,$sortbydate, $search){
		/**  Status	  **/
		$status = getStatus();
		
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		
		// ANZAHL AN ZEILEN 
		$sql = "SELECT COUNT(ID) AS rows FROM [Task]";
		$DB->set_sql($sql);
		$DB->_query();
		$rows = $DB->_fetch_array(1);
		$rows = $rows[0]["rows"];
		
		// PAGE LIMIT 
		$page_limit = 16;
		// PAGES 
		$pages = $rows / $page_limit;
		// GRENZE SETZEN 
		if( $_GET['page'] == 1 ){
			$page = 0;
		} else {
			$page = $page_limit * ($_GET['page'] - 1);
		}
		
		// LINKS
		echo '<div id="paging">';
		for ( $i=1 ; $i <= $pages + 1; $i++ ) {			
			
			if( $_GET['page'] == $i ) {
				echo '<a href="http://'.$_SERVER['SERVER_NAME'].'/Tool/php/jobs/liste.php?page='.$i.'" class="active">'.$i.'</a>';			
			} else {
				echo '<a href="http://'.$_SERVER['SERVER_NAME'].'/Tool/php/jobs/liste.php?page='.$i.'" class="inactive">'.$i.'</a>';			
			}
		}
		echo '</div>';		
		
		function setSQL($page, $where, $sortbydate, $search) {
		
		if( $where == "") {
			$sql = 	"SELECT TOP 16 * FROM(
					SELECT [Task].TaskName, [Task].NextRunTime, [Task].Status FROM [Fcap17] 
					JOIN [Task] ON [Task].TaskName = [Fcap17].TaskName
					WHERE [Task].ID > ".$page." 
					" . $where . 				 
					$search .
					"					
					UNION ALL			
					SELECT [Task].TaskName, [Task].NextRunTime, [Task].Status FROM [Fcap09]
					JOIN [Task] ON [Task].TaskName = [Fcap09].TaskName
					WHERE [Task].ID > ".$page." 
					" . $where . 
					$sortbydate .
					$search . "				
					) a";
		} else {
			$sql = 	"SELECT TOP 16 * FROM(
					SELECT [Task].TaskName, [Task].NextRunTime, [Task].Status FROM [Fcap17] 
					JOIN [Task] ON [Task].TaskName = [Fcap17].TaskName
					WHERE [Task].ID > 0 
					" . $where . 				 
					$search .
					"					
					UNION ALL			
					SELECT [Task].TaskName, [Task].NextRunTime, [Task].Status FROM [Fcap09]
					JOIN [Task] ON [Task].TaskName = [Fcap09].TaskName
					WHERE [Task].ID > 0 
					" . $where . 
					$sortbydate .
					$search . "				
					) a";
		
		}
			return $sql;
		}
		
		$sql = setSQL($page , $where , $sortbydate , $search);		
		
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		
		$treffer = array();
	
	if( array_key_exists("rows" , $result[0]) ){
		echo "<div id='no_result'><img src='../../img/icons/stop.png' name='no_result' /><p>Kein Treffer</p></div>";
	}
	else {
		foreach($result as $value){
		// BOOKMARK 	
		foreach($status as $st ) {
			if ( in_array( $value['TaskName'] , $st ) ) {
				$treffer[] = $value['TaskName'];
			}
		}	
		echo "<tr>";		
		if($value['NextRunTime'] == "Disabled"){ 
			echo "<td><img src='../../img/icons/stop.png' class='symbol' /></td>";			
		} else {
			echo "<td><img src='../../img/icons/scheduled_task.png' class='symbol' /></td>";
		}		
		// COULD NOT START 
		if(substr_count($value['Status'],'Could not start') == 1){			
			echo "<td><a style='color:red;' href='../jobs/job_info.php?id=" . htmlentities($value['TaskName'],ENT_HTML5, 'ISO-8859-1' ) ."&page=task'>" . htmlentities($value['TaskName'],ENT_HTML5, 'ISO-8859-1' ) . "</a></td>";
			if ( in_array( $value['TaskName'] , $treffer ) ) {
				echo "<td><img src='../../img/icons/bookmark.png' name='bookmark' /></td>";
			} else {
				echo "<td></td>";
			}			
			echo "<td style='color:red;'>" . $value['NextRunTime'] . "</td>";			
			echo "<td style='color:red;'>" . $value['Status'] . "</td>";
			echo "<td><img src='../../img/icons/giff/ampel_red.gif' class='ampel' /></td>";
		}
		// ELSE 
		else {
			echo "<td><a href='../jobs/job_info.php?id=" . htmlentities($value['TaskName'],ENT_HTML5, 'ISO-8859-1' ) ."&page=task'>" . htmlentities($value['TaskName'],ENT_HTML5, 'ISO-8859-1' ) . "</a></td>";
			if ( in_array( $value['TaskName'] , $treffer ) ) {
				echo "<td><img src='../../img/icons/bookmark.png' name='bookmark' /></td>";
			} else {
				echo "<td></td>";
			}			
			echo "<td>" . $value['NextRunTime'] . "</td>";
			echo "<td>" . $value['Status'] . "</td>";
			if( substr_count($value['Status'],'Ready') == 1 || substr_count($value['Status'],'Unknown') == 1) {
				echo "<td><img src='../../img/icons/ampel_yellow.png' class='ampel' /></td>";			
			}
			else {
				echo "<td><img src='../../img/icons/ampel_green.png' class='ampel' /></td>";						
			}
		}
			echo "</tr>";
		}		
	}		
	
	$DB->_close();
		
	}
		
?>