<?php 
	include('../validate.php');	
	require_once('../Controller/DBclass.php');	
	
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $id = $_GET['id'];     
    $page = urlencode($_GET['page']);
        
    $script_name = explode("/",$script, 5);   	
	
	function getTaskInfo() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		
		$sql = "SELECT * FROM dbo.[Task] WHERE TaskName = '" . $_GET['id'] . "' ";
		$DB->set_sql($sql);	
		$DB->_query();
		
		$result = $DB->_fetch_array(1);		
				
		return $result;
				
		$DB->close();
	}
	
	function getComments($ID){
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT CAST(createdAT AS nvarchar(30)) as createdAT , createdFROM , Inhalt, Nr FROM [Comments] WHERE ID = '".$ID."' ORDER BY createdAT ";		
		$DB->set_sql($sql);
		$DB->_query();		
		$result = $DB->_fetch_array(1);
		foreach($result as $value){
			echo '<div id="comment">';
            echo '<acronym title="Delete?">';
            echo '<img src="../../img/icons/delete16.png" name="delete_comment" class='.$value['Nr'].'>';
			echo '</acronym>';
        
			echo '<div id="title">';
            echo '<div id="header_left">';
			$erstellt_um = explode(" ",$value['createdAT']);
            echo '<p>erstellt am: '.$erstellt_um[0].' um: '.$erstellt_um[1].' Uhr</p>';
            echo '</div>';
            echo '<div id="header_right">';
            echo '<p>erstellt von: '.$value['createdFROM'].'</p>';
            echo '</div>';
			echo '</div>'; 
        
			echo '<img src="../../img/icons/quote64.png" name="quote">';
        
			echo '<p class="comment">'.$value['Inhalt'].'</p>';
			echo '</div>	';
		}
		$DB->_close();
	}
	
	function getAbteilungen() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT Name 
				FROM [Abteilung]
				WHERE NOT EXISTS
				( SELECT Abteilung
					FROM [Task_Abteilung]
					WHERE Name = Abteilung
					AND TaskName = '".$_GET['id']."' )";		
		$DB->set_sql($sql);
		$DB->_query();		
		$result = $DB->_fetch_array(1);
		echo "<ol id='selectable' class='select'>";
		foreach($result as $value) {
			echo '<li class="ui-widget-content" id="draggable">'.$value['Name'].'</li>';
		}
		echo "</ol>";
		$DB->_close();
	}
	
	function getSavedAbteilungen() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT Abteilung FROM [Task_Abteilung] WHERE TaskName ='".$_GET['id']."' ";		
		$DB->set_sql($sql);
		$DB->_query();		
		$result = $DB->_fetch_array(1);
		echo "<ol>";
		foreach($result as $value) {
			echo '<li class="ui-widget-content" id="draggable">'.$value['Abteilung'].'<i class="icon-remove-sign"></i></li>';
		}
		echo "</ol>";
		$DB->_close();
	
	
	}
	
	function getDocuments() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$sql = "SELECT TaskID , Name , Dateipfad , [Task_Documents].ID
				FROM [Task_Documents]
				JOIN [Task] ON [Task].ID = [Task_Documents].TaskID
				WHERE [Task].TaskName = '". $_GET['id'] ."' ";
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		if( $result != NULL ) {
			foreach( $result as $value ) {
				echo '<div id="file">';
				echo '<i class="icon-remove" value="'.$value['ID'].'" name="'.$value['Dateipfad'].'" ></i>';
				echo '<img src="../../img/icons/file_64.png" name="file">';
				$explode = explode("\\", $value['Dateipfad'] );
				array_pop($explode);
				$implode = implode("\\", $explode);
				echo '<p>Name: <br /><br /><span><a href="file:/// ' . $implode . '" >'.$value['Name'].'</a></span></p>';
				echo '<input type="hidden" name="documentID" value="'.$value['TaskID'].'" />';
				echo '</div>';
			}
		} else {
			echo '<div id="empty">';
			echo '<img src="../../img/icons/empty.png" name="empty">';
			echo '<p>Bisher wurde keine Datei hinterlegt.</p>';
			echo '</div>';
		}
		$DB->_close();
		
	}

	function getStatus() {
		$DB = new DB;
		$DB->set_database("Lindorff_DB");
		$DB->_connect();
		$return = getTaskInfo();
		$sql = "SELECT U.Kuerzel , TIB.TaskID 
					FROM [User] AS U
					JOIN [Task_InBearbeitung] AS TIB ON TIB.UserID = U.UID
					WHERE TIB.TaskID = ".$return[0]['ID']." ";		
		$DB->set_sql($sql);
		$DB->_query();
		$result = $DB->_fetch_array(1);
		return $result;
		$DB->_close();
	}
?>
<!DOCTYPE HTML5>
<html>
	<head>
		<title>Task - Informationen</title>	
	<meta http-equiv="Cache-control" content="no-cache" charset="ISO-8859-1">

<!-- Stylesheets -->

<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../../css/reset.css">
<link rel="stylesheet" type="text/css" href="../../css/index.css">
<link rel="stylesheet" type="text/css" href="../../css/job_info.css">
<link rel="stylesheet" type="text/css" href="../../css/tooltip/tooltip.css">
</head>
	
<body>
<!-- CKEditor -->
<script src="../../ckeditor/ckeditor.js"></script>
<script src="../../ckeditor/adapters/jquery.js"></script>

