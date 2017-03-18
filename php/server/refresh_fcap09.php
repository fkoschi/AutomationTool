<?php 
	
	require_once('../Controller/DBclass.php');
	
	if( $_GET['suche'] != '' ){
		$suchbegriff = $_GET['suche'];
		$where = "AND TaskName LIKE '%".$suchbegriff."%' ";
		getTasks($where);
	} else {
		$where = "";
		getTasks($where);
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
	
	function getTasks($where){
		$status = getStatus();
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		
		// ANZAHL AN ZEILEN 
		$sql = "SELECT COUNT(ID) AS rows FROM [Fcap09]";
		$DB->set_sql($sql);
		$DB->_query();
		$rows = $DB->_fetch_array(1);
		$rows = $rows[0]["rows"];
		
		// PAGE LIMIT 
		$page_limit = 17;
		// PAGES 
		$pages = $rows / $page_limit;
		// GRENZE SETZEN 
		if( $_GET['page'] == 1 ){
			$page = 0;
		} else {
			$page = $page_limit * ($_GET['page'] -1);
		}
		
		// LINKS
		echo '<div id="paging">';
		for ( $i=1 ; $i <= $pages + 1; $i++ ) {			
			
			if( $_GET['page'] == $i ) {
				echo '<a href="http://'.$_SERVER['SERVER_NAME'].'/Tool/php/server/fcap09.php?page='.$i.'" class="active">'.$i.'</a>';			
			} else {
				echo '<a href="http://'.$_SERVER['SERVER_NAME'].'/Tool/php/server/fcap09.php?page='.$i.'" class="inactive">'.$i.'</a>';			
			}
		}
		echo '</div>';	
		if( $where == "") {
			$sql = "SELECT TOP 16 * 
					FROM [Fcap09]
					WHERE ID > ".$page."
					".$where." ";
		}
		else {
			$sql = "SELECT TOP 16 * 
					FROM [Fcap09]
					WHERE ID > 0
					".$where." ";
		}
		
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);		
	if( array_key_exists("rows" , $result[0]) ){
		echo "<div id='no_result'><img src='../../img/icons/stop.png' name='no_result' /><p>Kein Treffer</p></div>";
	}
	else {
		$treffer = array();
		foreach($result as $value){
		foreach($status as $st ) {
			if ( in_array( $value['TaskName'] , $st ) ) {
				$treffer[] = $value['TaskName'];
			}
		}
			echo "<tr>";
				
			if($value['NextRunTime'] == 'Disabled'){ 				
				echo "<td><img src='../../img/icons/stop.png' class='symbol' /></td>";
			} else {				
				echo "<td><img src='../../img/icons/scheduled_task.png' class='symbol' /></td>";
			}
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
			} else if (substr_count($value['Status'],'Could not start') == 1){
				echo "<td><img src='../../img/icons/giff/ampel_red.gif' class='ampel' /></td>";
			}else {
				echo "<td><img src='../../img/icons/ampel_green.png' class='ampel' /></td>";						
			}
			echo "</tr>";
		} 
	}	
	
	}
?>