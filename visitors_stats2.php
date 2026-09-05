<?php
	// Get available years for filtering
	$years_res = $link->query("SELECT DISTINCT visit_year FROM visitors WHERE visit_year IS NOT NULL AND visit_year != '' ORDER BY visit_year DESC");
	$available_years = [];
	while($y_row = mysqli_fetch_assoc($years_res)) {
		$available_years[] = $y_row['visit_year'];
	}
	
	// Default to current year if available, otherwise first year or current date year
	$selected_year = isset($_GET['year']) ? $_GET['year'] : (in_array(date("Y"), $available_years) ? date("Y") : (!empty($available_years) ? $available_years[0] : date("Y")));
	
	$year_cond = "";
	if ($selected_year !== 'All') {
		$year_cond = " AND visit_year = '" . mysqli_real_escape_string($link, $selected_year) . "'";
	}
	
	// 1. Total Visits
	$res_total = $link->query("SELECT COUNT(*) as total FROM visitors WHERE 1=1 $year_cond");
	$total_visits = mysqli_fetch_assoc($res_total)['total'] ?? 0;
	
	// 2. Unique Visitors
	$res_unique = $link->query("SELECT COUNT(DISTINCT CONCAT(name_1st, ' ', name_fam)) as unique_vis FROM visitors WHERE 1=1 $year_cond");
	$unique_visitors = mysqli_fetch_assoc($res_unique)['unique_vis'] ?? 0;
	
	// 3. Top Office
	$res_top_office = $link->query("SELECT office, COUNT(*) as count FROM visitors WHERE 1=1 $year_cond AND office != '' GROUP BY office ORDER BY count DESC LIMIT 1");
	$top_office_row = mysqli_fetch_assoc($res_top_office);
	$top_office = $top_office_row['office'] ?? 'N/A';
	$top_office_count = $top_office_row['count'] ?? 0;
	
	// 4. Top Purpose
	$res_top_purpose = $link->query("SELECT visit_purpose, COUNT(*) as count FROM visitors WHERE 1=1 $year_cond AND visit_purpose != '' GROUP BY visit_purpose ORDER BY count DESC LIMIT 1");
	$top_purpose_row = mysqli_fetch_assoc($res_top_purpose);
	$top_purpose = $top_purpose_row['visit_purpose'] ?? 'N/A';
	$top_purpose_count = $top_purpose_row['count'] ?? 0;
	
	// --- Chart Data Processing ---
	// Month-by-month distribution
	$months_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	$month_counts = array_fill(1, 12, 0);
	$month_res = $link->query("SELECT visit_month, COUNT(*) as count FROM visitors WHERE 1=1 $year_cond GROUP BY visit_month");
	while($row = mysqli_fetch_assoc($month_res)) {
		$m = intval($row['visit_month']);
		if($m >= 1 && $m <= 12) {
			$month_counts[$m] = intval($row['count']);
		}
	}
	$month_data = array_values($month_counts);
	
	// Gender Distribution
	$gender_counts = ['Male' => 0, 'Female' => 0, 'Unknown' => 0];
	$gender_res = $link->query("SELECT sex, COUNT(*) as count FROM visitors WHERE 1=1 $year_cond GROUP BY sex");
	while($row = mysqli_fetch_assoc($gender_res)) {
		$sex = trim($row['sex']);
		if(strcasecmp($sex, 'M') == 0 || strcasecmp($sex, 'Male') == 0) {
			$gender_counts['Male'] += intval($row['count']);
		} elseif(strcasecmp($sex, 'F') == 0 || strcasecmp($sex, 'Female') == 0) {
			$gender_counts['Female'] += intval($row['count']);
		} else {
			$gender_counts['Unknown'] += intval($row['count']);
		}
	}
	
	// Top 8 Offices
	$office_names = [];
	$office_counts = [];
	$office_res = $link->query("SELECT office, COUNT(*) as count FROM visitors WHERE 1=1 $year_cond AND office != '' GROUP BY office ORDER BY count DESC LIMIT 8");
	while($row = mysqli_fetch_assoc($office_res)) {
		$office_names[] = $row['office'];
		$office_counts[] = intval($row['count']);
	}
	
	// Top 5 Purposes
	$purpose_names = [];
	$purpose_counts = [];
	$purpose_res = $link->query("SELECT visit_purpose, COUNT(*) as count FROM visitors WHERE 1=1 $year_cond AND visit_purpose != '' GROUP BY visit_purpose ORDER BY count DESC LIMIT 5");
	while($row = mysqli_fetch_assoc($purpose_res)) {
		$purpose_names[] = $row['visit_purpose'];
		$purpose_counts[] = intval($row['count']);
	}
	
	// Top Designations
	$position_names = [];
	$position_counts = [];
	$position_res = $link->query("SELECT position, COUNT(*) as count FROM visitors WHERE 1=1 $year_cond AND position != '' GROUP BY position ORDER BY count DESC LIMIT 6");
	while($row = mysqli_fetch_assoc($position_res)) {
		$position_names[] = $row['position'];
		$position_counts[] = intval($row['count']);
	}
	
	// Detailed Breakdown
	$office_breakdown = [];
	$breakdown_res = $link->query("SELECT office, COUNT(*) as total_visits, COUNT(DISTINCT CONCAT(name_1st, ' ', name_fam)) as unique_visitors FROM visitors WHERE 1=1 $year_cond GROUP BY office ORDER BY total_visits DESC LIMIT 15");
	while($row = mysqli_fetch_assoc($breakdown_res)) {
		$off = $row['office'] ? $row['office'] : 'Unspecified';
		
		// Find most common purpose for this office
		$purp_q = $link->query("SELECT visit_purpose, COUNT(*) as c FROM visitors WHERE office = '" . mysqli_real_escape_string($link, $row['office']) . "' $year_cond GROUP BY visit_purpose ORDER BY c DESC LIMIT 1");
		$purp_row = mysqli_fetch_assoc($purp_q);
		$common_purp = $purp_row ? $purp_row['visit_purpose'] : 'N/A';
		
		$office_breakdown[] = [
			'office' => $off,
			'total_visits' => $row['total_visits'],
			'unique_visitors' => $row['unique_visitors'],
			'common_purpose' => $common_purp
		];
	}
