<?php 
	require("connect.php");
	require("header.php");
	require("menu.php");
?>
<!--<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>-->

<link href="style/audit_trail.css" rel="stylesheet" type="text/css"/>

<div class="container py-4" style="margin-top:40px">
	<div class="row">
		<!-- Left / Main Pane: Audit Trail Table and Controls -->
		<div class="col-xl-12 col-lg-12">
			<div class="mt-2">
				<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
					<h3 class="dashboard-section-title mb-0">SysTrail</h3>
					<div class="d-flex align-items-center my-2">
						<div class="custom-control custom-switch bg-light border rounded px-3 py-2 shadow-sm d-flex align-items-center" style="cursor: pointer;">
							<input type="checkbox" class="custom-control-input" id="realtimeToggle" checked>
							<label class="custom-control-label font-weight-bold text-muted mb-0 mr-1" for="realtimeToggle" style="cursor: pointer; user-select: none;">
								<span id="realtimeStatusText">Realtime: ON</span>
							</label>
							<span id="realtimeDot" class="badge badge-success pulse-dot ml-2" style="width: 10px; height: 10px; border-radius: 50%; display: inline-block; background-color: #28a745;"></span>
						</div>
					</div>
				</div>

				<!-- KPI Summary Cards -->
				<div class="row mb-4">
					<div class="col-lg col-md-4 mb-2">
						<div class="card shadow-sm border rounded-lg bg-light text-dark h-100">
							<div class="card-body py-3 d-flex align-items-center justify-content-between">
								<div>
									<h6 class="text-uppercase text-muted font-weight-bold mb-1 text-nowrap" style="font-size: 10px; letter-spacing: 0.5px;">Logs Today</h6>
									<h3 class="mb-0 font-weight-bold" id="statTodayTotal">0</h3>
								</div>
								<div class="bg-primary-soft p-3 rounded-pill text-primary">
									<i class="fas fa-file-invoice fa-lg opacity-75"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg col-md-4 mb-2">
						<div class="card shadow-sm border rounded-lg bg-light text-dark h-100">
							<div class="card-body py-3 d-flex align-items-center justify-content-between">
								<div>
									<h6 class="text-uppercase text-muted font-weight-bold mb-1 text-nowrap" style="font-size: 10px; letter-spacing: 0.5px;">Created Today</h6>
									<h3 class="mb-0 font-weight-bold" id="statTodayCreated">0</h3>
								</div>
								<div class="bg-success-soft p-3 rounded-pill text-success">
									<i class="fas fa-plus-circle fa-lg opacity-75"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg col-md-4 mb-2">
						<div class="card shadow-sm border rounded-lg bg-light text-dark h-100">
							<div class="card-body py-3 d-flex align-items-center justify-content-between">
								<div>
									<h6 class="text-uppercase text-muted font-weight-bold mb-1 text-nowrap" style="font-size: 10px; letter-spacing: 0.5px;">Updated Today</h6>
									<h3 class="mb-0 font-weight-bold" id="statTodayUpdated">0</h3>
								</div>
								<div class="bg-warning-soft p-3 rounded-pill text-warning">
									<i class="fas fa-edit fa-lg opacity-75"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg col-md-4 mb-2">
						<div class="card shadow-sm border rounded-lg bg-light text-dark h-100">
							<div class="card-body py-3 d-flex align-items-center justify-content-between">
								<div>
									<h6 class="text-uppercase text-muted font-weight-bold mb-1 text-nowrap" style="font-size: 10px; letter-spacing: 0.5px;">Deletions Today</h6>
									<h3 class="mb-0 font-weight-bold" id="statTodayDeletes">0</h3>
								</div>
								<div class="bg-danger-soft p-3 rounded-pill text-danger">
									<i class="fas fa-trash-alt fa-lg opacity-75"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg col-md-4 mb-2">
						<div class="card shadow-sm border rounded-lg bg-light text-dark h-100">
							<div class="card-body py-3 d-flex align-items-center justify-content-between">
								<div>
									<h6 class="text-uppercase text-muted font-weight-bold mb-1 text-nowrap" style="font-size: 10px; letter-spacing: 0.5px;">Top Operator Today</h6>
									<h3 class="mb-0 font-weight-bold text-truncate" id="statTopAdmin" style="font-size: 1.4rem; max-width: 220px;">None</h3>
								</div>
								<div class="bg-info-soft p-3 rounded-pill text-info">
									<i class="fas fa-user-shield fa-lg opacity-75"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Search Filters -->
				<div class="form-row mb-3 align-items-end">
					<div class="col-lg-3 col-md-6 mb-2">
						<label class="font-weight-bold text-muted small mb-1">Operator Username</label>
						<input type="text" id="filterAdmin" class="form-control" placeholder="Search admin username...">
					</div>
					<div class="col-lg-2 col-md-6 mb-2">
						<label class="font-weight-bold text-muted small mb-1">Start Date</label>
						<input type="date" id="filterStart" class="form-control">
					</div>
					<div class="col-lg-2 col-md-6 mb-2">
						<label class="font-weight-bold text-muted small mb-1">End Date</label>
						<input type="date" id="filterEnd" class="form-control">
					</div>
					<div class="col-lg-2 col-md-6 mb-2">
						<label class="font-weight-bold text-muted small mb-1">Operation Type</label>
						<select id="filterOpType" class="form-control">
							<option value="">All Operations</option>
							<option value="create">Create / Add</option>
							<option value="update">Update / Edit</option>
							<option value="delete">Delete / Remove</option>
						</select>
					</div>
					<div class="col-lg-3 col-md-6 mb-2">
						<label class="font-weight-bold text-muted small mb-1">Registry Table</label>
						<select id="filterTargetTable" class="form-control">
							<option value="">All Tables</option>
							<?php
								global $db_tables;
								if (isset($db_tables) && is_array($db_tables)) {
									ksort($db_tables);
									foreach ($db_tables as $tbl => $pk) {
										echo '<option value="'.$tbl.'">'.strtoupper($tbl).'</option>';
									}
								}
							?>
						</select>
					</div>
				</div>
				<!--
				<div class="form-row mb-4">
					<div class="col-md-6 mb-2">
						<button class="btn btn-primary px-4 mr-2" onclick="loadAuditPage(1)"><i class="fas fa-search mr-1"></i> Search Logs</button>
						<button class="btn btn-outline-secondary px-3" onclick="resetFilters()"><i class="fas fa-sync-alt mr-1"></i> Reset</button>
					</div>
					<div class="col-md-6 mb-2 text-right">
						<button class="btn btn-success px-4 shadow-sm" onclick="exportCSV()"><i class="fas fa-file-csv mr-1"></i> Export to CSV</button>
					</div>
				</div>
				-->
				<div id="auditTableContainer"></div>
			</div>
		</div>
		<!-- Right Pane: History Graph Sidebar 
		<div class="col-xl-3 col-lg-4 mb-4" style="margin-top:15px">
			<div class="card shadow-sm border rounded-lg bg-light text-dark h-100" style="padding-bottom:150px">
				<div class="card-header bg-transparent border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
					<h6 class="font-weight-bold text-uppercase text-muted mb-0 text-nowrap" style="font-size: 10px; letter-spacing: 0.5px;">Daily Activity Chart</h6>
					<i class="fas fa-chart-line text-primary"></i>
				</div>
				<div class="card-body d-flex flex-column justify-content-center" style="position: relative; min-height: 280px;">
					<canvas id="auditHistoryChart" style="max-height: 290px; width: 100%;"></canvas>
				</div>
			</div>
		</div>-->
	</div>
