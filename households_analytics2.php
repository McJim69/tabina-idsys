<?php include_once("analytics-css.php");?>

<div class="container my-4">
	<!-- Page Header -->
	<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
		<div>
			<h2 class="font-weight-bold text-primary mb-1">
				<i class="fas fa-chart-bar mr-2"></i>Households <x class="thid">Analytics</x>
			</h2>
			<p class="text-muted small mb-0">Demographic distributions, sanitary access, and socio-economic analysis for the Municipality of Tabina</p>
		</div>
	</div>

	<?php
		// Fetch Metrics
		// 1. Total Households
		$total_hh = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM households"))['total'] ?? 0;

		// 2. Toilet Access
		$toilet_res = mysqli_fetch_assoc($link->query("SELECT SUM(CASE WHEN LOWER(toilet_have) = 'yes' OR toilet_have = '1' THEN 1 ELSE 0 END) as yes_count FROM households"));
		$toilet_yes = $toilet_res['yes_count'] ?? 0;
		$toilet_pct = $total_hh > 0 ? round(($toilet_yes / $total_hh) * 100, 1) : 0;

		// 3. Water Access
		$water_res = mysqli_fetch_assoc($link->query("SELECT SUM(CASE WHEN LOWER(water_access) = 'yes' OR water_access = '1' THEN 1 ELSE 0 END) as yes_count FROM households"));
		$water_yes = $water_res['yes_count'] ?? 0;
		$water_pct = $total_hh > 0 ? round(($water_yes / $total_hh) * 100, 1) : 0;

		// 4. Hospitalization Rate (in the past period)
		$hosp_res = mysqli_fetch_assoc($link->query("SELECT SUM(CASE WHEN LOWER(mem_hospitalize) = 'yes' OR mem_hospitalize = '1' THEN 1 ELSE 0 END) as yes_count FROM households"));
		$hosp_yes = $hosp_res['yes_count'] ?? 0;
		$hosp_pct = $total_hh > 0 ? round(($hosp_yes / $total_hh) * 100, 1) : 0;

		// 5. Solid Waste Management Averages
		$swm_res = mysqli_fetch_assoc($link->query("SELECT 
			SUM(CASE WHEN LOWER(swm_reuse) = 'yes' OR swm_reuse = '1' THEN 1 ELSE 0 END) as reuse,
			SUM(CASE WHEN LOWER(swm_reduce) = 'yes' OR swm_reduce = '1' THEN 1 ELSE 0 END) as reduce,
			SUM(CASE WHEN LOWER(swm_recycling) = 'yes' OR swm_recycling = '1' THEN 1 ELSE 0 END) as recycle,
			SUM(CASE WHEN LOWER(swm_composting) = 'yes' OR swm_composting = '1' THEN 1 ELSE 0 END) as compost,
			SUM(CASE WHEN LOWER(swm_waste_to_mrf) = 'yes' OR swm_waste_to_mrf = '1' THEN 1 ELSE 0 END) as mrf
			FROM households"));
		$swm_data = [
			'Reuse' => $swm_res['reuse'] ?? 0,
			'Reduce' => $swm_res['reduce'] ?? 0,
			'Recycle' => $swm_res['recycle'] ?? 0,
			'Compost' => $swm_res['compost'] ?? 0,
			'MRF Transfer' => $swm_res['mrf'] ?? 0
		];

		// 6. House Types Distribution
		$house_res = $link->query("SELECT house_type, COUNT(*) as count FROM households GROUP BY house_type");
		$house_types = [];
		while($row = mysqli_fetch_assoc($house_res)){
			$label = !empty($row['house_type']) ? $row['house_type'] : 'Unspecified';
			$house_types[$label] = (int)$row['count'];
		}

		// 7. Average Distance to Public Facilities (filters non-numeric values dynamically)
		$dist_res = mysqli_fetch_assoc($link->query("SELECT 
			AVG(CASE WHEN access_hospital_distance REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(access_hospital_distance AS DECIMAL(10,2)) ELSE NULL END) as hospital,
			AVG(CASE WHEN access_school_distance REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(access_school_distance AS DECIMAL(10,2)) ELSE NULL END) as school,
			AVG(CASE WHEN access_church_distance REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(access_church_distance AS DECIMAL(10,2)) ELSE NULL END) as church,
			AVG(CASE WHEN access_recreation_distance REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(access_recreation_distance AS DECIMAL(10,2)) ELSE NULL END) as recreation
			FROM households"));
		$avg_distances = [
			'Hospital' => round($dist_res['hospital'] ?? 0, 2),
			'School' => round($dist_res['school'] ?? 0, 2),
			'Church' => round($dist_res['church'] ?? 0, 2),
			'Playground/MRF' => round($dist_res['recreation'] ?? 0, 2)
		];

		// 8. Top Illnesses causing Hospitalization
		$ill_res = $link->query("SELECT illness_if_yes, COUNT(*) as count FROM households WHERE illness_if_yes IS NOT NULL AND illness_if_yes != '' GROUP BY illness_if_yes ORDER BY count DESC LIMIT 5");
		$illnesses = [];
		while($row = mysqli_fetch_assoc($ill_res)){
			$illnesses[$row['illness_if_yes']] = (int)$row['count'];
		}

		// 9. Top Causes of Mortality
		$death_res = $link->query("SELECT death_cause_if_yes, COUNT(*) as count FROM households WHERE death_cause_if_yes IS NOT NULL AND death_cause_if_yes != '' GROUP BY death_cause_if_yes ORDER BY count DESC LIMIT 5");
		$mortalities = [];
		while($row = mysqli_fetch_assoc($death_res)){
			$mortalities[$row['death_cause_if_yes']] = (int)$row['count'];
		}
	?>

	<!-- Top Highlights Cards -->
	<div class="row mb-4">
		<!-- Total Households -->
		<div class="col-md-3 mb-3">
			<div class="card analytics-card shadow-sm border p-3 bg-white">
				<div class="text-muted small text-uppercase font-weight-bold">Total Households</div>
				<div class="metric-num text-primary"><?php echo number_format($total_hh); ?></div>
				<div class="small text-muted"><i class="fas fa-home mr-1"></i> Registered Households</div>
			</div>
		</div>
		<!-- Toilet Access Pct -->
		<div class="col-md-3 mb-3">
			<div class="card analytics-card shadow-sm border p-3 bg-white">
				<div class="text-muted small text-uppercase font-weight-bold">Sanitary Toilet Access</div>
				<div class="metric-num text-success"><?php echo $toilet_pct; ?>%</div>
				<div class="small text-muted"><i class="fas fa-toilet mr-1"></i> <?php echo number_format($toilet_yes); ?> Households have toilets</div>
			</div>
		</div>
		<!-- Water Access Pct -->
		<div class="col-md-3 mb-3">
			<div class="card analytics-card shadow-sm border p-3 bg-white">
				<div class="text-muted small text-uppercase font-weight-bold">Clean Water Access</div>
				<div class="metric-num text-info"><?php echo $water_pct; ?>%</div>
				<div class="small text-muted"><i class="fas fa-tint mr-1"></i> <?php echo number_format($water_yes); ?> Households have water access</div>
			</div>
		</div>
		<!-- Hospitalization Rate -->
		<div class="col-md-3 mb-3">
			<div class="card analytics-card shadow-sm border p-3 bg-white">
				<div class="text-muted small text-uppercase font-weight-bold">Hospitalization Rate</div>
				<div class="metric-num text-danger"><?php echo $hosp_pct; ?>%</div>
				<div class="small text-muted"><i class="fas fa-hospital-user mr-1"></i> <?php echo number_format($hosp_yes); ?> Households hospitalized</div>
			</div>
		</div>
	</div>

	<!-- First Visual Analytics Section -->
	<div class="row">
		<!-- SWM adoption rates -->
		<div class="col-lg-6 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-recycle mr-2 text-success"></i>Solid Waste Management Adoption <x class="thid">(Household Count)</x>
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<canvas id="swmChart" style="max-height: 300px;"></canvas>
				</div>
			</div>
		</div>

		<!-- Average distance to facilities -->
		<div class="col-lg-6 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-road mr-2 text-primary"></i><x class="thid">Average Distance to Core</x> Public Facilities (km)
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<canvas id="distanceChart" style="max-height: 300px;"></canvas>
				</div>
			</div>
		</div>
	</div>

	<!-- Second Visual Analytics Section -->
	<div class="row">
		<!-- House Type distributions -->
		<div class="col-lg-4 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-home mr-2 text-warning"></i>Housing Types Distribution
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<canvas id="houseChart" style="max-height: 300px;"></canvas>
				</div>
			</div>
		</div>

		<!-- Illness distributions -->
		<div class="col-lg-4 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-heartbeat mr-2 text-danger"></i>Top Hospitalization Illnesses
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<?php if (empty($illnesses)) { ?>
						<div class="text-muted small text-center my-auto">No hospitalization details recorded.</div>
					<?php } else { ?>
						<canvas id="illnessChart" style="max-height: 300px;"></canvas>
					<?php } ?>
				</div>
			</div>
		</div>

		<!-- Mortality causes distributions -->
		<div class="col-lg-4 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-skull-crossbones mr-2 text-secondary"></i>Top Causes of Mortality
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<?php if (empty($mortalities)) { ?>
						<div class="text-muted small text-center my-auto">No mortality causes recorded.</div>
					<?php } else { ?>
						<canvas id="deathChart" style="max-height: 300px;"></canvas>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function() {
		// Define global instances variables
		var swmChartInstance, distanceChartInstance, houseChartInstance, illnessChartInstance, deathChartInstance;

		// Function to resolve current theme parameters
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

		// Chart Colors
		var colors = [
			'#3b82f6', // Blue
			'#10b981', // Green
			'#f59e0b', // Amber
			'#ef4444', // Red
			'#8b5cf6', // Purple
			'#ec4899', // Pink
			'#14b8a6', // Teal
			'#6366f1'  // Indigo
		];

		// 1. SWM Chart (Bar)
		var swmCtx = document.getElementById('swmChart').getContext('2d');
		swmChartInstance = new Chart(swmCtx, {
			type: 'bar',
			data: {
				labels: <?php echo json_encode(array_keys($swm_data)); ?>,
				datasets: [{
					label: 'Households',
					data: <?php echo json_encode(array_values($swm_data)); ?>,
					backgroundColor: 'rgba(16, 185, 129, 0.75)',
					borderColor: '#10b981',
					borderWidth: 1,
					borderRadius: 6
				}]
			},
			options: {
				responsive: true,
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
						grid: { color: config.gridColor },
						ticks: { color: config.textColor, stepSize: 1 }
					}
				}
			}
		});

		// 2. Average Distance Chart (Horizontal Bar)
		var distCtx = document.getElementById('distanceChart').getContext('2d');
		distanceChartInstance = new Chart(distCtx, {
			type: 'bar',
			data: {
				labels: <?php echo json_encode(array_keys($avg_distances)); ?>,
				datasets: [{
					label: 'Average Distance (km)',
					data: <?php echo json_encode(array_values($avg_distances)); ?>,
					backgroundColor: 'rgba(59, 130, 246, 0.75)',
					borderColor: '#3b82f6',
					borderWidth: 1,
					borderRadius: 6
				}]
			},
			options: {
				responsive: true,
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
						grid: { color: config.gridColor },
						ticks: { color: config.textColor }
					}
				}
			}
		});

		// 3. Housing Types Chart (Doughnut)
		var houseCtx = document.getElementById('houseChart').getContext('2d');
		houseChartInstance = new Chart(houseCtx, {
			type: 'doughnut',
			data: {
				labels: <?php echo json_encode(array_keys($house_types)); ?>,
				datasets: [{
					data: <?php echo json_encode(array_values($house_types)); ?>,
					backgroundColor: colors,
					borderColor: config.isDark ? '#1e293b' : '#ffffff',
					borderWidth: 2
				}]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						position: 'bottom',
						labels: { color: config.textColor, boxWidth: 12 }
					},
					tooltip: {
						backgroundColor: config.tooltipBg,
						titleColor: config.tooltipColor,
						bodyColor: config.tooltipColor,
						borderColor: config.tooltipBorder,
						borderWidth: 1
					}
				}
			}
		});

		// 4. Illness Chart (Pie)
		var illnessEl = document.getElementById('illnessChart');
		if (illnessEl) {
			var illCtx = illnessEl.getContext('2d');
			illnessChartInstance = new Chart(illCtx, {
				type: 'pie',
				data: {
					labels: <?php echo json_encode(array_keys($illnesses)); ?>,
					datasets: [{
						data: <?php echo json_encode(array_values($illnesses)); ?>,
						backgroundColor: colors.slice().reverse(),
						borderColor: config.isDark ? '#1e293b' : '#ffffff',
						borderWidth: 2
					}]
				},
				options: {
					responsive: true,
					plugins: {
						legend: {
							position: 'bottom',
							labels: { color: config.textColor, boxWidth: 12 }
						},
						tooltip: {
							backgroundColor: config.tooltipBg,
							titleColor: config.tooltipColor,
							bodyColor: config.tooltipColor,
							borderColor: config.tooltipBorder,
							borderWidth: 1
						}
					}
				}
			});
		}

		// 5. Mortality Causes Chart (Doughnut)
		var deathEl = document.getElementById('deathChart');
		if (deathEl) {
			var deathCtx = deathEl.getContext('2d');
			deathChartInstance = new Chart(deathCtx, {
				type: 'doughnut',
				data: {
					labels: <?php echo json_encode(array_keys($mortalities)); ?>,
					datasets: [{
						data: <?php echo json_encode(array_values($mortalities)); ?>,
						backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981', '#8b5cf6'],
						borderColor: config.isDark ? '#1e293b' : '#ffffff',
						borderWidth: 2
					}]
				},
				options: {
					responsive: true,
					plugins: {
						legend: {
							position: 'bottom',
							labels: { color: config.textColor, boxWidth: 12 }
						},
						tooltip: {
							backgroundColor: config.tooltipBg,
							titleColor: config.tooltipColor,
							bodyColor: config.tooltipColor,
							borderColor: config.tooltipBorder,
							borderWidth: 1
						}
					}
				}
			});
		}

		// Function to update active Chart.js instances dynamically when theme changes
		function updateChartThemes() {
			var newConfig = getThemeConfig();
			var charts = [swmChartInstance, distanceChartInstance, houseChartInstance, illnessChartInstance, deathChartInstance];
			
			charts.forEach(function(chart) {
				if (!chart) return;
				
				// Update scale text and grids
				if (chart.options.scales) {
					if (chart.options.scales.x) {
						if (chart.options.scales.x.ticks) chart.options.scales.x.ticks.color = newConfig.textColor;
						if (chart.options.scales.x.grid) chart.options.scales.x.grid.color = newConfig.gridColor;
					}
					if (chart.options.scales.y) {
						if (chart.options.scales.y.ticks) chart.options.scales.y.ticks.color = newConfig.textColor;
						if (chart.options.scales.y.grid) chart.options.scales.y.grid.color = newConfig.gridColor;
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

				// Update doughnut/pie borders
				if (chart.data.datasets && chart.data.datasets[0]) {
					if (chart.config.type === 'doughnut' || chart.config.type === 'pie') {
						chart.data.datasets[0].borderColor = newConfig.isDark ? '#1e293b' : '#ffffff';
					}
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
	});
</script>
