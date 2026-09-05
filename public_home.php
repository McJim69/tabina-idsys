<?php 
	require_once('connect.php');
	require('header.php');
	
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	
	// Aggregates
	$total_seniors = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM senior"))['total'] ?? 0;
	$total_seniors_80up = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM senior WHERE age >= 80"))['total'] ?? 0;
	$pensioners_count = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM senior WHERE LOWER(pensioner) = 'yes' OR pensioner = '1'"))['total'] ?? 0;
	
	$total_pwd = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM pwd"))['total'] ?? 0;
	$total_kinder = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM kinder"))['total'] ?? 0;
	$total_solo = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM solo_parent"))['total'] ?? 0;
	$total_ind = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM indigents"))['total'] ?? 0;
	
	$total_hh = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM households"))['total'] ?? 0;
	$total_hh_members = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM hh_members"))['total'] ?? 0;
	
	$total_biz_permits = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM permit_business"))['total'] ?? 0;
	$total_op_permits = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM permit_operate"))['total'] ?? 0;
	$total_fishing = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM reg_fishing"))['total'] ?? 0;
	
	// Barangay Aggregations
	$temp_brgys = [];
	$tables = ['senior', 'pwd', 'households', 'permit_business', 'indigents'];
	foreach ($tables as $t) {
		$res = $link->query("SELECT barangay FROM `$t` GROUP BY barangay");
		if ($res) {
			while($row = mysqli_fetch_assoc($res)){
				$b = trim($row['barangay']);
				if($b !== "" && $b !== null && strtoupper($b) !== 'ALL BARANGAYS') {
					$temp_brgys[$b] = true;
				}
			}
		}
	}
	
	ksort($temp_brgys);
	
	$barangay_data = [];
	foreach(array_keys($temp_brgys) as $b){
		$barangay_data[$b] = [
			'senior' => 0,
			'pwd' => 0,
			'households' => 0,
			'business' => 0,
			'total' => 0
		];
	}
	
	// Populate counts
	$res = $link->query("SELECT TRIM(barangay) as b, COUNT(*) as c FROM senior GROUP BY TRIM(barangay)");
	while($row = mysqli_fetch_assoc($res)){
		$b = $row['b'];
		if(isset($barangay_data[$b])) {
			$barangay_data[$b]['senior'] = intval($row['c']);
			$barangay_data[$b]['total'] += intval($row['c']);
		}
	}
	
	$res = $link->query("SELECT TRIM(barangay) as b, COUNT(*) as c FROM pwd GROUP BY TRIM(barangay)");
	while($row = mysqli_fetch_assoc($res)){
		$b = $row['b'];
		if(isset($barangay_data[$b])) {
			$barangay_data[$b]['pwd'] = intval($row['c']);
			$barangay_data[$b]['total'] += intval($row['c']);
		}
	}

	$res = $link->query("SELECT TRIM(barangay) as b, COUNT(*) as c FROM households GROUP BY TRIM(barangay)");
	while($row = mysqli_fetch_assoc($res)){
		$b = $row['b'];
		if(isset($barangay_data[$b])) {
			$barangay_data[$b]['households'] = intval($row['c']);
			$barangay_data[$b]['total'] += intval($row['c']);
		}
	}

	$res = $link->query("SELECT TRIM(barangay) as b, COUNT(*) as c FROM permit_business GROUP BY TRIM(barangay)");
	while($row = mysqli_fetch_assoc($res)){
		$b = $row['b'];
		if(isset($barangay_data[$b])) {
			$barangay_data[$b]['business'] = intval($row['c']);
			$barangay_data[$b]['total'] += intval($row['c']);
		}
	}
	
	uasort($barangay_data, function($a, $b) {
		return $b['total'] <=> $a['total'];
	});
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="style/home.css" rel="stylesheet" type="text/css"/>

