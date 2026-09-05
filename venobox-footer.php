<!-- VenoBox Video Plugin -->
<script src="venobox/venobox.min.js"></script>
<script>
	$(document).ready(function(){
		$('.venobox').venobox(); 
		$('.venobox_custom').venobox({
			framewidth: '300px',
			frameheight: '250px',
			border: '6px',
			bordercolor: '#ba7c36',
			numeratio: true
		});
	});	
</script>
