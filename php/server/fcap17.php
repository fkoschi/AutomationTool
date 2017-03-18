<?php 
	include('../../php/validate.php');
	include('../active_link.php');
	
	if( empty($_GET) ) {
		header('Location: http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . '?page=1');
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Fcap17</title>

<!-- Stylesheets -->
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../../css/reset.css">
<link rel="stylesheet" type="text/css" href="../../css/index.css">
<link rel="stylesheet" type="text/css" href="../../css/server.css">

</head>
<script type="text/javascript">
     $(document).ready(function() {
	   var suche = $("input[name='Suche']").val();
	   var page = $("input[name='Page']").val();
       $("tbody").load("refresh_fcap17.php?suche=" + suche + "&page=" + page);
       
	   $("input[name='Suche']").change(function(){
			var suche = $(this).val();
			$("tbody").load("refresh_fcap17.php?suche=" + suche + "&page=" + page);
	   });
	   
	   var refreshId = setInterval(function() {
          var suche = $("input[name='Suche']").val();
		  $("tbody").load('refresh_fcap17.php?suche=' + suche + '&page=' + page );
       }, 10000);
    });
</script>
<body>
<?php 
	/** 
	  * FRONTEND ANPASSEN 
	  **/
	require_once('../../editFrontend/editFrontend.php');		
?>
<!-- PAGE ID -->
<input type="hidden" name="Page" value="<?php echo $_GET['page'] ?>" />

<!----- Lindorff Logo ----->
<!------------------------->
<div id="logo" >
<img src="../../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div> 

<!-- Navigationsleiste -->
<!----------------------->
<div id="navi">	
<h1>Navigation</h1>
	<ul>
		<a href="../../index.php" ><li><i class="icon-home"></i> Home</li></a>
		<a href="../jobs.php" ><li class="active"><i class="icon-spinner "></i> Tasks</li></a>	
		<a href="../bib.php" class="bib" ><li><i class="icon-book "></i> Bibliothek</li></a>
		<a href="../user.php" class="user" ><li><i class="icon-user "></i> User</li></a>
		<a href="../settings.php" ><li><i class="icon-cogs "></i> Einstellungen</li></a>
		<a href="../monitoring.php?page=today"><li><i class="icon-code "></i> Monitoring</li></a>
	</ul>
</div>

<!-- Logout Button -->
<a class="btn" href="../../logout.php" ><i class="icon-off"></i></a>	


<div id="content">
	
	<!-- Header Content -->
	<div id="content_header">
		
	<img src="../../img/icons/server_128.png" class="h1_pic"/>
	<h4>Fcap17</h4>
		
	<!-- Suchfeld --> 
	<div class="input-prepend">
		<span class="add-on"><i class="icon-search"></i></span>
		<input class="span2" type="text" placeholder="Suche" id="Suche" name="Suche">
	</div>
	<br /><br /><br />
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
            <th class="four">Next Run Time</th>
			<th class="five">Status</th>
			<th class="six"></th>
		</tr>
	</thead>
	<tbody align="left">
	</tbody>
	</table>
</div>

<!-- Background picture -->
<!------------------------>
<div id="background-pic">
<img src="../../img/icons/server.png" name="server_pic" class="server_pic" />
</div>	
<?php	
/**
  * ANZAHL AN TASKS AUSGEBEN
  *
  **/
	$db = new DB;
	$db->set_database("Lindorff_DB");
	$db->_connect();
	$db->_count_tasks("Fcap17");
	$db->_close();
?>
<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>
</body>
</html>