<!-- Public Navigation Header -->
<nav class="navbar navbar-expand-lg navbar-dark public-navbar fixed-top py-3">
	<div class="container">
		<a class="navbar-brand d-flex align-items-center font-weight-bold" href="public_home.php">
			<img src="images/logo.webp" height="38" class="mr-2" alt="Tabina Seal">
			<span>LGU TABINA <small class="text-white-50 font-weight-normal">| Public Portal</small></span>
		</a>
		<div class="ml-auto d-flex align-items-center">
			<a href="explore_tabina.php" class="btn btn-sm btn-outline-light rounded-pill px-3 mr-2 font-weight-semibold">
				<x class="thid"><i class="fas fa-map-marked-alt mr-1"></i></x> Suroy Tabina
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

<!-- Hero Section -->
<div class="public-hero" style="margin-top:65px;">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-8">
				<span class="badge badge-pill badge-primary px-3 py-2 text-uppercase tracking-wider font-weight-bold mb-3" style="background: rgba(59, 130, 246, 0.2); color: #60a5fa;">
					Official Open Data & Analytics
				</span>
				<h1 class="display-5 font-weight-bold text-white mb-2">
					Municipality of Tabina
				</h1>
				<p class="lead text-white text-muted mb-4" style="max-width: 650px;">
					Executive information summary of social welfare programs, community registries, senior citizen affairs, and local economic development in Tabina, Zamboanga del Sur.
				</p>
				<div class="d-flex flex-wrap align-items-center" style="gap: 12px; max-width: 950px;">
					<a href="explore_tabina.php" class="btn btn-outline-light rounded-pill font-weight-bold shadow-sm hero-equal-btn mb-2">
						<i class="fa fa-photo mr-2"></i> Explore Tabina
					</a>
					<a href="public_dashboard.php" class="btn btn-outline-success rounded-pill font-weight-bold shadow-sm hero-equal-btn mb-2">
						<i class="fas fa-hand-o-up mr-2"></i> E-LGU Services
					</a>
					<a href="#barangay-directory" class="btn btn-outline-warning rounded-pill font-weight-bold shadow-sm hero-equal-btn mb-2">
						<i class="fas fa-map-marked-alt mr-2"></i> Barangay Directory
					</a>
					
					<a href="lgu_profile.php" class="btn btn-outline-info rounded-pill font-weight-bold shadow-sm hero-equal-btn mb-2">
						<i class="fa fa-award mr-2"></i> DTI CMCI Profile
					</a>
					<a href="#Analytics" class="btn btn-outline-primary rounded-pill font-weight-bold shadow-sm hero-equal-btn mb-2">
						<i class="fa fa-chart-pie mr-2"></i> Demographics
					</a>
					<a rel="facebox" href="disclaimer_modal.php" class="btn btn-outline-danger rounded-pill font-weight-bold shadow-sm hero-equal-btn mb-2">
						<i class="fas fa-award mr-2"></i> Pre-Build Disclaimer
					</a>
				</div>
			</div>
			<div class="col-lg-4 d-none d-lg-block text-center">
				<img src="images/logo.webp" height="100%" style="opacity:0.3" class="img-fluid drop-shadow" alt="Tabina Seal">
			</div>
		</div>
	</div>
</div>

<?php include("logo_slider.php");?>

