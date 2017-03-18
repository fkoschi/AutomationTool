/**
  * 
  * TODO: function changeValue() überflüssig ?
  * 
  */
$(document).ready(function(){
		
    var str = $( "select[class='task_option'] option:selected" ).text();  
    $( "legend span" ).text(str);
    
    $("#daily").css("visibility","visible");
    
    var input = $( "#start_time").val();
    $( "span[class='task_start_time']").text(input);
    
	/**
	  * * * * * * * * 
	  * Checkbox Legend (Advanced)
	  *
	  */
	$( "input[name='checkbox_legend']" ).click( function() {		
		
		if( $( "input[name='checkbox_legend'] option:checked" ) ){
			$( "div[id='all_advanced']" ).toggle("blind",800);
		}
	
	});
	
	/** 
	  * * * * * * * * * * * * 
	  * Datepicker 
	  *
	  */
	$( "#datepicker , #datepicker_start, #datepicker_end ").datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true
	});
	$("#datepicker_option_start, #datepicker_option_end").datepicker({
		dateFormat: 'dd/mm/yy',
		changeMonth: true,
		changeYear: true
	});
    /**
      * * * * * * * * * *
      * Form Submit
      * verhindern, dass Formular abgeschickt wird !
      */
    $( "form" ).submit( function(event) {        
       //event.preventDefault(); 
    });
    
	
	/** 
	  * * * * *  * * * * * 
	  * Button Up/Down
	  * für monatlich
	  * 
	  */
	
	// Plus 
	$( "button[name='day_of_month_up']" ).click( function() {
		var set_day_of_month = $( "input[name='day_of_month_number']" ).val();
		
		if ( set_day_of_month == "31" ) {
			return false;
		}
		else {
			var set_day_of_month_add = parseInt(set_day_of_month) + 1;
			$( "input[name='day_of_month_number']" ).val(set_day_of_month_add);
			}
	});
	// Minus
	$( "button[name='day_of_month_down']" ).click( function() {
	   var set_day_of_month = $( "input[name='day_of_month_number']" ).val();
	   
	   if ( set_day_of_month == "1") {
		return false; 
	   }
	   else {
			var set_day_of_month_min = parseInt(set_day_of_month) - 1;
			$( "input[name='day_of_month_number']" ).val(set_day_of_month_min);
	   }
	});
	
	/** 
	  * * * * * * * * * * 
	  * Button Up/Down
	  * für Week(s)
	  *
	  */
	
	// Plus
	$( "button[name='week_up']" ).click( function() {
		var week = $( "input[name='weeks']" ).val();
		var week_add = parseInt(week) + 1;
		$( "input[name='weeks']" ).val(week_add);
		changeValue();
	});
	// Minus 
	$( "button[name='week_down']" ).click( function() {
		
		var week = $( "input[name='weeks']" ).val();
		
		if ( week == "1" ) {
			return false; 
			}
		else {
			var week_min = parseInt(week) - 1;
			$( "input[name='weeks']" ).val(week_min);
		}
		
		changeValue();
	});
	
    /** 
      * Button Up/Down
      * für Day(s)
      *
      */
    $( "button[name='day_up']" ).click( function(){
        var day = $( "input[name='days']").val();
        var day_add = parseInt(day) + 1;
        $( "input[name='days']").val(day_add);
        changeValue(); 
    } );
    
    $( "button[name='day_down']").click(function(){
        var day = $( "input[name='days']").val();
        if ( day == "1"){
            return false;
        }
        else {
            var day_min = parseInt(day) - 1;
            $( "input[name='days']").val(day_min);
       } 
       changeValue();  
    });
	
    /** 
	  * * * * * * * * ** * * *
	  * Advanced Button
	  *
	  * 
	  */
	
	$( "input[name='checkbox_datepicker_end']" ).click( function() {
		if( $( "input[name='checkbox_datepicker_end'] option:checked") ){
			$( "input[id='datepicker_end']" ).toggle("linear");		
			}		
	});
	
	
	/** 
	  * * * * * * * * * * * * 
	  * Input Advanced validieren 
	  *
	  */
	$( "input[name='a_min_hours']" ).change( function() {
		var valid = new Array("0","1","2","3","4","5","6","7","8","9");
		var inhalt = $( "input[name='a_min_hours']" ).val();
		
		for( var i=0; i<inhalt.length; i++ ) {
			if ( valid.indexOf(inhalt.charAt(i)) == -1 ) {
				$( "input[name='a_min_hours']" ).css("border","1px solid red");
				$( "span[name='error_input_advanced']" ).show();
				$( "span[name='error_input_advanced']" ).text("Bitte korrekten Wert eingeben !").css("color","red");
			}
			else {
				$( "span[name='error_input_advanced']" ).hide();
				$( "input[name='a_min_hours']" ).css("border","0px solid black");
			}
		}
	});
	/** 
	  * * * * * * * * * * * * * * * * * * 
	  * Input Day(s) validieren 
	  * 
	  */
	$( "input[name='days']" ).change( function() {
		var valid = new Array("0","1","2","3","4","5","6","7","8","9");
		var inhalt = $( "input[name='days']" ).val();
		
		for( var i=0; i<inhalt.length; i++ ) {
			if ( valid.indexOf(inhalt.charAt(i)) == -1 ) {
				$( "input[name='days']" ).css("border","1px solid red");
				$( "span[name='error_input']" ).show();
				$( "span[name='error_input']" ).text("Bitte korrekten Wert eingeben !").css("color","red");			
			}
			else {
				$( "span[name='error_input']" ).hide();
				$( "input[name='error_input']" ).css("border","0px solid black");
			}
		}
	});	 
	/** 
	  * * * * * * * * * * * * * *   
	  *	Input Advanced Until 
	  * validieren 
	  *
	  */
	$( "input[name='input_hours_advanced'] , input[name='input_minutes_advanced']" ).change( function() {
		var valid = new Array("0","1","2","3","4","5","6","7","8","9");
		var inhalt_links = $( "input[name='input_hours_advanced']" ).val();
		var inhalt_rechts = $( "input[name='input_minutes_advanced']" ).val(); 
		var error_links = 0;
		var error_rechts = 0;
		
		// Beide Input Felder überprüfen 
		
		for( var i=0; i<inhalt_links.length; i++ ) {
			if ( valid.indexOf(inhalt_links.charAt(i)) == -1 ) {
				error_links = 1;
			}			
		}
		
		for( var i=0; i<inhalt_rechts.length; i++ ) {
			if ( valid.indexOf(inhalt_rechts.charAt(i)) == -1 ) {				
				error_rechts = 1;
			}			
		}
		var minutes = parseInt(inhalt_rechts);
		if ( minutes > 59 ) {			
			$( "input[name='input_minutes_advanced']" ).val("59");
		}
		if ( minutes <= 0 ) {			
			$( "input[name='input_minutes_advanced']" ).val("0");
		}
		
		// Fehlermeldung Ausgabe
		if ( error_links == 1 || error_rechts == 1 ) {
			$( "span[class='error_advanced_until']" ).show();
			$( "span[class='error_advanced_until']" ).text("Bitte korrekte Eingabe tätigen !").css("color","red");
		}
		else {
			$( "span[class='error_advanced_until']" ).hide();
		}
	});
    /** 
      * * * * * * * * * * * * * * * * * *
      * 			Button 
      * Halbe Stunde/Stunde heraufsetzen 
      *
      */
    $( "button[name='time_up']").click(function(){
        var input = $( "#start_time" ).val();
        var split = input.split(":");        
        if (split['0'] >= 0 && split['0'] < 24 ) {
            if ( split['0'] == 23 && split['1'] == 30 ){
                $( "#start_time" ).val("00:00");
            }
            else {
                if( split['1'] == 30 ) {
                    var date_add = parseInt(split['0']) + 1;
					
                    $( "#start_time" ).val(date_add + ":" + "00");
                    }
                 else{
                    var date = parseInt(split['0']);					
                    $( "#start_time" ).val(date + ":" + "30");
                 }   
            }
            
        }        
        else {
            return false;
        }   
        changeValue();
   }); 
   
   /**
     * * * * * * * * * * * * * * *  *
     *  			Button 
     * Halbe Stunde/ Stunde herabsetzen
     *
     */
     $( "button[name='time_down']" ).click(function() {
        var input = $( "#start_time" ).val();
        var split = input.split(":");
        
        if(split['0'] >= 0 && split['0'] < 24 ) {
            if ( split['0'] == 0 && split['1'] == 00) {
                $("#start_time").val("23:30");
            }
            else {
                if( split['1'] == 00 ) {
                    var date_add = parseInt(split['0']) - 1;					
					$( "#start_time" ).val(date_add + ":" + "30");                    
					}
                 else{
                    var date = parseInt(split['0']);
                    $( "#start_time" ).val(date + ":" + "00");                    
                 }   
            }
            
        }
        else {
            return false;
        }
        changeValue();
     });
     
	 /** 
	   * 
	   * Funktion verändert Informationen über den Task 
	   *
	   */	
