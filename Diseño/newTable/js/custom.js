$(document).ready(function(){

	// $("#btn-select-day").on("click", function(){
	// 	alert("funcca");
	// });

	$('#btn-reset').click(function(){
		$('#schedule-table').html("");
	});

	$('#btn-load').click(function(){
		$.post( "data.php", function( data ) {
		  $('#schedule-table').html(data);
		});

		// var items = 
		// 	'<div class="columns-container">'+
		// 		'<div class="column-item">'+
		// 			'<div class="cell-item cell-header">Lunes</div>'+
		// 			'<div class="cell-item cell-hour active">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 		'</div>'+
		// 		'<div class="column-item">'+
		// 			'<div class="cell-item cell-header">Martes</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 		'</div>'+
		// 		'<div class="column-item">'+
		// 			'<div class="cell-item cell-header">Miercoles</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 		'</div>'+
		// 		'<div class="column-item">'+
		// 			'<div class="cell-item cell-header">Jueves</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 		'</div>'+
		// 		'<div class="column-item">'+
		// 			'<div class="cell-item cell-header">Viernes</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 			'<div class="cell-item cell-hour">HH:MM</div>'+
		// 		'</div>'+
		// 	'</div>';
		// $('#schedule-table').html(items);
	});	



	//------------SELECCION
	// $('.schedule-selectable').on("click", '.cell-hour', function(){
	// 	alert("function");
	// });

	$('.schedule-selectable').on("click", ".cell-hour", function(){
		alert("si funciona");
	});

});