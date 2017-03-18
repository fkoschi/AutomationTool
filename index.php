<?php 
	include('php/validate.php');
	include('php/active_link.php');	
				
?>
<!DOCTYPE HTML5>
<html>
<head>
	<title>Home - Bereich</title>
	

<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/index.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/home.css" media="screen">
</head>
<body>	
<script src="http://code.jquery.com/jquery-1.9.1.js"  type="text/javascript" ></script>
<?php 
	/** 
	  * FRONTEND ANPASSEN 
	  **/
	require_once('editFrontend/editFrontend.php');	
?>

<!-- Lindorff Logo -->
<div id="logo" >
	<img src="img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div> 

<!-- Navigationsleiste -->
<div id="navi">	

<h1>Navigation</h1>
		
<ul>
	<a href="index.php"><li <?php if($fileName == '/index.php') echo ' class="active" ' ?>><i class="icon-home"></i> Home</li></a>
	<a href="php/jobs.php" ><li <?php if($fileName == '/jobs.php') echo ' class="active" ' ?>><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="php/bib.php" class="bib"><li <?php if($fileName == '/bib.php') echo ' class="active" ' ?>><i class="icon-book "></i> Bibliothek</li></a>
	<a href="php/user.php" class="user" ><li <?php if($fileName == '/user.php') echo ' class="active" ' ?>><i class="icon-user "></i> User</li></a>	
	<a href="php/settings.php" ><li <?php if($fileName == '/settings.php') echo ' class="active" ' ?>><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="php/monitoring.php?page=today" class="monitoring" ><li <?php if($fileName == '/monitoring.php') echo ' class="active" ' ?>><i class="icon-code "></i> Monitoring</li></a>
</ul>

<!-- Kalender --> 
<?php
	$now = getdate(time());
	$time = mktime(0,0,0, $now['mon'], 1, $now['year']);	
	$date = getdate($time);
	$dayTotal = cal_days_in_month(0, $date['mon'], $date['year']);	
	
	print '<div id="kalender"><table><tr><td colspan="7"><strong>' . $date['month'] . '</strong></td></tr>';
	for ($i = 0; $i < 6; $i++) {
		print '<tr>';
		for ($j = 1; $j <= 7; $j++) {
			$dayNum = $j + $i*7 - $date['wday'];
	
			print '<td';
			if ($dayNum > 0 && $dayNum <= $dayTotal) {
				print ($dayNum == $now['mday']) ? ' style="border: 1px solid grey; color: #3381FF;">' : '>';
				print $dayNum;
			}
			else {
	
				print '>';
			}
			print '</td>';
		}
		print '</tr>';
		if ($dayNum >= $dayTotal && $i != 6)
			break;
	}
	print '</table></div>';
?>

</div> 

<!-- Logout Button -->
<!------------------->
<acronym title="Logout ?">
<a class="btn" href="logout.php" ><i class="icon-signout"></i></a>		
</acronym>

<!-- Content Bereich -->
<!--------------------->
<div id="content">

	<!-- Überschrift -->
	<div id="title">
		<h1> Kontrollzentrale</h1>		
	</div> 

	
<a href="php/jobs.php" >
	<div id="o_links">
		
		<div id="h_o_links">
			<h2>Tasks</h2>
		</div>

		<div id="img_o_links">
			<img src="img/icons/job.png" />
		</div>		
	
		<div id="txt_o_links">
			<p>Hier geht es zur Überwachung und Bearbeitung der automatisch laufenden Tasks</p>
		</div>
		
	</div>
</a>	
	
<a href="php/bib.php" class="bib">	
	<div id="o_rechts">
		
		<div id="h_o_rechts">
			<h2>Bibliothek</h2>
		</div>
		
		<div id="img_o_rechts">
			<img src="img/icons/book.png" />
		</div>
	
		<div id="txt_o_rechts">
			<p>Hier geht es zur Bibliothek. Diese soll beim Erstellen neuer Jobs helfen.</p>
		</div>
		
	</div>
</a>
	
<a href="php/monitoring.php?page=today">			
	<div id="u_links">
		
		<div id="h_u_links">
			<h2>Monitoring</h2>
		</div>		
	
		<div id="img_u_links">
			<img src="img/icons/128/515.png" />
		</div>	
	
	<div id="txt_u_links">
			<p>Dieser Bereich bietet eine Palette an Charts zur Überwachung automatischer Tasks.</p>
		</div>
		
	</div>
</a>	
	
<a href="php/settings.php">	
	<div id="u_rechts">
	
		<div id="h_u_rechts">
			<h2>Settings</h2>
		</div>	
		
		<div id="img_u_rechts">		
			<img src="img/icons/settings.png" />
		</div>

		<div id="txt_u_rechts">
			<p>Hier können Einstellungen vorgenommen werden.</p>
		</div>
		
	</div>
</a>
	
</div>


<!-- Background picture -->
<!------------------------>
<div id="background-pic">
<img src="img/icons/home.png" name="home_pic" class="home_pic" />
</div>

<!-- Footer -->
<!------------>
<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>
 
</body>
</html>