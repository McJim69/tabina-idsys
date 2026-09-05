(function($) {
	var inactivity_timeout = 1800; 
	var inactivity_timer = inactivity_timeout;

	$(document).ready(function() {
		setInterval(checkInactivity, 1000); 
	});

	$(document).on('mousemove keydown click', function() {
		inactivity_timer = inactivity_timeout;
	});

	function checkInactivity() {
		inactivity_timer--;
		if (inactivity_timer <= 0) {
			window.location.href = 'logout.php?timeout';
		}
	}
})(jQuery);