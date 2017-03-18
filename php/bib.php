<?php 
	include('validate.php');
	include('active_link.php');
	require_once('../editFrontend/editFrontend.php');
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Bibliothek</title>
	<meta charset="utf-8" />
<!-- Stylesheets -->
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/reset.css">
<link rel="stylesheet" type="text/css" href="../css/index.css">
<link rel="stylesheet" type="text/css" href="../css/bib.css">

</head>
<body>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<!--
	FRONTEND ANPASSSEN 
-->
<?php 
	if( $_SESSION['Username'] != 'admin-fkos' ) {
		echo '<script type="text/javascript" src="../editFrontend/editFrontend.js"></script>';
	}
?>

<!-- Lindorff Logo -->
<!------------------->
<div id="logo" >
<img src="../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div> 

<!-- Navigationsleiste -->
<!----------------------->
<div id="navi">	
<h1>Navigation</h1>

<ul>
	<a href="../index.php" ><li <?php if($fileName == '/index.php') echo ' class="active" ' ?>><i class="icon-home"></i> Home</li></a>
	<a href="jobs.php" ><li <?php if($fileName == '/jobs.php') echo ' class="active" ' ?>><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="bib.php" ><li <?php if($fileName == '/bib.php') echo ' class="active" ' ?>><i class="icon-book "></i> Bibliothek</li></a>
	<a href="user.php" class="user"><li <?php if($fileName == '/user.php') echo ' class="active" ' ?>><i class="icon-user "></i> User</li></a>
	<a href="settings.php" ><li <?php if($fileName == '/settings.php') echo ' class="active" ' ?>><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="monitoring.php" ><li <?php if($fileName == '/monitoring.php') echo ' class="active" ' ?>><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> 

<acronym title="Logout ?">
<a class="btn" href="../logout.php" ><i class="icon-signout"></i></a>	
</acronym>

<div id="content">
	
	<div id="content_header">
		<h1>Bibliothek</h1>
		<p><i class="icon-info"></i> Die Bibliothek stellt Skripte und Informationen bereit. <br />
			Unter Anderem kann auch der Quellcode der Skripte eingesehen werden. </p>
	</div>
	
	<div id="content_boxes">
	
	<a href="./bib/sftp.php" >
	<div id="sftp">
		<div id="box_title">
			<p>SFTP-Accounts</p>
		</div>
		<img src="../img/icons/server.png" name="box_img">
		<p>Übersicht über alle SFTP-Accounts. </p>
	</div>
	</a>
	
	<a href="./bib/batch/batch.php">
	<div id="batch">
		<div id="box_title">
			<p>Batch - Skripte</p>			
		</div>
		<img src="../img/icons/batch.png" name="box_img" >
		<p>Übersicht über Batch-Skripte.</p>
	</div>
	</a>
	
	<a href="./bib/vbs/vbscript.php">
	<div id="vbs">
		<div id="box_title">
			<p>VBSkripte</p>
		</div>
		<img src="../img/icons/file_64.png" name="box_img" >
		<p>Übersicht über VBSkripte.</p>
	</div>
	</a>
	
	<a href="./bib/sql/sql.php">
	<div id="vbs">
		<div id="box_title">
			<p>SQL - Skripte</p>
		</div>
		<img src="../img/icons/sql_file.png" name="box_img" >
		<p>Übersicht über SQL Skripte.</p>
	</div>
	</a>
	
	</div>
	
</div>	

<!-- Background picture -->
<div id="background-pic">
	<img src="../img/icons/book.png" name="book_pic" class="book_pic" />
</div>

<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>

</body>
</html>