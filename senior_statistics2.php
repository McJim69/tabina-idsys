<?php	
	// Total Seniors >= 60
	$res_total = $link->query("SELECT COUNT(*) as total FROM senior WHERE age >= 60");
	$total_seniors = mysqli_fetch_assoc($res_total)['total'] ?? 0;
	
	// Average Age >= 60
	$res_avg = $link->query("SELECT AVG(age) as avg_age FROM senior WHERE age >= 60");
	$avg_age = round(mysqli_fetch_assoc($res_avg)['avg_age'] ?? 0, 1);
	
	// Pensioners vs Non-Pensioners >= 60
	$res_pens = $link->query("SELECT COUNT(*) as total FROM senior WHERE age >= 60 AND pensioner = 'Yes'");
	$pensioners_count = mysqli_fetch_assoc($res_pens)['total'] ?? 0;
	$non_pensioners_count = $total_seniors - $pensioners_count;
	
	// Gender Split (M vs F) >= 60
	$res_male = $link->query("SELECT COUNT(*) as total FROM senior WHERE age >= 60 AND (sex = 'M' OR sex = 'Male')");
	$males_count = mysqli_fetch_assoc($res_male)['total'] ?? 0;
	$females_count = $total_seniors - $males_count;
	
	// Age Brackets
	$group_60_69 = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM senior WHERE age BETWEEN 60 AND 69"))['total'] ?? 0;
	$group_70_79 = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM senior WHERE age BETWEEN 70 AND 79"))['total'] ?? 0;
	$group_80_89 = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM senior WHERE age BETWEEN 80 AND 89"))['total'] ?? 0;
	$group_90_up = mysqli_fetch_assoc($link->query("SELECT COUNT(*) as total FROM senior WHERE age >= 90"))['total'] ?? 0;
	
	// Get Age-by-Age distribution (60+)
	$ages = [];
	$age_counts = [];
	$res_ages = $link->query("SELECT age, COUNT(*) as count FROM senior WHERE age >= 60 GROUP BY age ORDER BY age ASC");
	while($row = mysqli_fetch_assoc($res_ages)){
		$ages[] = intval($row['age']);
		$age_counts[] = intval($row['count']);
	}
	
	// Get Barangay distribution
	$brgy_names = [];
	$brgy_counts = [];
	$res_brgy = $link->query("SELECT barangay, COUNT(*) as count FROM senior WHERE age >= 60 GROUP BY barangay ORDER BY count DESC");
	while($row = mysqli_fetch_assoc($res_brgy)){
		$brgy_names[] = $row['barangay'] ? $row['barangay'] : "Unknown";
		$brgy_counts[] = intval($row['count']);
	}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="style/stats-senior.css" rel="stylesheet" type="text/css"/>

