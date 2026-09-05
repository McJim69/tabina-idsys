<?php include_once("analytics-css.php");?>

<div class="container my-4">
	<!-- Page Header -->
	<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
		<div>
			<h2 class="font-weight-bold text-primary mb-1">
				<i class="fas fa-hand-holding-usd mr-2"></i>Revenue <x class="thid">& Transaction</x> Analytics
			</h2>
			<p class="text-muted small mb-0">Financial monitoring, service volumes, category contributions, and audit feeds for LGU Tabina</p>
		</div>
	</div>

	<?php
		// Fetch Metrics
		// 1. Volumes by table
		$cnt_clearance = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as cnt FROM clearances"))['cnt'] ?? 0;
		$cnt_business = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as cnt FROM permit_business"))['cnt'] ?? 0;
		$cnt_indigency = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as cnt FROM cert_indigency"))['cnt'] ?? 0;
		$cnt_fishing = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as cnt FROM reg_fishing"))['cnt'] ?? 0;
		$cnt_operate = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as cnt FROM permit_operate"))['cnt'] ?? 0;
		$total_transactions = $cnt_clearance + $cnt_business + $cnt_indigency + $cnt_fishing + $cnt_operate;

		// 2. Revenues by table
		$rev_clearance = mysqli_fetch_assoc($link->query("SELECT SUM(CASE WHEN oramount REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(oramount AS DECIMAL(10,2)) ELSE 0 END) as rev FROM clearances"))['rev'] ?? 0;
		$rev_business = mysqli_fetch_assoc($link->query("SELECT SUM(CASE WHEN oramount REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(oramount AS DECIMAL(10,2)) ELSE 0 END) as rev FROM permit_business"))['rev'] ?? 0;
		$rev_fishing = mysqli_fetch_assoc($link->query("SELECT SUM(CAST(oramount AS DECIMAL(10,2))) as rev FROM reg_fishing"))['rev'] ?? 0;
		$rev_operate = mysqli_fetch_assoc($link->query("SELECT SUM(CASE WHEN oramount REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(oramount AS DECIMAL(10,2)) ELSE 0 END) as rev FROM permit_operate"))['rev'] ?? 0;
		$total_revenue = $rev_clearance + $rev_business + $rev_fishing + $rev_operate;

		// 3. Average Transaction Value (for revenue-generating entries)
		$rev_generating_count = $cnt_clearance + $cnt_business + $cnt_fishing + $cnt_operate;
		$avg_transaction = $rev_generating_count > 0 ? round($total_revenue / $rev_generating_count, 2) : 0;

		// 4. Highest Single Transaction
		$max_clearance = mysqli_fetch_assoc($link->query("SELECT MAX(CASE WHEN oramount REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(oramount AS DECIMAL(10,2)) ELSE 0 END) as m FROM clearances"))['m'] ?? 0;
		$max_business = mysqli_fetch_assoc($link->query("SELECT MAX(CASE WHEN oramount REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(oramount AS DECIMAL(10,2)) ELSE 0 END) as m FROM permit_business"))['m'] ?? 0;
		$max_fishing = mysqli_fetch_assoc($link->query("SELECT MAX(CAST(oramount AS DECIMAL(10,2))) as m FROM reg_fishing"))['m'] ?? 0;
		$max_operate = mysqli_fetch_assoc($link->query("SELECT MAX(CASE WHEN oramount REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(oramount AS DECIMAL(10,2)) ELSE 0 END) as m FROM permit_operate"))['m'] ?? 0;
		$max_transaction = max($max_clearance, $max_business, $max_fishing, $max_operate);

		// 5. Volume Dataset
		$volume_data = [
			'Clearances' => $cnt_clearance,
			'Business Permits' => $cnt_business,
			'Indigency Certs' => $cnt_indigency,
			'Fishing Regs' => $cnt_fishing,
			'Operating Permits' => $cnt_operate
		];

		// 6. Revenue Dataset
		$revenue_data = [
			'Clearances' => (float)$rev_clearance,
			'Business Permits' => (float)$rev_business,
			'Fishing Regs' => (float)$rev_fishing,
			'Operating Permits' => (float)$rev_operate
		];

		// 7. Monthly Revenue timeline trends (combines past years and sorts them chronologically)
		$monthly_trend = [];
		
		// Helper query loop to compile monthly amounts
		$queries = [
			"SELECT is_year as y, is_month as m, SUM(CASE WHEN oramount REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(oramount AS DECIMAL(10,2)) ELSE 0 END) as amt FROM clearances GROUP BY is_year, is_month",
			"SELECT is_year as y, is_month as m, SUM(CASE WHEN oramount REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(oramount AS DECIMAL(10,2)) ELSE 0 END) as amt FROM permit_business GROUP BY is_year, is_month",
			"SELECT YEAR(date_issued) as y, MONTH(date_issued) as m, SUM(oramount) as amt FROM reg_fishing GROUP BY YEAR(date_issued), MONTH(date_issued)",
			"SELECT is_year as y, is_month as m, SUM(CASE WHEN oramount REGEXP '^[0-9]+(\\\\.[0-9]+)?$' THEN CAST(oramount AS DECIMAL(10,2)) ELSE 0 END) as amt FROM permit_operate GROUP BY is_year, is_month"
		];

		foreach ($queries as $sql) {
			$res = $link->query($sql);
			if ($res) {
				while ($row = mysqli_fetch_assoc($res)) {
					$y = (int)$row['y'];
					$m = (int)$row['m'];
					if ($y <= 0 || $m <= 0 || $m > 12) continue;
					$key = sprintf("%04d-%02d", $y, $m);
					if (!isset($monthly_trend[$key])) {
						$monthly_trend[$key] = 0;
					}
					$monthly_trend[$key] += (float)$row['amt'];
				}
			}
		}

		ksort($monthly_trend);
		// Slice monthly trend to show the latest 12 months with activity to avoid cluttering
		$monthly_trend_sliced = array_slice($monthly_trend, -12, 12, true);

		// Format keys for Chart.js labels (e.g. "2020-08" to "Aug 2020")
		$trend_labels = [];
		$trend_values = [];
		foreach ($monthly_trend_sliced as $key => $val) {
			$dt = DateTime::createFromFormat("Y-m", $key);
			$trend_labels[] = $dt ? $dt->format("M Y") : $key;
			$trend_values[] = $val;
		}

		// 8. Recent transactions UNION query
		$recent_sql = "
			(SELECT 'Clearance' as category, name_fam as last_name, name_1st as first_name, isorno, oramount, YEAR(date_issued) as is_year, MONTH(date_issued) as is_month, DAY(date_issued) as is_day, idn FROM clearances)
			UNION ALL
			(SELECT 'Business Permit' as category, name_fam as last_name, name_1st as first_name, isorno, oramount, YEAR(date_issued) as is_year, MONTH(date_issued) as is_month, DAY(date_issued) as is_day, idn FROM permit_business)
			UNION ALL
			(SELECT 'Indigency Cert' as category, name_fam as last_name, name_1st as first_name, 'N/A' as isorno, 0 as oramount, YEAR(date_issued) as is_year, MONTH(date_issued) as is_month, DAY(date_issued) as is_day, idn FROM cert_indigency)
			UNION ALL
			(SELECT 'Fishing Reg' as category, name_fam as last_name, name_1st as first_name, isorno, oramount, YEAR(date_issued) as is_year, MONTH(date_issued) as is_month, DAY(date_issued) as is_day, idn FROM reg_fishing)
			UNION ALL
			(SELECT 'Operating Permit' as category, name_fam as last_name, name_1st as first_name, isorno, oramount, YEAR(date_issued) as is_year, MONTH(date_issued) as is_month, DAY(date_issued) as is_day, idn FROM permit_operate)
			ORDER BY is_year DESC, CAST(is_month AS SIGNED) DESC, CAST(is_day AS SIGNED) DESC, idn DESC
			LIMIT 10";
		
		$recent_res = $link->query($recent_sql);
	?>

	<!-- Highlights Row -->
	<div class="row mb-4">
		<!-- Total Revenue -->
		<div class="col-md-3 mb-3">
			<div class="card analytics-card shadow-sm border p-3 bg-white">
				<div class="text-muted small text-uppercase font-weight-bold">Total Revenue</div>
				<div class="metric-num text-success">₱<?php echo number_format($total_revenue, 2); ?></div>
				<div class="small text-muted"><i class="fas fa-coins mr-1"></i> Accumulated Payments</div>
			</div>
		</div>
		<!-- Total Transactions -->
		<div class="col-md-3 mb-3">
			<div class="card analytics-card shadow-sm border p-3 bg-white">
				<div class="text-muted small text-uppercase font-weight-bold">Total Transactions</div>
				<div class="metric-num text-primary"><?php echo number_format($total_transactions); ?></div>
				<div class="small text-muted"><i class="fas fa-file-invoice mr-1"></i> Issued Permits & Certs</div>
			</div>
		</div>
		<!-- Average Transaction value -->
		<div class="col-md-3 mb-3">
			<div class="card analytics-card shadow-sm border p-3 bg-white">
				<div class="text-muted small text-uppercase font-weight-bold">Average Payment</div>
				<div class="metric-num text-info">₱<?php echo number_format($avg_transaction, 2); ?></div>
				<div class="small text-muted"><i class="fas fa-calculator mr-1"></i> Per paid document</div>
			</div>
		</div>
		<!-- Max Single Transaction -->
		<div class="col-md-3 mb-3">
			<div class="card analytics-card shadow-sm border p-3 bg-white">
				<div class="text-muted small text-uppercase font-weight-bold">Highest Payment</div>
				<div class="metric-num text-warning">₱<?php echo number_format($max_transaction, 2); ?></div>
				<div class="small text-muted"><i class="fas fa-crown mr-1"></i> Single maximum amount</div>
			</div>
		</div>
	</div>

	<!-- First Visual Analytics Section -->
	<div class="row">
		<!-- Revenue Trend Line Chart -->
		<div class="col-lg-8 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-chart-line mr-2 text-primary"></i>Accumulated Revenue Timeline Trend <x class="thid">(Monthly)</x>
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<?php if (empty($trend_values)) { ?>
						<div class="text-muted small text-center my-auto">No transaction data recorded.</div>
					<?php } else { ?>
						<canvas id="trendChart" style="max-height: 320px;"></canvas>
					<?php } ?>
				</div>
			</div>
		</div>

		<!-- Revenue Contribution Pie -->
		<div class="col-lg-4 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-pie-chart mr-2 text-success"></i>Revenue Contribution by Service
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<canvas id="revChart" style="max-height: 320px;"></canvas>
				</div>
			</div>
		</div>
	</div>

	<!-- Second Visual Analytics Section -->
	<div class="row">
		<!-- Transaction Volume Chart -->
		<div class="col-lg-5 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-chart-bar mr-2 text-info"></i>Transaction Volume per <x class="thid">Service</x> Category
				</div>
				<div class="card-body d-flex align-items-center justify-content-center">
					<canvas id="volChart" style="max-height: 300px;"></canvas>
				</div>
			</div>
		</div>

		<!-- Recent Transactions table feed -->
		<div class="col-lg-7 mb-4">
			<div class="card analytics-card shadow-sm border h-100 bg-white">
				<div class="card-header bg-transparent font-weight-bold text-dark">
					<i class="fas fa-history mr-2 text-warning"></i>Recent Transactions Audit Feed
				</div>
				<div class="card-body p-0 table-responsive" style="max-height: 350px; overflow-y: auto;">
					<table class="table table-hover table-striped mb-0 text-center small">
						<thead class="thead-dark text-white sticky-top">
							<tr>
								<th>Category</th>
								<th>Name</th>
								<th>Date Issued</th>
								<th>OR No.</th>
								<th>Amount Paid</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if ($recent_res && mysqli_num_rows($recent_res) > 0) {
									while ($row = mysqli_fetch_assoc($recent_res)) {
										$category = $row['category'];
										$fullname = trim($row['first_name'] . ' ' . $row['last_name']);
										$date = sprintf("%04d-%02d-%02d", (int)$row['is_year'], (int)$row['is_month'], (int)$row['is_day']);
										$or = !empty($row['isorno']) && $row['isorno'] !== '0' ? $row['isorno'] : 'N/A';
										$amount = (float)$row['oramount'];
										
										// Badge color definitions
										$badge = "badge-secondary";
										if ($category === 'Clearance') $badge = "badge-info";
										elseif ($category === 'Business Permit') $badge = "badge-primary";
										elseif ($category === 'Indigency Cert') $badge = "badge-success";
										elseif ($category === 'Fishing Reg') $badge = "badge-warning";
										elseif ($category === 'Operating Permit') $badge = "badge-danger";

										echo "<tr>
											<td><span class='badge {$badge} px-2 py-1'>{$category}</span></td>
											<td class='font-weight-bold text-left'>{$fullname}</td>
											<td>{$date}</td>
											<td><code>{$or}</code></td>
											<td class='text-right font-weight-bold text-success'>" . ($amount > 0 ? '₱' . number_format($amount, 2) : 'Free') . "</td>
										</tr>";
									}
								} else {
									echo "<tr><td colspan='5' class='text-muted py-4'>No transaction records found.</td></tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function() {
		// Define global instances variables
		var trendChartInstance, revChartInstance, volChartInstance;

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

		// 1. Revenue Timeline Trend Chart (Line)
		var trendEl = document.getElementById('trendChart');
		if (trendEl) {
			var trendCtx = trendEl.getContext('2d');
			trendChartInstance = new Chart(trendCtx, {
				type: 'line',
				data: {
					labels: <?php echo json_encode($trend_labels); ?>,
					datasets: [{
						label: 'Revenue Collected (₱)',
						data: <?php echo json_encode($trend_values); ?>,
						borderColor: '#10b981',
						backgroundColor: 'rgba(16, 185, 129, 0.08)',
						borderWidth: 2.5,
						tension: 0.35,
						fill: true,
						pointRadius: 4,
						pointHoverRadius: 6
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
							ticks: { 
								color: config.textColor,
								callback: function(value) { return '₱' + value.toLocaleString(); }
							}
						}
					}
				}
			});
		}

		// 2. Revenue Contribution Chart (Doughnut)
		var revCtx = document.getElementById('revChart').getContext('2d');
		revChartInstance = new Chart(revCtx, {
			type: 'doughnut',
			data: {
				labels: <?php echo json_encode(array_keys($revenue_data)); ?>,
				datasets: [{
					data: <?php echo json_encode(array_values($revenue_data)); ?>,
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
						borderWidth: 1,
						callbacks: {
							label: function(context) {
								var label = context.label || '';
								var value = context.parsed || 0;
								return label + ': ₱' + value.toLocaleString();
							}
						}
					}
				}
			}
		});

		// 3. Transaction Volume Chart (Bar)
		var volCtx = document.getElementById('volChart').getContext('2d');
		volChartInstance = new Chart(volCtx, {
			type: 'bar',
			data: {
				labels: <?php echo json_encode(array_keys($volume_data)); ?>,
				datasets: [{
					data: <?php echo json_encode(array_values($volume_data)); ?>,
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
						ticks: { color: config.textColor, stepSize: 1 }
					}
				}
			}
		});

		// Function to update active Chart.js instances dynamically when theme changes
		function updateChartThemes() {
			var newConfig = getThemeConfig();
			var charts = [trendChartInstance, revChartInstance, volChartInstance];
			
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
