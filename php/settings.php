<?php 
	include('validate.php');
	include('active_link.php');
	include_once('./Controller/DBclass.php');
	function getUserIcons() 
	{
		$DB = new DB;
		$DB->set_database('Lindorff_DB');
		$DB->_connect();
		$sql = "SELECT Geschlecht 
				FROM [User] 
				WHERE Kuerzel = '".$_SESSION['Username']."' ";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		
		switch ($result[0]['Geschlecht']) 
		{
			case 'm':
				echo "<input type='radio' name='user_icon' value='1' checked /><img src='../img/icons/User/user_one.png' width=64 height=64 /> ";
				echo "<input type='radio' name='user_icon' value='2' /><img src='../img/icons/User/user_two.png' width=64 height=64 /> ";
				echo "<input type='radio' name='user_icon' value='3' /><img src='../img/icons/User/user_three.png' width=64 height=64 /> ";
				echo "<input type='radio' name='user_icon' value='4' /><img src='../img/icons/User/user_four.png' width=64 height=64 /> ";
				echo "<input type='radio' name='user_icon' value='5' /><img src='../img/icons/User/user_five.png' width=64 height=64 /> ";
				echo "<input type='radio' name='user_icon' value='6' /><img src='../img/icons/User/user_six.png' width=64 height=64 /> ";
				break;
			case 'w':
				echo "<input type='radio' name='user_icon' value='11' /><img src='../img/icons/User/user_female_one.png' width=64 height=64 /> ";
				echo "<input type='radio' name='user_icon' value='12' /><img src='../img/icons/User/user_female_two.png' width=64 height=64 /> ";
				echo "<input type='radio' name='user_icon' value='13' /><img src='../img/icons/User/user_female_three.png' width=64 height=64 /> ";
				echo "<input type='radio' name='user_icon' value='14' /><img src='../img/icons/User/user_female_four.png' width=64 height=64 /> ";
				break;
			default:
				
				break;
		}
		
		$DB->_close();
	}	
?>
<!DOCTYPE HTML>
<html>
	<head>
	<meta charset="utf8" />
		<title>Einstellungen</title>

<!-- Stylesheets -->

<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/reset.css">
<link rel="stylesheet" type="text/css" href="../css/index.css">
<link rel="stylesheet" type="text/css" href="../css/settings.css">
</head>

<body>
<script src="http://code.jquery.com/jquery-1.9.1.js"  type="text/javascript"></script>
<?php 
	/** 
	  * FRONTEND ANPASSEN 
	  **/
	require_once('../editFrontend/editFrontend.php');	
