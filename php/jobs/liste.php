<?php 
	require_once('../validate.php');
	require_once('../active_link.php');	
	require_once('../Controller/DBClass.php');	
		
	header("Content-Type: text/html; charset=ISO-8859-1");	
	
	$server = $_SERVER['SERVER_NAME'];
	$script = $_SERVER['PHP_SELF'];
	$link = 'http://' . $server . $script . '?page=1';
	
	if ( !empty($_GET['page']) ) {		
		$page = $_GET['page'];
		echo '<input type="hidden" name="page" value="'.$page.'" />';
	} else {
		header('Location: ' . $link );
	}
?>
<!DOCTYPE HTML5>
<html>
	<head>	
		<title>Task - Liste</title>

<!-- Stylesheets -->
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"  type="text/javascript" ></script>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../../css/reset.css">
<link rel="stylesheet" type="text/css" href="../../css/index.css">
<link rel="stylesheet" type="text/css" href="../../css/liste.css">
<link rel="stylesheet" type="text/css" href="../../css/server.css">
</head> 

<script type="text/javascript">
$(function() {
    	
	var val = $("input[name='disable_tasks']").val();		
	var sort_date = $("i[class='icon-sort-by-attributes']").attr("name");	
	var search = $("input[name='Suche']").val();
	var page = $("input[name='page']").val();
	$("tbody").load('refresh_list.php?page=' + page + '&option=' + val + "&sortbydate=" + sort_date + "&search=" + search);
    /** 
	  * REFRESH 
	  *
	  * sorgt für einen asynchronen Reload der Seite alle 10 sek. 
	  **/
	var refreshId = setInterval(function() {
			var val = $("input[name='disable_tasks']").val();
			var sort_date = $("i[class='icon-sort-by-attributes']").attr("name");	
			var search = $("input[name='Suche']").val();
			$("tbody").load('refresh_list.php?page=' + page + '&option=' + val + "&sortbydate=" + sort_date + "&search=" + search );
    }, 10000);
	/** 
	  * INAKTIVE AUSBLENDEN 
	  **/
	$("input[name='disable_tasks']").click(function(){
		if( $(this).is(':checked') ){
				$(this).val("disable");			    
		} else { 
			$(this).val("enable");
		}		
	});
	/** 
	  * SORTIEREN 
	  **/
	$("i[class='icon-sort-by-attributes']").click(function(){
		if($(this).attr("name") == "on"){
			$(this).attr("name", "off");
		} else {
			$(this).attr("name", "on");
		}
	});	
	/**
		* SUCHEN  
		**/
	$("i[class='icon-search']").click(function(){
		var val = $("input[name='disable_tasks']").val();
		var sort_date = $("i[class='icon-sort-by-attributes']").attr("name");	
		var search = $("input[name='Suche']").val();
				
		$("tbody").load('refresh_list.php?option=' + val + "&sortbydate=" + sort_date + "&search=" + search);
	});
	
});
</script>

<body>
<?php 
	/**
	  * FRONTEND ANPASSEN 
	  **/	  
	require_once('../../editFrontend/editFrontend.php');	
?>


<!-- Lindorff Logo -->

<div id="logo" >
<img src="../../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div>  

<div id="navi">	
<h1>Navigation</h1>

<!-- Navigationsleiste -->

<ul>
	<a href="../../index.php" ><li><i class="icon-home"></i> Home</li></a>
	<a href="../../php/jobs.php" ><li  class="active"><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="../../php/bib.php" class="bib"><li><i class="icon-book "></i> Bibliothek</li></a>
	<a href="../../php/user.php" class="user"><li><i class="icon-user "></i> User</li></a>	
	<a href="../../php/settings.php" ><li><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="../../php/monitoring.php" ><li><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> 

<!-- Signout Button -->

<a class="btn" href="../../logout.php" ><i class="icon-signout"></i></a>	


<!-- CONTENT -->

<div id="content">

	<!-- Header Content -->
	<div id="content_header">
		
	<img src="../../img/icons/liste.png" class="h1_pic"/>
	<h4>Alle Tasks</h4>
	
	<!-- Suchfeld -->
	<div id="suchfeld">
		<div class="input-prepend">
			<span class="add-on"><i class="icon-search"></i></span>
			<input class="span2" type="text" placeholder="Suche" id="Suche" name="Suche">
		</div>
	</div> 

	<!-- Inaktive Tasks ausblenden ? -->
	<div id="checkbox">
		<p><input type="checkbox" name="disable_tasks" value="enable" /> Inaktive Tasks ausblenden ?</p>
	</div>
	<!-- Trennlinie --> 
	<hr class="hr_title">	
	
	</div>
		
	<!-- Tabelle -->
	<table>
	<thead align="left">
		<tr>
			<th class="one"></th>
			<th class="two">Name</th>	
			<th class="three"></th>
			<th class="four">Next Run Time<i class="icon-sort-by-attributes"></i></th>
			<th class="five">Status</th>
			<th class="six"></th>
		</tr>
	</thead>
	<tbody align="left">	
	</tbody>
	</table>

</div>
	
<!-- Background picture -->

<div id="background-pic">   
<img src="../../img/icons/job.png" name="job_pic" class="job_pic" />
</div>	
<?php	
/**
  * ANZAHL AUSGEBEN
  *
  **/
	$db = new DB;
	$db->set_database("Lindorff_DB");
	$db->_connect();
	$db->_count_tasks("Task");
	$db->_close();
?>
<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>

</body>
</html>