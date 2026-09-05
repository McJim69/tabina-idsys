$('body').append('<div id="loadingDiv"><div class="loader"></div></div>');

$(window).on('load', function(){
	setTimeout(removeLoader, 600); //wait for page load PLUS two seconds.

	});
	
	function removeLoader(){
		$( "#loadingDiv" ).fadeOut(600, function() {
		// fadeOut complete. Remove the loading div
		$( "#loadingDiv" ).remove(); //makes page more lightweight 
	});  
}
