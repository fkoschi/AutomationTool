$(document).ready(function(){	
		
	google.setOnLoadCallback(drawChart());
	
	var von = $("input[name='von_zeit']").val();	
	var bis = $("input[name='bis_zeit']").attr("id");
	$("tbody").load('./monitoring/getTableContent.php?function=tbody&von=' + von + '&bis=' + bis);
	
	/** 
	  * Body Bereich aktualiesieren über den Klick auf das refresh Icon
	  **/
	$("i.icon-refresh").click(function(){
		drawChart();
		var von = $("input[name='von_zeit']").val();
		var bis = $("input[name='bis_zeit']").attr("id");
		$("tbody").load('./monitoring/getTableContent.php?function=tbody&von=' + von + '&bis=' + bis);
		setUpdatedTime();
	});
	/** 
      * Aktualisiert am: --> Update 
	  **/
	function setUpdatedTime() {
		var d = new Date();
		var hour = d.getHours();
		var hour3 = hour + 3;
		var min = d.getMinutes();
		var sec = d.getSeconds();
		$("p.timeUpdated span").text(hour + ":" + min + ":" + sec);
		$("input[name='von_zeit']").val(hour + ":" + min);
		$("input[name='bis_zeit']").val(hour3 + ":" + min);
	}
	/** 
	  * CHART ZEICHNEN 
	  **/
	function drawChart() {
		var container = document.getElementById('timeline_one');
		var chart = new google.visualization.Timeline(container);
		var dataTable = new google.visualization.DataTable();
		var Von = $("input[name='von_zeit']").val();
		var Bis = $("input[name='bis_zeit']").val();

		dataTable.addColumn({ type: 'string', id: 'HostName' });
		dataTable.addColumn({ type: 'string', id: 'Name' });
		dataTable.addColumn({ type: 'date', id: 'Start' });
		dataTable.addColumn({ type: 'date', id: 'Start' });		
		$.ajax({
			type: 'GET',
			contentType: "application/json; charset=utf-8",
			url: '../php/monitoring/getInfoTimeline.php',
			data: {
				'option' : 'today',
				'Von' : Von,
				'Bis' : Bis
			},
			dataType: "json",
			async: false,
			success: function(data) {			
		
				var TaskName = [];
				var NextRunTime = [];
				var StartTime = [];
				var HostName = [];		
				var splittedHostName = [];
				var estimatedTime = [];
				var counter = [];	
			
				for(var i=0; i<data.length; i++)
				{	
					splittedHostName[i] = data[i].HostName.split('.');
					TaskName[i] = data[i].TaskName;
					NextRunTime[i] = data[i].NextRunTime;
					StartTime[i] = data[i].StartTime;				
					HostName[i] = splittedHostName[i][0];
					estimatedTime[i] = data[i].estimatedTime;
					counter[i] = data[i].counter;
				}
				
				var Start = [];
				var i = 0;
				
				$.each( StartTime, function(index, val) {
					Start[i] = val.toString().split(":");
					i++;
				});							
				
				for(var i=0; i<TaskName.length; i++)
				{
					for(var i=0; i<Start.length; i++)
					{
						for(var i=0; i<HostName.length; i++)
						{
							for (var i=0; i<estimatedTime.length; i++) 
							{
								for (var i=0; i<counter.length; i++) 
								{	
									if( counter[i] != 0 )
									{
										dataTable.addRows([
										[ HostName[i], TaskName[i] , new Date(0,0,0,Start[i][0],Start[i][1],0),  new Date(0,0,0,Start[i][0],parseInt(Start[i][1]) + ( estimatedTime[i] / counter[i] ) ,0) ]		
										]);
									}
									else 
									{
										dataTable.addRows([
										[ HostName[i], TaskName[i] , new Date(0,0,0,Start[i][0],Start[i][1],0),  new Date(0,0,0,Start[i][0],parseInt(Start[i][1]) + 5 ,0) ]		
										]);
									}									
								}																
							}								
						}
					}
				}			
			}		
		});
		
	
		var options = {
			timeline: { colorByRowLabel: true},	
			height: 300
		};  
	 
		var nRows = dataTable.getNumberOfRows();
		/** 
		  * CHART NUR ZEICHNEN WENN INHALT ÜBERMITTELT 
		  **/
			if ( nRows > 0 ) {
				chart.draw(dataTable);  
			} else {
				// Anzeige bleibt unverändert
			}
	
	} // FUCNTION DRAW CHART  
			
	/** 
	  * EROOR MESSAGE ANZEIGEN 
	  **/
	function showError(text){
		$("span[class='error_zeitgrenze']").text(text).show().fadeOut(2000);
			$("span[class='error_zeitgrenze']").css({
				"color" : "red",
				"margin-left": "380px"
			});
	}
	/** 
	  * EINGABE VERGLEICHEN 
	  **/
	function compareInput(von, bis){
		var splitVon = von.split(":");
		var splitBis = bis.split(":");
		
		if( parseInt(splitVon[0]) >= parseInt(splitBis[0]) )
		{	
			showError("Falsche Eingabe");
			return false;
		}
		else
		{
			var von = $("input[name='von_zeit']").val();
			var bis = $("input[name='bis_zeit']").val();
			$("input[name='von_zeit']").attr("id", von);
			$("input[name='bis_zeit']").attr("id", bis);
			return true;
		}
	}
	/** 
	  * EINGABEGRENZEN SETZEN 
	  **/
	function setInputBorder(one,two){
		if(one < 0){
			one = '00';
		}
		if(one > 24){
			one = 23;
		}
		if(two < 0){
			two = 00;
		}
		if(two > 59){
			two = 59;
		}		
		var value = one + ':' + two;		
		return value;
	}
	
	/** 
	  * SUCHE
	  **/
	$("i[class='icon-search']").click(function(){
		if( $("input[name='bis_zeit']").val() == '' )
		{	
			var von = $("input[name='von_zeit']").val();	
			var bis = $("input[name='bis_zeit']").attr("placeholder");
			$("tbody").load('./monitoring/getTableContent.php?function=tbody&von=' + von + '&bis=' + bis);
			setUpdatedTime();
		}
		else 
		{			
			var von = $("input[name='von_zeit']").val();	// Nur der zweite Wert darf gesetzt werden
			var bis = $("input[name='bis_zeit']").val();
			compareInput(von, bis);
			$("tbody").load('./monitoring/getTableContent.php?function=tbody&von=' + von + '&bis=' + bis);
			drawChart();
			setUpdatedTime();
		}	
	});
	/** 
	  * EINGABE VALIDIEREN 
	  **/
	$("input[name='von_zeit'] , input[name='bis_zeit']").change(function(){		
		if($.isNumeric($(this).val())){
			if($(this).val().length > 5 || $(this).val().length < 4 ){
				$(this).val("");
				showError("Bitte vier Ziffern eingeben.");		
			} 
			else if($(this).val().length == 4){
				var pos = new Array();
				for( var i = 0; i<$(this).val().length; i++){
					pos[i] = $(this).val().charAt(i);
				}	
				var _one = pos[0] + pos[1];
				var _two = pos[2] + pos[3];				
				var result = setInputBorder(_one, _two);				
				$(this).val( result );				
			}
			
		} 
		else {
			if($(this).val().length == 5){
				if($(this).val().charAt(2) == ':'){
					var pos = new Array();
					for( var i = 0; i<$(this).val().length; i++){
						pos[i] = $(this).val().charAt(i);
					}	
					var _one = pos[0] + pos[1];
					var _two = pos[3] + pos[4];									
					var result = setInputBorder(_one, _two);				
					$(this).val( result );
				} else {
					showError("Falsche Eingabe");
					$(this).val("");
				}
			} else {
				$(this).val("");
				showError("Bitte vier Ziffern eingeben.");					
			}
		}
	});	    
	/**  
	  * PAGE REFRESH 
	  **/
	var refreshId = setInterval(function() {						
			var von = $("input[name='von_zeit']").attr("id");
			var bis = $("input[name='bis_zeit']").attr("id");
			$("tbody").load('./monitoring/getTableContent.php?function=tbody&von=' + von + '&bis=' + bis);
			setUpdatedTime();
    }, 60000);  // jede Minute aktualisieren
	
	
});	