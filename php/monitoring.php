<?php 
	include('validate.php');
	include('active_link.php');	
	
	$host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
      
    $page = $_GET['page'];
        
    $script_name = explode("/",$script, 4);	
	
	/* Weiterleitung bei fehlendem GET */
	if( empty($_GET) ){
		header("Location: http://".$host . $script ."?page=today");
	}
	
?>
<!DOCTYPE HTML5>
<html>
<head>
		<title>Monitoring</title>
	<meta charset="UTF-8">
 <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/reset.css">
<link rel="stylesheet" type="text/css" href="../css/index.css">
<link rel="stylesheet" type="text/css" href="../css/monitoring/monitoring.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization',
       'version':'1','packages':['timeline']}]}"></script>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization',
       'version':'1','packages':['corechart']}]}"></script>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization',
       'version':'1','packages':['gauge']}]}"></script>
</head>
<body>
<?php 
	/** 
	  * FRONTEND ANPASSEN 
	  **/
	require_once('../editFrontend/editFrontend.php');
	/** 
	  * JS EINBINDEN 
	  * Nur eine Datei einbinden, um Blockaden zu verhindern
	  **/
	if($_GET['page'] == 'today') {			
		echo "<script src='../js/monitoring/monitoring_page_today.js'></script>";
	}
	else if ($_GET['page'] == 'runerr') {		
		echo "<script src='../js/monitoring/monitoring_page_runerr.js'></script>";
	}
	else if ($_GET['page'] == 'passed') {
		echo "<script src='../js/monitoring/monitoring_page_passed.js'></script>";
	}
?>	

	<div id="logo" >
		<img src="../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
	</div> <!-- logo --> 
	
	<div id="navi">	
		<h1>Navigation</h1>

<!-- Navigationsleiste -->
<ul>
	<a href="../index.php" ><li <?php if($fileName == '/index.php') echo ' class="active" ' ?>><i class="icon-home"></i> Home</li></a>
	<a href="jobs.php" ><li <?php if($fileName == '/jobs.php') echo ' class="active" ' ?>><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="bib.php" class="bib"><li <?php if($fileName == '/bib.php') echo ' class="active" ' ?>><i class="icon-book "></i> Bibliothek</li></a>
	<a href="user.php" class="user" ><li <?php if($fileName == '/user.php') echo ' class="active" ' ?>><i class="icon-user "></i> User</li></a>
	<a href="settings.php" ><li <?php if($fileName == '/settings.php') echo ' class="active" ' ?>><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="monitoring.php?page=today" ><li <?php if($fileName == '/monitoring.php') echo ' class="active" ' ?>><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> <!-- navi -->

<a class="btn" href="../logout.php" ><i class="icon-signout"></i></a>	

<div id="content">   

	<div id="header_content">
	<!--
		<img src="../img/icons/32/828.png" name="header_pic" />			
	-->
		<h1 class="monitoring">Monitoring</h1><br /><br />
	
		<div id="content_tabs">
			<ul class="content_tabs">
			<a href="<?php echo $script_name['3'] . '?page=today'?>"><li class="first" <?php if($page == 'today') echo 'id="active_tab"' ?>><img src='../img/icons/32/542.png' class='today' /><strong>Planned</strong></li></a>
			<a href="<?php echo $script_name['3'] . '?page=runerr'?>"><li  <?php if($page == 'runerr') echo 'id="active_tab"' ?>><img src='../img/icons/running_man.png' class='runerr' /> <strong>Active</strong></li></a>
			<a href="<?php echo $script_name['3'] . '?page=passed'?>"><li  <?php if($page == 'passed') echo 'id="active_tab"' ?>><img src='../img/icons//32/539.png' class='passed' /> <strong>Passed</strong></li></a>        			
			</ul>
		</div>
	</div>
