<?php 
	include('../../validate.php');
	include('../../active_link.php');
	require_once('../../Controller/DBclass.php');
		
	function getBatchJobs() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		
		$sql = "SELECT ID, Name, Von, Pfad, CONVERT(nchar, createdAT ) as date FROM [BatchJob]";
		$DB->set_sql($sql);	
		$DB->_query();		
		$result = $DB->_fetch_array(1);		
				
		foreach( $result as $value ){						
			echo '<div id="content_box">';
			echo '<div id="title">';
			echo '<i class="icon-bookmark"></i>';
			echo '<a href="./batch.php?id='.$value['ID'].'">';
			echo '<p>'. $value['Name'] .'</p>';
			echo '</a>';
			echo '</div>';
			echo '<div id="info">';
			echo '<p> von: </i></p>' .$value['Von']. '<br />';
			echo '<p> erstellt am: </i></p>' . $value['date']. ' ';
			echo '</div>';			
			echo '<i class="icon-remove-sign" id="'.$value['ID'].'" name="'.$value['Pfad'].'"></i>';
			echo '<img src="../../../img/icons/batch.png" name="box_background_img">';
			echo '</div>';			
		}		
		$DB->_close();
	}
	function getBatchJobInfos() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();		
		$sql = "SELECT Name, Von, ID, CONVERT(nchar, createdAT ) as date, Beschreibung, Pfad
				FROM [BatchJob]
				WHERE ID = ".$_GET['id']." ";
		$DB->set_sql($sql);	
		$DB->_query();		
		$result = $DB->_fetch_array(1);		
		echo '<div id="remove-icon"><i class="icon-remove-sign" id="'.$result[0]['ID'].'" name="'.$result[0]['Pfad'].'" ></i></div>';	
		echo '<div id="export"><img src="../../../img/icons/64/969.png" name="export" title="Skript weiterverarbeiten ?!" /></div>';
		echo '<div id="title"><div id="left_title"><h1>'.$result[0]['Name'].'</h1><a class="file" href="file:///'.$result[0]['Pfad'].'"></a></div>';
		echo '<div id="right_title">Erstellt von: ';
		echo '<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input class="span2" type="text" disabled="disabled" value="'.$result[0]['Von'].'"/></div></div></div>';	
		echo '<div id="description"><h2> Beschreibung: </h2><img src="../../../img/icons/quote64.png" name="" /><div id="result">'.$result[0]['Beschreibung'].'</div></div>	';
		echo '<form action="../../jobs/create/createBatchJob.php" method="POST" >';
		echo '<input type="hidden" name="type" value="shell" />';
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
		echo '<input type="hidden" value="'.$result[0]['Pfad'].'" name="pfad" />';
		$DB->_close();
	}
?>
<!DOCTYPE HTML5>
<html>
	<head>
		<title>Bibliothek - Batch</title>	
	<meta charset="utf-8">
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
<script src="../../../CodeMirror/mode/shell/shell.js"></script>

<!--- CK Editor --->
<script src="../../../ckeditor/ckeditor.js"></script>
<script src="../../../ckeditor/adapters/jquery.js"></script>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../../../css/reset.css">
<link rel="stylesheet" type="text/css" href="../../../css/index.css">
<link rel="stylesheet" type="text/css" href="../../../css/bib/batch/batch.css">
<script type="text/javascript">
$(document).ready(function() {
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
	
	$("button[name='anzeigen']").click( function() {
	var file = $("input[name='pfad']").val(); 

	var editor = CodeMirror.fromTextArea(document.getElementById('code'), {
		mode: 'shell',
		lineNumbers: true,
		matchBrackets: true
	});
	editor.setSize(900,450);
	editor.setOption("theme", "monokai");
	$(this).css("display","none");
	});
	
	$("#create button[name='Speichern']").click( function() {
				
		var Username = $("input[name='User']").val();	
		var Dateiname = $("input[name='Name']");
		var Pfad = $("input[name='Pfad']");
		
		var Felder = new Array( Dateiname, Pfad, Beschreibung );
		var counter = 0;
		$.each( Felder, function() {
			if( $(this).val() == '' ) {
				$(this).css("border", "1px solid red");
				counter += 1;
			} else {
				counter - 1;
				$(this).css("border", "1px solid black");				
			}			
		});
	
	if ( counter == 0 ) {
		var Beschreibung = CKEDITOR.instances['create_textarea'].getData();
		var Dateiname = $("input[name='Name']").val();
		var Pfad = $("input[name='Pfad']").val();
		$.ajax({
			type	: 	'GET',
			url 	: 	'ajaxBatch.php',
			data :	{
				'function'	: 	'insert',
				'Username'	:   Username,
				'Dateiname' : 	Dateiname,
				'Pfad'		: 	Pfad,
				'Beschreibung':	Beschreibung
			},
			sucess: function(data) {
				$("#success_box").html("<div id='success'><p><i class='icon-ok'>Gespeichert!</i></p></div>").show().fadeOut(3000);
				setTimeout(function(){ window.location.href="http://localhost/Tool/php/bib/batch/batch.php" }, 3000);
			}
		});	
	}
	
	});
	
	// Eintrag löschen
	$("i.icon-remove-sign").click(function() {
		var answer = window.confirm("Eintrag wirklich löschen ?");
		var ID = $(this).attr("id");
		var Pfad = $(this).attr("name");
		// true
		if(answer) {						
			$.ajax({
				type		: 	'GET',
				url		: 	'ajaxBatch.php',
				data	: {
					'function'	: 'delete',
					'ID' 	: 	ID,
					'Pfad' 	: 	Pfad
				},
				success	: function(data) {										
					$("#success_box").html("<div id='success'><p><i class='icon-ok'>Gelöscht!</i></p></div>").show().fadeOut(3000);
					setTimeout(function(){ window.location.href = "http://localhost/Tool/php/bib/batch/batch.php" }, 3000);
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
	<a href="../../user.php" ><li><i class="icon-user "></i> User</li></a>
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
		<img src="../../../img/icons/batch.png" name="img_header">
		<h1>Batch - Skripte</h1>
		<a class="folder" href="file:///C:\xampp_1.8.2\htdocs\Tool\Skripte\batch"></a>
		<p class="info">Hier steht eine Auswahl von angelegten Skripten zur Verfügung.</p><br />	
		<p class="info">Um eine Batch-Datei zur Datenbank hinzufügen, einfach auf das <b>+</b> klicken.</p>
		<a href="./batch.php?option=create"><i class="icon-plus"></i></a>
	</div>
	
	<div id="content_content">';		
			echo getBatchJobs();
	echo '
	</div>
';
}
else {
	if( isset($_GET['id']) ) {
		getBatchJobInfos();
	}
	else if( isset($_GET['option']) ) {	
		echo '
		<div id="create">
		<form enctype="multipart/form-data" action="ajaxBatch.php" method="POST" >
			<h1>Skript hinzufügen: </h1>
			<p class="create_info">Hier können Skripte hinzugefügt werden. <br />Skripte werden automatisch in den Ordner /Skripte/batch verschoben.</p>
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
				<img src="../../../img/icons/quote64.png" name="" />
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