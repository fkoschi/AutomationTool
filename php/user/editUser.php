<?php 
	include('../validate.php');
    require_once('../Controller/DBclass.php');

	header("Content-Type: text/html; charset=ISO-8859-1");	

	function getAllUser($search){
		
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$DB->set_sql("SELECT [User].UID , [User].Kuerzel , [User].Geschlecht, [User].Vorname , [User].Nachname , [Abteilung].Name , [User].Email
					  FROM [User] 
				      JOIN [Abteilung] ON  [User].AbteilungsID = [Abteilung].ID 
					  WHERE [User].Vorname LIKE '%".$search."%'
					  OR [User].Kuerzel LIKE '%".$search."%' ");
		$DB->_query();
		$result = $DB->_fetch_array(1);
		
		foreach( $result as $user ){
			echo "<tr>";
			echo "<td>". $user['UID'] ."</td>";
			echo "<td >". $user['Kuerzel'] ."</td>";
			if ( $user['Geschlecht'] == "w" ) {
				echo "<td><img src='../../img/icons/Female-icon.png' name='gender' /></td>";
			}
			else {
				echo "<td><img src='../../img/icons/Male-icon.png' name='gender' /></td>";
			}
			echo "<td>". $user['Vorname'] ."</td>";
			echo "<td>". utf8_decode($user['Nachname']) ."</td>";
			echo "<td>". $user['Name'] ."</td>";
			echo "<td>". $user['Email'] ."</td>";
			echo "<td align='center'><a class='update' href='./updateUser.php?id=".$user['UID']."'><i class='icon-edit'></i></a></td>";
			echo "<td align='center'><a class='delete' name='".$user['UID']."' ><i class='icon-minus-sign' style='color:red;'></i></a></td>";
			echo "</tr>";
		}
		$DB->_close();
	}
	function getUserCount()
	{
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$DB->set_sql("SELECT COUNT(*) AS UserCount FROM [User]");
		$DB->_query();
		$result = $DB->_fetch_array(1);	
		echo $result[0]['UserCount'];
		$DB->_close();
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Benutzer - Ändern</title>
		
<!-- Stylesheets -->
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../../css/reset.css">
<link rel="stylesheet" type="text/css" href="../../css/index.css">
<link rel="stylesheet" type="text/css" href="../../css/user.css">
<link rel="stylesheet" type="text/css" href="../../css/user/editUser.css">

</head>

<body>
<?php 
	/** 
	  * FRONTEND ANPASSEN 
	  **/
	require_once('../../editFrontend/editFrontend.php');		
?>
<script type="text/javascript">
$(function(){
	/** 
	  * DELETE USER
	  **/
	$("a[class='delete']").click(function(){	
	var user = $(this).attr('name');
	
	$.ajax({
		type: 'GET',
		url: 'deleteUser.php',
		data: { 'function' : 'delete' , 'userid' : user }, 
		success: function(data) {
			$("#success_box").html("<div id='success'><p><i class='icon-ok'> Benutzer erfolgreich gelöscht !</i></p></div>").show().fadeOut(5000,"linear");	
			setTimeout(function(){ location.reload() }, 5000);	
		},
		error: function(xhr, textStatus, errorThrown) {
			switch(xhr.status) {
				case 404:
					alert("Element not found");
					break;
				default:
					AjaxError(xhr, textStatus, errorThrown);
					break;					
			}
		}
	});
	
	});
	
});
</script>
<div id="success_box"></div>

<div id="logo" >
<img src="../../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div> <!-- logo --> 

</div> <!-- logo --> 
<div id="navi">	
<h1>Navigation</h1>
<!-- Navigationsleiste -->
<ul>
	<a href="../../index.php" ><li><i class="icon-home"></i> Home</li></a>
	<a href="../jobs.php" ><li><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="../bib.php" ><li><i class="icon-book "></i> Bibliothek</li></a>
	<a href="../user.php" ><li class="active"><i class="icon-user "></i> User</li></a>
	<a href="../settings.php" ><li><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="../monitoring.php" ><li><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> <!-- navi -->

<a class="btn" href="../../logout.php" ><i class="icon-signout"></i></a>	

<div id="content">
 	<img src="../../img/icons/User/user_group.png" class="background" />
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="GET">	
	
	<!-- Header Content -->
	<div id="content_header">
		
	<img src="../../img/icons/user_group.png" class="user_pic"/>
	<p class="header">Alle User</p>
		
	<!-- Suchfeld --> 
	<div class="input-prepend">
		<span class="add-on"><i class="icon-search"></i></span>
		<input class="span2" type="text" placeholder="Suche" id="Suche" name="Suche">
	</div>
	<!-- Anzahl User -->
	<p class="counter_user">Anzahl User: <span><?php getUserCount(); ?></span></p>
	<!-- Trennlinie --> 
	<hr class="hr_title">	
	</div>
	
	<table>
		<thead align="left">
			<tr>
				<th class="one">ID</th>
				<th class="two">Kürzel</th>
				<th class="gender"></th>
				<th class="three">Vorname</th>
				<th class="four">Nachname</th>
				<th class="five">Abteilung</th>
				<th class="six">Email</th>
				<th class="seven" align="center">Edit</th>
				<th class="eight" align="center">Delete</th>
			</tr>
		</thead>
		
		<tbody align="left">
			<?php 
				if( empty($_GET) ) {	
					getAllUser("");
				}
				else {
					getAllUser($_GET['Suche']);
				}
			?>
		</tbody>
	</table>
    
</form>

</div>	
<!-- Background picture -->
<div id="background-pic">
<img src="../../img/icons/admin.png" name="admin_pic" class="admin_pic" />
</div>	
<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>
</body>
</html>