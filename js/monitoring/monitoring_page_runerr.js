$(function(){

	$("tbody").load('../php/monitoring/getTableContent.php?function=getRunErr');
	
	// Manueller Refresh der Tabelle 
	$("i.icon-refresh").click( function() {
		
		// Tabelle neu laden 
		$("tbody.RunErr").load('../php/monitoring/getTableContent.php?function=getRunErr');
		var d = new Date();
		var hour = d.getHours();
		var min = d.getMinutes();
		var sec = d.getSeconds();
		$("p.timeUpdated span").text(hour + ":" + min + ":" + sec);

		// Grafik / Chart neu laden 
		$("#chartRunErr").load(drawChart());
		$("#GaugeDiv").load(drawGauge());		
	});

	var server = $("select[name='server'] option:selected").text();
	$("#GaugeDiv p.title_server").text(server);

	// Server Auswahl
	$("select[name='server']").change(function() {				
		$("#gauge_div").load(drawGauge());
		$("#GaugeDiv p.title_server").text( $("select[name='server'] option:selected").text() );		
	});

	google.setOnLoadCallback(drawChart); // Balkendiagramm

	google.setOnLoadCallback(drawGauge);  // Gauge Diagramm 
    /**
	  * CHART ZEICHEN 
	  **/
function drawChart() {
    // Show loading image
    $("#loading-image_runerr").show();

    var jsonData = $.ajax({
					type: 'GET',
					contentType: "application/json; charset=utf-8",
					url: '../php/monitoring/getInfoChart.php',
					dataType: 'json',
					async: false,
					data : {
						'option' : 'getRunErr'
					}					
				}).responseText;
		
	var obj = jQuery.parseJSON(jsonData);	
	var data = google.visualization.arrayToDataTable(obj);
	
    var options = {
        title: 'ErrorLog',
		colors: ['green', 'red'],
		backgroundColor: {fill: '#F4F4F4'},
        hAxis: {title: 'Server', titleTextStyle: {color: 'black'}}
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('chartRunErr'));
    chart.draw(data, options);
    // Hide Loading Image
    $("#loading-image_runerr").hide();
   }  
	
function drawGauge() {

	var server = $("select[name='server'] option:selected").val();	
	
	// Show Loading Image 
	$('#loading-image_gauge').show();

	var jsonData = $.ajax({
			type: 'GET',
			contentType: 'application/json; charset=utf-8',
			url: '../php/monitoring/getInfoChart.php',
			dataType: 'json',
			async : false,
			data: {
				'option' : 'getCPUCapacity',
				'server' : server
			}
	}).responseText;
	
	var obj = jQuery.parseJSON(jsonData);
	
	var data = google.visualization.arrayToDataTable(obj);
	
	var options = {
		width: 400, height: 120,
		redFrom: 90, redTo: 100,
		yellowFrom: 75, yellowTo: 90,
		minorTicks: 5
	};

	var chart = new google.visualization.Gauge(document.getElementById('gauge_div'));
	chart.draw(data, options);
	
	$('#loading-image_gauge').hide();
}

});