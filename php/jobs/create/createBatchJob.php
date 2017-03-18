<?php 
	include_once('../../validate.php');
	include_once('../../active_link.php');	
	
?>
<!DOCTYPE HTML5>
<html>
	<head>
	<meta charset="utf8" />
		<title>Batch Job erstellen</title>

<!-- Stylesheets -->

<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../../../css/reset.css">
<link rel="stylesheet" type="text/css" href="../../../css/index.css">
<link rel="stylesheet" type="text/css" href="../../../css/jobs/createBatchJob.css">

<link rel="stylesheet" type="text/css" href="../../../CodeMirror/lib/codemirror.css">
<script src="../../../CodeMirror/lib/codemirror.js"></script>
<script src="../../../CodeMirror/addon/edit/matchbrackets.js"></script>
<script src="../../../CodeMirror/mode/shell/shell.js"></script>
<script src="../../../CodeMirror/mode/sql/sql.js"></script>
<script src="../../../CodeMirror/mode/vbscript/vbscript.js"></script>
<link rel="stylesheet" href="../../../CodeMirror/theme/monokai.css">
</head>

<body>
<?php 
	/** 
	  * FRONTEND ANPASSEN
	  **/
	require_once('../../../editFrontend/editFrontend.php');	 
?>
<script src="http://code.jquery.com/jquery-1.9.1.js"  type="text/javascript"></script>
<script>
$(document).ready(function() {
	var type = $("input[name='type']").val();
	switch (type)
	{
		case 'sql':
			var mime = 'text/x-sql';
			$("select option[value='.sql']").attr("selected","selected");
			break;
		case 'vbscript':
			$("select option[value='.vbs']").attr("selected","selected");
			var mime = 'text/vbscript';
			break;
		case 'shell':
			$("select option[value='.bat']").attr("selected","selected");
			var mime = 'shell';
			break;
		default:
			var mime = 'shell'
	}
	
	var editor = CodeMirror.fromTextArea(document.getElementById('code'), {
		mode: mime,
		lineNumbers : true,
		matchBrackets: true
	});
	editor.setSize(900,600);
	editor.setOption("theme","monokai"); 
	$("button").click( function() {		
		var text = editor.getValue();		
		var inhalt = $("input[name='Dateiname']").val();		
		var typ = $("select option:selected").val();		
		if ( inhalt == "" ) {
			$("#error").html("<p>Bitte Dateinamen angeben</p>").css("color","red").show().fadeOut(3000);
		} else if ( inhalt.indexOf(".") != -1 ) {
			$("#error").html("<p>Es darf kein Punkt enthalten sein.</p>").css("color","red").show().fadeOut(3000);
		} else if ( inhalt.indexOf(".bat") != -1 ) {
			$("#error").html("<p>Es wird keine Endung benötigt.</p>").css("color","red").show().fadeOut(3000);
		} else {
			$.ajax({
				type	: 'GET',
				url 	: './ajax/ajaxBatchJob.php',
				data	: { 
					'text' 		:  text ,
					'dateiname' :  inhalt,
					'dateityp'	:  typ
				} , 
				success	: function(data) {
					if ( data == 'Success' ) {
						$("#success_box").append("<div id='success'><p><i class='icon-check'></i> Datei angelegt !</p></div>").show().fadeOut(3000);
						setTimeout( function() { window.location.href = "http://localhost/Tool/php/jobs/create_job.php" }, 3000 );
					} else {
						$("#success_box").append("<div id='error'><p><i class='icon-remove'></i> Fehler </p></div>").show().fadeOut(3000);
					}
				}
			});
		}
	});

});
</script>

<div id="success_box"></div>


<!-- Lindorff Logo -->

<div id="logo" >
<img src="../../../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div>  

<div id="navi">	
<h1>Navigation</h1>

<!-- Navigationsleiste -->

<ul>
	<a href="../../../index.php" ><li><i class="icon-home"></i> Home</li></a>
	<a href="../../jobs.php" ><li class="active"><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="../../bib.php" ><li><i class="icon-book "></i> Bibliothek</li></a>
	<a href="../../user.php" ><li><i class="icon-user "></i> User</li></a>	
	<a href="../../settings.php" ><li><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="../../monitoring.php?page=today" ><li><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> 

<!-- Signout Button -->

<acronym title="Logout ?">
<a class="btn" href="../../../logout.php" ><i class="icon-signout"></i></a>	
</acronym>

<!----- Content ---->
<div id="content">		

	<div id="choose_template">
		<a href="../../bib.php">	
			<acronym title="Skript aus Bibliothek als Vorlage w&auml;hlen ?">
			<img src="../../../img/icons/64/591.png" name="choose" />
			</acronym>
		</a>
	</div>
	
	<div id="location">
		<a href="file:///C:\xampp_1.8.2\htdocs\Tool\Skripte\erstellt\"></a>
	</div>
	
	<div id="header">
		<h1>Skript erstellen </h1>
		<p>Hierf&uuml;r entweder ein Skript aus der Bibliothek als Vorlage verwenden, <br />
		oder ein neues Skript anlegen.</p><br /><br />
		<p> Dateiname: 	<br /><input type="text" name="Dateiname" placeholder="Dateinamen eingeben und Endung ausw&auml;hlen." /></p>
		<div id="select">
		<select>
			<option value=".vbs">.vbs</option>
			<option value=".bat">.bat</option>
			<option value=".sql">.sql</option>
		</select>
		</div>
		<div id="error"></div>
	</div>
	<textarea id="code"> 
<?php
	if( empty($_POST) ) {
		echo '  # Hier bitte Code eintragen' . "\n";
		echo '  :: und zum Speichern den Button klicken' . "\n\n\n";
		echo '  ________________________________________' . "\n\n";
		echo '  Speicherpfad : ' . "\n\n";
		echo '  C:\xampp_1.8.2\htdocs\Tool\Skripte\batch\erstellt' ;
	} 
	else {	
		print_r($_POST['code']);		
	}
?>
	</textarea>
<?php if ( !empty($_POST) ) { echo '<input type="hidden" value="' . $_POST['type'] . '" name="type" />'; } ?>
<div id="button">
	<button>Speichern</button>
</div>
</div>

<!-- Background picture -->

<div id="background-pic">
<img src="../../../img/icons/add.png" name="background_pic" class="background_pic" />
</div>	

<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>
</body>
</html>