?>
<script>
$(document).ready(function() {
	/** 
	  * NEUES PASSWORT
	  **/
	  
	$("a[id='save_new_pw']").click( function(event) {
		event.preventDefault();
		var oldPW = $("input[name='old_pw']").val();
		var newPW = $("input[name='new_pw']").val();
		var username = $("span.username").text();	
				
		if( oldPW == "" || newPW == "" ) 
		{
			$("#error").text("Bitte beide Werte eingeben.").show().fadeOut(2500);
		} 
		else 
		{
			$.ajax({
				type	: 'POST',								
				url		: './settings/saveSettings.php',
				data 	: {
					'function'	: 'set_new_pw',
					'oldPW' 	: oldPW,
					'newPW'		: newPW,
					'Username'	: username
				},	
										
				success	: function(data) {										
					var jsonData = $.parseJSON(data);
										
					switch(jsonData) 
					{	
						case "Kein Treffer": 
							$("#not_found").text("Altes Passwort stimmt nicht überein.").show().fadeOut(3500);
							break;
						case "Mehr als ein Treffer":
							$("#error").text("Bitte den Administrator kontaktieren.").show().fadeOut(3500);
							break;
						default:
							$("#success").text("Neues Passwort erfolgreich gespeichert.").show().fadeOut(5000);
							setTimeout( function() { window.location.reload() } , 5000 );
							break;
					}
					
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					console.log(textStatus, errorThrown);
				}				
			});
		}
	});
	/** 
	  * Passwort Stärke
	  **/
	$("input[name='new_pw']").keyup(function(){
		// Anpassung der Grafik unmittelbar nach Eingabe durch den Benutzer
		var value = $(this).val();
		//var array = new array();

		var passwordLength = value.length;
		var passwordStrengthRegexDigits = /([0-9]){2,}/gm;  // Ziffer zwischen 0-9		
		var passwordStrengthRegexStrong = /((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20})/gm; 
		var passwordSonderzeichen = /(\#|\+|\^|\!|\[|\]|\{|\}|\&|\<|\>|\_)/gm;
		 /* 10 to 20 character string with at least one
		  * upper case letter, one lower case letter,
		  * and one digit (useful for passwords).
		 **/
		var PWdigits = passwordStrengthRegexDigits.test(value);
		var PWsonderzeichen = passwordSonderzeichen.test(value);
		var PWstrong = passwordStrengthRegexStrong.test(value);
			
		var meter = $("meter");
		
		var veryweak = "sehr schwach";
		var weak = "schwach";
		var middle = "mittel";
		var strong = "stark";
		var heavy = "sehr stark";
		
		if( passwordLength == 0)
		{
			meter.val(0);
			$("#info").text("");
		}
		else if( passwordLength > 0 && passwordLength < 5 )
		{	
			meter.val(2);
			$("#info").text(veryweak).css("color","red");
		}		
		else if ( passwordLength >= 5 && passwordLength < 8 )
		{			
			if( PWdigits == true && PWsonderzeichen == false )
			{
				meter.val(4);
				$("#info").text(weak).css("color","red");	
			}
			else if ( PWdigits == true && PWsonderzeichen == true)
			{	
				meter.val(6);
				$("#info").text(middle).css("color","black");	
			}			
		}
		else if ( passwordLength >= 8 )
		{
			if ( PWstrong == true && PWsonderzeichen == false )
			{
				meter.val(8);
				$("#info").text(strong).css("color","green");	
			}
			else if ( PWdigits == true && PWsonderzeichen == false )
			{
				meter.val(6);
				$("#info").text(middle).css("color", "black");
			}
			else if ( PWstrong == true && PWsonderzeichen == true )
			{
				meter.val(10);
				$("#info").text(heavy).css("color","green");	
			}			
		}		

	});
	/** 
	  * FEHLER MELDEN
	  **/
	 $("span.melden").click( function() {
		$("span.email").toggle();
	 });

	$("button[name='Save']").click(function(){
	 	var icon = $("input[name='user_icon']:radio:checked").val();	 	
	 	$.ajax({
	 		type	: 'POST',
	 		url 	: './settings/saveUserIcon.php',
	 		data 	: {
	 			'icon' : icon
	 		},
	 		success : function(data) 
	 		{
	 			location.reload();
	 		}
	 	});
	});
});
</script>

<!-- Lindorff Logo -->

<div id="logo" >
<img src="../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div>  

<div id="navi">	
<h1>Navigation</h1>

<!-- Navigationsleiste -->

<ul>
	<a href="../index.php" ><li <?php if($fileName == '/index.php') echo ' class="active" ' ?>><i class="icon-home"></i> Home</li></a>
	<a href="jobs.php" ><li <?php if($fileName == '/jobs.php') echo ' class="active" ' ?>><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="bib.php" class="bib"><li <?php if($fileName == '/bib.php') echo ' class="active" ' ?>><i class="icon-book "></i> Bibliothek</li></a>
	<a href="user.php" class="user"><li <?php if($fileName == '/user.php') echo ' class="active" ' ?>><i class="icon-user "></i> User</li></a>	
	<a href="settings.php" ><li <?php if($fileName == '/settings.php') echo ' class="active" ' ?>><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="monitoring.php?page=today" ><li <?php if($fileName == '/monitoring.php') echo ' class="active" ' ?>><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> 

<!-- Signout Button -->

<acronym title="Logout ?">
<a class="btn" href="../logout.php" ><i class="icon-signout"></i></a>	
</acronym>

<!--- Content -->
<div id="content">		
	<div id="content_header">		
		<h1>Einstellungen</h1>	
	<?php 
		echo '<p>Hallo <span class="username">' . $_SESSION['Username'] . '</span><br/><br/>Hier können Sie erkannte Fehler melden, und ein neues Passwort setzen.</p>';
	?>
	</div>
	<div id="settings">
		<ul>
			<li>Fehler <span class="melden">melden !</span>
				<br /><span class="email">Bitte eine Email an <a href="mailto:Felix.Koschmidder@lindorff.com?subject=Report Bug InTime Tool"><i class='icon-envelope'></i></a> Felix.Koschmidder@lindorff.com </span>
			</li>
			<div id="bug">
				<img src="../img/icons/giff/crawlingbug.gif" name="bug" />
			</div>
			<li>Passwort neu setzen ?
				<div id="li_box">
					Altes Passwort: <input type="password" name="old_pw" value="" />
					<div id="not_found"></div>
					Neues Passwort: <input type="password" name="new_pw" value="" />					
					<div id="error"></div>
					<div id="success"></div>
					<meter min="0" max="10" value="0"></meter>					
					<div id="info"></div>					
					<a class="btn" href="" id="save_new_pw"><i class="icon-save"></i> Save </a>					
				</div>
			</li>
		</ul>

		<div id="user_icon">
			<h3>Icon: </h3>
			<?php
				getUserIcons();	// alle verfügbaren Icons einblenden
			?>
			<br /><br />
			<button name="Save">Übernehmen</button>
		</div>
	</div>
	
</div>

<!-- Background picture -->

<div id="background-pic">
<img src="../img/icons/settings.png" name="settings_pic" class="settings_pic" />
</div>	

<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>
</body>
</html>