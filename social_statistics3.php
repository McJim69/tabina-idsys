<?php 	
	// --- SENIORS DATA ---
	$res_senior = $link->query("SELECT COUNT(*) as total FROM senior");
	$total_senior = mysqli_fetch_assoc($res_senior)['total'] ?? 0;
	
	$res_senior_m = $link->query("SELECT COUNT(*) as total FROM senior WHERE sex = 'M' OR sex = 'Male'");
	$senior_m = mysqli_fetch_assoc($res_senior_m)['total'] ?? 0;
	$senior_f = $total_senior - $senior_m;
	
	$res_senior_pen = $link->query("SELECT COUNT(*) as total FROM senior WHERE pensioner = 'Yes'");
	$senior_pen = mysqli_fetch_assoc($res_senior_pen)['total'] ?? 0;
	
	// --- KINDER DATA ---
	$res_kinder = $link->query("SELECT COUNT(*) as total FROM kinder");
	$total_kinder = mysqli_fetch_assoc($res_kinder)['total'] ?? 0;
	
	$res_kinder_m = $link->query("SELECT COUNT(*) as total FROM kinder WHERE sex = 'M' OR sex = 'Male'");
	$kinder_m = mysqli_fetch_assoc($res_kinder_m)['total'] ?? 0;
	$kinder_f = $total_kinder - $kinder_m;
	
	// --- PWD DATA ---
	$res_pwd = $link->query("SELECT COUNT(*) as total FROM pwd");
	$total_pwd = mysqli_fetch_assoc($res_pwd)['total'] ?? 0;
	
	$res_pwd_m = $link->query("SELECT COUNT(*) as total FROM pwd WHERE sex = 'M' OR sex = 'Male'");
	$pwd_m = mysqli_fetch_assoc($res_pwd_m)['total'] ?? 0;
	$pwd_f = $total_pwd - $pwd_m;

	// --- SOLO PARENTS DATA ---
	$res_solo = $link->query("SELECT COUNT(*) as total FROM solo_parent");
	$total_solo = mysqli_fetch_assoc($res_solo)['total'] ?? 0;
	
	$res_solo_m = $link->query("SELECT COUNT(*) as total FROM solo_parent WHERE sex = 'M' OR sex = 'Male'");
	$solo_m = mysqli_fetch_assoc($res_solo_m)['total'] ?? 0;
	$solo_f = $total_solo - $solo_m;
	
	// --- HOUSEHOLDS DATA ---
	$res_hh = $link->query("SELECT COUNT(*) as total FROM households");
	$total_hh = mysqli_fetch_assoc($res_hh)['total'] ?? 0;
	
	// --- INDIGENTS DATA ---
	$res_ind = $link->query("SELECT COUNT(*) as total, SUM(amount) as total_amount FROM indigents");
	$row_ind = mysqli_fetch_assoc($res_ind);
	$total_ind = $row_ind['total'] ?? 0;
	$ind_amount = $row_ind['total_amount'] ?? 0;
	
	// --- BARANGAY DIRECTORY (COMBINED) ---
	$temp_brgys = [];
	
	// Senior
	$res = $link->query("SELECT barangay FROM senior GROUP BY barangay");
	if ($res) {
		while($row = mysqli_fetch_assoc($res)){
			$b = trim($row['barangay']);
			if($b !== "" && $b !== null && strtolower($b) !== 'all barangays') {
				$temp_brgys[$b] = true;
			}
		}
	}
	
	// Kinder
	$res = $link->query("SELECT barangay FROM kinder GROUP BY barangay");
	if ($res) {
		while($row = mysqli_fetch_assoc($res)){
			$b = trim($row['barangay']);
			if($b !== "" && $b !== null && strtolower($b) !== 'all barangays') {
				$temp_brgys[$b] = true;
			}
		}
	}

	// PWD
	$res = $link->query("SELECT barangay FROM pwd GROUP BY barangay");
	if ($res) {
		while($row = mysqli_fetch_assoc($res)){
			$b = trim($row['barangay']);
			if($b !== "" && $b !== null && strtolower($b) !== 'all barangays') {
				$temp_brgys[$b] = true;
			}
		}
	}

	// Solo Parent
	$res = $link->query("SELECT barangay FROM solo_parent GROUP BY barangay");
	if ($res) {
		while($row = mysqli_fetch_assoc($res)){
			$b = trim($row['barangay']);
			if($b !== "" && $b !== null && strtolower($b) !== 'all barangays') {
				$temp_brgys[$b] = true;
			}
		}
	}

	// Households
	$res = $link->query("SELECT barangay FROM households GROUP BY barangay");
	if ($res) {
		while($row = mysqli_fetch_assoc($res)){
			$b = trim($row['barangay']);
			if($b !== "" && $b !== null && strtolower($b) !== 'all barangays') {
				$temp_brgys[$b] = true;
			}
		}
	}

	// Indigents
	$res = $link->query("SELECT barangay FROM indigents GROUP BY barangay");
	if ($res) {
		while($row = mysqli_fetch_assoc($res)){
			$b = trim($row['barangay']);
			if($b !== "" && $b !== null && strtolower($b) !== 'all barangays') {
				$temp_brgys[$b] = true;
			}
		}
	}
	
	ksort($temp_brgys);
	
	$barangays = [];
	foreach(array_keys($temp_brgys) as $b){
		$barangays[$b] = [
			'senior' => 0,
			'kinder' => 0,
			'pwd' => 0,
			'solo' => 0,
			'households' => 0,
			'indigents' => 0,
			'total' => 0
		];
	}
	
	// Populate counts
	$res = $link->query("SELECT barangay, COUNT(*) as count FROM senior GROUP BY barangay");
	while($row = mysqli_fetch_assoc($res)){
		$b = trim($row['barangay']);
		if(isset($barangays[$b])) {
			$barangays[$b]['senior'] = intval($row['count']);
			$barangays[$b]['total'] += intval($row['count']);
		}
	}
	
	$res = $link->query("SELECT barangay, COUNT(*) as count FROM kinder GROUP BY barangay");
	while($row = mysqli_fetch_assoc($res)){
		$b = trim($row['barangay']);
		if(isset($barangays[$b])) {
			$barangays[$b]['kinder'] = intval($row['count']);
			$barangays[$b]['total'] += intval($row['count']);
		}
	}
	
	$res = $link->query("SELECT barangay, COUNT(*) as count FROM pwd GROUP BY barangay");
	while($row = mysqli_fetch_assoc($res)){
		$b = trim($row['barangay']);
		if(isset($barangays[$b])) {
			$barangays[$b]['pwd'] = intval($row['count']);
			$barangays[$b]['total'] += intval($row['count']);
		}
	}

	$res = $link->query("SELECT barangay, COUNT(*) as count FROM solo_parent GROUP BY barangay");
	while($row = mysqli_fetch_assoc($res)){
		$b = trim($row['barangay']);
		if(isset($barangays[$b])) {
			$barangays[$b]['solo'] = intval($row['count']);
			$barangays[$b]['total'] += intval($row['count']);
		}
	}
	
	$res = $link->query("SELECT barangay, COUNT(*) as count FROM households GROUP BY barangay");
	while($row = mysqli_fetch_assoc($res)){
		$b = trim($row['barangay']);
		if(isset($barangays[$b])) {
			$barangays[$b]['households'] = intval($row['count']);
			$barangays[$b]['total'] += intval($row['count']);
		}
	}
	
	$res = $link->query("SELECT barangay, COUNT(*) as count FROM indigents GROUP BY barangay");
	while($row = mysqli_fetch_assoc($res)){
		$b = trim($row['barangay']);
		if(isset($barangays[$b])) {
			$barangays[$b]['indigents'] = intval($row['count']);
			$barangays[$b]['total'] += intval($row['count']);
		}
	}
	
	// Sort by total count
	uasort($barangays, function($a, $b) {
		return $b['total'] <=> $a['total'];
	});
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="style/stats-social.css" rel="stylesheet" type="text/css"/>

