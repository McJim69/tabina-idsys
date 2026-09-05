<?php
	require_once('connect.php');
	
	// Set SEO metadata parameters
	$page_title = "Explore Tabina | Coastal Tourism & Marine Sanctuary";
	$page_description = "Discover the hidden paradise of Tabina, Zamboanga del Sur. Explore the stunning Baganian Blue Lagoon, the award-winning Tambunan Marine Sanctuary, and beautiful pebble beaches.";
	$page_keywords = "Tabina Zamboanga del Sur, Tabina Tourism, Baganian Blue Lagoon, Tambunan Marine Protected Area, Pebbles Beach, Travel Zamboanga del Sur";
	$page_robots = "index, follow";
	
	require('header.php');
	
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
?>
<!-- Custom Theme Styles for Tourism Page -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="owlcarousel/css/owl.carousel.min.css" rel="stylesheet" type="text/css" />
<link href="style/explore.css?ver.2.0.6" rel="stylesheet" type="text/css"/>

<!-- Public Navigation Header -->
<nav class="navbar navbar-expand-lg navbar-dark public-navbar fixed-top py-3">
	<div class="container">
		<a class="navbar-brand d-flex align-items-center font-weight-bold" href="public_home.php">
			<img src="images/logo.webp" height="38" class="mr-2" alt="Tabina Seal">
			<span>LGU TABINA <small class="text-white-50 font-weight-normal">| Public Portal</small></span>
		</a>
		<div class="ml-auto d-flex align-items-center">
			<a href="public_home.php" class="btn btn-sm btn-outline-primary rounded-pill px-3 mr-2 font-weight-semibold">
				<x class="thid"><i class="fa fa-home mr-1"></i></x> Back to Home
			</a>
			<?php 				
				if (isset($_SESSION['user'])) { 
					echo '
					<a href="index.php" class="btn btn-sm btn-info rounded-pill mr-2 px-3 font-weight-semibold">
						<x class="thid"><i class="fas fa-cog mr-1"></i></x> Dashboard
					</a>
					<a onclick=\'sessionEnd("gid")\' class="btn btn-sm btn-warning rounded-pill mr-2 px-3 font-weight-semibold">
						<x class="thid"><i class="fas fa-lock mr-1"></i></x> Logout
					</a>';
				} else {
					echo '
					<a href="users_register_public.php" class="btn btn-sm btn-info rounded-pill mr-2 px-3 font-weight-semibold">
						<x class="thid"><i class="fas fa-user mr-1"></i></x> Register
					</a>
					<a href="login.php" class="btn btn-sm btn-warning rounded-pill mr-2 px-3 font-weight-semibold">
						<x class="thid"><i class="fas fa-lock mr-1"></i></x> Login
					</a>';
				}
			?>
		</div>
	</div>
</nav>

<!-- Hero Section Banner -->
<div class="tourism-hero">
	<div class="tourism-hero-content">
		<span class="badge badge-pill badge-primary px-3 py-2 text-uppercase tracking-wider font-weight-bold mb-3" style="background: rgba(56, 189, 248, 0.25); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.35);">
			<i class="fas fa-compass mr-1"></i> Explore Tabina
		</span>
		<h2>Discover the Hidden Paradise</h2>
		<p>A coastal sanctuary of crystal lagoons, rich marine reserves, and raw natural wonders in Zamboanga del Sur.</p>
		<a href="#attractions" class="btn btn-primary rounded-pill px-4 py-2 font-weight-bold shadow-lg" style="background: var(--accent-gradient); border: none;">
			Start Exploring <i class="fas fa-arrow-down ml-1"></i>
		</a>
	</div>
</div>

<?php
	function get_all_scenic_images() {
		$patterns = [
			"images/scenic/coastal/*.{jpg,jpeg,png,webp}",
		];
		$results = [];
		foreach ($patterns as $pattern) {
			$files = glob($pattern, GLOB_BRACE);
			if ($files) {
				$results = array_merge($results, $files);
			}
		}
		$results = array_unique($results);
		usort($results, function($a, $b) {
			return filemtime($b) - filemtime($a);
		});
		return $results;
	}

	$scenic_images = get_all_scenic_images();
?>

<?php
	function get_all_scenic_images1() {
		$patterns = [
			"images/scenic/underwater/*.{jpg,jpeg,png,webp}",
		];
		$results = [];
		foreach ($patterns as $pattern) {
			$files = glob($pattern, GLOB_BRACE);
			if ($files) {
				$results = array_merge($results, $files);
			}
		}
		$results = array_unique($results);
		usort($results, function($a, $b) {
			return filemtime($b) - filemtime($a);
		});
		return $results;
	}

	$scenic_images1 = get_all_scenic_images1();
