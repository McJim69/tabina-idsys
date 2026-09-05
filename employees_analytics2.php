<?php include_once("analytics-css.php");?>

<div class="container my-4">
	<!-- Page Header -->
	<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
		<div>
			<h2 class="font-weight-bold text-primary mb-1">
				<i class="fas fa-users-cog mr-2"></i>Employees <x class="thid">Advanced Analytics</x>
			</h2>
			<p class="text-muted small mb-0">Demographic profiles, department structures, benefit coverage audits, and appointment trends for LGU Tabina Staff</p>
		</div>
	</div>

	<?php
		// Fetch Metrics
		// 1. Total Employees & Benefit Coverage
		$audit_res = mysqli_fetch_assoc($link->query("SELECT 
			COUNT(*) as total,
			SUM(CASE WHEN sex = 'Female' OR sex = 'F' THEN 1 ELSE 0 END) as female_cnt,
			SUM(CASE WHEN gsis IS NOT NULL AND TRIM(gsis) != '' AND TRIM(gsis) != '0' AND LOWER(TRIM(gsis)) != 'n/a' THEN 1 ELSE 0 END) as gsis_cnt,
			SUM(CASE WHEN philhealth IS NOT NULL AND TRIM(philhealth) != '' AND TRIM(philhealth) != '0' AND LOWER(TRIM(philhealth)) != 'n/a' THEN 1 ELSE 0 END) as ph_cnt,
			SUM(CASE WHEN pagibig IS NOT NULL AND TRIM(pagibig) != '' AND TRIM(pagibig) != '0' AND LOWER(TRIM(pagibig)) != 'n/a' THEN 1 ELSE 0 END) as ibig_cnt,
			SUM(CASE WHEN tin IS NOT NULL AND TRIM(tin) != '' AND TRIM(tin) != '0' AND LOWER(TRIM(tin)) != 'n/a' THEN 1 ELSE 0 END) as tin_cnt,
			SUM(CASE WHEN emergencyno IS NOT NULL AND TRIM(emergencyno) != '' AND LOWER(TRIM(emergencyno)) != 'n/a' THEN 1 ELSE 0 END) as emergency_cnt
			FROM employees"));
		
		$total_emp = $audit_res['total'] ?? 0;
		$female_cnt = $audit_res['female_cnt'] ?? 0;
		$gsis_cnt = $audit_res['gsis_cnt'] ?? 0;
		$ph_cnt = $audit_res['ph_cnt'] ?? 0;
		$ibig_cnt = $audit_res['ibig_cnt'] ?? 0;
		$tin_cnt = $audit_res['tin_cnt'] ?? 0;
		$emergency_cnt = $audit_res['emergency_cnt'] ?? 0;

		$female_pct = $total_emp > 0 ? round(($female_cnt / $total_emp) * 100, 1) : 0;
		$emergency_pct = $total_emp > 0 ? round(($emergency_cnt / $total_emp) * 100, 1) : 0;

		// Average benefit completion rate
		$total_fields_expected = $total_emp * 4;
		$total_fields_filled = $gsis_cnt + $ph_cnt + $ibig_cnt + $tin_cnt;
		$benefit_pct = $total_fields_expected > 0 ? round(($total_fields_filled / $total_fields_expected) * 100, 1) : 0;

		// 2. Gender representation
		$gender_data = [
			'Male' => $total_emp - $female_cnt,
			'Female' => $female_cnt
		];

		// 3. Department distributions
		$dept_res = $link->query("SELECT department, COUNT(*) as count FROM employees GROUP BY department ORDER BY count DESC LIMIT 8");
		$departments = [];
		while($row = mysqli_fetch_assoc($dept_res)){
			$label = !empty($row['department']) ? $row['department'] : 'Unspecified';
			$departments[$label] = (int)$row['count'];
		}

		// 4. Age Demographics
		$age_res = mysqli_fetch_assoc($link->query("SELECT 
			SUM(CASE WHEN (2026 - YEAR(date_birth)) < 30 THEN 1 ELSE 0 END) as under_30,
			SUM(CASE WHEN (2026 - YEAR(date_birth)) BETWEEN 30 AND 39 THEN 1 ELSE 0 END) as age_30_39,
			SUM(CASE WHEN (2026 - YEAR(date_birth)) BETWEEN 40 AND 49 THEN 1 ELSE 0 END) as age_40_49,
			SUM(CASE WHEN (2026 - YEAR(date_birth)) BETWEEN 50 AND 59 THEN 1 ELSE 0 END) as age_50_59,
			SUM(CASE WHEN (2026 - YEAR(date_birth)) >= 60 THEN 1 ELSE 0 END) as age_60_up
			FROM employees 
			WHERE date_birth IS NOT NULL AND date_birth != '0000-00-00'"));
		
		$age_groups = [
			'Under 30' => (int)($age_res['under_30'] ?? 0),
			'30 - 39' => (int)($age_res['age_30_39'] ?? 0),
			'40 - 49' => (int)($age_res['age_40_49'] ?? 0),
			'50 - 59' => (int)($age_res['age_50_59'] ?? 0),
			'60+' => (int)($age_res['age_60_up'] ?? 0)
		];

		// 5. Employment Tenure (Timeline)
		$tenure_res = $link->query("SELECT app_year, COUNT(*) as count FROM employees WHERE app_year REGEXP '^[0-9]+$' GROUP BY app_year ORDER BY app_year ASC");
		$tenure_timeline = [];
		while($row = mysqli_fetch_assoc($tenure_res)){
			$tenure_timeline[$row['app_year']] = (int)$row['count'];
		}
	?>

	<!-- Highlights Row -->
	<div class="row mb-4">
		<!-- Total Employees -->
		<div class="col-md-3 mb-3">
			<div class="card analytics-card shadow-sm border p-3 bg-white">
				<div class="text-muted small text-uppercase font-weight-bold">Total Staff</div>
				<div class="metric-num text-primary"><?php echo number_format($total_emp); ?></div>
				<div class="small text-muted"><i class="fas fa-id-card mr-1"></i> Active Municipal Employees</div>
			</div>
		</div>
		<!-- Female Representation Pct -->
		<div class="col-md-3 mb-3">
			<div class="card analytics-card shadow-sm border p-3 bg-white">
				<div class="text-muted small text-uppercase font-weight-bold">Female Representation</div>
				<div class="metric-num text-success"><?php echo $female_pct; ?>%</div>
				<div class="small text-muted"><i class="fas fa-venus mr-1"></i> <?php echo number_format($female_cnt); ?> Female Employees</div>
			</div>
		</div>
		<!-- Benefit Coverage Audit -->
		<div class="col-md-3 mb-3">
			<div class="card analytics-card shadow-sm border p-3 bg-white">
				<div class="text-muted small text-uppercase font-weight-bold">Government Benefits Completion</div>
				<div class="metric-num text-info"><?php echo $benefit_pct; ?>%</div>
				<div class="small text-muted"><i class="fas fa-clipboard-check mr-1"></i> GSIS/PH/PagIBIG/TIN audited</div>
			</div>
		</div>
		<!-- Emergency Contact Completion -->
		<div class="col-md-3 mb-3">
			<div class="card analytics-card shadow-sm border p-3 bg-white">
				<div class="text-muted small text-uppercase font-weight-bold">Emergency Contact Set</div>
				<div class="metric-num text-warning"><?php echo $emergency_pct; ?>%</div>
				<div class="small text-muted"><i class="fas fa-phone-alt mr-1"></i> <?php echo number_format($emergency_cnt); ?> Contacts on record</div>
			</div>
		</div>
	</div>

	<!-- First Visual Analytics Section -->
	<div class="row">
		<!-- Department Distribution -->
		<div class="col-lg-7 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-sitemap mr-2 text-primary"></i>Departmental Staff Distribution
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<?php if(empty($departments)) { ?>
						<div class="text-muted small text-center my-auto">No departmental records found.</div>
					<?php } else { ?>
						<canvas id="deptChart" style="max-height: 320px;"></canvas>
					<?php } ?>
				</div>
			</div>
		</div>

		<!-- Gender representation -->
		<div class="col-lg-5 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-venus-mars mr-2 text-success"></i>Gender representation Breakdown
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<canvas id="genderChart" style="max-height: 320px;"></canvas>
				</div>
			</div>
		</div>
	</div>

	<!-- Second Visual Analytics Section -->
	<div class="row">
		<!-- Benefit Coverage Audit -->
		<div class="col-lg-4 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-clipboard-check mr-2 text-info"></i>Benefits Registration Compliance
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<canvas id="benefitsChart" style="max-height: 300px;"></canvas>
				</div>
			</div>
		</div>

		<!-- Age Demographics -->
		<div class="col-lg-4 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-baby-carriage mr-2 text-warning"></i>Age Group Demographics
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<canvas id="ageChart" style="max-height: 300px;"></canvas>
				</div>
			</div>
		</div>

		<!-- Appointment Timeline -->
		<div class="col-lg-4 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-history mr-2 text-danger"></i>Appointment Tenure Timeline
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<?php if(empty($tenure_timeline)) { ?>
						<div class="text-muted small text-center my-auto">No appointment dates on record.</div>
					<?php } else { ?>
						<canvas id="tenureChart" style="max-height: 300px;"></canvas>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function() {
		// Define global instances variables
		var deptChartInstance, genderChartInstance, benefitsChartInstance, ageChartInstance, tenureChartInstance;

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

		// 1. Department Chart (Horizontal Bar)
		var deptEl = document.getElementById('deptChart');
		if (deptEl) {
			var deptCtx = deptEl.getContext('2d');
			deptChartInstance = new Chart(deptCtx, {
				type: 'bar',
				data: {
					labels: <?php echo json_encode(array_keys($departments)); ?>,
					datasets: [{
						data: <?php echo json_encode(array_values($departments)); ?>,
						backgroundColor: 'rgba(59, 130, 246, 0.75)',
						borderColor: '#3b82f6',
						borderWidth: 1,
						borderRadius: 6
					}]
				},
				options: {
					indexAxis: 'y',
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
							ticks: { color: config.textColor, stepSize: 1 }
						},
						y: {
							grid: { color: config.gridColor },
							ticks: { color: config.textColor }
						}
					}
				}
			});
		}

		// 2. Gender Chart (Pie)
		var genderCtx = document.getElementById('genderChart').getContext('2d');
		genderChartInstance = new Chart(genderCtx, {
			type: 'pie',
			data: {
				labels: <?php echo json_encode(array_keys($gender_data)); ?>,
				datasets: [{
					data: <?php echo json_encode(array_values($gender_data)); ?>,
					backgroundColor: ['#6366f1', '#ec4899'],
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

		// 3. Benefits Audit Chart (Bar)
		var benefitsCtx = document.getElementById('benefitsChart').getContext('2d');
		benefitsChartInstance = new Chart(benefitsCtx, {
			type: 'bar',
			data: {
				labels: ['GSIS', 'PhilHealth', 'Pag-IBIG', 'TIN'],
				datasets: [{
					data: [<?php echo "$gsis_cnt, $ph_cnt, $ibig_cnt, $tin_cnt"; ?>],
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
						ticks: { color: config.textColor }
					}
				}
			}
		});

		// 4. Age Chart (Doughnut)
		var ageCtx = document.getElementById('ageChart').getContext('2d');
		ageChartInstance = new Chart(ageCtx, {
			type: 'doughnut',
			data: {
				labels: <?php echo json_encode(array_keys($age_groups)); ?>,
				datasets: [{
					data: <?php echo json_encode(array_values($age_groups)); ?>,
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

		// 5. Tenure Chart (Vertical Bar/Line)
		var tenureEl = document.getElementById('tenureChart');
		if (tenureEl) {
			var tenureCtx = tenureEl.getContext('2d');
			tenureChartInstance = new Chart(tenureCtx, {
				type: 'line',
				data: {
					labels: <?php echo json_encode(array_keys($tenure_timeline)); ?>,
					datasets: [{
						label: 'Appointed Staff',
						data: <?php echo json_encode(array_values($tenure_timeline)); ?>,
						borderColor: '#ef4444',
						backgroundColor: 'rgba(239, 68, 68, 0.1)',
						borderWidth: 2,
						tension: 0.3,
						fill: true
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
		}

		// Function to update active Chart.js instances dynamically when theme changes
		function updateChartThemes() {
			var newConfig = getThemeConfig();
			var charts = [deptChartInstance, genderChartInstance, benefitsChartInstance, ageChartInstance, tenureChartInstance];
			
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

<?php require("footer.php"); ?>