<div class="container py-4">
	<!-- Page Header -->
	<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
		<div>
			<h2 class="font-weight-bold text-dark mb-1">
				<i class="fas fa-users mr-2 text-primary"></i>Social <x class="thid">Services</x>
			</h2>
			<p class="text-muted mb-0">Demographics and registry analytics across municipal social welfare programs</p>
		</div>
	</div>

	<!-- Top Stats Overview (6 Cards) -->
	<div class="row">
		<div class="col-xl-2 col-lg-4 col-md-6 col-sm-12">
			<div class="stats-card card-blue">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Seniors</span>
				<div class="d-flex align-items-baseline mt-1">
					<h3 class="mb-0 font-weight-bold"><?php echo number_format($total_senior); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-lg-4 col-md-6 col-sm-12">
			<div class="stats-card card-purple">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">PWDs</span>
				<div class="d-flex align-items-baseline mt-1">
					<h3 class="mb-0 font-weight-bold"><?php echo number_format($total_pwd); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-lg-4 col-md-6 col-sm-12">
			<div class="stats-card card-green">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Kinder</span>
				<div class="d-flex align-items-baseline mt-1">
					<h3 class="mb-0 font-weight-bold"><?php echo number_format($total_kinder); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-lg-4 col-md-6 col-sm-12">
			<div class="stats-card card-indigo">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Solo Parents</span>
				<div class="d-flex align-items-baseline mt-1">
					<h3 class="mb-0 font-weight-bold"><?php echo number_format($total_solo); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-lg-4 col-md-6 col-sm-12">
			<div class="stats-card card-rose">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Indigents</span>
				<div class="d-flex align-items-baseline mt-1">
					<h3 class="mb-0 font-weight-bold"><?php echo number_format($total_ind); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-lg-4 col-md-6 col-sm-12">
			<div class="stats-card card-orange">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Households</span>
				<div class="d-flex align-items-baseline mt-1">
					<h3 class="mb-0 font-weight-bold"><?php echo number_format($total_hh); ?></h3>
				</div>
			</div>
		</div>
	</div>

	<!-- Main Demographics Charts Row -->
	<div class="row">
		<!-- Registry Comparison Bar Chart -->
		<div class="col-lg-6 col-md-12">
			<div class="chart-container">
				<h4 class="chart-title">Social Registries <x class="thid">Size Comparison</x></h4>
				<div style="position: relative; height: 320px;">
					<canvas id="registryBarChart"></canvas>
				</div>
			</div>
		</div>

		<!-- Gender Composition Chart -->
		<div class="col-lg-6 col-md-12">
			<div class="chart-container">
				<h4 class="chart-title">Gender Distribution <x class="thid">Across Groups</x></h4>
				<div style="position: relative; height: 320px;">
					<canvas id="genderGroupedBarChart"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// Define instances variables
	var registryBarChartInstance, genderGroupedBarChartInstance;

	function getThemeConfig() {
		var theme = document.documentElement.getAttribute('data-theme') || 'light';
		var isDark = (theme === 'dark');
		return {
			isDark: isDark,
			textColor: isDark ? '#cbd5e1' : '#1e293b',
			gridColor: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.05)',
			tooltipBg: isDark ? '#1e293b' : '#ffffff',
			tooltipBorder: isDark ? '#334155' : '#e2e8f0',
			tooltipColor: isDark ? '#f8fafc' : '#1e293b'
		};
	}

	var config = getThemeConfig();

	// Registry Comparison Bar Chart
	const ctxReg = document.getElementById('registryBarChart').getContext('2d');
	registryBarChartInstance = new Chart(ctxReg, {
		type: 'bar',
		data: {
			labels: ['Seniors', 'PWDs', 'Kinder', 'Solo Parents', 'Indigents (4P\'s)', 'Households'],
			datasets: [{
				data: [
					<?php echo $total_senior; ?>, 
					<?php echo $total_pwd; ?>, 
					<?php echo $total_kinder; ?>, 
					<?php echo $total_solo; ?>, 
					<?php echo $total_ind; ?>, 
					<?php echo $total_hh; ?>
				],
				backgroundColor: ['#2563eb', '#6366f1', '#10b981', '#6f42c1', '#ec4899', '#f59e0b'],
				borderRadius: 8
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: { display: false },
				tooltip: {
					backgroundColor: config.tooltipBg,
					titleColor: config.tooltipColor,
					bodyColor: config.tooltipColor,
					borderColor: config.tooltipBorder,
					borderWidth: 1
				}
			},
			scales: {
				x: {
					grid: { color: config.gridColor },
					ticks: { color: config.textColor }
				},
				y: {
					beginAtZero: true,
					title: { display: true, text: 'Registered Count', color: config.textColor, font: { weight: 'bold' } },
					grid: { color: config.gridColor },
					ticks: { color: config.textColor }
				}
			}
		}
	});

	// Gender Composition Grouped Bar Chart
	const ctxGender = document.getElementById('genderGroupedBarChart').getContext('2d');
	genderGroupedBarChartInstance = new Chart(ctxGender, {
		type: 'bar',
		data: {
			labels: ['Seniors', 'PWDs', 'Kindergarten', 'Solo Parents'],
			datasets: [
				{
					label: 'Male',
					data: [<?php echo $senior_m; ?>, <?php echo $pwd_m; ?>, <?php echo $kinder_m; ?>, <?php echo $solo_m; ?>],
					backgroundColor: '#3b82f6',
					borderRadius: 6
				},
				{
					label: 'Female',
					data: [<?php echo $senior_f; ?>, <?php echo $pwd_f; ?>, <?php echo $kinder_f; ?>, <?php echo $solo_f; ?>],
					backgroundColor: '#ec4899',
					borderRadius: 6
				}
			]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: { 
					position: 'bottom',
					labels: { color: config.textColor }
				},
				tooltip: {
					backgroundColor: config.tooltipBg,
					titleColor: config.tooltipColor,
					bodyColor: config.tooltipColor,
					borderColor: config.tooltipBorder,
					borderWidth: 1
				}
			},
			scales: {
				x: {
					grid: { color: config.gridColor },
					ticks: { color: config.textColor }
				},
				y: {
					beginAtZero: true,
					title: { display: true, text: 'Count', color: config.textColor, font: { weight: 'bold' } },
					grid: { color: config.gridColor },
					ticks: { color: config.textColor }
				}
			}
		}
	});

	// Function to update active Chart.js instances dynamically when theme changes
	function updateChartThemes() {
		var newConfig = getThemeConfig();
		var charts = [registryBarChartInstance, genderGroupedBarChartInstance];
		
		charts.forEach(function(chart) {
			if (!chart) return;
			
			// Update scale text and grids
			if (chart.options.scales) {
				if (chart.options.scales.x) {
					if (chart.options.scales.x.ticks) chart.options.scales.x.ticks.color = newConfig.textColor;
					if (chart.options.scales.x.grid) chart.options.scales.x.grid.color = newConfig.gridColor;
					if (chart.options.scales.x.title) chart.options.scales.x.title.color = newConfig.textColor;
				}
				if (chart.options.scales.y) {
					if (chart.options.scales.y.ticks) chart.options.scales.y.ticks.color = newConfig.textColor;
					if (chart.options.scales.y.grid) chart.options.scales.y.grid.color = newConfig.gridColor;
					if (chart.options.scales.y.title) chart.options.scales.y.title.color = newConfig.textColor;
				}
			}
			
			// Update legends
			if (chart.options.plugins && chart.options.plugins.legend) {
				if (chart.options.plugins.legend.labels) {
					chart.options.plugins.legend.labels.color = newConfig.textColor;
				}
			}
			
			// Update tooltips
			if (chart.options.plugins && chart.options.plugins.tooltip) {
				chart.options.plugins.tooltip.backgroundColor = newConfig.tooltipBg;
				chart.options.plugins.tooltip.borderColor = newConfig.tooltipBorder;
				chart.options.plugins.tooltip.titleColor = newConfig.tooltipColor;
				chart.options.plugins.tooltip.bodyColor = newConfig.tooltipColor;
			}
			
			chart.update();
		});
	}

	// Watch for theme changes dynamically using MutationObserver
	var observer = new MutationObserver(function(mutations) {
		mutations.forEach(function(mutation) {
			if (mutation.attributeName === 'data-theme') {
				updateChartThemes();
			}
		});
	});
	observer.observe(document.documentElement, { attributes: true });
</script>

<?php 
	//require('users_profile.php');
	require('footer.php');
?>