/*	   
     function changeValue() {
        
        // Uhrzeit
        var input = $( "#start_time" ).val();
        $( "span[class='task_start_time']").text(input).css("color","red");
        
        // Schedule Task Option
        
        /**
          * wenn Tag = 1 dann Täglich ausgeben
          * ansonsten die Anzahl an Tagen 
          *
          *
        var day = $( "input[name='days']").val();
        if ( day == 1) {
            var str = $( "select option:selected" ).text();
            $( "span[class='task_start_date']" ).text(str).css("color","red");
            }
        else {
            $( ".actual_info").text("soll").css("color","red");
            $( "span[class='task_start_date']" ).text("alle " + day + " Tage ").css("color","red");
            $( ".new_info").text(" werden.").css("color","red");
        }  

     }
    */
   
  /**
    * * * * * * * * * * * * * * * * * *
    * Input Feld start_time validieren 
    */
  $( "#start_time").change(function() {
    
	var valid = new Array("0","1","2","3","4","5","6","7","8","9",":");    
    var input = $( "#start_time").val();
    
	for( var i=0; i<input.length; i++) {
		
		if( valid.indexOf(input.charAt(i)) == -1 ) {			
			$( "#start_time").val("00:00");
			$( ".error_input_start_time" ).text("Fehlerhafte Eingabe").show().fadeOut(3000).css("color","red");		
		}
	
	}
	if ( input.length < 4 || input.charAt(2) != ":"  )   {
		$( "#start_time").focus();
		$( "#start_time").val("00:00");
        $( ".error_input_start_time" ).text("Fehlerhafte Eingabe").show().fadeOut(3000).css("color","red");		
		
        }
		
  });
    
   /* Div-Container können verschoben werden */ 
  //$( "#sortable" ).sortable();
	
   /* Pfeile erlauben das einfahren der Div-Container */ 
  $( "#arrow1" ).click(function() {
		$( "#toggle_task" ).toggle("blind", 800);
	});
  $( "#arrow2" ).click(function() {
		$( "#toggle_schedule" ).toggle("blind",800);
  });
   
   var schedule_options = new Array("#daily", "#weekly", "#monthly", "#once", "#at system startup", "#at logon", "#when_idle");
  
   var str = $( "select option:selected" ).val();   
   
  /**
	* * * * * * * * * * * *
	* Auswahloption Task
	* - Täglich
	* - Monatlich
	* ....
	*/
  $( "select[class='task_option']" ).change(function() {
   		
		$( "fieldset[name='select_months']" ).css("visibility","hidden");
		
        var str = $( "select[class='task_option'] option:selected" ).text();
	    $( "span[class='legend']" ).text(str).css("font-weight","bold");   
            
            for ( var i=0; i<=schedule_options.length; i++){
                $( schedule_options[i] ).css("visibility","hidden");
            }
            var id = $( "select option:selected" ).val();
			
            $( schedule_options[id] ).css("visibility","visible");
          

        /** 
		  * * * * * * * * * * * * * * * * 
		  * At System Startup & At Logon 
		  * Bereiche ausblenden 
		  */
		if (id == "4" || id == "5" ) {
			$( ".Advanced" ).css("visibility", "hidden");
			$( "#start_time" ).attr("disabled", "disabled");
			$( "fieldset" ).css("visibility", "hidden");
			$( "button[name='time_up']" ).css("visibility", "hidden");
			$( "button[name='time_down']" ).css("visibility", "hidden");			
		}
		else {			
			$( ".Advanced" ).css("visibility", "visible");
			$( "#start_time" ).removeAttr("disabled");
			$( "fieldset" ).css("visibility", "visible");
		 } 
       
	});
	
	
	/** 
	  * * * * * * * * * * * * *
	  * Task Option Monatlich
	  * 
	  * Radio Button - Click
	  */
	  
	$( "input[class='radio_month']" ).click(function() {
		var val = $( "input[class='radio_month']:checked" ).val();		
		
		if ( val == "one" ) {
			$( "input[name='day_of_month_number']").removeAttr("disabled");
			$( "select[name='monthly_day_option']" ).attr("disabled","disabled");
			$( "select[name='monthly_day_name']" ).attr("disabled","disabled");
		}
		else {
			$( "input[name='day_of_month_number']").attr("disabled","disabled");				
			$( "select[name='monthly_day_option']" ).removeAttr("disabled");
			$( "select[name='monthly_day_name']" ).removeAttr("disabled");
		}
		
	});
	/**
	  * * * * * * * * * * * * * * *  *
	  * Advanced Option Time/Duration
	  *
	  * Radio Button - Click
	  */
	$( "input[name='radio_advanced']" ).click(function() {
		var val = $( "input[name='radio_advanced']:checked" ).val();		
		
		if ( val == "one" ) {
			$( "input[name='start_time_advanced']").removeAttr("disabled");
			$( "input[name='input_hours_advanced']" ).attr("disabled","disabled");
			$( "input[name='input_minutes_advanced']" ).attr("disabled","disabled");
		}
		else {
			$( "input[name='start_time_advanced']").attr("disabled","disabled");				
			$( "input[name='input_hours_advanced']" ).removeAttr("disabled");
			$( "input[name='input_minutes_advanced']" ).removeAttr("disabled");
		}
		
	});	  
	  
	/*
	 * * * * * * * * * * * * * 
	 */
	 
	 
	 /** 
	   * * * * * * * * * * * *
	   * Button ( Select Months ) 
	   * 
	   */
	$( "button[name='select_months']" ).click(function() {
		$( "div[class='select_months']" ).css("visibility","visible");	
		$( "div[class='select_months']" ).toggle();		
	});   
	
	/**
	  * * * * * * * * * * * * * 
	  * 
	  * Button Advanced
	  */
	$( "button[class='Advanced']" ).click( function() {
		$( "div[class='advanced_schedule_options']" ).css("visibility","visible");				
		$( "div[class='advanced_schedule_options']" ).toggle();
	});
    
    /** 
      * * * * * * * *
      * SETTINGS
      *
      */
    $("input[name='idle_at_least']").click( function() {
        var thisCheck = $(this);
        if( thisCheck.is(':checked') ){
            $("input[name='idle_at_least_minutes'] , input[name='idle_retry']").removeAttr("disabled");
        }
        else {
            $("input[name='idle_at_least_minutes'] , input[name='idle_retry']").attr("disabled","disabled");
        }
                
        
    });
    
    $( "input[name='idle_at_least_minutes'] , input[name='idle_retry']" ).change( function() {
		var valid = new Array("0","1","2","3","4","5","6","7","8","9");
		var inhalt_links = $( "input[name='idle_at_least_minutes']" ).val();
		var inhalt_rechts = $( "input[name='idle_retry']" ).val(); 
		var error_links = 0;
		var error_rechts = 0;
		
		// Beide Input Felder überprüfen 
		
		for( var i=0; i<inhalt_links.length; i++ ) {
			if ( valid.indexOf(inhalt_links.charAt(i)) == -1 ) {
				error_links = 1;
			}			
		}
		
		for( var i=0; i<inhalt_rechts.length; i++ ) {
			if ( valid.indexOf(inhalt_rechts.charAt(i)) == -1 ) {				
				error_rechts = 1;
			}			
		}
		var minutes = parseInt(inhalt_rechts);
		if ( minutes > 59 ) {			
			$( "input[name='idle_at_least_minutes']" ).val("59");
		}
		if ( minutes <= 0 ) {			
			$( "input[name='idle_at_least_minutes']" ).val("0");
		}
		
		// Fehlermeldung Ausgabe
		if ( error_links == 1 || error_rechts == 1 ) {
			$( "span[class='idle_time_error']" ).show();
			$( "span[class='idle_time_error']" ).text("Bitte korrekte Eingabe tätigen !").css("color","red");
		}
		else {
			$( "span[class='idle_time_error']" ).hide();
		}
	});
    
    /**
      * * * * * * * * * * * * * 
      *         COMMENTS
      *
      */
 
        
      //Kommentar löschen
      //ID des Kommentares übergeben
      $("img[name='delete']").click(function(){
        var id = $("p[class='comment']").text();
        $.get("job_info_auswertung.php", { name: id});
            
      });    
      
      /**
        * * * * * * * * * * * * * * 
        * Scheduled Task Completed
        *
        */
        $("input[name='stop_task_if_runs_for']").click(function(){
           var thisCheck = $(this);
           if (thisCheck.is(':checked')){
                $("input[name='hours'] , input[name='minutes']").removeAttr("disabled");
           } 
           else {
                $("input[name='hours'] , input[name='minutes']").attr("disabled","disabled");
           }
        });
});