<!-- Interactive Visual Intro Showcase -->
<div class="container pt-5 pb-2">
	<div class="card border-0 shadow-lg rounded-xl overflow-hidden mb-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 20px;">
		<div class="row no-gutters align-items-center">
			<div class="col-lg-7 p-4 p-md-5">
				<span class="badge badge-pill badge-primary px-3 py-2 text-uppercase font-weight-bold mb-3" style="letter-spacing: 0.5px; background: rgba(59, 130, 246, 0.2); color: #60a5fa;">
					<i class="fas fa-layer-group mr-1"></i> Integrated Municipal Core Platform
				</span>
				<h2 class="font-weight-bold text-white mb-3">
					Digital Identity, Civic Welfare & Transparency
				</h2>
				<p class="text-white-50 mb-4" style="font-size: 1.05rem; line-height: 1.6;">
					The Citizen-Centric Digital Platform (CCDP) serves as the official digital backbone for the Municipality of Tabina. It connects resident demographics, senior citizen affairs, social welfare assistance, and local economic licensing into a unified transparent public portal.
				</p>
				<div class="row">
					<div class="col-sm-6 mb-3">
						<div class="d-flex align-items-center">
							<div class="rounded-circle bg-primary text-white p-3 mr-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; flex-shrink: 0;">
								<i class="fas fa-users fa-lg"></i>
							</div>
							<div>
								<h5 class="font-weight-bold text-white mb-0"><?php echo number_format($total_seniors + $total_pwd + $total_solo + $total_ind); ?>+</h5>
								<small class="text-white-50">Social Welfare Beneficiaries</small>
							</div>
						</div>
					</div>
					<div class="col-sm-6 mb-3">
						<div class="d-flex align-items-center">
							<div class="rounded-circle bg-success text-white p-3 mr-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; flex-shrink: 0;">
								<i class="fas fa-home fa-lg"></i>
							</div>
							<div>
								<h5 class="font-weight-bold text-white mb-0"><?php echo number_format($total_hh); ?></h5>
								<small class="text-white-50">Mapped Municipal Households</small>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-5 text-center p-3">
				<img src="images/tabina_intro_banner.webp" alt="Tabina ID-Sys Intro Visual Showcase" class="img-fluid rounded-lg shadow-lg" style="max-height: 320px; object-fit: cover; width: 100%; border-radius: 16px; border: 1px solid rgba(255,255,255,0.15);">
			</div>
		</div>
	</div>

	<!-- 4 Core System Pillars Cards -->
	<div class="row mb-2">
		<div class="col-lg-3 col-md-6 mb-4">
			<div class="card h-100 border-0 shadow-sm p-4 tranpurokn-hover" style="border-radius: 16px; background: #ffffff;">
				<div class="icon-shape icon-blue mb-3" style="width: 52px; height: 52px; font-size: 1.4rem;">
					<i class="fas fa-hand-holding-heart"></i>
				</div>
				<h5 class="font-weight-bold text-dark mb-2">Social Welfare</h5>
				<p class="small text-muted mb-0">Comprehensive tracking for Senior Citizens, PWDs, Solo Parents, and 4P's Indigent Program recipients.</p>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 mb-4">
			<div class="card h-100 border-0 shadow-sm p-4 tranpurokn-hover" style="border-radius: 16px; background: #ffffff;">
				<div class="icon-shape icon-purple mb-3" style="width: 52px; height: 52px; font-size: 1.4rem;">
					<i class="fas fa-file-signature"></i>
				</div>
				<h5 class="font-weight-bold text-dark mb-2">Permits & Clearances</h5>
				<p class="small text-muted mb-0">Streamlined processing for Business Permits, PTOs, Mayor's Clearances, and Fishing Vessel Registrations.</p>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 mb-4">
			<div class="card h-100 border-0 shadow-sm p-4 tranpurokn-hover" style="border-radius: 16px; background: #ffffff;">
				<div class="icon-shape icon-green mb-3" style="width: 52px; height: 52px; font-size: 1.4rem;">
					<i class="fas fa-map-marked-alt"></i>
				</div>
				<h5 class="font-weight-bold text-dark mb-2">Household Census</h5>
				<p class="small text-muted mb-0">Barangay-level census surveys, sanitation, water access, and demographic resident profiling across 15 barangays.</p>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 mb-4">
			<div class="card h-100 border-0 shadow-sm p-4 tranpurokn-hover" style="border-radius: 16px; background: #ffffff;">
				<div class="icon-shape icon-orange mb-3" style="width: 52px; height: 52px; font-size: 1.4rem;">
					<i class="fas fa-chart-pie"></i>
				</div>
				<h5 class="font-weight-bold text-dark mb-2">Open Data Analytics</h5>
				<p class="small text-muted mb-0">Real-time public executive statistics, verification, and interactive open data directory for municipal governance.</p>
			</div>
		</div>
	</div>

	<!-- About the Municipality of Tabina Card -->
	<div class="card border-0 shadow-sm rounded-lg mt-3 mb-4" id="municipal-history" style="border-radius: 18px; background: #ffffff; overflow: hidden;">
		<div class="card-body p-4 p-md-5">
			<div class="row align-items-center">
				<div class="col-lg-8">
					<span class="badge badge-pill badge-warning px-3 py-2 text-uppercase font-weight-bold mb-3" style="letter-spacing: 0.5px;">
						<i class="fas fa-landmark mr-1"></i> Overview & History
					</span>
					<h3 class="font-weight-bold text-dark mb-3">
						About the Municipality
					</h3>
					<p class="text-secondary mb-3" style="font-size: 1.05rem; line-height: 1.7;">
						The Municipality of Tabina was carved out of the Municipality of Dimataling on <strong>August 16, 1961</strong> by virtue of <strong>Executive Order No. 443</strong> signed by then President Carlos P. Garcia.
					</p>
					<p class="text-secondary mb-3" style="font-size: 1.05rem; line-height: 1.7;">
						It is part of the <strong>2<sup>nd</sup> district of Zamboanga del Sur</strong> and is classified as a <strong>4<sup>th</sup> class municipality</strong>. The municipality has a total land area of approximately <strong>86.90 square kilometers</strong> and a population of <strong>25,061</strong> as of CY 2015 census.
					</p>
					<p class="text-secondary mb-0" style="font-size: 1.05rem; line-height: 1.7;">
						Farming and fishing were the main and dominant occupation. Agriculture, fishery and forestry tops among the major industry group employer of the municipality followed by business and commerce.
					</p>
					<div class="mt-4">
						<a href="explore_tabina.php" class="btn btn-primary font-weight-bold px-4 py-2" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(59,130,246,0.3); background-image: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border: none;">
							<i class="fas fa-umbrella-beach mr-1"></i> Explore Tourism Highlights
						</a>
					</div>
				</div>
				<div class="col-lg-4 mt-4 mt-lg-0">
					<div class="p-4 rounded-lg bg-light border text-center" style="border-radius: 16px;">
						<img src="images/logo.webp" alt="Tabina Municipal Seal" style="height:150px" class="img-fluid mb-3 drop-shadow">
						<h6 class="font-weight-bold text-dark mb-1">MUNICIPAL PROFILE</h6>
						<div class="dropdown-divider my-2"></div>
						<div class="d-flex justify-content-between text-left small py-1">
							<span class="text-muted"><i class="fas fa-calendar-alt text-primary mr-1"></i> Established:</span>
							<strong class="text-dark">Aug 16, 1961 <x class="thid">(EO 443)</x></strong>
						</div>
						<div class="d-flex justify-content-between text-left small py-1">
							<span class="text-muted"><i class="fas fa-map-marker-alt text-primary mr-1"></i> District:</span>
							<strong class="text-dark">2nd District <x class="thid">Zamboanga del Sur</x></strong>
						</div>
						<div class="d-flex justify-content-between text-left small py-1">
							<span class="text-muted"><i class="fas fa-layer-group text-primary mr-1"></i> Class:</span>
							<strong class="text-dark">4th Class Municipality</strong>
						</div>
						<div class="d-flex justify-content-between text-left small py-1">
							<span class="text-muted"><i class="fas fa-ruler-combined text-primary mr-1"></i> Land Area:</span>
							<strong class="text-dark">86.90 sq km</strong>
						</div>
						<div class="d-flex justify-content-between text-left small py-1">
							<span class="text-muted"><i class="fas fa-industry text-primary mr-1"></i> <x class="thid">Top</x> Economy</x>:</span>
							<strong class="text-dark">Agriculture & Fishery</strong>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	// Query 15 Barangays & their Sitios (Districts)
	$bgy_purok_data = [];
	
	// Query distinct barangays from households
	$b_query = $link->query("SELECT DISTINCT barangay FROM households WHERE barangay != '' ORDER BY barangay ASC");
	if ($b_query) {
		while ($b_row = $b_query->fetch_assoc()) {
			$b_name = trim($b_row['barangay']);
			if (!empty($b_name)) {
				$bgy_purok_data[$b_name] = [];
			}
		}
	}
	
	// Query puroks per barangay from all tables
	$tables_to_check = ['households', 'hh_members', 'senior', 'indigents', 'pwd', 'solo_parent', 'clearances', 'permit_business', 'permit_operate', 'reg_fishing', 'cert_indigency'];
	
	foreach ($tables_to_check as $tbl) {
		$has_bgy = $link->query("SHOW COLUMNS FROM `$tbl` LIKE 'barangay'")->num_rows > 0;
		$has_addr = $link->query("SHOW COLUMNS FROM `$tbl` LIKE 'purok'")->num_rows > 0;
		
		if ($has_bgy && $has_addr) {
			$res_sit = $link->query("SELECT DISTINCT barangay, purok FROM `$tbl` WHERE barangay != '' AND purok != ''");
			if ($res_sit) {
				while ($s_row = $res_sit->fetch_assoc()) {
					$b = trim($s_row['barangay']);
					$s = trim($s_row['purok']);
					if (isset($bgy_purok_data[$b]) && !empty($s)) {
						if (!in_array($s, $bgy_purok_data[$b])) {
							$bgy_purok_data[$b][] = $s;
						}
					}
				}
			}
		}
	}

	// Sort puroks alphabetically for each barangay
	foreach ($bgy_purok_data as $b => $s_arr) {
		sort($s_arr);
		$bgy_purok_data[$b] = $s_arr;
	}