</div>

<script>
	var currentAuditPage = 1;
	var currentSortBy = 'timestamp';
	var currentSortOrder = 'DESC';
	var realtimeInterval = null;
	var auditChartInstance = null;

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

	function renderHistoryChart(labels, values) {
		if (auditChartInstance) {
			auditChartInstance.destroy();
		}

		var canvas = document.getElementById('auditHistoryChart');
		if (!canvas) return;
		var ctx = canvas.getContext('2d');
		var themeConfig = getThemeConfig();

		auditChartInstance = new Chart(ctx, {
			type: 'line',
			data: {
				labels: labels,
				datasets: [{
					label: 'Daily Logs',
					data: values,
					borderColor: '#007bff',
					backgroundColor: 'rgba(0, 123, 255, 0.08)',
					borderWidth: 2,
					pointBackgroundColor: '#007bff',
					pointRadius: 3,
					tension: 0.2,
					fill: true
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: { display: false },
					tooltip: {
						backgroundColor: themeConfig.tooltipBg,
						titleColor: themeConfig.tooltipColor,
						bodyColor: themeConfig.tooltipColor,
						borderColor: themeConfig.tooltipBorder,
						borderWidth: 1
					}
				},
				scales: {
					x: {
						grid: { color: themeConfig.gridColor },
						ticks: { 
							color: themeConfig.textColor,
							font: { size: 9 }
						}
					},
					y: {
						beginAtZero: true,
						grid: { color: themeConfig.gridColor },
						ticks: { 
							color: themeConfig.textColor,
							font: { size: 9 },
							stepSize: 1
						}
					}
				}
			}
		});
	}

	function updateChartThemes() {
		if (!auditChartInstance) return;
		var newConfig = getThemeConfig();
		
		if (auditChartInstance.options.scales) {
			if (auditChartInstance.options.scales.x) {
				if (auditChartInstance.options.scales.x.ticks) auditChartInstance.options.scales.x.ticks.color = newConfig.textColor;
				if (auditChartInstance.options.scales.x.grid) auditChartInstance.options.scales.x.grid.color = newConfig.gridColor;
			}
			if (auditChartInstance.options.scales.y) {
				if (auditChartInstance.options.scales.y.ticks) auditChartInstance.options.scales.y.ticks.color = newConfig.textColor;
				if (auditChartInstance.options.scales.y.grid) auditChartInstance.options.scales.y.grid.color = newConfig.gridColor;
			}
		}
		
		if (auditChartInstance.options.plugins && auditChartInstance.options.plugins.tooltip) {
			auditChartInstance.options.plugins.tooltip.backgroundColor = newConfig.tooltipBg;
			auditChartInstance.options.plugins.tooltip.borderColor = newConfig.tooltipBorder;
			auditChartInstance.options.plugins.tooltip.titleColor = newConfig.tooltipColor;
			auditChartInstance.options.plugins.tooltip.bodyColor = newConfig.tooltipColor;
		}
		
		auditChartInstance.update();
	}

	function loadAuditPage(page){
		currentAuditPage = page;
		var admin = document.getElementById("filterAdmin").value;
		var start = document.getElementById("filterStart").value;
		var end   = document.getElementById("filterEnd").value;
		var op_type = document.getElementById("filterOpType").value;
		var target_table = document.getElementById("filterTargetTable").value;

		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState === 4 && xhr.status === 200){
				document.getElementById("auditTableContainer").innerHTML = xhr.responseText;
				
				// Parse stats and update KPI cards
				var statsEl = document.getElementById("auditStatsData");
				if (statsEl) {
					document.getElementById("statTodayTotal").textContent = statsEl.getAttribute("data-today-total") || "0";
					document.getElementById("statTodayCreated").textContent = statsEl.getAttribute("data-today-created") || "0";
					document.getElementById("statTodayUpdated").textContent = statsEl.getAttribute("data-today-updated") || "0";
					document.getElementById("statTopAdmin").textContent = statsEl.getAttribute("data-top-admin") || "None";
					document.getElementById("statTodayDeletes").textContent = statsEl.getAttribute("data-today-deletes") || "0";

					// Update daily history chart
					try {
						var labels = JSON.parse(statsEl.getAttribute("data-chart-labels") || "[]");
						var values = JSON.parse(statsEl.getAttribute("data-chart-values") || "[]");
						renderHistoryChart(labels, values);
					} catch(e) {
						console.error("Failed to parse chart data:", e);
					}
				}
			}
		};
		var url = "audit_trail_data.php?page=" + page + 
		          "&admin=" + encodeURIComponent(admin) + 
		          "&start=" + encodeURIComponent(start) + 
		          "&end=" + encodeURIComponent(end) +
		          "&op_type=" + encodeURIComponent(op_type) +
		          "&target_table=" + encodeURIComponent(target_table) +
		          "&sort_by=" + encodeURIComponent(currentSortBy) + 
		          "&sort_order=" + encodeURIComponent(currentSortOrder);
		xhr.open("GET", url, true);
		xhr.send();
	}

	function changeSort(col, order){
		currentSortBy = col;
		currentSortOrder = order;
		loadAuditPage(currentAuditPage);
	}

	function resetFilters(){
		document.getElementById("filterAdmin").value = "";
		document.getElementById("filterStart").value = "";
		document.getElementById("filterEnd").value = "";
		document.getElementById("filterOpType").value = "";
		document.getElementById("filterTargetTable").value = "";
		loadAuditPage(1);
	}

	function exportCSV(){
		var admin = document.getElementById("filterAdmin").value;
		var start = document.getElementById("filterStart").value;
		var end   = document.getElementById("filterEnd").value;
		var op_type = document.getElementById("filterOpType").value;
		var target_table = document.getElementById("filterTargetTable").value;

		var url = "audit_trail_export.php?admin=" + encodeURIComponent(admin) + 
		          "&start=" + encodeURIComponent(start) + 
		          "&end=" + encodeURIComponent(end) +
		          "&op_type=" + encodeURIComponent(op_type) +
		          "&target_table=" + encodeURIComponent(target_table);
		window.location.href = url;
	}

	function startRealtimeMonitoring(){
		if (realtimeInterval) clearInterval(realtimeInterval);
		realtimeInterval = setInterval(function(){
			loadAuditPage(currentAuditPage);
		}, 3000);
	}

	function stopRealtimeMonitoring(){
		if (realtimeInterval) {
			clearInterval(realtimeInterval);
			realtimeInterval = null;
		}
	}

	// Load first page on page load and start monitoring
	document.addEventListener("DOMContentLoaded", function(){
		loadAuditPage(1);
		
		var toggle = document.getElementById("realtimeToggle");
		var dot = document.getElementById("realtimeDot");
		var text = document.getElementById("realtimeStatusText");
		
		if (toggle) {
			toggle.addEventListener("change", function(){
				if (this.checked) {
					startRealtimeMonitoring();
					text.textContent = "Realtime: ON";
					dot.className = "badge badge-success pulse-dot ml-2";
					dot.style.backgroundColor = "#28a745";
				} else {
					stopRealtimeMonitoring();
					text.textContent = "Realtime: OFF";
					dot.className = "badge badge-secondary ml-2";
					dot.style.backgroundColor = "#6c757d";
				}
			});
			
			if (toggle.checked) {
				startRealtimeMonitoring();
			}
		}
	});

	// Watch for theme changes dynamically using MutationObserver
	var themeObserver = new MutationObserver(function(mutations) {
		mutations.forEach(function(mutation) {
			if (mutation.attributeName === 'data-theme') {
				updateChartThemes();
			}
		});
	});
	themeObserver.observe(document.documentElement, { attributes: true });
</script>