?>

<div class="container py-5" id="attractions">
	<div class="text-center mb-5">
		<h2 class="section-title">Major Scenic Highlights</h2>
		<p class="text-secondary" style="max-width: 600px; margin: 0 auto; font-size: 1.05rem;">Tabina boasts some of the most spectacular, untouched natural destinations in the province. Dive in and explore what makes us unique.</p>
	</div>

	<div class="row">
		<!-- Blue Lagoon -->
		<div class="col-lg-4 col-md-6 mb-4">
			<div class="spot-card">
				<div class="spot-img-container">
					<img src="images/scenic/bluelagoon.jpg?<?php echo date("h:i:s");?>" alt="Baganian Blue Lagoon" class="spot-img">
					<div class="spot-badge"><i class="fas fa-swimmer mr-1"></i> Cliff Jumping</div>
				</div>
				<div class="card-body p-4">
					<h4 class="font-weight-bold text-dark mb-2">Baganian Blue Lagoon</h4>
					<p class="small text-secondary mb-3" style="line-height: 1.6;">
						A breathtaking hidden lagoon nestled in Barangay Baganian. Known for its deep, crystal-clear turquoise waters and towering rock walls, this location is an absolute dream for cliff-jumpers and adventure seekers.
					</p>
					<div class="d-flex align-items-center text-primary font-weight-bold small">
						<i class="fas fa-map-marker-alt mr-1"></i> Brgy. Baganian, Tabina
					</div>
				</div>
			</div>
		</div>

		<!-- Marine Sanctuary -->
		<div class="col-lg-4 col-md-6 mb-4">
			<div class="spot-card">
				<div class="spot-img-container">
					<img src="images/scenic/tambunan.jpg?<?php echo date("h:i:s");?>" alt="Tambunan Marine Sanctuary" class="spot-img">
					<div class="spot-badge"><i class="fas fa-water mr-1"></i> Snorkeling & Diving</div>
				</div>
				<div class="card-body p-4">
					<h4 class="font-weight-bold text-dark mb-2">Tambunan Marine Reserve</h4>
					<p class="small text-secondary mb-3" style="line-height: 1.6;">
						An award-winning Marine Protected Area (MPA). Highly celebrated for its massive coral covers and rich biodiversity, snorkelers can swim directly alongside giant clams (*taklobos*), clownfish, and thriving reef life.
					</p>
					<div class="d-flex align-items-center text-primary font-weight-bold small">
						<i class="fas fa-fish mr-1"></i> Brgy. Tambunan, Tabina
					</div>
				</div>
			</div>
		</div>

		<!-- Pebbles Beach -->
		<div class="col-lg-4 col-md-6 mb-4">
			<div class="spot-card">
				<div class="spot-img-container">
					<img src="images/scenic/peebles.jpg?<?php echo date("h:i:s");?>" alt="Pebbles Beach" class="spot-img">
					<div class="spot-badge"><i class="fas fa-umbrella-beach mr-1"></i> Scenic Shoreline</div>
				</div>
				<div class="card-body p-4">
					<h4 class="font-weight-bold text-dark mb-2">Pebbles Beach</h4>
					<p class="small text-secondary mb-3" style="line-height: 1.6;">
						Unlike traditional sandy beaches, the shore of Barangay Manicaan is blanketed in smooth, naturally polished pebbles. A peaceful, highly Instagrammable escape perfect for watch sunsets and listening to rolling waves.
					</p>
					<div class="d-flex align-items-center text-primary font-weight-bold small">
						<i class="fas fa-camera mr-1"></i> Brgy. Manicaan, Tabina
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Image Slides Gallery Section -->
<div class="container pb-5">
	<div class="text-center mb-5">
		<h2 class="section-title">Scenic Showcase</h2>
		<p class="text-secondary" style="max-width: 600px; margin: 0 auto; font-size: 1.05rem;">A visual tour of the natural landscapes, coastal views, and community life of Tabina.</p>
	</div>

	<div class="row"><div style="margin-left:15px"><h4>Coastal</h4></div>
		<div class="col-lg-12 col-md-8 col-sm-2 mb-4">
			<div class="owl-carousel slider_carousel">
			<?php
				$limit = 100;
				$count = 0;
				foreach ($scenic_images as $img_path) {
					if ($count >= $limit) break;
					echo '
						<div class="gallery-item">
							<a href="' . htmlspecialchars($img_path) . '" target="_blank">
								<img src="' . htmlspecialchars($img_path) . '" alt="Tabina Scenic Highlight">
								<div class="gallery-overlay">
									<span class="font-weight-bold small"><i class="fas fa-search-plus mr-1"></i> Zoom Image</span>
								</div>
							</a>
						</div>
					';
					$count++;
				}
			?>
			</div>
		</div>
	</div>
	<div class="row"><div style="margin-left:15px"><h4>Underwater</h4></div>
		<div class="col-lg-12 col-md-8 col-sm-2 mb-4">
			<div class="owl-carousel slider_carousel">
			<?php
				$limit = 100;
				$count = 0;
				foreach ($scenic_images1 as $img_path) {
					if ($count >= $limit) break;
					echo '
						<div class="gallery-item">
							<a href="' . htmlspecialchars($img_path) . '" target="_blank">
								<img src="' . htmlspecialchars($img_path) . '" alt="Tabina Scenic Highlight">
								<div class="gallery-overlay">
									<span class="font-weight-bold small"><i class="fas fa-search-plus mr-1"></i> Zoom Image</span>
								</div>
							</a>
						</div>
					';
					$count++;
				}
			?>
			</div>
		</div>
	</div>	
