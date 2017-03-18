<?php 
	include('../validate.php');
    require_once('../Controller/DBclass.php');						

	function getAbteilung() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$DB->set_sql("SELECT * FROM [Abteilung]");
		$DB->_query();
		$result = $DB->_fetch_array(2);
		
		echo "<select id='Abteilung' name='Abteilung'>";
		foreach($result as $value) {
			echo "<option value=" . $value['ID'] . ">" . $value['Name'] . "</option>";
		}		
		echo "</select>";
		$DB->_close();
	}	
	
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Benutzer - Erstellen</title>

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
	  * EINGABEN ÜBERPRÜFEN 
	  * 
	  **/
$("input[name='Speichern']").click(function() {
	var createAutoWV = $("input[name='createAutoWV']");
	var createAutoJob = $("input[name='createAutoJob']");
	var createSQLJob = $("input[name='createSQLJob']");
	var createBatchJob = $("input[name='createBatchJob']");
	var editUser = $("input[name='editUser']");
	var arrRechte = new Array(createAutoWV, createAutoJob, createSQLJob, createBatchJob, editUser);
	
		$.each(arrRechte, function(key,value){
			if( $(this).is(':checked') ){
				$(this).val("1");
			}
		});	
	
	var Vorname = $("input[name='Vorname']");
	var Nachname = $("input[name='Nachname']");
	var Passwort = $("input[name='Passwort']");
	var Kuerzel = $("input[name='Kürzel']");
	var Email = $("input[name='Email']");
	var counter = 0;
	var arr = new Array( Vorname , Nachname , Passwort , Kuerzel , Email  );
		
		$.each(arr, function(key,value){
            if( $(this).val() == "" ){
                $(this).css("border","1px solid red").show("slow");
                counter = counter + 1;                               
            } else {
				var at = Email.val().indexOf("@");				
				$(this).css("border","1px solid grey");
			}
			
		});
	
	var Vorname = $("input[name='Vorname']").val();
	var Nachname = $("input[name='Nachname']").val();
	var Passwort = $("input[name='Passwort']").val();
	var Kuerzel = $("input[name='Kürzel']").val();
	var Email = $("input[name='Email']").val();
	var createAutoWV = $("input[name='createAutoWV']").val();
	var createAutoJob = $("input[name='createAutoJob']").val();
	var createSQLJob = $("input[name='createSQLJob']").val();
	var createBatchJob = $("input[name='createBatchJob']").val();
	var editUser = $("input[name='editUser']").val();
	var Abteilung = $("select option:selected").val();
	var gender = $("input:radio[name=gender]:checked").val();
	/** 
	  * WENN PFLICHTFELDER AUSGEFÜLLT -> AJAX CALL 
	  **/ 
		if( counter == 0 && gender ){
			$.ajax({
				type: 'POST',
				url: 'saveUser.php',
				data: {
					'function'		: 'create',
					'Vorname'  		: Vorname,
					'Nachname' 		: Nachname,
					'Passwort' 		: Passwort,
					'Kuerzel' 		: Kuerzel,
					'Email'	   		: Email,
					'createAutoWV'  : createAutoWV,
					'createAutoJob' : createAutoJob,
					'createSQLJob'  : createSQLJob,
					'createBatchJob': createBatchJob,
					'editUser'		: editUser,
					'Abteilung' 	: Abteilung,
					'Geschlecht'	: gender
				},
				success: function(data) {										
					$("#success_box").html("<div id='success'><p><i class='icon-ok'> Benutzer erfolgreich angelegt !</i></p></div>").show().fadeOut(5000);
					setTimeout(function(){ window.location.href = "http://localhost/Tool/php/user/editUser.php" }, 4000);			
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
		}
		else {
			alert("Bitte ein Geschlecht wählen!");
		}	
	});
});
</script>
<div id="success_box"></div>

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
	<a href="../monitoring.php?page=today" ><li><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> <!-- navi -->

<a class="btn" href="../../logout.php" ><i class="icon-signout"></i></a>	

<div id="content">
    
    <div id="content_header" >
        <img src="../../img/icons/add_user.png" />
        <p>Einen neuen Benutzer anlegen: </p><br />
		<p class="info">Alle linken Felder sind Pflichtfelder. </p>
		<p class="info">Email-Adressen für die jeweiligen Abteilungen, sind in der Datenbank hinterlegt.</p>
		<p class="info">Ein Passwort wird generiert und dem Benutzer zugesendet.</p>
    </div>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">	
  
  <div id="links">
  <p class="box_info">Infos:</p><br /><br />  
    
	<div id="gender">
		<input type="radio" name="gender" value="w" /> <img src="../../img/icons/Female-icon.png" name="gender" /><span></span>
		<input type="radio" name="gender" value="m" /> <img src="../../img/icons/Male-icon.png" name="gender" /> 
	</div>
	
	<div class="input-prepend">
        <span class="add-on"><i class="icon-user"></i></span>
        <input class="span2" type="text" placeholder="Vorname" id="Vorname" name="Vorname" value=""/>
    </div>
    
    <div class="input-prepend">
        <span class="add-on"><i class="icon-user"></i></span>
        <input class="span2" type="text" placeholder="Nachname" id="Nachname" name="Nachname" value=""/>
    </div>   
	<?php 
		getAbteilung();		
	?>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-tag"></i></span>
        <input class="span2" type="text" placeholder="Kürzel" id="Kürzel" name="Kürzel" value="" />
    </div>
           
    <div class="input-prepend">
        <span class="add-on"><i class="icon-envelope"></i></span>
        <input class="span2" type="email" placeholder="Email" id="Email" name="Email" value="" />
    </div>
  </div>
  
  <div id="rechts">
	<p class="box_info">Rechte: </p><br /><br />
		
		<ul class="user_groups">
			<a href="?set_groups=true" ><li <?php if ( !empty($_GET) ) { if ( $_GET['set_groups'] == 'true' ) { echo "class='active' 	"; } }	?>><i class="icon-group"></i> Benutzergruppe</li></a>
			<a href="?set_groups=false"><li <?php if ( !empty($_GET) ) { if ( $_GET['set_groups'] == 'false' ) { echo "class='active' 	"; } }	?>><i class="icon-user-md"></i> Zusätzliche Rechte</li></a>
		<ul>
		
		<!-- TABS ZUM SETZEN VON GRUPPEN / RECHTEN -->
		<?php 				
		
		function setAdditionalRights() {
			echo '<div id="rechte">';
			echo '	<p><input type="checkbox" name="createAutoWV" value="0" />create Auto WV</p>';
			echo '	<p><input type="checkbox" name="createAutoJob" value="0" />create Auto Job</p>';
			echo '	<p><input type="checkbox" name="createSQLJob" value="0" />create SQL Job</p>';
			echo '	<p><input type="checkbox" name="createBatchJob" value="0" />create Batch Job</p>';
			echo ' 	<p><input type="checkbox" name="editUser" value="0" />edit User</p>';
			echo '</div>';			
		}				
		function setGroups() {
			echo '<select name="group" size="6">';				
			echo '	<option>Admin</option>';
			echo '	<option>Benutzer</option>';
			echo ' 	<option>Benutzer1</option>';
			echo '	<option>Benutzer2</option>';
			echo '	<option>Nobody</option>';
			echo '</select>';
		}
		if( !empty($_GET) ) {
			
			if( $_GET['set_groups'] == 'true'  ) {
				setGroups();
			}
			else if ( $_GET['set_groups'] == 'false' ) {								
				setAdditionalRights();
			}
		}
		else {
			//setAdditionalRights();	
			echo '<p class="option">Wählen Sie eine Option aus.</p>';
		}
		?>		
		
    <img src="../../img/icons/key_128.png" />
  </div>
  <span class="error"></span>
  <input type="button" value="Speichern" name="Speichern" />

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