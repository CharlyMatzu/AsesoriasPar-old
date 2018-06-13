$(document).ready(function(){

	$('.single-select .hour-item').on("click", function(e){
		var target = $( event.target );
	    //------Cambia el estilo del elemento
	    singleToggleButton(target, "selected");
	});

	// $(".single-select .materia-item").on( "click", function(event) {
	//     var target = $( event.target );
	//     // //------Cambia el estilo del elemento
	//     singleToggleButton(target, "materia-selected");
	// });



});