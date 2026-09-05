<?php
	// Dynamic SEO Settings based on active page script
	$current_file = basename($_SERVER['SCRIPT_NAME']);
	
	// Default values
	$seo_title = "LGU Tabina Municipal Information & Registry System";
	$seo_desc = "Official open data portal and citizen registry for the Local Government Unit of Tabina, Zamboanga del Sur. Access public executive records, demographics, and social welfare statistics.";
	$seo_keywords = "Tabina, LGU Tabina, Zamboanga del Sur, Municipal Information System, Citizen Registry, Open Data Tabina, Tabina Analytics";
	$seo_robots = "noindex, nofollow"; // Secure default for administrative pages

	if ($current_file === 'public_home.php') {
		$seo_title = "Public Portal | LGU Tabina";
		$seo_desc = "Official open data and analytics dashboard for LGU Tabina, Zamboanga del Sur. Explore executive charts and summaries of local government welfare and statistics.";
		$seo_keywords = "LGU Tabina Public Portal, Tabina Open Data, Tabina Zamboanga del Sur, Executive Dashboard Tabina";
		$seo_robots = "index, follow";
	} else if ($current_file === 'public_home2.php') {
		$seo_title = "Demographics & Public Analytics Portal | LGU Tabina";
		$seo_desc = "Interactive demographic statistics, community registries, and public sector dashboards for the Municipality of Tabina, Zamboanga del Sur.";
		$seo_keywords = "Tabina Demographics, Tabina Community Registry, Tabina Data Analytics";
		$seo_robots = "index, follow";
	} else if ($current_file === 'lgu_profile.php') {
		$seo_title = "DTI CMCI Profile | LGU Tabina";
		$seo_desc = "DTI Cities and Municipalities Competitiveness Index (CMCI) evaluation profile for the Municipality of Tabina. Check economic dynamism, government efficiency, and resiliency.";
		$seo_keywords = "Tabina CMCI, Tabina DTI Profile, Tabina Competitiveness Index, Tabina Government Efficiency";
		$seo_robots = "index, follow";
	} else if ($current_file === 'login.php' || $current_file === 'index.php') {
		$seo_title = "Authorized Portal Access | CCDP ";
		$seo_desc = "Secure staff log in and authentication gateway for the Tabina Municipal Information System.";
		$seo_keywords = "Tabina Staff Login, Tabina Admin Login, Tabina Info System Access";
		$seo_robots = "noindex, nofollow"; // Do not index login pages for security
	}

	// Allow individual page scripts to override if they set variables before inclusion
	if (isset($page_title)) {
		$seo_title = $page_title;
	}
	if (isset($page_description)) {
		$seo_desc = $page_description;
	}
	if (isset($page_keywords)) {
		$seo_keywords = $page_keywords;
	}
	if (isset($page_robots)) {
		$seo_robots = $page_robots;
	}
