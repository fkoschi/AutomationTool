<?php

	
	include_once('./php/Controller/DBclass.php');
	//include('./php/Controller/UPDATEDBclass.php');
	//require_once('./php/validate.php');

	$command = 'sudo wmic /node:fcap09.falcon.local cpu get loadpercentage';

	$command_ = 'dir';

	$output = shell_exec( escapeshellcmd( $command_ ) );
	
	echo '<pre>'.$output.'</pre>';
	exit;

	$exp = explode('LoadPercentage', $output); // String zerlegen -> LoadPercentage entfernen
	$percentage = $exp[1];
			$values = explode(' ', $percentage);	// Werte zerlegen
	$arr = array();
	foreach( $values as $strings ){
		if( strlen($strings) > 0){	
			$arr[] = $strings;		// Werte dem Array hinzufügen
		}
	}
	array_pop($arr); // Letztes Element löschen, da leer
	
	$header = array('Label', 'Value');
	$values_cpu_1 = array( 'CPU1', (int)(ltrim($arr[0])) );
	$values_cpu_2 = array( 'CPU2', (int)(ltrim($arr[1])) );
	$values_cpu_3 = array( 'CPU3', (int)(ltrim($arr[2])) );
	$values_cpu_4 = array( 'CPU4', (int)(ltrim($arr[3])) );
	$array = array();
	
	array_push($array, $header, $values_cpu_1, $values_cpu_2, $values_cpu_3, $values_cpu_4);
			
	echo json_encode($array);		


?>
<!DOCTYPE HTML5>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bibbi</title>
<link rel="shortcut icon" type="image/x-icon" href="localhost/Tool/img/favicon/favicon_.ico">
</head>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"  type="text/javascript"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"  type="text/javascript"></script>

<script type="text/javascript">
 	$(document).ready(function(){
 		$("button").click(function(){
 			var old_pw = $("input[name='old_pw']").val();
 			var new_pw = $("input[name='new_pw']").val();

 			if( old_pw == new_pw )
 			{
 				alert("Passwörter sind gleich !");
 			}
 			else 
 			{
 				alert("Passwörter sind ungleich !");
 			}
 		});
 	});
</script>
<body>
<div id="loading">
</div>
<div id="test">	


</div>
	<!--Altes Passwort : <input name="old_pw" type="password" value="" /><br />
	Neues Passwort : <input name="new_pw" type="password" value="" /><br />
	<button>Push ME</button>-->
</body>
</html>