<div class="container py-4" style="margin-top:-30px">
	<!-- Page Header -->
	<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
		<div>
			<h2 class="font-weight-bold text-dark mb-1">
				<i class="fas fa-chart-pie mr-2 text-primary"></i>Senior <x class="thid">Citizens</x> Statistics
			</h2>
			<p class="text-muted mb-0">Demographics and analytics for citizens aged 60 and above in Tabina</p>
		</div>
	</div>

	<!-- Top Stats Overview -->
	<div class="row">
		<div class="col-xl-3 col-md-6 col-sm-12">
			<div class="stats-card card-blue">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Total Seniors (60+)</span>
				<div class="d-flex align-items-baseline mt-1">
					<h2 class="mb-0 font-weight-bold"><?php echo number_format($total_seniors); ?></h2>
					<span class="ml-2 small text-white-50">citizens</span>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 col-sm-12">
			<div class="stats-card card-green">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Pensioners</span>
				<div class="d-flex align-items-baseline mt-1">
					<h2 class="mb-0 font-weight-bold"><?php echo number_format($pensioners_count); ?></h2>
					<span class="ml-2 small text-white-50">(<?php echo $total_seniors > 0 ? round(($pensioners_count / $total_seniors) * 100) : 0; ?>%)</span>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 col-sm-12">
			<div class="stats-card card-orange">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Average Age</span>
				<div class="d-flex align-items-baseline mt-1">
					<h2 class="mb-0 font-weight-bold"><?php echo $avg_age; ?></h2>
					<span class="ml-2 small text-white-50">years old</span>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 col-sm-12">
			<div class="stats-card card-purple">
				<span class="text-white-50 small text-uppercase font-weight-bold tracking-wider">Gender Balance</span>
				<div class="d-flex align-items-baseline mt-1">
					<h2 class="mb-0 font-weight-bold" style="font-size: 1.7em;"><?php echo number_format($males_count); ?>M <?php echo number_format($females_count); ?>F</h2>
				</div>
			</div>
		</div>
	</div>

	<!-- Main Demographics Charts Row -->
	<div class="row">
		<!-- Age Distribution Line Chart -->
		<div class="col-lg-8 col-md-12">
			<div class="chart-container">
				<h4 class="chart-title">Age-by-Age Distribution (60+)</h4>
				<div style="position: relative; height: 350px;">
					<canvas id="ageLineChart"></canvas>
				</div>
			</div>
		</div>

		<!-- Age Group Brackets Bar Chart -->
		<div class="col-lg-4 col-md-12">
			<div class="chart-container">
				<h4 class="chart-title">Demographic Brackets</h4>
				<div style="position: relative; height: 350px;">
					<canvas id="bracketsBarChart"></canvas>
				</div>
			</div>
		</div>
	</div>

	<!-- Secondary Breakdown Charts Row -->
	<div class="row">
		<!-- Gender Split -->
		<div class="col-md-4 col-sm-12">
			<div class="chart-container text-center">
				<h4 class="chart-title text-left">Gender Distribution</h4>
				<div class="mx-auto" style="position: relative; height: 220px; max-width: 220px;">
					<canvas id="genderDoughnutChart"></canvas>
				</div>
			</div>
		</div>

		<!-- Pension Status Split -->
		<div class="col-md-4 col-sm-12">
			<div class="chart-container text-center">
				<h4 class="chart-title text-left">Pensioner Status</h4>
				<div class="mx-auto" style="position: relative; height: 220px; max-width: 220px;">
					<canvas id="pensionerPieChart"></canvas>
				</div>
			</div>
		</div>

		<!-- Barangay Breakdown (Dynamic Progress Bars) -->
		<div class="col-md-4 col-sm-12">
			<div class="chart-container">
				<h4 class="chart-title">SC Top Barangays</h4>
				<div class="brgy-progress-container">
					<?php 
						$max_brgy_count = count($brgy_counts) > 0 ? max($brgy_counts) : 1;
						for ($idx = 0; $idx < count($brgy_names); $idx++) {
							$percent = round(($brgy_counts[$idx] / $max_brgy_count) * 100);
							$real_percent = $total_seniors > 0 ? round(($brgy_counts[$idx] / $total_seniors) * 100, 1) : 0;
					?>
						<div class="brgy-progress-item">
							<div class="brgy-progress-label">
								<span><?php echo htmlspecialchars($brgy_names[$idx]); ?></span>
								<span class="text-muted"><?php echo number_format($brgy_counts[$idx]); ?> <small>(<?php echo $real_percent; ?>%)</small></span>
							</div>
							<div class="progress" style="height: 8px; border-radius: 4px;">
								<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $percent; ?>%; border-radius: 4px;"></div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// Age-by-Age Line Chart
	const ageLabels = <?php echo json_encode($ages); ?>;
	const ageData = <?php echo json_encode($age_counts); ?>;
	
	const ctxLine = document.getElementById('ageLineChart').getContext('2d');
	new Chart(ctxLine, {
		type: 'line',
		data: {
			labels: ageLabels,
			datasets: [{
				label: 'Seniors Count',
				data: ageData,
				borderColor: '#3b82f6',
				backgroundColor: 'rgba(59, 130, 246, 0.1)',
				borderWidth: 3,
				fill: true,
				tension: 0.3,
				pointRadius: 4,
				pointBackgroundColor: '#2563eb'
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: { display: false }
			},
			scales: {
				x: {
					title: { display: true, text: 'Age (Years Old)', color: '#4b5563', font: { weight: 'bold' } },
					grid: { display: false }
				},
				y: {
					title: { display: true, text: 'Number of Citizens', color: '#4b5563', font: { weight: 'bold' } },
					beginAtZero: true,
					ticks: { stepSize: 5 }
				}
			}
		}
	});

	// Demographic Brackets Bar Chart
	const ctxBar = document.getElementById('bracketsBarChart').getContext('2d');
	new Chart(ctxBar, {
		type: 'bar',
		data: {
			labels: ['60-69 (Young)', '70-79 (Mid)', '80-89 (Old)', '90+ (Longevity)'],
			datasets: [{
				data: [
					<?php echo $group_60_69; ?>, 
					<?php echo $group_70_79; ?>, 
					<?php echo $group_80_89; ?>, 
					<?php echo $group_90_up; ?>
				],
				backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ec4899'],
				borderRadius: 8
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: { display: false }
			},
			scales: {
				y: {
					beginAtZero: true,
					title: { display: true, text: 'Seniors Count', color: '#4b5563', font: { weight: 'bold' } }
				}
			}
		}
	});

	// Gender Doughnut Chart
	const ctxDoughnut = document.getElementById('genderDoughnutChart').getContext('2d');
	new Chart(ctxDoughnut, {
		type: 'doughnut',
		data: {
			labels: ['Male', 'Female'],
			datasets: [{
				data: [<?php echo $males_count; ?>, <?php echo $females_count; ?>],
				backgroundColor: ['#6366f1', '#ec4899'],
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: { position: 'bottom' }
			}
		}
	});

	// Pensioner Pie Chart
	const ctxPie = document.getElementById('pensionerPieChart').getContext('2d');
	new Chart(ctxPie, {
		type: 'pie',
		data: {
			labels: ['With Pension', 'No Pension'],
			datasets: [{
				data: [<?php echo $pensioners_count; ?>, <?php echo $non_pensioners_count; ?>],
				backgroundColor: ['#10b981', '#ef4444'],
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: { position: 'bottom' }
			}
		}
	});
</script>
