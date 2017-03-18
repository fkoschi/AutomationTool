$(document).ready(function(){
	$("#loading").fadeOut("slow");	
	$("#content").css("opacity","1.0");		

/**
  * INFO
**/
	setTimeout( function() {
		$("#info").fadeOut();
	} , 6000); 
/** 
  * WERTE 
  **/
  $("i.icon-chevron-down").click( function() {
		$("#werte").toggle();
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
  * PFLICHTFELDER 
**/
	$("input[name='submit']").click(function(event){
			
		var error_counter = 0;
		var error_array = ["Fehlende Felder: \n"];
		
		// Pflichtfelder 
		if ( $("input[name='/TN']").val() == "" ) {
			error_counter += 1;
			error_array.push(" - Aufgabenname  \n");
		} 
		if ( $("input[name='/TR']").val() == "" ) {
			error_counter += 1;
			error_array.push(" - Auszufuehrende Aufgabe \n");
		} 
		
		// Beenden ? 
		if ( $("input[name='/K']").is( ':checked' )  ) {
			if ( $("input[name='/ET']").val() == "" && $("input[name='/DU']").val() == "" ) {
				//error				
				error_counter += 1;
				error_array.push(" - Dauer oder Endzeit \n");				
			} 
		}
				
		// SUBMIT
		if ( error_counter <= 0 ) {
			$("form").submit();
			$("#loading").append("<i class='icon-spinner icon-spin icon-large'></i> Loading...").show();	
			$("#content").css("opacity","0.2");
		} else {
			event.preventDefault();
			alert(error_array);
		}
		
	});
/** --------- **/	
/** 
  * STARTZEIT - ENDZEIT - DAUER
**/
	$("input[name='/ST'] , input[name='/ET'] , input[name='/DU']").change( function() {
		var eingabe = [];
		eingabe = $(this).val();
		
		var h = eingabe.charAt(0) + eingabe.charAt(1);
		var m = eingabe.charAt(3) + eingabe.charAt(4);
		
		if( eingabe.length == 4	) {
			if( $.isNumeric(eingabe) ) {				
				if ( parseInt(h) <= 23 && parseInt(m) > 59  ) {
					$(this).val( h + ":59" );
				}
				else if ( parseInt(h) > 24 && parseInt(m) <= 59 ) {
					$(this).val( "23:" + m );
				}				
				else {
					$(this).val( eingabe[0] + eingabe[1] + ":" + eingabe[2] + eingabe[3] );
				}
			}
			else {
				$(this).val("");
			}
		} 
		if ( eingabe.length == 5 ) {
			if ( eingabe.charAt(2) == ":" ) {
				if ( $.isNumeric( eingabe.charAt(0) ) == false || $.isNumeric( eingabe.charAt(1) ) == false || $.isNumeric( eingabe.charAt(3) ) == false || $.isNumeric( eingabe.charAt(4) ) == false ) {
					alert("Bitte Ziffern eingeben");
					$(this).val("");
				}			
			} 
			else {
				$(this).val("");
			}
		
			if ( parseInt(h) > 24 ) {				
				$(this).val( "23:" + m );
			}
			else if ( parseInt(m) >= 59 && parseInt(h) > 23 ) {
				$(this).val( "24:00");
			}
			else if ( parseInt(m) > 59 ) {
				$(this).val( h + ":59" );
			}
		} 
		else if( eingabe.length < 4 || eingabe.length > 5){
			$(this).val("");			
		}
	});
/** 
  * MONATE 
   **/
	$("input[name='/M']").change( function() {
		if( $(this).val() == "*" ){
			$("#monate_check").hide();
		} else {
			$("#monate_check").show();
		}
	});	
/** 
  * TAGE
   **/ 
	$("input[name='/D']").change( function() {
		if( parseInt($(this).val()) > 31 ) {
			$(this).val("31");
		}
		if( parseInt($(this).val()) < 1 ) {
			$(this).val("1");
		}
	});
/** 
  * WENN XML -> KEIN VISTA 
	**/
	$("input[name='/XML']").change(function() {
		if ( $(this).val() != "" ) {
			$("#vista").hide();
		} else {
			$("#vista").show();
		}
	});
/** 
  * SCHEDULE TYPE CHANGE
  **/
	// Schedule Type 
	// Felder nach Wahl des Schedule Typ
	// ein - bzw. ausblenden
    $("#zeitplan").change(function(){
       var thisOption = $("#zeitplan :selected").val();
       
	   // Parameter Feld für den Zeitplan-Typ
	   var parameter = $("input[name='/MO']");
       
	   switch(thisOption){
        
        case "MINUTE":
            $("#intervall , #delay, #monate ,#tage ").hide();			
            $("#endzeit , #dauer , #beenden , #startdatum , #enddatum ").show();
			parameter.removeAttr("disabled").val("");	
			$("input[name='/D']").removeAttr("disabled");
            break;
        case "HOURLY":
            $("#intervall , #delay, #monate , #tage").hide();
            $("#endzeit , #dauer , #beenden , #startdatum , #enddatum , #tage_check ").show();
			parameter.removeAttr("disabled").val("");
			$("input[name='/D']").removeAttr("disabled");
            break;
        case "DAILY":
            $("#intervall , #endzeit , #dauer , #beenden , #startdatum , #enddatum , #tage_check, #tage").show();
            $("#delay , #monate").hide();
			parameter.removeAttr("disabled").val("");
			$("input[name='/D']").removeAttr("disabled");
            break;
        case "WEEKLY":
            $("#intervall , #endzeit , #dauer , #beenden , #startdatum , #enddatum , #tage_check ").show();
            $("#delay, #monate, #tage").hide();
			$("input[name='/D']").removeAttr("disabled");
			parameter.removeAttr("disabled").val("");
            break;
        case "ONCE":
            $("#intervall , #endzeit , #dauer , #beenden ").show();
            $("input[name='/ST']").focus();
            $("span[class='/ST']").show().fadeOut(8600);			
            $("#startdatum , #enddatum , #delay , #tage , #monate").hide();
			
			parameter.attr("disabled","disabled").val("");
            break;
        case "ONSTART":
            $("#intervall , #endzeit , #dauer , #beenden , #startdatum , #enddatum , #tage , #monate ").hide();
            $("#delay").show();
			parameter.attr("disabled","disabled").val("");
            break;
        case "ONLOGON":
            $("#intervall , #endzeit , #dauer , #beenden , #startdatum , #enddatum , #tage, #monate").hide();
            $("#delay").show();
			parameter.attr("disabled","disabled").val("");
            break;
        case "ONIDLE":
            $("input[name='Parameter'] , #enddatum , #startdatum , #beenden , #intervall , #endzeit , #dauer , #tage, #monate").hide();
            parameter.attr("disabled","disabled").val("");
			break;
        case "MONTHLY":
            $("#intervall , #endzeit , #dauer , #beenden , #startdatum , #enddatum ").show();
            $("#delay , #tage_check, #monate, #tage").hide();
			parameter.removeAttr("disabled").val("");			
			$("input[name='/D']").attr("placeholder", "1 - 31").removeAttr("disabled");			
            break;
        case "ONEVENT":
            $("input[name='Parameter'] , #delay").show();
            $("#enddatum , #startdatum , #intervall , #endzeit , #dauer , #beenden , #tage, #monate").hide();
			parameter.removeAttr("disabled").val("");
            break;        
		
       } 
    
		
	});
/**
  * PARAMETER - CHANGE 
  **/ 	
	$("input[name='/MO']").change( function() {
		var thisOption = $("#zeitplan :selected").val();
		var parameter = $(this);
		switch(thisOption) {
			case 'MINUTE' : 
				if ( parseInt(parameter.val()) > 1439 ) {
					parameter.val("1439");
				} 
				if ( parseInt(parameter.val()) < 1 ) {
					parameter.val("1");
				}
				if ( $.isNumeric(parameter.val()) == false ) {
					alert("Bitte eine Ziffer als Wert eingeben");
					parameter.val("");
				}
				break;
			case 'HOURLY' :
				if ( parseInt(parameter.val()) > 23 ) {
					parameter.val("23");
				} 
				if ( parseInt(parameter.val()) < 1 ) {
					parameter.val("1");
				}
				if ( $.isNumeric(parameter.val()) == false ) {
					alert("Bitte eine Ziffer als Wert eingeben");
					parameter.val("");
				}
				break;
			case 'DAILY' :
				if ( parseInt(parameter.val()) > 365 ) {
					parameter.val("365");
				} 
				if ( parseInt(parameter.val()) < 1 ) {
					parameter.val("1");
				}
				if ( $.isNumeric(parameter.val()) == false ) {
					alert("Bitte eine Ziffer als Wert eingeben");
					parameter.val("");
				}
				break;
			case 'WEEKLY' :
				if ( parseInt(parameter.val()) > 52 ) {
					parameter.val("52");
				} 
				if ( parseInt(parameter.val()) < 1 ) {
					parameter.val("1");
				}
				if ( $.isNumeric(parameter.val()) == false ) {
					alert("Bitte eine Ziffer als Wert eingeben");
					parameter.val("");
				}
				break;
			case 'MONTHLY' :
				if ( parseInt(parameter.val()) > 12 ) {
					parameter.val("12");
				} 
				if ( parseInt(parameter.val()) < 1 ) {
					parameter.val("1");
				}
				if ( $.isNumeric(parameter.val()) == false ) {
					alert("Bitte eine Ziffer als Wert eingeben");
					parameter.val("");
				}
				break;
		}
		
	});

/** 
  * LEERLAUFZEIT - CHANGE 
  **/
	$("input[name='/I']").change( function() {
		var value = $(this).val();
		if ( parseInt(value) > 999 ) {
			$(this).val("999");
		}
		if ( parseInt(value) < 1 ) {
			$(this).val("1");
		}
		if ( $.isNumeric(value) == false ) {
			alert("Bitte eine Ziffer als Wert angeben.");
			$(this).val("");
		}
	});	
	
/**
  * INTERVALL - CHANGE 
**/
	$("input[name='/RI']").change( function() {
		var value = $(this).val();
		if ( parseInt(value) > 599940 ) {
			$(this).val("");
		}
		if ( parseInt(value) < 1 ) {
			$(this).val("");
		}
		if ( $.isNumeric(value) == false ) {
			alert("Bitte eine Ziffer als Wert angeben.");
			$(this).val("");
		}
	});
	
	$("#sortable").sortable();
    
/** 
  * DATEPICKER 
W  **/
    $("#datepicker_start , #datepicker_end").datepicker({
        dateFormat: 'dd/mm/yy',
		changeMonth: true,
		changeYear: true
    });
    
});