?>

<?php include_once("analytics-css.php"); ?>
<link href="style/stats-visitor.css" rel="stylesheet" type="text/css"/>

<div style="margin-top:50px"></div>

<div class="container py-4">
	<!-- Page Header & Filter -->
	<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
		<div>
			<h2 class="font-weight-bold text-dark mb-1">
				<i class="fas fa-chart-line mr-2 text-primary"></i>Visitor Analytics
			</h2>
			<p class="text-muted mb-0">Demographics, visit frequency, and agency participation analytics for LGU Tabina visitors</p>
		</div>
		
		<!-- Year Filter Dropdown -->
		<div class="d-flex align-items-center mt-3 mt-md-0">
			<label class="font-weight-bold text-muted mr-2 mb-0 small text-uppercase">Filter Year:</label>
			<select class="form-control form-control-sm border-primary text-primary font-weight-bold shadow-sm" style="width: 140px; border-radius: 8px;" onchange="jump('visitor_stats.php?year=' + this.value)">
				<option value="All" <?php echo $selected_year === 'All' ? 'selected' : ''; ?>>All Years</option>
				<?php foreach($available_years as $yr) { ?>
					<option value="<?php echo $yr; ?>" <?php echo $selected_year === $yr ? 'selected' : ''; ?>><?php echo $yr; ?></option>
				<?php } ?>
			</select>
		</div>
	</div>

	<!-- Metric Highlights Row -->
	<div class="row">
		<div class="col-xl-3 col-md-6 col-sm-12">
			<div class="stats-card card-blue">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Total Visits Recorded</span>
				<div class="d-flex align-items-baseline mt-1">
					<h2 class="mb-0 font-weight-bold"><?php echo number_format($total_visits); ?></h2>
					<span class="ml-2 small text-white-50">visits</span>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 col-sm-12">
			<div class="stats-card card-purple">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Unique Guests</span>
				<div class="d-flex align-items-baseline mt-1">
					<h2 class="mb-0 font-weight-bold"><?php echo number_format($unique_visitors); ?></h2>
					<span class="ml-2 small text-white-50">visitors</span>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 col-sm-12">
			<div class="stats-card card-green">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Top Agency / Origin</span>
				<div class="d-flex align-items-baseline mt-1">
					<h2 class="mb-0 font-weight-bold" style="font-size: 1.25rem;" title="<?php echo htmlspecialchars($top_office); ?>">
						<?php echo strlen($top_office) > 18 ? htmlspecialchars(substr($top_office, 0, 16)) . '..' : htmlspecialchars($top_office); ?>
					</h2>
					<span class="ml-2 small text-white-50">(<?php echo number_format($top_office_count); ?>)</span>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 col-sm-12">
			<div class="stats-card card-orange">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Top Visit Purpose</span>
				<div class="d-flex align-items-baseline mt-1">
					<h2 class="mb-0 font-weight-bold" style="font-size: 1.25rem;" title="<?php echo htmlspecialchars($top_purpose); ?>">
						<?php echo strlen($top_purpose) > 18 ? htmlspecialchars(substr($top_purpose, 0, 16)) . '..' : htmlspecialchars($top_purpose); ?>
					</h2>
					<span class="ml-2 small text-white-50">(<?php echo number_format($top_purpose_count); ?>)</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Main Demographics Charts Row -->
	<div class="row">
		<!-- Monthly Visit Frequency Line Chart -->
		<div class="col-lg-8 col-md-12">
			<div class="chart-container">
				<h4 class="chart-title">Monthly Visit Frequency (Year: <?php echo htmlspecialchars($selected_year); ?>)</h4>
				<div style="position: relative; height: 350px;">
					<canvas id="monthlyLineChart"></canvas>
				</div>
			</div>
		</div>

		<!-- Gender Distribution Pie Chart -->
		<div class="col-lg-4 col-md-12">
			<div class="chart-container text-center">
				<h4 class="chart-title text-left">Gender Distribution</h4>
				<div class="mx-auto" style="position: relative; height: 260px; max-width: 260px; margin-top: 40px;">
					<canvas id="genderDoughnutChart"></canvas>
				</div>
			</div>
		</div>
	</div>

	<!-- Secondary Breakdown Charts Row -->
	<div class="row">
		<!-- Top Offices / Agencies Bar Chart -->
		<div class="col-md-7 col-sm-12">
			<div class="chart-container">
				<h4 class="chart-title">Top Visiting Offices / Agencies</h4>
				<div style="position: relative; height: 320px;">
					<canvas id="officeBarChart"></canvas>
				</div>
			</div>
		</div>

		<!-- Top Visitor Designations (Dynamic Progress Bars) -->
		<div class="col-md-5 col-sm-12">
			<div class="chart-container">
				<h4 class="chart-title">Top Designations / Positions</h4>
				<div class="brgy-progress-container" style="height: 320px; overflow-y: auto;">
					<?php 
						$max_pos_count = !empty($position_counts) ? max($position_counts) : 1;
						if (empty($position_names)) {
							echo "<div class='text-center text-muted py-5'>No designation records found.</div>";
						} else {
							for ($idx = 0; $idx < count($position_names); $idx++) {
								$percent = round(($position_counts[$idx] / $max_pos_count) * 100);
								$real_percent = $total_visits > 0 ? round(($position_counts[$idx] / $total_visits) * 100, 1) : 0;
						?>
							<div class="brgy-progress-item mb-3">
								<div class="brgy-progress-label d-flex justify-content-between font-weight-bold text-dark small mb-1">
									<span class="text-uppercase"><?php echo htmlspecialchars($position_names[$idx]); ?></span>
									<span class="text-muted"><?php echo number_format($position_counts[$idx]); ?> <small>(<?php echo $real_percent; ?>%)</small></span>
								</div>
								<div class="progress" style="height: 10px; border-radius: 5px; background: #eef2f5;">
									<div class="progress-bar bg-purple" role="progressbar" style="width: <?php echo $percent; ?>%; border-radius: 5px; background: linear-gradient(90deg, #7f00ff 0%, #e100ff 100%);"></div>
								</div>
							</div>
						<?php 
							}
						}
					?>
				</div>
			</div>
		</div>
	</div>

	<!-- Top Visit Purposes Doughnut Chart -->
	<div class="row">
		<div class="col-md-5 col-sm-12">
			<div class="chart-container text-center">
				<h4 class="chart-title text-left">Top Purposes of Visit</h4>
				<div class="mx-auto" style="position: relative; height: 260px; max-width: 260px; margin-top: 20px;">
					<canvas id="purposeDoughnutChart"></canvas>
				</div>
			</div>
		</div>

		<!-- Office breakdown detailed table -->
		<div class="col-md-7 col-sm-12">
			<div class="chart-container">
				<h4 class="chart-title">Detailed Office Participation Audit</h4>
				<div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
					<table class="modern-table">
						<thead>
							<tr>
								<th>Office / Agency</th>
								<th class="text-center">Total Visits</th>
								<th class="text-center">Unique Guests</th>
								<th>Primary Purpose</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if(empty($office_breakdown)) {
									echo "<tr><td colspan='4' class='text-center text-muted'>No office data found for the selected year.</td></tr>";
								} else {
									foreach($office_breakdown as $ob){ 
							?>
								<tr>
									<td class="font-weight-bold text-dark text-uppercase small"><?php echo htmlspecialchars($ob['office']); ?></td>
									<td class="text-center text-primary font-weight-bold"><?php echo number_format($ob['total_visits']); ?></td>
									<td class="text-center text-purple font-weight-bold"><?php echo number_format($ob['unique_visitors']); ?></td>
									<td class="text-muted small text-uppercase"><?php echo htmlspecialchars($ob['common_purpose']); ?></td>
								</tr>
							<?php 
									} 
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// Define instances variables
	var monthlyLineChartInstance, genderDoughnutChartInstance, officeBarChartInstance, purposeDoughnutChartInstance;

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

	// 1. Monthly Visit Line Chart
	const monthLabels = <?php echo json_encode($months_labels); ?>;
	const monthData = <?php echo json_encode($month_data); ?>;
	
	const ctxLine = document.getElementById('monthlyLineChart').getContext('2d');
	monthlyLineChartInstance = new Chart(ctxLine, {
		type: 'line',
		data: {
			labels: monthLabels,
			datasets: [{
				label: 'Visits Count',
				data: monthData,
				borderColor: '#3f51b5',
				backgroundColor: 'rgba(63, 81, 181, 0.1)',
				borderWidth: 3,
				fill: true,
				tension: 0.3,
				pointRadius: 4,
				pointBackgroundColor: '#3f51b5'
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
					grid: { color: config.gridColor },
					ticks: { color: config.textColor, stepSize: 1 }
				}
			}
		}
	});

	// 2. Gender Doughnut Chart
	const genderLabels = ['Male', 'Female', 'Unknown/Unspecified'];
	const genderData = [
		<?php echo $gender_counts['Male']; ?>, 
		<?php echo $gender_counts['Female']; ?>, 
		<?php echo $gender_counts['Unknown']; ?>
	];
	
	// Filter out labels with 0 counts to keep chart clean
	let finalGenderLabels = [];
	let finalGenderData = [];
	let genderColors = ['#2563eb', '#ec4899', '#64748b'];
	let finalGenderColors = [];
	
	for(let i=0; i<genderData.length; i++) {
		if(genderData[i] > 0) {
			finalGenderLabels.push(genderLabels[i]);
			finalGenderData.push(genderData[i]);
			finalGenderColors.push(genderColors[i]);
		}
	}
	// Fallback if all zero
	if(finalGenderData.length === 0) {
		finalGenderLabels = ['No Data'];
		finalGenderData = [0];
		finalGenderColors = ['#cbd5e1'];
	}

	const ctxGender = document.getElementById('genderDoughnutChart').getContext('2d');
	genderDoughnutChartInstance = new Chart(ctxGender, {
		type: 'doughnut',
		data: {
			labels: finalGenderLabels,
			datasets: [{
				data: finalGenderData,
				backgroundColor: finalGenderColors,
				borderColor: config.isDark ? '#1e293b' : '#ffffff',
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
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

	// 3. Top Offices Bar Chart
	const officeLabels = <?php echo json_encode($office_names); ?>;
	const officeData = <?php echo json_encode($office_counts); ?>;
	
	const ctxOffice = document.getElementById('officeBarChart').getContext('2d');
	officeBarChartInstance = new Chart(ctxOffice, {
		type: 'bar',
		data: {
			labels: officeLabels,
			datasets: [{
				data: officeData,
				backgroundColor: '#6a11cb',
				borderRadius: 8,
				borderWidth: 0
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
					ticks: { 
						color: config.textColor,
						maxRotation: 45,
						minRotation: 45
					}
				},
				y: {
					beginAtZero: true,
					grid: { color: config.gridColor },
					ticks: { color: config.textColor, stepSize: 1 }
				}
			}
		}
	});

	// 4. Top Purposes Doughnut Chart
	const purposeLabels = <?php echo json_encode($purpose_names); ?>;
	const purposeData = <?php echo json_encode($purpose_counts); ?>;
	const purposeColors = ['#ff6b6b', '#feca57', '#1dd1a1', '#54a0ff', '#5f27cd'];
	
	const ctxPurpose = document.getElementById('purposeDoughnutChart').getContext('2d');
	purposeDoughnutChartInstance = new Chart(ctxPurpose, {
		type: 'doughnut',
		data: {
			labels: purposeLabels.length > 0 ? purposeLabels : ['No Data'],
			datasets: [{
				data: purposeData.length > 0 ? purposeData : [0],
				backgroundColor: purposeLabels.length > 0 ? purposeColors : ['#cbd5e1'],
				borderColor: config.isDark ? '#1e293b' : '#ffffff',
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
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

	// Function to update active Chart.js instances dynamically when theme changes
	function updateChartThemes() {
		var newConfig = getThemeConfig();
		var charts = [monthlyLineChartInstance, genderDoughnutChartInstance, officeBarChartInstance, purposeDoughnutChartInstance];
		
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
</script>

<?php require('footer.php'); ?>