?>

<!-- Barangay & Sitio (District) Collapsible 3-Column Directory Card -->
<div class="container pb-5" id="barangay-directory">
	<div class="card border-0 shadow-sm rounded-lg" style="border-radius: 20px; background: #ffffff; overflow: hidden;">
		<!-- Card Header -->
		<div class="card-header bg-primary text-white p-4 d-flex align-items-center justify-content-between flex-wrap">
			<div>
				<h4 class="font-weight-bold text-white mb-1">
					<i class="fas fa-map-marked-alt mr-2"></i>Barangay Directory
				</h4>
				<p class="small text-white-50 mb-0">Explore all 15 Barangays and their corresponding Purok (Districts) across Tabina, Zamboanga del Sur</p>
			</div>
			<div class="mt-2 mt-md-0">
				<button type="button" class="btn btn-sm btn-light font-weight-bold rounded-pill shadow-sm" id="toggleAllBarangays">
					<i class="fas fa-expand-alt mr-1"></i> Expand / Collapse All
				</button>
			</div>
		</div>

		<!-- Municipal Map Graphic Banner Showcase -->
		<div class="p-4 bg-white border-bottom">
			<div class="row align-items-center">
				<div class="col-lg-6 mb-3 mb-lg-0">
					<div class="position-relative overflow-hidden rounded-lg shadow-sm border" style="border-radius: 14px;">
						<a href="https://www.google.com/maps/place/Tabina,+Zamboanga+del+Sur/@7.4136476,123.4053475,13z/data=!3m1!4b1!4m6!3m5!1s0x3256bfc6ca0302a3:0x7e9b5c1706e0a1d7!8m2!3d7.4220836!4d123.4047109!16zL20vMDZxMDk2?entry=ttu&g_ep=EgoyMDI2MDcxNS4wIKXMDSoASAFQAw%3D%3D" alt="Tabina Map" target="_blank" title="Click to View in Google Map">
							<div class="image" style="padding:5px;border-radius:12px;background:#fff;position:absolute;top:10px;left:10px;opacity:.9"><small> &nbsp; View in Map &nbsp; </small></div>
							<img src="images/tabina_map.jpg" alt="Zamboanga del Sur Map Indexing Tabina" class="img-fluid w-100" style="max-height: 380px; object-fit: fill; border-radius: 14px;">
						</a>
						<div class="position-absolute bottom-0 left-0 right-0 p-3 text-white" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px);">
							<small class="font-weight-bold text-uppercase tracking-wider"><i class="fas fa-map-marked-alt text-primary mr-1"></i> Official Barangay Territorial Map & Boundary Reference</small>
						</div>
					</div>
				</div>
				<div class="col-lg-6 pl-lg-4">
					<span class="badge badge-pill badge-primary px-3 py-2 text-uppercase font-weight-bold mb-3" style="letter-spacing: 0.5px; background: rgba(59, 130, 246, 0.15); color: #2563eb;">
						<i class="fas fa-compass mr-1"></i> Geographic & Administrative
					</span>
					<h3 class="font-weight-bold text-dark mb-3">
						Administrative <x class="thid">Barangays</x>
					</h3>
					<p class="text-secondary mb-3" style="line-height: 1.6;">
						The Municipality of Tabina comprises <strong>15 coastal and agricultural Barangays</strong> in the 2<sup>nd</sup> District of Zamboanga del Sur. Each Barangay is subdivided into local Purok (Districts) monitored under the Citizen-Centric Digital Platform (CCDP).
					</p>
					<div class="p-3 bg-light rounded-lg border" style="border-radius: 12px;">
						<div class="row text-center">
							<div class="col-4 border-right">
								<h5 class="font-weight-bold text-primary mb-0">15</h5>
								<small class="text-muted">Total Barangays</small>
							</div>
							<div class="col-4 border-right">
								<h5 class="font-weight-bold text-success mb-0">86.90</h5>
								<small class="text-muted">Square Kilometers</small>
							</div>
							<div class="col-4">
								<h5 class="font-weight-bold text-purple mb-0">16,298+</h5>
								<small class="text-muted">Surveyed Residents</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Card Body: 3-Column Grid -->
		<div class="card-body p-4 bg-light">
			<div class="row">
				<?php
					foreach ($bgy_purok_data as $bgy_name => $puroks) {
						$purok_count = count($puroks);
						$collapse_id = "collapseBgy_" . md5($bgy_name);
						
						// Get household count for this barangay
						$hh_cnt = 0;
						$hh_res = $link->query("SELECT COUNT(*) as cnt FROM households WHERE barangay = '" . $link->real_escape_string($bgy_name) . "'");
						if ($hh_res && $r = $hh_res->fetch_assoc()) {
							$hh_cnt = $r['cnt'];
						}
				?>
				<div class="col-lg-4 col-md-6 mb-4">
					<div class="card border-0 shadow-sm h-100 tranpurokn-hover" style="border-radius: 14px; background: #ffffff;">
						<!-- Barangay Header (Collapsible Trigger) -->
						<div class="card-header bg-white border-0 p-3 d-flex align-items-center justify-content-between cursor-pointer" 
							 data-toggle="collapse" 
							 data-target="#<?php echo $collapse_id; ?>" 
							 aria-expanded="false" 
							 aria-controls="<?php echo $collapse_id; ?>"
							 style="cursor: pointer;">
							<div class="d-flex align-items-center">
								<div class="rounded-circle text-primary p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #e0e7ff;">
									<i class="fas fa-map-marker-alt"></i>
								</div>
								<div>
									<h6 class="font-weight-bold text-dark mb-0"><?php echo htmlspecialchars($bgy_name); ?></h6>
									<small class="text-muted"><?php echo number_format($hh_cnt); ?> Households</small>
								</div>
							</div>
							<div class="d-flex align-items-center">
								<span class="badge badge-pill badge-primary px-2 py-1 mr-2 font-weight-bold small">
									<?php echo $purok_count; ?> Purok
								</span>
								<i class="fas fa-chevron-down text-muted"></i>
							</div>
						</div>

						<!-- Collapsible Purok List -->
						<div id="<?php echo $collapse_id; ?>" class="collapse">
							<div class="card-body pt-0 pb-3 px-3 border-top bg-light">
								<small class="text-uppercase font-weight-bold text-muted d-block mb-2 mt-2" style="font-size: 11px; letter-spacing: 0.5px;">
									<i class="fas fa-location-arrow text-primary mr-1"></i> Purok / Districts:
								</small>
								<?php if ($purok_count > 0): ?>
									<div class="d-flex flex-wrap">
										<?php foreach ($puroks as $purok): ?>
											<span class="badge badge-light border text-dark font-weight-normal p-2 m-1 shadow-2xs" style="border-radius: 8px; background: #f8fafc;">
												<i class="fas fa-dot-circle text-primary mr-1 small"></i><?php echo htmlspecialchars($purok); ?>
											</span>
										<?php endforeach; ?>
									</div>
								<?php else: ?>
									<p class="small text-muted mb-0 font-italic">No Purok recorded</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<!-- Main Content Container -->
<div id="Analytics" class="container py-5" id="executive-metrics" style="margin-top:-80px">
	<!-- Interactive Analytics Charts Row -->
	<div class="row mt-4">
		<!-- Economic Breakdown -->
		<div class="col-lg-5">
			<div class="chart-card text-center">
				<h3 class="section-title text-left"><x class="thid">Municipal</x> Economy & Licensing</h3>
				<div class="mx-auto" style="position: relative; height: 260px; max-width: 260px;">
					<canvas id="publicEcoChart"></canvas>
				</div>
				<div class="mt-3 text-muted small">
					Local Business Permits, Vessels, and Permits to Operate in Tabina
				</div>
			</div>
		</div>
		<!-- Social Programs Overview -->
		<div class="col-lg-7">
			<div class="chart-card">
				<h3 class="section-title">Social Welfare Program <x class="thid">Registries</x></h3>
				<div style="position: relative; height: 320px;">
					<canvas id="publicSocialChart"></canvas>
				</div>
			</div>
		</div>
	</div>
	<!-- Executive Summary Metric Cards (Row 1) -->
	<div class="row">
		<!-- Senior Citizens & OSCA -->
		<div class="col-lg-3 col-md-6">
			<div class="exec-card">
				<div class="icon-shape icon-blue">
					<i class="fas fa-user-shield"></i>
				</div>
				<span class="text-uppercase text-muted font-weight-bold small tracking-wider">Senior Citizens</span>
				<h2 class="font-weight-bold text-dark my-1"><?php echo number_format($total_seniors); ?></h2>
				<p class="text-muted small mb-0">
					<span class="text-success font-weight-bold"><i class="fas fa-hand-holding-heart mr-1"></i><?php echo number_format($pensioners_count); ?></span> Pensioners &bull; 
					<span class="text-primary font-weight-bold"><?php echo number_format($total_seniors_80up); ?></span> Aged 80+
				</p>
			</div>
		</div>
		<!-- Social Welfare & PWD -->
		<div class="col-lg-3 col-md-6">
			<div class="exec-card">
				<div class="icon-shape icon-purple">
					<i class="fas fa-wheelchair"></i>
				</div>
				<span class="text-uppercase text-muted font-weight-bold small tracking-wider">Social Welfare</span>
				<h2 class="font-weight-bold text-dark my-1"><?php echo number_format($total_pwd); ?></h2>
				<p class="text-muted small mb-0">
					<span class="text-purple font-weight-bold"><?php echo number_format($total_solo); ?></span> Solo Parents &bull; 
					<span class="text-danger font-weight-bold"><?php echo number_format($total_ind); ?></span> Indigents
				</p>
			</div>
		</div>

		<!-- Households & Population Mapped -->
		<div class="col-lg-3 col-md-6">
			<div class="exec-card">
				<div class="icon-shape icon-green">
					<i class="fas fa-home"></i>
				</div>
				<span class="text-uppercase text-muted font-weight-bold small tracking-wider">Households Mapped</span>
				<h2 class="font-weight-bold text-dark my-1"><?php echo number_format($total_hh); ?></h2>
				<p class="text-muted small mb-0">
					<span class="text-success font-weight-bold"><i class="fas fa-users mr-1"></i><?php echo number_format($total_hh_members); ?></span> Residents Surveyed
				</p>
			</div>
		</div>

		<!-- Economic Registries -->
		<div class="col-lg-3 col-md-6">
			<div class="exec-card">
				<div class="icon-shape icon-orange">
					<i class="fas fa-briefcase"></i>
				</div>
				<span class="text-uppercase text-muted font-weight-bold small tracking-wider">Business & Permits</span>
				<h2 class="font-weight-bold text-dark my-1"><?php echo number_format($total_biz_permits); ?></h2>
				<p class="text-muted small mb-0">
					<span class="text-warning font-weight-bold"><?php echo number_format($total_op_permits); ?></span> Operating Permits &bull; 
					<span class="text-info font-weight-bold"><?php echo number_format($total_fishing); ?></span> Vessels
				</p>
			</div>
		</div>
	</div>
</div>

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div style="margin-top:-30px"></div>

<?php include_once("social_statistics2.php");?>
<?php include_once("households_analytics2.php");?>
<?php include_once("senior_statistics2.php");?>
<?php include_once("footer2.php");?>

<script>
	// Apply dark theme styles to Chart.js defaults if dark mode is active
	if (document.documentElement.getAttribute('data-theme') === 'dark') {
		Chart.defaults.color = '#e2e8f0';
		Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.12)';
	}

	// Social Registries Chart
	const ctxSocial = document.getElementById('publicSocialChart').getContext('2d');
	new Chart(ctxSocial, {
		type: 'bar',
		data: {
			labels: ['Seniors', 'PWDs', 'Kindergarten', 'Solo Parents', 'Indigents (4Ps)', 'Households'],
			datasets: [{
				data: [
					<?php echo $total_seniors; ?>, 
					<?php echo $total_pwd; ?>, 
					<?php echo $total_kinder; ?>, 
					<?php echo $total_solo; ?>, 
					<?php echo $total_ind; ?>, 
					<?php echo $total_hh; ?>
				],
				backgroundColor: ['#2563eb', '#7c3aed', '#10b981', '#0284c7', '#ec4899', '#f59e0b'],
				borderRadius: 8
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: { legend: { display: false } },
			scales: {
				y: { beginAtZero: true, grid: { color: (document.documentElement.getAttribute('data-theme') === 'dark' ? 'rgba(255,255,255,0.08)' : '#f1f5f9') } },
				x: { grid: { display: false } }
			}
		}
	});

	// Economy Chart
	const ctxEco = document.getElementById('publicEcoChart').getContext('2d');
	new Chart(ctxEco, {
		type: 'doughnut',
		data: {
			labels: ['Business Permits', 'Operating Permits', 'Fishing Vessels'],
			datasets: [{
				data: [<?php echo $total_biz_permits; ?>, <?php echo $total_op_permits; ?>, <?php echo $total_fishing; ?>],
				backgroundColor: ['#f59e0b', '#3b82f6', '#10b981'],
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: { legend: { position: 'bottom' } }
		}
	});

	// Expand / Collapse All Barangays Toggle
	let allExpanded = false;
	const toggleBtn = document.getElementById('toggleAllBarangays');
	if (toggleBtn) {
		toggleBtn.addEventListener('click', function() {
			allExpanded = !allExpanded;
			document.querySelectorAll('#barangay-directory .collapse').forEach(function(el) {
				if (allExpanded) {
					el.classList.add('show');
				} else {
					el.classList.remove('show');
				}
			});
			this.innerHTML = allExpanded ? 
				'<i class="fas fa-compress-alt mr-1"></i> Collapse All' : 
				'<i class="fas fa-expand-alt mr-1"></i> Expand / Collapse All';
		});
	}

	// 100% Reliable Collapse Toggle Handler for Individual Barangay Cards
	document.querySelectorAll('#barangay-directory [data-toggle="collapse"]').forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			const targetSelector = this.getAttribute('data-target') || this.getAttribute('href');
			if (targetSelector) {
				const targetEl = document.querySelector(targetSelector);
				if (targetEl) {
					targetEl.classList.toggle('show');
					const isShown = targetEl.classList.contains('show');
					this.setAttribute('aria-expanded', isShown ? 'true' : 'false');
					const icon = this.querySelector('.fa-chevron-down, .fa-chevron-up');
					if (icon) {
						if (isShown) {
							icon.classList.remove('fa-chevron-down');
							icon.classList.add('fa-chevron-up');
						} else {
							icon.classList.remove('fa-chevron-up');
							icon.classList.add('fa-chevron-down');
						}
					}
				}
			}
		});
	});
	function sessionEnd(gid){	
		if(confirm("Are you sure you want to Logout?")){
			window.location.href = 'logout.php';
		}
	}
</script>

<div style="position:fixed;top:25px;right:15px;z-index:9999">
	<button id='theme-toggle-btn' class='btn btn-sm btn-link text-white shadow-none' onclick='toggleTheme()' style='font-size: 16px; border: none; background: transparent; cursor: pointer; padding: 5px 10px;' title='Toggle Dark/Light Mode'>
		<i id='theme-toggle-icon' class='fas fa-moon'></i>
	</button>
</div>

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
</script>