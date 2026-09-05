<!DOCTYPE html>
<html style="background: linear-gradient(150deg, rgb(10, 30, 120) 0.00%, rgb(100, 30, 10) 100.00%)">
	<head>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
			<?php
				$titleCode = http_response_code(); // Get current response code
				switch ($titleCode) {
					case 400: echo "McJim Server ERROR 400"; break;
					case 403: echo "McJim Server ERROR 403"; break;
					case 404: echo "McJim Server ERROR 404"; break;
					case 408: echo "McJim Server ERROR 408"; break;
					case 500: echo "McJim Server ERROR 500"; break;
					case 502: echo "McJim Server ERROR 502"; break;
					default:  echo "McJim Server ERROR 403"; break;
				}
			?>
		</title>
		<link rel="shortcut icon" href="/../indexer/theme/favicon.ico" />
		<!-- Stylesheets -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.4.0/css/lightgallery-bundle.min.css" integrity="sha256-1gtpcmuOA//0xcazytnM9JqBM3mUDWgwqW1P9U+2/F8=" crossorigin="anonymous">
		<link rel="stylesheet" href="/../indexer/theme/style.css"/>
		<script src="/../indexer/theme/indexer.js"></script>	
	</head>

	<body class="fadeDown"><center>
		<div class="center">
			<div style="margin-top:50px;font-size:40px;font-weight:bold;color:#bbb">Citizen-Centric Digital Platform</div>
			<h1 style="margin-top:50px;color: pink">E R R O R</h1>
			<div style="color:#bbb"><br>
				<?php
					$imageCode = http_response_code(); // Get current response code
					switch ($imageCode) {
						case 400: echo "<img src='/../indexer/theme/400.jpg' height='180' style='border-radius:30px;opacity:.50'/>"; break;
						case 403: echo "<img src='/../indexer/theme/403.jpg' height='180' style='border-radius:30px;opacity:.70'/>"; break;
						case 404: echo "<img src='/../indexer/theme/404.png' height='170'>"; break;
						case 408: echo "<img src='/../indexer/theme/408.jpg' height='180' style='border-radius:20px;opacity:.70'/>"; break;
						case 500: echo "<img src='/../indexer/theme/500.jpg' height='200' style='border-radius:20px;opacity:.60'/>"; break;
						case 502: echo "<img src='/../indexer/theme/502.jpg' height='250' style='border-radius:20px;opacity:.60'/></div>"; break;
						default:  echo "<img src='/../indexer/theme/403.jpg' height='180' style='border-radius:30px;opacity:.70'/>"; break;
					}
				?>			
			</div>
			<div style="padding:20px;font-size:18px;color: pink">
				<?php
					$textCode = http_response_code(); // Get current response code
					switch ($textCode) {
						case 400: echo "Sorry, I don't understand what you want me to do."; break;
						case 403: echo "You aren't allowed to be here."; break;
						case 404: echo "I can't find what you are looking for..."; break;
						case 408: echo "I refuse to wait any longer."; break;
						case 500: echo "I don't know what to do. This isn't your fault."; break;
						case 502: echo "I received some invalid information from my master."; break;
						default:  echo "You don't have the user rights to view this page."; break;
					}
				?>
			</div><br>
			<div>
				<a href="/../">
					<button class="button">
						Back to Public Portal
					</button>
				</a>
			</div>
		</div><br><br>
		<div class="footer" style="color:#bbb">
			Citizen-Centric Digital Platform
			&copy; 2020 &bull; Designed by McJim Cyberworks </a> <br> 
			<a href="https://mcjim-server.com" target="_blank"> 
				www.mcjim-server.com
			</a>
		</div>
	</center>
	</body>
</html>
	