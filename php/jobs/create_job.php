<?php 
	include('../validate.php');
	include('../active_link.php');
?>
<!DOCTYPE HTML5>
<html>
<head>
	<title>Jobs</title>

<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../../css/reset.css" media="screen">
<link rel="stylesheet" type="text/css" href="../../css/index.css" media="screen">
<link rel="stylesheet" type="text/css" href="../../css/home.css" media="screen">
<link rel="stylesheet" type="text/css" href="../../css/create_job.css" media="screen">

</head>
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

<!-- Navigationsleiste -->
<!----------------------->
<div id="navi">	

<h1>Navigation</h1>
		
<ul>
	<a href="../../index.php"><li><i class="icon-home"></i> Home</li></a>
	<a href="../../php/jobs.php" ><li class="active"><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="../../php/bib.php" ><li><i class="icon-book "></i> Bibliothek</li></a>
	<a href="../../php/user.php" ><li><i class="icon-user "></i> User</li></a>	
	<a href="../../php/settings.php" ><li><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="../../php/monitoring.php?page=today" ><li><i class="icon-code "></i> Monitoring</li></a>
</ul>

</div> 

<!-- Logout Button -->
<!------------------->
<acronym title="Logout ?">
<a class="btn" href="../../logout.php" ><i class="icon-signout"></i></a>		
</acronym>

<!-- Content Bereich -->
<!--------------------->
<div id="content">

	<!-- Überschrift -->
	<div id="title">
		<h1> Create </h1>
		<p>Bitte wählen Sie eine der Optionen.</p>
	</div> 

	
<a href="./create/autoWV.php">
	<div id="o_links">
		
		<div id="h_o_links">
			<h2>Auto WV</h2>
		</div>

		<div id="img_o_links">
			<img src="../../img/icons/WV.png" />
		</div>		
	
		<div id="txt_o_links">
			<p>Eine Auto-Widervorlage einrichten.</p>
		</div>
		
	</div>
</a>	
	
<a href="">	
	<div id="o_rechts">
		
		<div id="h_o_rechts">
			<h2>SQL - Ikaros</h2>
		</div>
		
		<div id="img_o_rechts">
			<img src="../../img/icons/sql.png" />
		</div>
	
		<div id="txt_o_rechts">
			<p>Hier besteht die Möglichkeit entweder ein SQL-Skript zu erstellen, oder aber auch Ikaros-Reporte zu definieren.</p>
		</div>
		
	</div>
</a>
	
<a href="../jobs/create/createBatchJob.php">			
	<div id="u_links">
		
		<div id="h_u_links">
			<h2>Skripte</h2>
		</div>		
	
		<div id="img_u_links">
			<img src="../../img/icons/batch.png" />
		</div>	
	
	<div id="txt_u_links">
			<p>Legen Sie unter dieser Option neue Skripte an. ( Batch, SQL, VBSkripte )</p>
		</div>
		
	</div>
</a>	
	
<a href="../jobs/create/createAutoJob.php">	
	<div id="u_rechts">
	
		<div id="h_u_rechts">
			<h2>Automatischer Task</h2>
		</div>	
		
		<div id="img_u_rechts">		
			<img src="../../img/icons/schedule.png" />
		</div>

		<div id="txt_u_rechts">
			<p>Hier kann ein automatischer Task gesetzt werden.</p>
		</div>
		
	</div>
</a>
	
</div>


<!-- Background picture -->
<!------------------------>
<div id="background-pic">
<img src="../../img/icons/job.png" name="job_pic" class="job_pic" />
</div>

<!-- Footer -->
<!------------>
<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>
 
</body>
</html>