$(function(){
    
	/** 
	  *	INFOBOX
	  **/
    setTimeout( function() { $("#infobox").fadeOut("blind"); }, 60000);
    /** 
      * * * * * * * * * * * * * * 
      * Radio-Button
      * 
      **/ 
    $("input[type='radio']").click(function(){
        var value = $("input[type='radio']:checked").val();
        
        if( value == "one" ){
            $("input[name='Ausschlussvorgangsliste']").removeAttr("disabled");
            $("input[name='Ausschlussvorgang']").attr("disabled","disabled").val("");
        }
        else {
            $("input[name='Ausschlussvorgang']").removeAttr("disabled");
            $("input[name='Ausschlussvorgangsliste']").attr("disabled","disabled").val("");
        }
    });
      
    
    /** 
      * * * * * * * * * * * * * *
      * Felder überprüfen bevor Submit 
      *
      **/
    $("form").submit(function(event) {
       
       var dateiname = $("input[name='dateiname']");
       var UID = $("input[name='UID']");
       var PW = $("input[name='PW']");
       var DB = $("input[name='DB']"); 
       var datepicker_von = $("#datepicker_von");
       var datepicker_bis = $("#datepicker_bis");
       var counter = 0
                 
       var arr = new Array(dateiname,UID,PW,DB,datepicker_von,datepicker_bis);
       $.each(arr, function(key, value){
            if( this.val() == "" ){
                this.css("border","1px solid red");
                counter = counter + 1;                               
            }
            else {
                this.css("border","0px solid gray");
            }
            
       });
       if(counter == 0) {
            return;
       }      
       
       event.preventDefault(); 
    });
        
    /** Datepicker **/
    $("#datepicker_von , #datepicker_bis , #datepicker_anschreiben ").datepicker({
        dateFormat: 'dd.mm.yy',
		changeMonth: true,
		changeYear: true
    });
   
   
   
   /** 
     * * * * * * * * * 
     *   MaxAnzahl
     * 
     */
   $("input[name='MaxAnzahl_checkbox']").click(function(){   
        var thisCheck = $(this);
        
        if( thisCheck.is(':checked')) {
            $("input[name='MaxAnzahl']").removeAttr("disabled");
          
        }
        else{
            $("input[name='MaxAnzahl']").val("");
            $("input[name='MaxAnzahl']").attr("disabled","disabled");   
        }       
       
   });
   /**
     * * * * * * * * * * * * * * * * 
     * Vorgang oder Vorgangsliste ?
     *
     */
   $("input[name='WV_Vorgang_Checkbox']").click(function(){
        var thisCheck = $(this);
        if ( thisCheck.is(':checked')) {
            $("input[name='WV_Vorgang']").removeAttr("disabled"); 
            $("input[name='Vorgangsliste']").attr("disabled","disabled").val(""); 
        }
        else {
            $("input[name='Vorgangsliste']").removeAttr("disabled");
            $("input[name='WV_Vorgang']").attr("disabled","disabled").val("");
        }
   });  
   
});