</div>

<!-- Geography & Travel Guide Section -->
<div class="container pb-5">
	<div class="row">
		<!-- Geographical Profile -->
		<div class="col-lg-6 mb-4">
			<div class="spot-card p-4 p-md-5">
				<h3 class="font-weight-bold text-dark mb-3"><i class="fas fa-globe-asia text-primary mr-1"></i> Geographical Profile</h3>
				<p class="text-secondary" style="line-height: 1.7; font-size: 1rem;">
					Tabina is strategically situated along the coastline of Zamboanga del Sur. It has a total land area of approximately <strong>86.90 square kilometers</strong>, spanning across 15 coastal and agricultural barangays. Its geographical positioning gives it access to rich, biodiverse marine corridors and scenic cliff shorelines facing the Celebes Sea.
				</p>
				<div class="text-center mt-4" style="position:relative">
					<a href="https://www.google.com/maps/place/Tabina,+Zamboanga+del+Sur/@7.4136476,123.4053475,13z/data=!3m1!4b1!4m6!3m5!1s0x3256bfc6ca0302a3:0x7e9b5c1706e0a1d7!8m2!3d7.4220836!4d123.4047109!16zL20vMDZxMDk2?entry=ttu&g_ep=EgoyMDI2MDcxNS4wIKXMDSoASAFQAw%3D%3D" alt="Tabina Map" target="_blank" title="Click to View in Google Map">
						<div class="image" style="padding:5px;border-radius:12px;background:#fff;position:absolute;top:10px;left:10px;opacity:.9"><small> &nbsp; View in Map &nbsp; </small></div>
						<img src="images/tabina_map.jpg" alt="Zamboanga del Sur Map Indexing Tabina" class="img-fluid w-100" style="max-height: 380px; object-fit: fill; border-radius: 14px;">
					</a>
				</div>
			</div>
		</div>

		<!-- How to Get There -->
		<div class="col-lg-6 mb-4">
			<div class="spot-card p-4 p-md-5 d-flex flex-column justify-content-between">
				<div>
					<h3 class="font-weight-bold text-dark mb-3"><i class="fas fa-route text-primary mr-1"></i> Travel & Access Guide</h3>
					
					<h6 class="font-weight-bold text-dark mt-4 mb-2"><i class="fas fa-bus text-info mr-1"></i> How to Get Here</h6>
					<p class="text-secondary small mb-3" style="line-height: 1.6;">
						From Pagadian City (the provincial capital), passenger vans and motorcycles run regular trips directly to Tabina. The journey takes approximately <strong>2 hours</strong> through well-paved highways offering panoramic country views.
					</p>

					<h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-motorcycle text-info mr-1"></i> Getting Around</h6>
					<p class="text-secondary small mb-3" style="line-height: 1.6;">
						Once in the town proper, local tricycles are readily available to transport you to the coastal barangays (like Baganian or Manicaan) and specific resort entry points.
					</p>

					<h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-leaf text-success mr-1"></i> Eco-Tourism Guidelines</h6>
					<ul class="text-secondary small pl-3 mb-0" style="line-height: 1.7;">
						<li><strong>Zero Waste:</strong> Bring out whatever trash you bring in. Help protect our marine ecosystems.</li>
						<li><strong>Respect Marine Life:</strong> Do not step on, touch, or take corals or marine organisms at the sanctuary.</li>
						<li><strong>Safety First:</strong> For deep-water diving or lagoon cliff jumping, coordinate with local tour guides or barangay officials.</li>
					</ul>
				</div>

				<div class="mt-4 pt-3 border-top text-center">
					<small class="text-muted">
						Need tourist information? Contact the Municipal Tourism Office.
					</small>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	include_once("footer2.php");
