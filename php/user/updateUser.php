<?php 
	include('../validate.php');
    require_once('../Controller/DBclass.php');
    
	function getUser() {
		
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT [User].* , [Rechte].*
				FROM [User] 
				JOIN [Rechte] ON [Rechte].ID = [User].UID 
				JOIN [Abteilung] ON [Abteilung].ID = [User].AbteilungsID
				WHERE UID = ".$_GET['id']." ";		
		$DB->set_sql($sql);		
		$DB->_query();
		$result = $DB->_fetch_array(1);
		return $result;				
	}
		
	function getAbteilung() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$DB->set_sql("SELECT ID, Name FROM [Abteilung]");
		$DB->_query();
		$result = $DB->_fetch_array(2);
		
		echo "<select id='Abteilung' name='Abteilung'>";
		$return = getUser();		
		foreach($result as $value) {			
			if( $value['ID'] == $return[0]['AbteilungsID'] )
				echo "<option selected value=" . $value['ID'] . ">" . $value['Name'] . "</option>";
			else 
				echo "<option value=" . $value['ID'] . ">" . $value['Name'] . "</option>";
		}		
		echo "</select>";
		$DB->_close();
	}
	
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Benutzer - Update</title>

<!-- Stylesheets -->
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../../css/reset.css">
<link rel="stylesheet" type="text/css" href="../../css/index.css">
<link rel="stylesheet" type="text/css" href="../../css/user.css">
<link rel="stylesheet" type="text/css" href="../../css/user/createUser.css">

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
	  * RECHTE SETZEN
	  **/
	$("input[name='setRechte']").click(function() {
		var createAutoWV = $("input[name='createAutoWV']");
		var createAutoJob = $("input[name='createAutoJob']");
		var createSQLJob = $("input[name='createSQLJob']");
		var createBatchJob = $("input[name='createBatchJob']");
		var editUser = $("input[name='editUser']");
		var arrRechte = new Array(createAutoWV, createAutoJob, createSQLJob, createBatchJob, editUser);
		
			$.each(arrRechte, function(key,value){
				if( $(this).is(':checked') ){
					$(this).val("1");
				} else {
					$(this).val("0");
				}
			});
	
	
		var id = $("input[name='ID']").val();
		var createAutoWV = $("input[name='createAutoWV']").val();
		var createAutoJob = $("input[name='createAutoJob']").val();
		var createSQLJob = $("input[name='createSQLJob']").val();
		var createBatchJob = $("input[name='createBatchJob']").val();
		var editUser = $("input[name='editUser']").val();
		var Abteilung = $("select option:selected").val();
	
		
			$.ajax({
				type: 'POST',
				url: 'saveUser.php',
				data: {
					'function' 		: 'updateRechte',
					'ID'			: id,					
					'createAutoWV'  : createAutoWV,
					'createAutoJob' : createAutoJob,
					'createSQLJob'  : createSQLJob,
					'createBatchJob': createBatchJob,
					'editUser'		: editUser					
				},
				success: function(data) {					
					//alert(data);
					$("#success_box").html("<div id='success'><p><i class='icon-ok'> Änderungen übernommen !</i></p></div>").show().fadeOut(5000);
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
	/**
	  * INFOS SETZEN 
	  **/
	$("input[name='setInfos']").click(function(){
		var id = $("input[name='ID']").val();
		var Vorname = $("input[name='Vorname']").val();
		var Abteilung = $("select[name='Abteilung'] :checked").val();		
		var Nachname = $("input[name='Nachname']").val();		
		var Kuerzel = $("input[name='Kürzel']").val();
		var Email = $("input[name='Email']").val();		
		$.ajax({
			type: 'POST',
			url: 'saveUser.php',
				data: {
					'function' 		: 'updateInfos',
					'ID'			: id,					
					'Vorname'  		: Vorname,
					'Nachname' 		: Nachname,	
					'Abteilung'		: Abteilung,
					'Kuerzel'		: Kuerzel,
					'Email'			: Email					
				},
				success: function(data) {							
					$("#success_box").html("<div id='success'><p><i class='icon-ok'> Änderungen übernommen !</i></p></div>").show().fadeOut(5000);
					setTimeout(function(){ location.reload() }, 3000);			
					
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
	<div id="time">
<?php 
	echo "<i class='icon-time'><b>  " . date("d.m.Y") . "</b></i>";
?>
</div>
<div id="logo" >
<img src="../../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
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
</div> 

<a class="btn" href="../../logout.php" ><i class="icon-signout"></i></a>	

<div id="content">
    
    <div id="content_header" >
        
        <?php $return = getUser();			
			echo '<p><span style="font-size:32px; color:navy;"> '.$return[0]['Kuerzel'].' </span> bearbeiten:</p><br />';
		?>
		<p class="info">Hier können Änderungen der Daten für den gewählten Benutzer angepasst werden.<br />Die Werte in den Input Feldern sind die in der Datenbank gespeicherten Werte.</p>
    </div>
	
  <div id="links">
  <p class="box_info">Infos:</p><br /><br />  
    <div class="input-prepend">
        <span class="add-on"><i class="icon-user"></i></span>
		<?php getUser();
		echo '<input type="hidden" name="ID" value='.$_GET['id'].' />';
        echo '<input class="span2" type="text" placeholder='.$return[0]['Vorname'].' id="Vorname" name="Vorname" value=""/>';
		?>
	</div>
    
    <div class="input-prepend">
        <span class="add-on"><i class="icon-user"></i></span>
        <?php getUser();
        echo '<input class="span2" type="text" placeholder='.utf8_decode($return[0]['Nachname']).' id="Nachname" name="Nachname" value=""/>';
		?>
    </div>
   <!--
	PASSWORT AUTOMATISCH SETZEN
    <div class="input-prepend">
        <span class="add-on"><i class="icon-key"></i></span>		
        <input class="span2" type="text" placeholder="Passwort" id="Passwort" name="Passwort" value="" />
    </div>
	-->
	<?php 
		getAbteilung();		
	?>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-tag"></i></span>
		<?php getUser();
        echo '<input class="span2" type="text" placeholder='.$return[0]['Kuerzel'].' id="Kürzel" name="Kürzel" value=""/>';
		?>
    </div>
           
    <div class="input-prepend">
        <span class="add-on"><i class="icon-envelope"></i></span>
        <?php getUser();
        echo '<input class="span2" type="text" placeholder='.$return[0]['Email'].' id="Email" name="Email" value=""/>';
		?>
    </div>
	
	<div id="gender">
		<?php
			getUser();
			if ( $return[0]['Geschlecht'] == "w" ) {
				echo '<img src="../../img/icons/Female-icon.png" name="gender_update" />';
			}
			else {
				echo '<img src="../../img/icons/Male-icon.png" name="gender_update" />';
			}
		?>
	</div>
	
  </div>
  
  <div id="rechts">
  <p class="box_info">Rechte: </p><br /><br />
    <div id="rechte">	
	<?php 
		$return = getUser();
		
		if($return[0]['createAutoWV'] == 1)
			echo '<p><input type="checkbox" checked="checked" name="createAutoWV" value='.$return[0]['createAutoWV'].'  />create Auto WV</p>';
		else 
			echo '<p><input type="checkbox" name="createAutoWV" value='.$return[0]['createAutoWV'].'  />create Auto WV</p>';
		if( $return[0]['createAutoJobs'] == 1)
			echo '<p><input type="checkbox" checked="checked" name="createAutoJob" value='.$return[0]['createAutoJobs'].' />create Auto Job</p>';
		else 
			echo '<p><input type="checkbox" name="createAutoJob" value='.$return[0]['createAutoJobs'].' />create Auto Job</p>';
		if( $return[0]['createSQLJobs'] == 1) 
			echo '<p><input type="checkbox" checked="checked" name="createSQLJob" value='.$return[0]['createSQLJobs'].' />create SQL Job</p>';
		else 
			echo '<p><input type="checkbox" name="createSQLJob" value='.$return[0]['createSQLJobs'].' />create SQL Job</p>';
		if($return[0]['createBatchJobs'] == 1)
			echo '<p><input type="checkbox" checked="checked" name="createBatchJob" value='.$return[0]['createBatchJobs'].' />create Batch Job</p>';
		else 
			echo '<p><input type="checkbox" name="createBatchJob" value='.$return[0]['createBatchJobs'].' />create Batch Job</p>';
		if( $return[0]['editUser'] == 1)
			echo '<p><input type="checkbox" name="editUser" value='.$return[0]['editUser'].' checked="checked" />edit User</p>';		
		else
			echo '<p><input type="checkbox" name="editUser" value='.$return[0]['editUser'].' />edit User</p>';		
	?>
    </div>
    <img src="../../img/icons/key_128.png" />
  </div>
  <span class="error"></span>
  <input type="button" value="Infos speichern" name="setInfos" />
  <input type="button" value="Rechte speichern" name="setRechte" />	


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