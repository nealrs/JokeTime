$(document).ready(function(){

	$('#help').click(function() {
	  $('#diagram').slideToggle('slow', function() {
		// Animation complete.
	  });
	});
	
	
	
	//id = 1;
	//$('#'+id).slideToggle('slow', function() {});
	//$('#1').slideToggle('slow', function() {});

	//form_slide(1);
	
	//form_slide(id);

});


function form_slide (id) {
		$('#'+id).slideToggle('slow', function() {});
		//alert(id+' has been hidden');
}