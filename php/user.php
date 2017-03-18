<?php 
	include('validate.php');
	include('active_link.php');
?>
<!DOCTYPE HTML5>
<html>
	<head>
		<title>Benutzer</title>

<!-- Stylesheets -->
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/reset.css">
<link rel="stylesheet" type="text/css" href="../css/index.css">
<link rel="stylesheet" type="text/css" href="../css/user.css">
<style type="text/css">
#user_logo {
	position: absolute;
	z-index: 2;
	top: 90px !important;
	right: 40px !important;	
}
</style>
</head>

<body>
	
<?php 

	/** 
	  * FRONTEND ANPASSEN 
	  **/
	require_once('../editFrontend/editFrontend.php');	

	
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
	<a href="bib.php" ><li <?php if($fileName == '/bib.php') echo ' class="active" ' ?>><i class="icon-book "></i> Bibliothek</li></a>
	<a href="user.php" ><li <?php if($fileName == '/user.php') echo ' class="active" ' ?>><i class="icon-user "></i> User</li></a>
	<a href="settings.php" ><li <?php if($fileName == '/settings.php') echo ' class="active" ' ?>><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="monitoring.php" ><li <?php if($fileName == '/monitoring.php') echo ' class="active" ' ?>><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> <!-- navi -->

<a class="btn" href="../logout.php" ><i class="icon-signout"></i></a>	

<div id="content">
    
    <div id="content_header">
	   <img src="../img/icons/admin.png" class="header_pic" />
       <p class="header">Benutzerverwaltung</p><br /><br />
       <p class="info">Hier besteht zum Einen die Möglichkeit neue Benutzer anzulegen.<br /><br /> Zum Anderen können bereits angelegte Benutzer verwaltet werden.</p>
	</div>	
    <br /><br />    
 
    <a href="./user/createUser.php">
	<div id="create_user">        
		<p><img src="../img/icons/add_user.png" class="create_user" /></p>
        <p>Einen neuen User anlegen ?</p>		
    </div>
    </a>
	<a href="./user/editUser.php">
    <div id="edit_user">        
		<p><img src="../img/icons/edit_user.png" class="edit_user" /></p>
        <p>Angelegte User bearbeiten.</p>		
	</div>     
    </a>
     
</div>	
<!-- Background picture -->
<div id="background-pic">
<img src="../img/icons/admin.png" name="admin_pic" class="admin_pic" />
</div>	
<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>
</body>
</html>