<link rel="stylesheet" type="text/css" href="style/slideshow.css" media="screen" />
<script type="text/javascript" src="scripts/jquery.nivo.slider.pack.js"></script>

<div align="center" class="slideshow" style="padding-top:10px;width:500px;height:360px;background:#738396;border-radius:5px;box-shadow:0 0 18px rgba(0,0,0,0.4);">
	<div id="slideshow" style="width:480px;height:350px;box-shadow:0 0 18px rgba(0,0,0,0.4);">
		<?php
			$imgfolder = trim('images/slides');
			function listImages($dirname='.') {
			  return glob($dirname .'*.{jpg,png,jpeg,gif}', GLOB_BRACE);
			}
			$catalog = listImages("./".$imgfolder."/");
			$total = count ($catalog);
			for ($i=0;$i<$total;$i++) {
			  $file = $catalog[$i];
				echo"<img src='$file' />";
			}
		?>	
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#slideshow').nivoSlider();
	});
</script>
