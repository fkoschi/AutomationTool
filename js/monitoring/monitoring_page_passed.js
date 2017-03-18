$(function(){
	var von = $("input[name='von_date']").val();
	var bis = $("input[name='bis_date']").val();
	$("tbody").load('../php/monitoring/getTableContent.php?function=getPassed&von=&bis=');	
	
	/** 
	  * DATEPICKER 
	  **/
	$("#datepicker_von, #datepicker_bis").datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true
	});
	/**
	  * ERROR MESSAGE ANZEIGEN 
	  **/
	function showError(text){
		$("span[class='error_grenze']").text(text).show().fadeOut(2000);
			$("span[class='error_grenze']").css({
				"color" : "red",
				"margin-left": "380px"
			});
	}
	/** 
	  * PAGE REFRESH 
	  **/
	var refresh = setInterval(function() {						
			var von = $("input[name='von_date']").val();
			var bis = $("input[name='bis_date']").val();
			$("tbody").load('../php/monitoring/getTableContent.php?function=getPassed&von=' + von + '&bis=' + bis);			
    }, 30000);
	/** 
	   * SUCHE
	   **/
	$("i[class='icon-search']").click(function(){
		var von = $("input[name='von_date']").val();
		var bis = $("input[name='bis_date']").val();
		$("tbody").load('../php/monitoring/getTableContent.php?function=getPassed&von=' + von + '&bis=' + bis);
		
	});
	/** 
	  * CHART ZEICHNEN 
	  **/
	google.setOnLoadCallback(drawChart);

	/**
	  * Balkendiagramm
	  **/
	google.setOnLoadCallback( drawPieChart );

	// Server Auswahl
	$("select[name='server']").change(function(){
		drawPieChart();
		countCompletedTasks();
	});

	countCompletedTasks();
    
function drawChart()
{   
	var jsonData = $.ajax({
			type: 'GET',
			contentType: "application/json; charset=utf-8",
			url: '../php/monitoring/getInfoChart.php',
			dataType: 'json',
			async: false,
			data : {
				'option' : 'getPassed'
			}					
	}).responseText;
		
	var obj = jQuery.parseJSON(jsonData);	
	var data = google.visualization.arrayToDataTable(obj);
	
    var options = {
        title: 'Wochenübersicht',
	    //curveType: 'function',
	    colors: ['navy', 'purple']
    };
    
    var chart = new google.visualization.LineChart(document.getElementById('chartPassed'));
    chart.draw(data, options);
}	
	
function drawPieChart()
{
		var server = $("select[name='server'] option:selected").val();

		var jsonData = $.ajax({
			type: 'GET',
			contentType: 'application/json; charset=utf-8',
			url: '../php/monitoring/getInfoChart.php',
			dataType: 'json',
			async : false,
			data: {
				'option' : 'overviewData',
				'tag' 	 : 'one',
				'server' : server
			}
		}).responseText;			

		var obj = jQuery.parseJSON(jsonData);

		var data = google.visualization.arrayToDataTable(obj);
		
        var options = {  
          title : 'Ergebnisse:',        
          colors: ['green','red', 'orange'],
          is3D: true
          // hAxis: {title: server, titleTextStyle: {color: 'red'}}
          // vAxis: {title: 'counter', titleTextStyle: {color: 'black'}}        
        };

        var chart = new google.visualization.PieChart(document.getElementById('chartOverview'));
        chart.draw(data, options);
}

function countCompletedTasks() 
{
	var server = $("select[name='server'] option:selected").val();

	var jsonData = $.ajax({
			type: 'GET',
			contentType: 'application/json; charset=utf-8',
			url: '../php/monitoring/getInfoChart.php',
			dataType: 'json',
			async : false,
			data: {
				'option' : 'overviewData',
				'tag' 	 : 'two',
				'server' : server
			}
		}).responseText;

	$("p.counterCompletedTasks span").text(jsonData);
}

});