?>

<div style="position:fixed;top:25px;right:15px;z-index:9999">
	<button id='theme-toggle-btn' class='btn btn-sm btn-link text-white shadow-none' onclick='toggleTheme()' style='font-size: 16px; border: none; background: transparent; cursor: pointer; padding: 5px 10px;' title='Toggle Dark/Light Mode'>
		<i id='theme-toggle-icon' class='fas fa-moon'></i>
	</button>
</div>

<script src="owlcarousel/js/owl.carousel.min.js"></script>

<script>	
	function slider_carouselInit() {
		$('.owl-carousel.slider_carousel').owlCarousel({
			dots: false,
			loop: true,
			margin: 30,
			stagePadding: 2,
			autoplay: true,
		//	nav: true,
		//  navText: ["<i class='far fa-arrow-alt-circle-left'></i>","<i class='far fa-arrow-alt-circle-right'></i>"],
			autoplayTimeout: 1500,
			autoplayHoverPause: true,
			responsive: {
				0: {
					items: 1
				},
				768: {
					items: 2,
				},
				992: {
					items: 5
				}
			}
		});
	}
	slider_carouselInit();
</script>

<script type="text/javascript">
	function toggleTheme() {
		var currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
		var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
		
		if (newTheme === 'dark') {
			document.documentElement.setAttribute('data-theme', 'dark');
			localStorage.setItem('theme', 'dark');
			var icon = document.getElementById('theme-toggle-icon');
			if (icon) {
				icon.classList.remove('fa-moon');
				icon.classList.add('fa-sun');
			}
		} else {
			document.documentElement.removeAttribute('data-theme');
			localStorage.setItem('theme', 'light');
			var icon = document.getElementById('theme-toggle-icon');
			if (icon) {
				icon.classList.remove('fa-sun');
				icon.classList.add('fa-moon');
			}
		}
	}
</script>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		// Sync the dark mode button icon state on page load
		var initialTheme = localStorage.getItem('theme') || 'dark';
		var toggleIcon = document.getElementById('theme-toggle-icon');
		if (toggleIcon) {
			if (initialTheme === 'dark') {
				toggleIcon.classList.remove('fa-moon');
				toggleIcon.classList.add('fa-sun');
			} else {
				toggleIcon.classList.remove('fa-sun');
				toggleIcon.classList.add('fa-moon');
			}
		}

		// Dynamically add has-sub class to all li elements that contain a ul (submenus)
		$('#cssmenu li').has('ul').addClass('has-sub');

		// Clean up legacy text arrow symbols so CSS chevrons can style them cleanly
		$('#cssmenu a').each(function() {
			var html = $(this).html();
			html = html.replace(/▼|&#9660;|►|&#9658;/g, '');
			$(this).html(html);
		});

		// Insert responsive menu button dynamically if not present
		if ($('#menu-button').length === 0) {
			$('#cssmenu').prepend('<div id="menu-button">Menu</div>');
		}

		// Toggle top-level menu collapse (mobile)
		$('#menu-button').on('click', function(e) {
			e.stopPropagation();
			var menu = $(this).next('ul');
			if (menu.hasClass('open')) {
				menu.removeClass('open').hide();
				$(this).removeClass('menu-opened');
			} else {
				menu.addClass('open').show();
				$(this).addClass('menu-opened');
			}
		});

		// Auto hide menu when clicking/tapping outside or scrolling on mobile
		$(document).on('click touchstart', function(e) {
			if (!$(e.target).closest('#cssmenu').length) {
				var menu = $('#menu-button').next('ul');
				if (menu.hasClass('open')) {
					menu.removeClass('open').hide();
					$('#menu-button').removeClass('menu-opened');
				}
			}
		});

		$(window).on('scroll', function() {
			var menu = $('#menu-button').next('ul');
			if (menu.hasClass('open')) {
				menu.removeClass('open').hide();
				$('#menu-button').removeClass('menu-opened');
			}
		});

		// Setup submenus
		$('#cssmenu li.has-sub').prepend('<span class="submenu-button"></span>');
		$('#cssmenu li.has-sub .submenu-button').on('click', function() {
			$(this).toggleClass('submenu-opened');
			var submenu = $(this).siblings('ul');
			if (submenu.hasClass('open')) {
				submenu.removeClass('open').hide();
			} else {
				submenu.addClass('open').show();
			}
		});
	});
	function sessionEnd(gid){	
		if(confirm("Are you sure you want to Logout?")){
			window.location.href = 'logout.php';
		}
	}
</script>

</body>

</html>
