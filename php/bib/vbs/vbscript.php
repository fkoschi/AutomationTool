<?php 
	include('../../validate.php');
	include('../../active_link.php');
	require_once('../../Controller/DBclass.php');
		
	function getVBSJobs() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		
		$sql = "SELECT ID, Name, Von, Pfad, CONVERT(nchar, createdAT ) as date FROM [VBSJob]";
		$DB->set_sql($sql);	
		$DB->_query();		
		$result = $DB->_fetch_array(1);		
				
		foreach( $result as $value ){			
			echo '<div id="content_box">';
			echo '<div id="title">';
			echo '<i class="icon-bookmark"></i>';
			echo '<a href="./vbscript.php?id='.$value['ID'].'">';
			echo '<p>'. $value['Name'] .'</p>';
			echo '</a>';
			echo '</div>';
			echo '<div id="info">';
			echo '<p> von: </i></p>' .$value['Von']. '<br />';
			echo '<p> erstellt am: </i></p>' . $value['date']. ' ';
			echo '</div>';			
			echo '<i class="icon-remove-sign" name="'.$value['ID'].'" id="'.$value['Pfad'].'"></i>';
			echo '<img src="../../../img/icons/batch.png" name="box_background_img">';
			echo '</div>';			
		}		
		$DB->_close();
	}
	function getVBSInfos() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();		
		$sql = "SELECT Name, Von, CONVERT(nchar, createdAT ) as date, Beschreibung, Pfad
				FROM [VBSJob]
				WHERE ID = ".$_GET['id']." ";
		$DB->set_sql($sql);	
		$DB->_query();		
		$result = $DB->_fetch_array(1);			
		echo '<div id="remove-icon"><i class="icon-remove-sign" id="'.$_GET['id'].'" name="'.$result[0]['Pfad'].'"></i></div>';
		echo '<div id="export"><img src="../../../img/icons/64/969.png" name="export" title="Skript weiterverarbeiten ?!" /></div>';
		echo '<div id="title"><div id="left_title"><h1>'.$result[0]['Name'].'</h1><a class="file" href="file:///'.$result[0]['Pfad'].'"></a></div>';
		echo '<div id="right_title">Erstellt von: ';
		echo '<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input class="span2" type="text" disabled="disabled" value="'.$result[0]['Von'].'"/></div></div></div>';	
		echo '<div id="description"><h2> Beschreibung: </h2><img src="../../../img/icons/quote64.png" name="quote" /><div id="description_content">'.$result[0]['Beschreibung'].'</div></div>	';
		echo '<form action="../../jobs/create/createBatchJob.php" method="POST" >';
		echo '<input type="hidden" name="type" value="vbscript" />';
		echo '<div id="textarea"><h2>Skript: </h2><textarea id="code" name="code">';
		$pfad = $result[0]['Pfad'];	
		$handle = fopen( $pfad, "r" );	
		while(!feof($handle)){
			echo htmlentities(fgets($handle), ENT_HTML5, 'ISO-8859-1');
		}			
		echo '</textarea></div>';
		echo '</form>';
		echo '<button name="anzeigen" >Syntax Highlighting</button>';
		echo '<input type="hidden" value="'.$_GET['id'].'" name="ID" />';
		echo '<input type="hidden" value="'.$result[0]['Pfad'].'" name="Pfad" />';
		$DB->_close();
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Bibliothek - VBS</title>
	<meta charset="utf-8" />
</head>
<body>
<?php 
	/** 
	  * FRONTEND ANPASSEN
	  **/
	require_once('../../../editFrontend/editFrontend.php');	 
?>
<!-- Stylesheets -->
<!----------------->
<!-- CodeMirror -->
<script src="../../../CodeMirror/lib/codemirror.js"></script>
<link rel="stylesheet" href="../../../CodeMirror/lib/codemirror.css">

<!-- Theme -->
<link rel="stylesheet" href="../../../CodeMirror/theme/monokai.css">

<script src="../../../CodeMirror/addon/edit/matchbrackets.js"></script>
<script src="../../../CodeMirror/mode/vbscript/vbscript.js"></script>

<!--- CK Editor --->
<script src="../../../ckeditor/ckeditor.js"></script>
<script src="../../../ckeditor/adapters/jquery.js"></script>
<!----------------->

