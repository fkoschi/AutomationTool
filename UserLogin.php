
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Login</title>	
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/reset.css">
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"  type="text/javascript" ></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"  type="text/javascript" ></script>	

<!-- JQUERY -->
<script type="text/javascript">
$(function(){	
/**
  * PASSWORT VERGESSEN 
  **/
$("a[name='forgotpw']").click(function(event) {
	event.preventDefault();
	var Email = $("input[name='email']").val();
	
	$.ajax({
		type	: 	'POST',
		url		:	'login.php',
		data	: {
			'function' 	: 'resetpw',
			'Email'		: Email
		},			
		success	: function(data) {				
			if ( data.indexOf('Fehler') != -1 ){
				$("#error_pw").html("<p>Kein Benutzerkonto für diese Email-Adresse gefunden !</p>").css("color","red").show().fadeOut(3000);				
			} 	
			else {				
				$("a[name='forgotpw']").css("display","none");
				$("#error_pw").html("<p>Ihr neues Passwort wurde Ihnen per Email zugeschickt !</p>").css("color","green");				
				setTimeout( function() {
					window.location.href = 'http://localhost/Tool/UserLogin.php';										
				}, 2000 );
			}			
		},
		error : function( xhr, status ) {
			console.log( "Status " + xhr.status);
		}
	});
});
/**
  * LOGIN 
  **/
$( "a[name='Login']" ).click(function(event){
    var usr = $("input[name='Username']").val();
    var pw = $("input[name='Password']").val();
	
	event.preventDefault();
	
    $.ajax({
      type: 'POST',
      url: 'login.php',	  
      data: { 
		'function': 'login',
		'Username': usr, 
		'Password': pw
		},
      success: function(data) {
		if(data == 0){
			$("#error").html("Keine Benutzerdaten für die Eingabe gefunden!");  	
			$('#error').fadeIn().delay(3000).fadeOut();
			} else if (data == 1) {
				window.location.href = "index.php";
			}
    	}
	
    });
  });
/**
  * ABOUT 
  * MOUSEENTER & MOUSELEAVE
  **/
$("a.a_one")
	.mouseenter(function() {
		$("#p_one").animate({
			left: '-=35px',
			opacity: '1'
		});	
	})
	.mouseleave(function() {
		$("#p_one").animate({
			left: '+=35px',    
			opacity: '0'
    });	
});
$("a.a_two")
	.mouseenter(function() {
		$("#p_two").animate({
			left: '-=20px',
			opacity: '1'
		});	
	})
	.mouseleave(function() {
		$("#p_two").animate({
			left: '+=20px',    
			opacity: '0'
    });	
}); 
$("a.a_three")
	.mouseenter(function() {
		$("#p_three").animate({
			left: '-=5px',
			opacity: '1'
		});	
	})
	.mouseleave(function() {
		$("#p_three").animate({
			left: '+=5px',    
			opacity: '0'
    });	
});
 /**
   * BALKEN AM RECHTEN RAND ZUR ANZEIGE DER DREI BUTTONS
   **/
$("#show_hide_about")
	.mouseenter( function() {
		$(this).css({
			background : "#E0E2E5",
			cursor : "pointer"
		});	
	})
	.mouseleave( function() {
		$(this).css("background","white");
	})
	.click( function() {
		$("#about").animate({
			left: '+=10px',
			opacity: '1'
		}).css("visibility","visible");
		$("#show_hide_about i").attr("class","icon-chevron-left");
		$("#show_hide_about").css("visibility","hidden");
	});
 
});
</script>
  
<!-- Logo -->
<img src="img/Lindorff_Logo.jpg" class="logo">
<div id="p">
<p>Kontroll und Management Tool</p><br><br><br>
</div>
<?php 
	if(!$_GET) 
	{
		echo '
		<!--
		<div class="icon-help">
			<acronym title="Kontroll- und Management Tool für automatisch laufende Jobs"><i class="icon-question-sign"></i></acronym>
		</div>
		-->
		
		  <!-- Eingabe -->
		  <div id="boxes">
		  <div class="input-prepend">
		    <span class="add-on"><i class="icon-user"></i></span>
		    <input class="span2" type="text" placeholder="Kürzel" id="Username" name="Username">
		  </div>
		  <br>
		  <div class="input-prepend">
		    <span class="add-on"><i class="icon-key"></i></span>
		    <input class="span2" type="password" placeholder="Password" id="Password" name="Password">
		  </div>
		  
		  </div>
		
		  <div id="error"></div>
		  <!-- -->
		  <br>
		  <div id="submit">   
		   
		   <!-- <input class="submit" type="submit" value="Login" name="submit" /> -->
			<a class="btn" href="" name="Login">
				Login
			</a>
			<a class="btn" href="UserLogin.php?option=forgotpw">
				<i class="icon-help"></i> Passwort vergessen ?
			</a>
		  
		  </div>
		';
	} 
	else 
	{
	echo '
	<div id="forgotpw">
	<p>Bitte geben Sie ihre Email-Adresse ein, <br /> und wir lassen Ihnen ein neues Passwort zukommen. </p>
	<input type="email" name="email" id="email" value="" /><br />
	<div id="error_pw"></div>
	<a class="btn" name="forgotpw" href="" >
		<i class="icon-ok"></i> Abschicken 
	</a>
	<a class="btn" name="back" href="./UserLogin.php">
		<i class="icon-double-angle-left"></i>		
		Zurück
	</a>
	</div>	
	';
	}
?>
 <div id="show_hide_about">
	<i class="icon-chevron-right"></i>
</div>

	<div id="about">
		<a href="./Impressum.html"  class="a_one"><img src="./img/icons/about_1.png" /></a>
		<a href="http://www.lindorff.com" target="_newtab" class="a_two"><img src="./img/icons/about_2.png" /></a>
		<a href="http://eynar.groupad1.com/de/Pages/default.aspx" target="_newtab" class="a_three"><img src="./img/icons/about_3.png" /></a>
	</div>
	<div id="about_text">
		<div id="p_one">
			<p>Impressum</p>
		</div>
		<div id="p_two">
			<p>Lindorff</p>
		</div>
		<div id="p_three">
			<p>Eynar</p>
		</div>
	</div>
	
	<div id="footer">
		<p>&copy; Lindorff 2013</p>
	</div>
</form>
</body>
</html>