<?php 
	/** 
	  * PAGE TODAY 
	  **/
	if($_GET['page'] == 'today'){
	echo '
	<input type="hidden" name="today" value="today" />
	<i class="icon-refresh" title="Refresh ?"></i>
	<!-- INFORMATIONEN --
	<p class="info_header_content">Die Grafik zeigt alle in den kommenden drei Stunden anstehenden Tasks. <br />
	Angepasst werden kann die Anzeige durch die beiden Input-Felder. 
	</p><br />		
	-->
	<br /><br />

	<div id="timeline_one" style="width: auto; height: 300px;"></div>
	<div id="no_timeline"></div>
	'; 
	$zeit = date("H:i", time() );
	$zeit_bis  = explode(":", $zeit);
	$zeit_bis_1 = $zeit_bis[0] + 3;

	echo '
	<p class="Zeitgrenzen"> <input type="text" name="von_zeit" id="" placeholder="Von" value="'. date("H:i", time() ) .'" disabled /> - <input type="text" id="" name="bis_zeit" placeholder="' . $zeit_bis_1 . ':' . $zeit_bis[1] . '"/> Uhr <i class="icon-search"></i></p>
	
	<span class="error_zeitgrenze"></span>
	<!-- Tabelle -->
	<div id="table_tasks_today">
	<table class="today">
	<thead align="left">
		<tr>
			<th class="one"></th>
			<th class="two">Name</th>	
			<th class="three">Server</th>		            			
			<th class="four">Next Run Time</th>
			<th class="five">Status</th>
			<th class="six"></th>
			<th class="seven">Estimated Time <i class="icon-time"></i></th>
		</tr>
	</thead>
	<!-- tbody -->
	<tbody align="left">	
	</tbody>
	</table>
	</div>
	<p class="timeUpdated">Zuletzt aktualisiert um: <span>' . date("H:i:s", time() ) . '</span> </p>
    ';}
	/** 
	  * PAGE RUNERR
	  **/
	if($_GET['page'] == 'runerr'){
	echo '
	<input type="hidden" name="runerr" value="runerr" />
	<i class="icon-refresh" title="Refresh ?"></i>
	<!--<p class="info_header_content">Die Grafik zeigt solche Tasks, deren <b>Status</b> entweder gerade <b><i>laufend</i></b> oder aber 
	<b><i> konnte nicht gestartet werden </i></b> ist.<br />-->
	</p>	
		
	<div id="chartRunErr"></div>
	<!-- loading image -->	
	<div id="loading-image_gauge">
		<i class="icon-spinner icon-spin"></i>
	</div>	
	<div id="GaugeDiv">
		<h3>Prozessorauslastung</h3>
			<select name="server">
				<option selected value="fcap09.falcon.local">fcap09</option>
				<option value="fcap17.falcon.local">fcap17</option>
			</select>
			<p class="header_server">Auslastung auf Server: </p>
			<p class="title_server"></p>
		<div id="gauge_div"></div>		
	</div>
	<!-- Tabelle -->
	<table class="RunErr">
	<thead align="left">
		<tr>
			<th class="one"></th>
			<th class="two">Name</th>	
			<th class="three">Server</th>		            			
			<th class="four">Status</th>
			<th class="five"></th>
			<th class="six">Time in Status</th>			
		</tr>
	</thead>
	<!-- tbody -->
	<tbody class="RunErr" align="left">	
	</tbody>
	</table>	
	<p class="timeUpdated">Zuletzt aktualisiert um: <span>' . date("H:i:s", time() ) . '</span> </p>
	';
	}
	/** 
	  * PAGE PASSED 
	  **/
	if($_GET['page'] == 'passed'){
	echo '
	<input type="hidden" name="runerr" value="runerr" />
	<!--
	<p class="info_header_content">Die Grafik zeigt Tasks deren letzte Ausführungszeit 
	in einem Zeitraum von vor 5 Tagen bis zum aktuellen Tag liegen.<br />
	Die Auswahl der Eingabeparameter wird für die <b>letzte Ausführungszeit </b> gesetzt.<br /></p>	
	-->
	<div id="chartPassed" ></div>
	<div id="chartOverviewBox" >
		<h3>Tagesauswertung</h3>
		<p class="date">Auswertung vom: ' . date("d.m.Y", time() - (60*60*24) ) . ' </p>
		<p class="selectTitle">Server:</p>
		<select name="server">
			<option value="*">Alle</option>
			<option value="fcap09.falcon.local">fcap09</option>
			<option value="fcap17.falcon.local">fcap17</option>
		</select>
		<div id="chartOverview"></div>
		<p class="counterCompletedTasks">Anzahl ausgeführter Tasks: <span></span></p>
	</div>
	
	<p class="Zeitgrenzen"> <input type="date" name="von_date" id="datepicker_von" placeholder="Von"/> - <input type="date" id="datepicker_bis" name="bis_date" placeholder="Bis"/> Datum <i class="icon-search"></i></p>
	<span class="error_grenze"></span>	
	
	<!-- Tabelle -->
	<table class="passed">
	<thead align="left">
		<tr>
			<th class="one"></th>
			<th class="two">Name</th>		
			<th class="three">Server</th>	            			
			<th class="four">Last Run Time</th>
			<th class="five">Last Result</th>			
			<th class="six">Status</th>
			<th class="seven"></th>
		</tr>
	</thead>
	<!-- tbody -->
	<tbody align="left">	
	</tbody>
	</table>
	
	';
	}
?>
</div>	
<!-- Background picture -->
<div id="background-pic">
<img src="../img/icons/128/828.png" name="monitoring_pic" class="monitoring_pic" />
</div>	
<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>
</body>
</html>