<!-- JQuery -->

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"  type="text/javascript"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"  type="text/javascript"></script>
<script src="../../js/job_info.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	/**
	  * START - ENDZEIT
	  * 	CHANGE 
	  **/
	$("input[name='/ET'] , input[name='/ST']").change( function() {
		if( $.isNumeric( $(this).val() ) && $(this).val().length == 4 ) {			
				var split = $(this).val().split("");
				var one = parseInt(split[0] +  split[1]);
				var two = parseInt(split[2] +  split[3]);
				if ( one < 0 ) {
					one = "00";
				}
				if ( one > 23 ) {
					one = "23";
				}
				if ( one > 23 && two > 59 ) {
					one = "23";
					two = "59";
				}
				if ( two >= 60 ) {
					two = "59";
				}				
				$(this).val( one + ":" + two );			
		}
		else {
			if ( $(this).val().charAt(2) == ":" ) {
				var split = $(this).val().split(":");
				if ( $.isNumeric( split[0] && split[2] ) ) {
					var one = parseInt(split[0]);
					var two = parseInt(split[2]);
					if ( one > 23 || one < 0 ) {
						one = "00";
					}	
					if ( two > 59 ) {
						two = "59";
					}
					$(this).val( one + ":" + two );
				}
				else {
					$("span[class='error_change']").html("<p>Fehlerhafte Eingabe</p>").show().fadeOut(2000);
					$(this).val("");
				}
			}
			else {
				$("span[class='error_change']").html("<p>Fehlerhafte Eingabe</p>").show().fadeOut(2000);
				$(this).val("");
			}
		}		
	});
	/** 
	  * ICON BOOKMARK
	  * Task markieren, um darüber zu informieren, dass dieser Task gerade bearbeitet wird
	  **/ 
	$("i.icon-bookmark").click( function() {
		var user = $(this).attr("name");
		var task = $("p.header").text();
		var id = $("input[name='TaskID']").val();
		
		$.ajax({
			type: 'GET',
			url : './ajax/ajax_jobinfo.php',
			dataType: 'json',
			data: {
				'function' 	: 	'setStatus',
				'user'		: 	user,
				'task'		: 	task,
				'id'		: 	id
			},
			success: function(data) {				
				// Status => in Bearbeitung (1)	
					console.log(data);
				if( data[0].Kuerzel ) {					
					$("#bookmark").html("<img src='../../img/icons/bookmark.png' name='bookmark' title='wird bearbeitet von: " + data[0].Kuerzel + " ' />").fadeIn("slow");
				}
				// Status => NOT (2)
				else {
					$("#bookmark").fadeOut("slow");
					$("img[name='bookmark']").fadeOut("slow");
				}			
			}
		});
	});
	/**
	  * DOKUMENTE SPEICHERN
	   **/
	$("input[name='submit_docs']").click(function(event){
		event.preventDefault();		
		
		var file = document.getElementById('file').files[0];
		var job = $("p.header").text();
		var id = $("input[name='TaskID']").val();
		var fileData = new FormData();
		fileData.append('file' , file );
		fileData.append( 'job' , job );
		fileData.append( 'id' , id );
				    
		$.ajax({
			type : 'POST',
			url  : './docs/docs.php',
			enctype: 'multipart/form-data',
			processData: false,
			contentType: false,
			data : fileData ,
			
			success: function(data) {
				console.log(data);	
				$("body").append("<div id='success'><p><i class='icon-check'></i> Dokument gesichert.</p></div>");				
				$("#success").fadeOut(3500);
				setTimeout( function() { window.location.reload(); }, 3200 );
			}			
		});		
	});	
	/**
	  * TOOLTIP
	  **/
	$( document ).tooltip({
		position: {			
			show: null,
			position: {
				my: "left top",
				at: "left bottom"
			},
			open: function( event, ui ) {
				ui.tooltip.animate({ top: ui.tooltip.position().top + 10 }, "fast" );
			},
			using: function( position, feedback ) {
				$(this).css(position);
				$( "<div>" )
					.addClass( "arrow" )
					.addClass( feedback.vertical )
					.addClass( feedback.horizontal )
					.appendTo( this );
			}
		}
	});
	
	/** 
	  * DOKUMENT LÖSCHEN
	  **/
	$("#file i.icon-remove").click( function() {		
		var answer = window.confirm("Dokument löschen? ");
		if(answer){
			var id = $(this).attr("value");	
			var pfad = $( this ).attr("name");			
			$.ajax({
				type: 'GET',
				url  : './docs/docs.php',
				data : {
					'id' : id,
					'pfad' : pfad
				},
				success: function(data) {
					console.log(data);
					$("body").append("<div id='success'><p><i class='icon-remove'></i> Dokument gelöscht.</p></div>");				
					$("#success").fadeOut(3500);
					setTimeout( function() { window.location.reload(); }, 3200 );
				}
			});
		}
	});
	/** 
	  * ABTEILUNG
		**/
	$("#Betrifft i.icon-remove-sign").click(function(){
			var Abteilung = $(this).parent().text();
			var TaskName = $("p.header").text();
			$.ajax({
				type	: 'GET',
				url 	: './change/changeAbteilung.php',
				data 	: {
					'function' 	: 	'DeleteFromList',
					'Abteilung'	:	Abteilung,
					'TaskName'	: 	TaskName
				},
				success: function(data) {
					$("#success_box").html("<div id='success'><p><i class='icon-ok'>Änderung vorgenommen!</i></p></div>").show().fadeOut(4000);
					setTimeout(function(){ location.reload() }, 4000);
				}				
			});
		
	});
	
	$("#Liste li").draggable({
		revert: "invalid",		
		scroll: false,
		helper: 'clone',
		cursor: 'move',
		start: function() {
			this.style.display="none";
		},
		stop: function() {
			this.style.display="";
		}		
	});
	
	$("#Betrifft").droppable({
		activeClass: "ui-state-hover",
		drop: function( event, ui ){
			$("#Betrifft ol").append("<li>" + ui.draggable.text() + "</li>");
			$(ui.draggable).fadeOut("slow").remove(); 
		}
	});
	
	$("button[name='saveAbteilung']").click(function(){
		var TaskName = $("p[class='header']").text();
		var Abteilungen = [];
		$("#Betrifft ol li").each(function(){
			Abteilungen.push( $(this).text() );
		});
		if(Abteilungen.length != 0){
			save(TaskName, Abteilungen);
		}
	function save(TaskName, Abteilungen) {
		$.ajax({
			type	: 'GET',
			url 	: './change/changeAbteilung.php',
			data 	: {
					'function'		: 'saveChanges',
					'TaskName'  	: TaskName,
					'Abteilungen' 	: Abteilungen
			},
			success	: function(data){
				$("#success_box").html("<div id='success'><p><i class='icon-ok'>Gespeichert!</i></p></div>").show().fadeOut(3000);
				setTimeout(function(){ location.reload() }, 3000);
			}
		});
	}
	});
	/**
	  * ALLE TASKS VERSCHIEBEN
		**/
	$("i.icon-chevron-right").click(function(index){
		$("ol.select li").each(function() {
			$("#Betrifft ol").append("<li>" +$(this).text() + "</li>");
			$(this).remove();
		});
	});
	$("i.icon-chevron-left").click( function(index) {
		var TaskName = $("p[class='header']").text();
			$.ajax({
				type: 'GET',
				url 	: './change/changeAbteilung.php',
				data : {
					'function' : 'deleteALLabteilungen',
					'TaskName' : TaskName
				},
				success: function(data) {
					window.location.reload();
				}				
			});	
	});
	/** 
	  * KOMMENTAR HINZUFÜGEN
		**/
	$("button[name='add_comment']").click(function(){
		var text = $("textarea").val();
		var id = $("p[class='header']").text();
		
		$.ajax({
			type: 'POST',
			url:  './comments/edit_comment.php',
			data: {
				'function'  : 'add_comment',
				'ID'   		: id,
				'Text' 		: text
			},
			success: function(data){				
				$("#success_box").html("<div id='success'><p><i class='icon-ok'>Kommentar angelegt !</i></p></div>").show().fadeOut(3000);
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
	/** 
	  * KOMMENTAR LÖSCHEN
	**/
	$("img[name='delete_comment']").click(function(){
		var nr = $(this).attr('class');
		var id = $("p[class='header']").text();
		$.ajax({
			type: 'POST',			
			url:  'comments/edit_comment.php',
			data: {
				'function'  : 'delete_comment',
				'Nr' 		: nr,
				'ID' 		: id
			},			
			success: function(data) {	
				
				$("#success_box").html("<div id='success'><p><i class='icon-ok'>Kommentar gelöscht !</i></p></div>").show().fadeOut(4000);
				setTimeout(function(){ location.reload() }, 4000);
				
			},
			error: function(xhr, status, error) {
				console.log(xhr + status);
			}
		});
	});
	/**
	  * RUN TASK
	  **/
	$("img[name='run_task']").click( function() {
		var TaskName = $("p[class='header']").text();
		var HostName = $("input[name='/S']").val();
		
		$("#loading").append("<i class='icon-spinner icon-spin icon-large'></i> Loading...");
		$("#content").css("opacity","0.2");				
		
		$.ajax({
			type: 'POST',
			url:  'change/changeTask.php',
			data: {
				'function'  : 'run_task',				
				'TaskName'  : TaskName,
				'HostName'  : HostName
			},
			success: function(data)  {		
				$("#loading").fadeOut("slow");	
				$("#content").css("opacity","1.0");	
				$("#success_box").html("<div id='success'><p><i class='icon-ok'> Task wurde ausgeführt!</i></p></div>").show().fadeOut(4000);
				setTimeout(function(){ location.reload() }, 4000);
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
	  * CHANGE TASK INFOS
		**/
	$("button[name='task_speichern']").click(function(){
		var TaskName = $("p[class='header']").text();
		var HostName = $("input[name='HostName']").val();
		var RunAsUser = $("input[name='RunAsUser']").val();
		var TaskToRun = $("input[name='TaskToRun']").val();
		$("#loading").append("<i class='icon-spinner icon-spin icon-large'></i> Loading...");
		$("#content").css("opacity","0.2");		
		$.ajax({
			type: 'POST',
			url:  'change/changeTask.php',
			data: {
				'function'  : 'change_task_info',
				'TaskToRun' : TaskToRun,
				'HostName'	: HostName,
				'RunAsUser' : RunAsUser,
				'TaskName'  : TaskName
			},
			success: function(data) {
				$("#loading").fadeOut("slow");	
				$("#content").css("opacity","1.0");	
				$("#success_box").html("<div id='success'><p><i class='icon-ok'>Kommentar gelöscht !</i></p></div>").show().fadeOut(4000);
				setTimeout(function(){ location.reload() }, 4000);
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
	  ** @ TODO !
	  *
	  * CHANGE SCHEDULE TYPE
	  * 
      * --->   WORKAROUND !	  
	**/
	$("button[name='schedule_speichern']").click(function(){
		var TaskName = $("p[class='header']").text();
		
		var options = new Array();
		$("select[class='task_option'] option").each(function(){
			var index = $(this).val();
			switch(index)
			{
				case 0:
					$.ajax({
						type: 'POST',
						url:  'change/changeTask.php',
						data: {
							'function'  : 'change_task_schedule',				
							'TaskName'  : TaskName
						},
						success: function(data) {
							//alert(data);
							//$("#success_box").html("<div id='success'><p><i class='icon-ok'>Kommentar gelöscht !</i></p></div>").show().fadeOut(4000);
							//setTimeout(function(){ location.reload() }, 4000);
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
		});	
		
	});	 /** Button clicked **/
	
	
	/**
	   * ENABLE / DISABLE TASK
		**/
	$("img[name='disable'] , img[name='enable']").click(function(){		
		
		if( $(this).attr('name') == 'enable' ){
				var answer = window.confirm("Task fortsetzen ?");
			} else {
				var answer = window.confirm("Task stoppen ? ");
			}
		if(answer){
			var TaskName = $("p[class='header']").text();
			var HostName = $("input[name='HostName']").val();
			var Status = $(this).attr("class");			
			$("#loading").append("<i class='icon-spinner icon-spin icon-large'></i> Loading...");
			$("#content").css("opacity","0.2");
			$.ajax({
				type 	: 'POST',
				url:  'change/changeTask.php',
						data: {
							'function'  : 'enable_disable_task',				
							'TaskName'  : TaskName,
							'HostName'	: HostName,
							'Status'	: Status
						},
				success: function(data) {					
					$("#loading").fadeOut("slow");	
					$("#content").css("opacity","1.0");	
					$("#success_box").html("<div id='success'><p><i class='icon-ok'> Status geändert !</i></p></div>").show().fadeOut(4000);
					location.reload();
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
	});
	/** 
	  * DELETE TASK
		**/
	$("img[name='delete_task']").click(function(){
		var answer = window.confirm("Task wirklich löschen ?");
		if(answer) {
			var TaskName = $("p[class='header']").text();
			var HostName = $("input[name='HostName']").val();
			var TaskID = $("input[name='TaskID']").val();
			$("#loading").append("<i class='icon-spinner icon-spin icon-large'></i> Loading...");
			$("#content").css("opacity","0.2");			
			$.ajax({
				type 	: 'POST',
				url:  'change/changeTask.php',
						data: {
							'function'  : 'delete_task',				
							'TaskName'  : TaskName,
							'HostName'	: HostName,
							'TaskID'	: TaskID
						},
				success: function(data) {					
					$("#loading").fadeOut("slow");	
					$("#content").css("opacity","1.0");	
					$("#success_box").html("<div id='success'><p><i class='icon-ok'> Task gelöscht !</i></p></div>").show();					
					setInterval(function(){ window.location.href="http://localhost/Tool/php/server/fcap17.php" }, 4000);					
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
	});

	/**
	  * INFOBOX TOGGLE
	  **/
	$("#infobox").click( function() {
		$(this).toggle();
	});
	
	$("button").click( function(event) {
		event.preventDefault();
	});
});	
	
</script>
<?php 
	
	/** 
	  * FRONTEND ANPASSEN 
	  **/
	require_once('../../editFrontend/editFrontend.php');	

	/** 
	  * INFOBOX für die Rückgabe nach der Änderung von Einstellungen 
	  **/
	if( !empty($_GET['info']) ) {
		echo '<div id="infobox">'.$_GET['info'].'<i class="icon-level-up icon-2x" title="Ausblenden ?"></i></div>';
	}
	
	$return = getStatus();
	
	if( !empty($return) ) {		
		echo '<img src="../../img/icons/bookmark.png" name="bookmark" title="wird bearbeitet von : '.$return[0]['Kuerzel'].'" />';
		
	}	
	/**
	  * * BACK TO LIST 
	  **/
	 $return = getTaskInfo();
	 $explode = explode('.',$return[0]['HostName']);
	 $host = $explode[0]; 
	 
	echo '<a href="../server/' . $host .'.php" class="back_to_list"><i class="icon-chevron-sign-left"></i> Back </a>';
?>
<div id="bookmark"></div>
<div id="success_box"></div>
<div id="loading"></div>

<!-- Lindorff Logo --> 

<div id="logo" >
<img src="../../img/Lindorff_Logo.jpg" alt="Logo" class="logo" />
</div> 

<!-- Navigationsleiste -->

<div id="navi">	
	<h1>Navigation</h1>

<ul>
	<a href="../../index.php" ><li><i class="icon-home"></i> Home</li></a>
	<a href="../../php/jobs.php" ><li class="active"><i class="icon-spinner "></i> Tasks</li></a>	
	<a href="../../php/bib.php" class="bib"><li><i class="icon-book "></i> Bibliothek</li></a>
	<a href="../../php/user.php" class="user"><li><i class="icon-user "></i> User</li></a>
	<a href="../../php/settings.php" ><li><i class="icon-cogs "></i> Einstellungen</li></a>
	<a href="../../php/monitoring.php?page=today" ><li><i class="icon-code "></i> Monitoring</li></a>
</ul>
</div> 

<!-- Logout Button --> 

<acronym title="Logout ?">
<a class="btn" href="../../logout.php" ><i class="icon-signout"></i></a>	
</acronym>

<!-- Content Bereich -->

<div id="content">	
	<?php 
		echo '<i title="Flag setzen ?" name="'.$_SESSION['Username'].'" class="icon-bookmark"></i>'; 
	?>
	<div id="task_header">		
		<?php
		$return = getTaskInfo();
		// BOOKMARK ID
		echo '<input type="hidden" name="TaskID" value="'.$return[0]['ID'].'" />';
		if( substr_count($return[0]['ScheduledTaskState'], "Enabled") == 1){
			echo '<img src="../../img/icons/scheduled_task.png" /><p class="header" id="header">' . htmlentities($_GET['id'], ENT_HTML5, 'ISO-8859-1') . ' </p>';			
		}
		else
			echo '<img src="../../img/icons/stop.png"  /><p class="header">' . $_GET['id'] . '</p>';
		?>
		<img src="../../img/icons/delete32.png" name="delete_task" title="Task endgültig löschen?" />
		<hr>
	</div>	
<div id="tabs_div">
	<!-- TABS -->
    <ul class="tabs">
        <a href="<?php echo $script_name['4'] . '?id=' . urlencode($id) . '&page=task'?>"><li class="first" <?php if($page == 'task') echo 'id="active_tab"' ?>>Task</li></a>
        <a href="<?php echo $script_name['4'] . '?id=' . urlencode($id) . '&page=schedule'?>"><li  <?php if($page == 'schedule') echo 'id="active_tab"' ?>>Schedule</li></a>
        <a href="<?php echo $script_name['4'] . '?id=' . urlencode($id) . '&page=change' ?>"><li <?php if($page == 'change') echo 'id="active_tab"' ?>>Change</li></a>
		<a href="<?php echo $script_name['4'] . '?id=' . urlencode($id) . '&page=settings'?>"><li  <?php if($page == 'settings') echo 'id="active_tab"' ?>>Settings</li></a>
		<a href="<?php echo $script_name['4'] . '?id=' . urlencode($id) . '&page=abteilung'?>"><li  <?php if($page == 'abteilung') echo 'id="active_tab"' ?>>Abteilungen</li></a>
        <a href="<?php echo $script_name['4'] . '?id=' . urlencode($id) . '&page=change_history'?>"><li  <?php if($page == 'change_history') echo 'id="active_tab"' ?>>Change History</li></a>
        <a href="<?php echo $script_name['4'] . '?id=' . urlencode($id) . '&page=comments'?>"><li  <?php if($page == 'comments') echo 'id="active_tab"' ?>>Comments</li></a>
		<a href="<?php echo $script_name['4'] . '?id=' . urlencode($id) . '&page=docs'?>"><li  <?php if($page == 'docs') echo 'id="active_tab"' ?>>Docs</li></a>
    </ul>


<?php 

if($page == 'task')
{
	echo '
		<div id="scrollable">
		<div id="tab_title">
		    <div id="title">
		        <img src="../../img/icons/schedule.png" name="task_img" /><p>Task</p>';
				/**
				  * TASK STOPPEN / FORTSETZEN
				  **/
				if(substr_count($return[0]['ScheduledTaskState'], "Enabled") == 1 ){
					echo '<img src="../../img/icons/64/983.png" name="disable" class="DISABLE" title="Task stoppen?" />';
				} else {
					echo '<img src="../../img/icons/64/981.png" name="enable" class="ENABLE" title="Task fortsetzen?" />';
				}
				echo'
		    </div> 
		</div>


		<div id="sortable">      <!------- SORTABLE --------->
			<div id="task">	
				<div id="tab_header">
					<p>Task</p>
					<div id="arrow1">
						<acronym title="verkleinern ?">
							<i class="icon-arrow-down"></i>
						</acronym>
					</div>
				</div>
		  
		        
			<div id="toggle_task">	
			
				<div id="p">
					<div id="o_p">
						<p>Task To Run:</p>
					</div>
					
					<div id="u_p">			
					';
					$return = getTaskInfo();
					
					echo '<input type="text" id="txt_run" disabled="disabled" name="TaskToRun" value="' . $return[0]['TaskToRun'] . '"  />';			
					echo '<p>Start In.</p>';
					echo '<input type="text" id="txt_run" disabled="disabled" value="' . $return[0]['StartIn'] . '"  />';					
					echo '			
					</div>
		
		</div>
		
		<div id="p">
			<div id="o_p">
				<p>Author: </p>
			</div>
			
			<div id="u_p">
			';
			echo '<input type="text" disabled="disabled" value="'.$return[0]['Author'].'" />';
			echo '
			</div>		
		</div>
		
		<div id="dp">
			<div id="dp_l">
				<p>Status:</p>
				';
				// AMPEL 
				echo '<input type="text" disabled="disabled" style="width: 250px;" value="'.$return[0]['Status'].'">';
					if( $return[0]['Status'] == "Unknown" || $return[0]['Status'] == "Ready" ) {
						echo '<img src="../../img/icons/ampel_yellow.png" class="ampel" />';
					} else if ( $return[0]['Status'] == "Could not start" ) {
						echo '<img src="../../img/icons/ampel_red.png" class="ampel" />';
					} else {
						echo '<img src="../../img/icons/ampel_green.png" class="ampel" />';
					}
				echo '
			</div>
			<div id="dp_r">
				<p>Host Name:</p>
				';
				echo '<input type="text" disabled="disabled" name="HostName" value="'.$return[0]['HostName'].'">';
				echo '
			</div>
		</div>

		<div id="dp">
			<div id="dp_l">
				<br />
				';
				echo '<p>Last Run Time: </p>';
				echo '<input type="text" disabled="disabled" name="LastRunTime" value="'.$return[0]['LastRunTime'].'">';
				echo '
			</div>
			<div id="dp_r">
				<p>Next Run Time:</p>
				';
				echo '<input type="text" disabled="disabled" value="'.$return[0]['NextRunTime'].'">';
				echo '
			</div>
		</div>		
		
		<div id="dp">
			<div id="dp_l">
				<p>Run As User: </p>		
			';			
			echo '<input type="text" name="RunAsUser" disabled="disabled" value="'.$return[0]['RunAsUser'].'" />';
			echo '
			</div>
			<div id="dp_r">
				<p>Last Result</p>';
				// LAST RESULT
				$LastResult = $return[0]['LastResult'];
				switch($LastResult)
				{
					case "0":
						echo '<input type="text" class="green" name="LastResult" value="'.$return[0]['LastResult'].'" title="Der Vorgang wurde erfolgreich beendet." />';
						break;
					case "1":
						echo '<input type="text" class="red"  name="LastResult" value="'.$return[0]['LastResult'].'" title="Es wurde eine falsche oder unbekannte Funktion aufgerufen." />';
						break;
					case "-1073741510":
						echo '<input type="text" class="red" name="LastResult" value="'.$return[0]['LastResult'].'" title="Die Anwendung wurde mit STRG + C unterbrochen." />';
						break;
					default:
						echo '<input type="text" class="yellow" name="LastResult" value="<i class="icon-chevron-right"></i>" title="Unbekannter Fehler." />';
				}
				echo '
			</div>
			</div>
			
			<!--<button name="task_speichern" >Speichern</button>-->
			</form>
			</div>
			</div>	
 		</div> <!------- SORTABLE --------->
		</div>
		'; 
	}  

if($page == 'schedule')
{ 
	$return = getTaskInfo();
	echo '
	<div id="tab_title">
	    <div id="title">
	        <img src="../../img/icons/calender.png" name="schedule_img" /><p>Schedule</p>
	    </div> 
	</div>
	
	<div id="sortable">         <!------- SORTABLE --------->
	
	<div id="schedule">
			
			<div id="tab_header">
				<p>Schedule</p>
				<div id="arrow2">
					<acronym title="verkleinern ?">
						<i class="icon-arrow-down"></i>
					</acronym>
				</div>
			</div>
			
	   
			<div id="toggle_schedule">
			<form>
				<div id="info">
					<h4>Aktuell wird der Task mit folgenden Optionen ausgeführt: </h4>
					<div id="first_row">
						<div id="fr_left">
						<p>Schedule Type: </p>
						<input type="text" disabled="disabled" value="'.$return[0]["ScheduleType"].'" />
						</div>
						<div id="fr_right">
						<p>Start Time: </p>
						<input type="text" disabled="disabled" value="'.$return[0]["StartTime"].'" />
						</div>
					</div>
					<div id="second_row">
						<p>Days: </p>
						<input type="text" disabled="disabled" value="'.$return[0]["Days"].'" />
					</div>
					<div id="third_row">
						<p>Months: </p>
						<input type="text" disabled="disabled" value="'.$return[0]["Months"].'" />
					</div>
					<div id="fourth_row">
						<div id="fr_left">
							<p>StartDate: </p>	
						<input type="text" disabled="disabled" value="' .$return[0]["StartDate"]. '" />
					</div>
					<div id="fr_right">
						<p>EndDate: </p>
						<input type="text" disabled="disabled" value="' .$return[0]["EndDate"]. '" />
					</div>
				</div>
			</div>';
		/*<br><br><br>
		
		<div id="dp">							
				<div id="dp_l">
					<p>Schedule Task:</p>
					<select class="task_option">					
							<option selected value="0">Täglich</option>
							<option value="1">Wöchentlich</option>
							<option value="2">Monatlich</option>
							<option value="3">Einmalig</option>
							<option value="4">Beim Systemstart</option>
							<option value="5">Nach dem Login</option>
							<option value="6">When idle</option>					
					</select>
				</div>				
				
				<div id="dp_r">
					<p>Start time:</p> ';					
					echo '<input type="time" name="start_time" id="start_time" value="'.$return[0]['StartTime'].'" maxlength="5" min="00:00" max="24:00" />';
					echo '					
                        <div id="button_up_down">
                            <button name="time_up"><i class="icon-caret-up"></i></button>
                            <button name="time_down"><i class="icon-caret-down"></i></button>
                        </div>
                    <p><span class="error_input_start_time"></span></p>
					
				</div>
				
			</div>
			<button name="Advanced" class="Advanced">Advanced</button>
			
			<! --- Fieldset -->
			<fieldset name="main">
				<legend>Schedule Task <span class="legend"></span></legend>			
				
				<!-------------------------- Daily --------------------------->
				<div id="daily">
				<p>Every: <input type="number" name="days" value="1"> <span>Day(s)</span></p>
                      
						<div id="button_up_down">
                            <button name="day_up"><i class="icon-caret-up"></i></button>
                        <button name="day_down"><i class="icon-caret-down"></i></button>
						</div>  
					
				</div>
				<span name="error_input"></span>
				<!----------------------------------------------------------->
					
					
				<!-------------------------- Weekly ----------------------------------->
				<div id="weekly">
                      
						<p>Every: <input type="number" name="weeks" value="1"> <span>Week(s) on :</span> </p>
                      				  
						<div id="button_up_down">
                            <button name="week_up"><i class="icon-caret-up"></i></button>
                        	  <button name="week_down"><i class="icon-caret-down"></i></button>
						</div>
                      
						<div id="checkbox_weekday">               	
							<input type="checkbox" name="monday" value="Montag">Montag<br>
							<input type="checkbox" name="tuesday" value="Dienstag" >Dienstag<br>
							<input type="checkbox" name="wednesday" value="Mittwoch">Mittwoch<br>
							<input type="checkbox" name="thursday" value="Donnerstag">Donnerstag<br>
							<input type="checkbox" name="friday" value="Freitag">Freitag<br>
						</div>
						<div id="checkbox_weekend">
							<input type="checkbox" name="saturday" value="Samstag">Samstag<br>
							<input type="checkbox" name="sunday" value="Sonntag">Sonntag<br>			
						</div>
				</div>
				<!------------------------------------------------------------------->				
				
				<!---------------------------- Monthly -----------------------------------> 
				<div id="monthly">
					
					<p name="monthly_option_one">
						<input type="radio" class="radio_month" name="month" value="one" checked>Day 
						<input type="number" name="day_of_month_number" value="1"> of the month(s) 
						
						<div id="button_up_down">
                            <button name="day_of_month_up"><i class="icon-caret-up"></i></button>
                            <button name="day_of_month_down"><i class="icon-caret-down"></i></button>
						</div>
					</p>
					<br><br>
					<p name="monthly_option_two">
						<input type="radio" class="radio_month" name="month" value="two"> The					
					
					<select name="monthly_day_option" disabled="disabled">
						<option value="first">first</option>
						<option value="second">second</option>
						<option value="third">third</option>
						<option value="fourth">fourth</option>
						<option value="last">last</option>
					</select>
					
					<select name="monthly_day_name" disabled="disabled">
						<option value="Monday">Monday</option>
						<option value="Tuesday">Tuesday</option>
						<option value="Wednesday">Wednesday</option>
						<option value="Thursday">Thursday</option>
						<option value="Friday">Friday</option>
						<option value="Saturday">Saturday</option>
						<option value="Sunday">Sunday</option>
					</select>
					of the month(s)
					</p>
					
					<button name="select_months">Select Months</button>
				</div>
				<!---------------------------------------------------------------------->
				
				
				
				<!-------------------------- Once ---------------------------------->
				<div id="once">
					<div id="div_once">
						<acronym name="help_datepicker" title="Bitte auf das Input Feld klicken"><p>Run on: <input type="date" id="datepicker" size="30" ></p></acronym>
					</div>
				</div>
				<!---------------------------------------------------------------------->
				
				
				
				<!-------------------------- When idle ---------------------------------->
				<div id="when_idle">
					<p>When the computer has been idle for: <input type="number" value="10" > minute(s) </p>
					<div id="button_up_down">
                            <button name="week_up"><i class="icon-caret-up"></i></button>
                            <button name="week_down"><i class="icon-caret-down"></i></button>
                    </div>
				</div>
				<!------------------------------------------------------------------------>
				
			</fieldset>
			
			<button name="schedule_speichern">Speichern</button>			
		</form>	*/	
		echo '
        </div>	
	</div>	
	
	<div id="schedule" class="select_months">
			
			<div id="tab_header">
				<p>Select Months</p>			
			</div>
			
	   
			<div id="toggle_schedule">
			<form>
				<p class="info">Please select the months you would like the task to run.</p>
				<br><br><br>
				
							
				<fieldset name="select_months">
				<div id="months">
					<div id="select_months_left">
						<input type="checkbox" value="January" checked>January<br>
						<input type="checkbox" value="February" checked>February<br>
						<input type="checkbox" value="March" checked>March<br>
						<input type="checkbox" value="April" checked>April<br>
						<input type="checkbox" value="May" checked>May<br>
						<input type="checkbox" value="June" checked>June<br>
					</div>	
					<div id="select_months_right">
						<input type="checkbox" value="July" checked>July<br>
						<input type="checkbox" value="August" checked>August<br>
						<input type="checkbox" value="September" checked>September<br>
						<input type="checkbox" value="October" checked>October<br>
						<input type="checkbox" value="November" checked>November<br>
						<input type="checkbox" value="December" checked>December<br>
					</div>	
				</div>
				</fieldset>			
			</form>
	      
	
			</div>
	</div>
	
	
	
	<!------------ Advanced ------------------>
	<!****************************************>
	<div id="schedule" class="advanced_schedule_options" >
			
			<div id="tab_header">
			<p>Advanced</p>			
		</div>
		
	<div class="advanced_top">
		<legend>Advanced Schedule Options</legend>		
		
		<div id="start_date_advanced">';
		
		echo 'Start Date: <input type="date" name="start_date" id="datepicker_start" value="'.$return[0]['StartDate'].'" />';
		echo '
		</div>
		<div id="end_date_advanced">
		<input type="checkbox" name="checkbox_datepicker_end">';
		echo 'End Date: <input type="date" id="datepicker_end" value="'.$return[0]['EndDate'].'" />';
		echo '
		</div>
		<fieldset class="advanced_bottom">			
			<legend><input type="checkbox" name="checkbox_legend" value="checkbox_legend" checked> Repeat task</legend>
			
			<div id="all_advanced"> <!-- All Advanced -->
			
			<div class="advanced_bottom">
			Every: 
				<input type="number" value="10" name="a_min_hours" maxlength="5">
					<select>
						<option value="minutes">minutes</option>
						<option value="hours">hours</option>
					</select>
				<p><span name="error_input_advanced"></span></p>	
			</div>
			<br><br>
			<p>Until: </p>
			<div id="div_radio_button_advanced">
				<input type="radio" name="radio_advanced" value="one"> Time: <input type="time" name="start_time_advanced" id="start_time_advanced" value="12:00" maxlength="5" min="00:00" max="24:00"><br>
				<input type="radio" name="radio_advanced" value="two"> Duration: <input type="number" name="input_hours_advanced" value="1" disabled="disabled"> hour(s) <input type="number" name="input_minutes_advanced" value="0" disabled="disabled"> minute(s) <br>
			</div>
			<p><span class="error_advanced_until"></span></p>
			<br><br>
			<input type="checkbox"> If the task is still running, stop at this time.
			</div> <!-- All Advanced -->
		</fieldset>
	
	</div>
   
	</div>
	
	
	</div>          <!------- SORTABLE --------->
	';
}
if($page == 'settings')
{
	echo '
	<div id="tab_title">
	    <div id="title">
	        <img src="../../img/icons/settings20.png" name="settings_img" /><p>Settings</p>
	    </div> 
	</div>
	
	<div id="sortable">       <!------- SORTABLE --------->
	
	<form action="job_info_auswertung.php" method="POST">
	
	<div id="scheduled_task_completed">	
			<div id="tab_header">
				<p>Scheduled Task Completed</p>
			</div>
			
		<div id="scheduled_task_completed_input">
			<p>';
			
			if($return[0]['DeleteTaskIfNotRescheduled'] == "Enabled")
			{				
				echo '<input type="checkbox" disabled="disabled" name="delete_task_ifnot_scheduled" checked />Delete the task if it is not scheduled to run again.</p><br>';
			} 
			else 
			{
				echo '<input type="checkbox" disabled="disabled" name="delete_task_ifnot_scheduled"  />Delete the task if it is not scheduled to run again.</p><br>';
			}		
			
			$str = explode(":",$return[0]['StopTaskIfRunsXHoursandXMins']);		
			echo '<input type="checkbox" disabled="disabled" name="stop_task_if_runs_for" checked="checked" />Stop the task if it runs for: <input type="number" name="hours" value="'.$str[0].'" disabled="disabled"> hour(s) <input type="number" name="minutes" value="'.$str[1].'" disabled="disabled"> minute(s).';		
		echo '
		</div>
	</div>'; 	 
	/*
	<div id="idle_time">
        <div id="tab_header">
			<p>Idle Time</p>			
		</div>
        
        <div id="idle_time_input">
        <p><input type="checkbox" name="idle_at_least" value="idle_at_least" > Only start the task if the computer has been idle for at least: </p><br>
        <div id="disable">
            <p class="idle_p_one"><input type="number" name="idle_at_least_minutes" value="60" disabled="disabled"> minute(s) </p>            
            <p class="idle_p_two">If the computer has not been idle that long, retry for up to: </p><br>
            <p class="idle_p_three"><input type="number" name="idle_retry" value="10" disabled="disabled"> minute(s) </p>
            <span class="idle_time_error" style="color:red;"></span><br><br>
        </div>
        <p><input type="checkbox" name="stop_task_if"> Stop the task if the computer ceases to be idle. </p><br>
        </div>
	</div>
	*/
	echo'
	<div id="power_management">
    	<div id="tab_header">
			<p>Power Management</p>		
		</div>
    	    
    	<div id="power_management_input">';
			$batteries = explode(", ", $return[0]['PowerManagement'] );
			if ( $batteries[1] == "No Start On Batteries" ) {
			echo'
    	    <p><input type="checkbox" disabled="disabled" name="dont_run_on_batteries" checked>Don´t start the task if the computer is running on batteries. </p><br>
    	    <p><input type="checkbox" disabled="disabled" name="battery_mode_begins" checked> Stop the task if batterie mode begins. </p><br>		
			';
			}
			else {
			echo ' 
			<p><input type="checkbox" disabled="disabled" name="dont_run_on_batteries" >Don´t start the task if the computer is running on batteries. </p><br>
    	    <p><input type="checkbox" disabled="disabled" name="battery_mode_begins" > Stop the task if batterie mode begins. </p><br>		
			';
			}
			echo'
    	    <!--<p><input type="checkbox" name="wake_computer_to_run"> Wake the computer to run this task. </p><br>-->
    	</div>
	</div>

	</form>

	</div>      <!------- SORTABLE --------->
	<!--<input type="submit" value="Speichern">-->
	';
}
if($page == 'change') 
{
	echo '		
	<div id="tab_title">
		<div id="title">
			<img src="../../img/icons/64/725.png" name="change_task_img" /><p>Change </p>
		</div>
	</div>		
	<div id="change">			
		<div id="tab_header">
			<p>Change</p>
		</div>
		<div id="options">
			<form action="./change/changeTask.php" method="POST" name="options">
				<input type="hidden" name="/TN" value="' .$_GET['id']. '" />			
				<input type="hidden" name="/S" value="'.$return[0]['HostName'].'" />			
				<p>Task to run: </p>
				<input type="text" name="/TR" value="" />				
				<div id="options_time">
					<div id="left">
						<p>Startzeit: </p>
						<input type="time" name="/ST" />
					</div>
					<div id="right">
						<p>Endzeit: </p>
						<input type="time" name="/ET" />
					</div>
					<span class="error_change"></span>
				</div>
				<div id="options_date">
					<div id="left">
						<p>Startdatum: </p>
						<input type="date" name="/SD" id="datepicker_option_start" />
					</div>
					<div id="right">
						<p>Enddatum: </p>
						<input type="date" name="/ED" id="datepicker_option_end" />
					</div>
				</div>
				<p>Nach der Ausführung löschen ? 
				<input type="checkbox" name="/Z" /> </p>
				<input type="hidden" name="function" value="update" />		
				<input type="submit" name="submit" value="Speichern" />
			</form>
		</div>		
	</div>
	<div id="run_task">
		<img src="../../img/icons/run.png" name="run_task" title="Run ?!" />
	</div>
	';
}
if( $page == 'change_history' )
{ 
	echo '
	<div id="tab_title">
	    <div id="title">
	        <img src="../../img/icons/64/825.png" name="change_history_img" /><p>Change History</p>
	    </div> 
	</div>
	
	<div id="">         <!------- SORTABLE --------->
	        
	<div id="change_history">
	    <p><img src="../../img/icons/128/501.png" name="stop"><br/> In Bearbeitung </p>
	    <div id="tab_header">';
	
		define("P" , "\\\groupad1.com\data\Apps");
		define("W" , "\\\groupad1.com\data");
		$info = getTaskInfo();
		
		$filename =  $info[0]['TaskToRun'] ;
		$_filename = explode(":", $filename);	
		
		if ( $_filename[0] == "w" || $_filename[0] == "W" ) {
			$path = W . $_filename[1];		
		}
		else if ( $_filename[0] == "P" || $_filename[0] == "p" ) {
			$path = P . $_filename[1];		
		} 
		else {
			$path = $filename;
		}
		
		$command = "powershell.exe ls " . escapeshellarg($path);
		$output = shell_exec($command. "2>&1");
		echo "<pre>";
		echo "<div id='infos'>" . $output . "</div>";
		echo "</pre>";
		echo '	
	    </div>
	</div>
	</div>        <!------- SORTABLE --------->
	';
}
if($page=='comments')
{ 
	echo '
	<div id="tab_title">
	    <div id="title">
	        <img src="../../img/icons/comment.png" name="comments_img" /><p>Comments</p>
	    </div> 
	</div>
	                
	<div id="add_comments">    
	    <div id="tab_header">
	        <p>Add a new comment</p>   
	    </div>      
	    <textarea rows="4" cols="50" placeholder="Enter your comment in here..."></textarea>
	    <button name="add_comment" value="Add Comment"><i class="icon-plus"> Add Comment</i></button>    
	</div>
	
	<div id="comments">';         
	    if(isset($_GET['id'])){
			getComments($_GET['id']);
		}      
	echo '    
	</div>
	';
}
if( $page == 'docs' ) 
{
	echo '
	<div id="tab_title">
	    <div id="title">
	        <img src="../../img/icons/64/560.png" name="docs_img" /><p>Documents</p>
	    </div> 
	</div>
	
	<div id="docs">	
		<div id="docs_header">
			<p>Hier kann eine Dokumentation für den Job hochgeladen werden. </p>
			<p>Nach erfolgreichem Upload wird ein Ordner angelegt in dem das Dokument gespeichert wird.</p>
		</div>
		<div id="upload">
		<!-- AJAX REALISIERUNG -->
		<!--<form enctype="multipart/form-data" method="POST" action="job_info_auswertung.php" id="form_docs">-->
			<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
			<input type="file" name="file" id="file" />
			<input type="submit" value="Abschicken" name="submit_docs" />
		<!--</form>	-->
		</div>
		<div id="files"> ';
		if(isset($_GET['id'])){		
			getDocuments();
		}
		echo '	
		</div>
	</div>
	';
}
if( $page == 'abteilung' ) 
{
	echo '
	<div id="Abteilung">	
		<div id="tab_title">
			<div id="title">
				<img src="../../img/icons/arrows.png" name="abteilung" /><p>Abteilung</p>			
			</div><br /> 
			<p>Welche Abteilung soll bei fehlerhaftem Start des Task informiert werden ?<br />
			Hierfür einfach die entsprechende Abteilung aus der linken Auswahl in den rechten Bereich ziehen, und abspeichern.</p>
		</div>
		<i class="icon-chevron-right"></i><i class="icon-chevron-left"></i>
		<div id="Liste">';
			echo getAbteilungen();
			echo '
		</div>
		<div id="Betrifft" class="droppable">';
			echo getSavedAbteilungen();
			echo '			
		</div>
		<button name="saveAbteilung">Speichern</button>
	</div>
	';
}

?>
</div>
</div>
<!-- Background picture -->

<div id="background-pic">
	<img src="../../img/icons/job.png" name="job_pic" class="job_pic" />
</div>

<!-- Footer --> 

<div id="footer">
	<p>&copy; Lindorff 2013</p>
</div>
</body>
</html>