<!----------------->
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../../../css/reset.css">
<link rel="stylesheet" type="text/css" href="../../../css/index.css">
<link rel="stylesheet" type="text/css" href="../../../css/bib/vbs/vbs.css">
<script type="text/javascript">
$(document).ready(function() {	
	
	// Editor anzeigen
	$("button.setEditor").click(function(event){
		event.preventDefault();
		var ckedior = CKEDITOR.replace('create_textarea', {
			height: '380px'
		});
		CKEDITOR.editorConfig = function( config ) {
			config.language = 'de';			
		};
		
		
		$(this).css("display","none");
	});
	// Quellcode anzeigen
	$("button[name='anzeigen']").click( function(event) {
	event.preventDefault();
	var file = $("input[name='pfad']").val(); 

	var editor = CodeMirror.fromTextArea(document.getElementById('code'), {		
		lineNumbers: true,
		matchBrackets: true,
		indentUnit: 4
	});
	editor.setOption("theme", "monokai");
	editor.setSize(900,450);
	$(this).css("display","none");
	});
	// Eintrag speichern
	$("#create button[name='Speichern']").click( function(event) {
		event.preventDefault();
		var Username = $("input[name='User']").val();		
		var Dateiname = $("input[name='Name']");
		var Pfad = $("input[name='file']");				
				
		var Felder = new Array( Dateiname, Pfad );
		var counter = 0;
		$.each( Felder, function() {
			if( $(this).val() == '' ) {
				$(this).attr("placeholder", "Bitte Wert eintragen").css("color", "red");
				counter += 1;
			} else {
				counter - 1;
				$(this).css("color", "black");				
			}			
		});	
		if ( counter == 0 ) {
			$("form").submit();		
		}
	
	});
	
	$("i.icon-edit").click(function() {
		CKEDITOR.replace( 'description_content', {
			height: '300px'
		});
		$("img[name='quote']").css("display","none");
	});
	// Eintrag löschen
	$("i.icon-remove-sign").click(function() {
		var answer = window.confirm("Eintrag wirklich löschen ?");
		var ID = $(this).attr("name");		
		var Pfad = $(this).attr("id");		
		// true
		if(answer) {								
			$.ajax({
				type	: 	'GET',
				url		: 	'ajaxVbs.php',
				data	: {
					'function'	: 'delete',
					'ID' 	: 	ID,
					'Pfad'	: Pfad
				},
				success	: function(data) {					
					$("#success_box").html("<div id='success'><p><i class='icon-ok'>Gelöscht!</i></p></div>").show().fadeOut(3000);
					setTimeout(function(){ window.location.href = "http://localhost/Tool/php/bib/vbs/vbscript.php" }, 3000);
				}
			});
		}	
	});
});
</script>
<script src="../../../js/bib/useTemplate.js" ></script>
<div id="success_box"></div>

<!-- Lindorff Logo -->
<!------------------->
<div id="logo" >
	<img src="../../../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div> 

<!-- Navigationsleiste -->
<!----------------------->
<div id="navi">	
<h1>Navigation</h1>

<ul>
	<a href="../../../index.php" ><li><i class="icon-home"></i> Home</li></a>
	<a href="../../jobs.php" ><li><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="../../bib.php" ><li class="active"><i class="icon-book "></i> Bibliothek</li></a>
	<a class="user" href="../../user.php" ><li><i class="icon-user "></i> User</li></a>
	<a href="../../settings.php" ><li><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="../../monitoring.php?page=today" ><li><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> 

<acronym title="Logout ?">
<a class="btn" href="../../../logout.php" ><i class="icon-signout"></i></a>	
</acronym>

<div id="content">
<?php 
if( empty($_GET) ) {
echo '
	<div id="content_header">
		<img src="../../../img/icons/file_64.png" name="img_header" >
		<h1>VB - Skripte</h1>
		<a class="folder" href="file:///C:\xampp_1.8.2\htdocs\Tool\Skripte\vbs"></a>
		<p class="info">Hier steht eine Auswahl von angelegten Skripten zur Verfügung.</p><br />	
		<p class="info">Um eine VBSkript-Datei zur Datenbank hinzufügen, einfach auf das <b>+</b> klicken.</p>
		<a href="./vbscript.php?option=create"><i class="icon-plus"></i></a>
	</div>
	
	<div id="content_content">';		
			echo getVBSJobs();
	echo '
	</div>
';
}
else {
	if( isset($_GET['id']) ) {
		getVBSInfos();
	}
	else if( isset($_GET['option']) ) {	
		echo '
		<div id="create">
		<form enctype="multipart/form-data" action="ajaxVbs.php" method="POST" >
			<h1>Skript hinzufügen: </h1>
			<p class="create_info">Hier können Skripte hinzugefügt werden. <br />Skripte werden automatisch in den Ordner /Skripte/vbs verschoben.</p>
			<div id="Name">
				<h2>Name: </h2>
				<input type="text" name="Name" value="" />
				<p>Bitte einen Namen für das Skript festlegen.</p>
			</div>
			
			<div id="Pfad">
				<h2>Pfad: </h2>
				<input type="file" name="file" />
			</div>
			
			<div id="description">
				<h2>Beschreibung: </h2>				
				<textarea id="create_textarea" name="create_textarea" placeholder="Um Textformatierungen vornehmen zu können, Editor aktivieren !"></textarea>				
				<p>Bitte geben Sie eine Beschreibung zu dem Skript ein.</p>
			</div>
			<button class="setEditor">Show Editor</button>			
			<button name="Speichern" >Speichern</button>			
			<input type="hidden" name="function" value="insert" />
			<input type="hidden" name="User" value="'.$_SESSION['Username'].'" />
		</form>
		</div>
		';		
	}
}
?>	
</div>	

<!-- Background picture -->
<div id="background-pic">
	<img src="../../../img/icons/book.png" name="book_pic" class="book_pic" />
</div>

<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>

</body>
</html>