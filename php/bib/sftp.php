<?php 
	include('../validate.php');
	include('../active_link.php');
	require_once('../Controller/DBclass.php');
	require_once('../../editFrontend/editFrontend.php');
	
	function get_Accounts(){
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		
		$sql = "SELECT * FROM dbo.[SFTP] ";
		$DB->set_sql($sql);	
		$DB->_query();
		
		$result = $DB->_fetch_array(1);		
		
		foreach( $result as $value ){			
			echo '<div id="account">';
			echo '<p class="title">'.$value['Name'].'</p>';
			echo '<div id="info">';
			echo '<p><i class="icon-location-arrow"> Server: </i></p>'.$value['Server'].'<br />';
			if($value['IP'] != NULL){
				echo '<p><i class="icon-globe"> IP : </i></p>'.$value['IP'].'<br />';
			}
			echo '<p><i class="icon-info"> Benutzername : </i></p>'.$value['Benutzername'].'<br />';
			echo '<p><i class="icon-key"> Passwort: </i></p>'.$value['Passwort'].'<br />';
			echo '<p><i class="icon-asterisk"> Port: </i></p>' . $value['Port'];
			echo '</div>';
			echo '</div>';
		}
		
		$DB->_close();	
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>SFTP - Accounts</title>
	<meta charset="utf-8" />
<!-- Stylesheets -->
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../../css/reset.css">
<link rel="stylesheet" type="text/css" href="../../css/index.css">
<link rel="stylesheet" type="text/css" href="../../css/bib/sftp.css">
<script type="text/javascript">
$(function(){
	// \\ //	
});
</script>
</head>

<body>

<!-- Lindorff Logo -->
<!------------------->
<div id="logo" >
<img src="../../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div> 

<!-- Navigationsleiste -->
<!----------------------->
<div id="navi">	
<h1>Navigation</h1>

<ul>
	<a href="../../index.php" ><li><i class="icon-home"></i> Home</li></a>
	<a href="../jobs.php" ><li><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="../bib.php" ><li class="active"><i class="icon-book "></i> Bibliothek</li></a>
	<a class="user" href="../user.php" ><li><i class="icon-user "></i> User</li></a>
	<a href="../settings.php" ><li><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="../monitoring.php?page=today" ><li><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> 

<acronym title="Logout ?">
<a class="btn" href="../../logout.php" ><i class="icon-signout"></i></a>	
</acronym>

<div id="content">
	<div id="content_header">		
		<img src="../../img/icons/book.png" name="" />
		<h1>(S)FTP Accounts</h1>
		<p>Zugangsdaten:</p>
	</div>
	<div id="all_accounts">
		<?php echo get_Accounts();	?>
	</div>
</div>	

<!-- Background picture -->
<div id="background-pic">
	<img src="../../img/icons/book.png" name="book_pic" class="book_pic" />
</div>

<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>

</body>
</html>