?>
<html lang="en">
<head>
	<script type="text/javascript">
		(function() {
			var theme = localStorage.getItem('theme') || 'dark';
			if (theme === 'dark') {
				document.documentElement.setAttribute('data-theme', 'dark');
			}
		})();
	</script>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title><?php echo htmlspecialchars($seo_title); ?></title>
	<meta name="description" content="<?php echo htmlspecialchars($seo_desc); ?>"/>
	<meta name="keywords" content="<?php echo htmlspecialchars($seo_keywords); ?>"/>
	<meta name="robots" content="<?php echo htmlspecialchars($seo_robots); ?>"/>

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-CW0ZYMCM2S"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-CW0ZYMCM2S');
	</script>
	
	<!-- Open Graph / Facebook Metadata for Social Media Preview -->
	<meta property="og:type" content="website"/>
	<meta property="og:url" content="https://<?php echo htmlspecialchars($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"/>
	<meta property="og:title" content="<?php echo htmlspecialchars($seo_title); ?>"/>
	<meta property="og:description" content="<?php echo htmlspecialchars($seo_desc); ?>"/>
	<meta property="og:image" content="https://<?php echo htmlspecialchars($_SERVER['HTTP_HOST']); ?>/images/logo.webp"/>

	<!-- Twitter Metadata -->
	<meta name="twitter:card" content="summary_large_image"/>
	<meta name="twitter:url" content="https://<?php echo htmlspecialchars($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"/>
	<meta name="twitter:title" content="<?php echo htmlspecialchars($seo_title); ?>"/>
	<meta name="twitter:description" content="<?php echo htmlspecialchars($seo_desc); ?>"/>
	<meta name="twitter:image" content="https://<?php echo htmlspecialchars($_SERVER['HTTP_HOST']); ?>/images/logo.webp"/>
	<link rel="shortcut icon" href="images/favicon.png" />
	<link href="style/stylesheets.css?v=2.0.7" rel="stylesheet" type="text/css"/>
	<link href="style/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="fonts/fontawesome/css/all.min.css" rel="stylesheet" type="text/css"/>
	<link href="style/responsive.css" rel="stylesheet" type="text/css"/> 
	<link href="venobox/venobox.css" rel="stylesheet">
	<script type="text/javascript" src="scripts/jquery-3.6.0.min.js"></script>
	<script type="text/javascript" src="scripts/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="scripts/head.js"></script>
	<script type="text/javascript" src="scripts/jquery.imagesloaded.js"></script>
	<script type="text/javascript" src="scripts/jquery.wookmark.js"></script>
	<script type="text/javascript" src="scripts/sweetalert.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			// Auto-wrap tables in responsive containers
			$('table').each(function() {
				var $table = $(this);
				if (!$table.parent().hasClass('table-responsive') && $table.parents('table').length === 0) {
					$table.wrap('<div class="table-responsive" style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; margin-bottom: 1rem;"></div>');
				}
			});

			// Wookmark grid layout
			$('#tiles').imagesLoaded(function() {
				var options = {
					autoResize: true,
					container: $('#main'),
					offset: 10
				};
				var handler = $('#tiles li');
				handler.wookmark(options);
			});
		});
	</script>
	<link href="facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
	<script src="facebox/facebox.js" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
		  $('a[rel*=facebox]').facebox({
			overlay      : true,
			opacity      : 0.2,
			loadingImage : 'facebox/loading.gif',
			closeImage   : 'facebox/closelabel.png'
		  })
		})
	</script>				
	<script type="text/javascript">
		function toggleReactionMenu(event, button) {
			event.stopPropagation();
			var menus = document.querySelectorAll('.reaction-menu-popup');
			var currentMenu = button.nextElementSibling;
			for (var i = 0; i < menus.length; i++) {
				if (menus[i] !== currentMenu) {
					menus[i].classList.add('d-none');
				}
			}
			if (currentMenu) {
				currentMenu.classList.toggle('d-none');
			}
		}
		document.addEventListener('click', function() {
			var menus = document.querySelectorAll('.reaction-menu-popup');
			for (var i = 0; i < menus.length; i++) {
				menus[i].classList.add('d-none');
			}
		});
	</script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			// Redirect any clicks on input group padding to the input box directly
			$(document).on('click', '.p-3.bg-white.border-top .input-group', function(e) {
				if (!$(e.target).is('button, i, input')) {
					$(this).find('.chat-input').focus();
				}
			});
		});
	</script>
	<script type="text/javascript" src="scripts/auto-logout.js"></script>
	<?php include("crud_functionjs.php"); ?>
	<?php include("crud_functionjs2.php"); ?>
</head>

<?php	
	function jump($page = '') {
		$target = $page ?: $_SERVER['REQUEST_URI'];
		if (!headers_sent()) {
			header("Location: $target");
			exit;
		} else {
			echo "<script>
				if (window.parent) {
					window.parent.location = '$target';
				} else {
					window.location = '$target';
				}
			</script>";
			exit;
		}
	}
?>

<body>

<!--<script type="text/javascript" src="scripts/page_loader.js"></script>-->
