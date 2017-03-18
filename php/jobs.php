<?php 
	include('validate.php');
	include('active_link.php');	
	/*
	echo "<pre>";
	print_r( $_SERVER );
	echo "</pre>";
	*/
?>
<!DOCTYPE HTML>
<html>
	<head>
	<meta charset="utf8" />
		<title>Jobs</title>

<!-- Stylesheets -->
<!----------------->
<link rel="stylesheet" type="text/css" href="../css/jobs.css">
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/reset.css">
<link rel="stylesheet" type="text/css" href="../css/index.css">
</head>

<body>
<script src="http://code.jquery.com/jquery-1.9.1.js"  type="text/javascript" ></script>

<?php 
	/** 
	  * FRONTEND ANPASSEN 
	  **/
	require_once('../editFrontend/editFrontend.php');
?>

<!----------->

<!-- Lindorff Logo -->
<!------------------->
<!------------------->
<div id="logo" >
<img src="../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div>  

<div id="navi">	
<h1>Navigation</h1>

<!-- Navigationsleiste -->
<!----------------------->
<ul>
	<a href="../index.php"><li <?php if($fileName == '/index.php') echo ' class="active" ' ?>><i class="icon-home"></i> Home</li></a>
	<a href="jobs.php" ><li <?php if($fileName == '/jobs.php') echo ' class="active" ' ?>><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="bib.php" class="bib"><li <?php if($fileName == '/bib.php') echo ' class="active" ' ?>><i class="icon-book "></i> Bibliothek</li></a>
	<a href="user.php" class="user" ><li <?php if($fileName == '/user.php') echo ' class="active" ' ?>><i class="icon-user "></i> User</li></a>	
	<a href="settings.php" ><li <?php if($fileName == '/settings.php') echo ' class="active" ' ?>><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="monitoring.php" ><li <?php if($fileName == '/monitoring.php') echo ' class="active" ' ?>><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> 

<!-- Signout Button -->
<!-------------------->
<acronym title="Logout ?">
<a class="btn" href="../logout.php" ><i class="icon-signout"></i></a>	
</acronym>

<div id="content">		

<!-- Headline -->
<!-------------->
<h1>Task - Übersicht</h1>

<a href="../php/jobs/liste.php">
	<div id="links">
		<div id="l_header">
			<h2>Liste</h2>
		</div>
	
		<div id="l_img">
			<img src="../img/icons/list.png" />
		</div>	
	
		<div id="l_text">
			<p>Hier gelangen Sie zu einer Übersicht über alle angelegten Tasks. Unabhängig auf welchem Server dieser Task angelegt wurden.</p>
		</div>
	</div>
</a>

<a href="../php/jobs/create_job.php" class="create">
	<div id="rechts">
		<div id="r_header">	
			<h2>Create</h2>
		</div>
		<div id="r_img">
			<img src="../img/icons/add.png" />
		</div>
		<div id="r_text">
			<p>Hier können neue Tasks eingerichtet, oder aber Skripte und Batch Jobs erstellt werden. Zusätzlich besteht die Möglichkeit zum Anlegen einer neuen Auto WV.</p>
		</div>
	</div>
</a>


	<div id="unten">
		<div id="u_header">
			<h2>Server</h2>
			<!-- Für die Auswahl von Servern -->
			<!--<select>
				<option>Eins</option>
				<option>Zwei</option>
			</select> -->
		</div>	
		
	<a href="../php/server/fcap09.php">
		<div id="u_l">
			<div id="u_l_header">
				<h2>\\fcap09</h2>
			</div>
			<div id="u_l_img">
				<img src="../img/icons/server_128.png" />
			</div>
		</div>
	</a>
	
	<a href="../php/server/fcap17.php">
		<div id="u_r">		
			<div id="u_r_header">
				<h2>\\fcap17</h2>
			</div>				
			<div id="u_r_img">		
				<img src="../img/icons/server.png" />
			</div>		
		</div>
	</a>
		 
	<div id="unten_text">
		<p>Hier kann ein Server ausgewählt werden.</p>
	</div>
	
	
	</div>
</div>

<!-- Background picture -->
<!------------------------>
<div id="background-pic">
<img src="../img/icons/job.png" name="job_pic" class="job_pic" />
</div>	

<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>
</body>
</html>