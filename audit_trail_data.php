<?php
	require("connect.php");

	$limit = 10;
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	if ($page < 1) $page = 1;
	$offset = ($page - 1) * $limit;

	// Sort Parameters
	$valid_sorts = ['id', 'admin_user', 'action', 'ip_address', 'timestamp'];
	$sort_by = isset($_GET['sort_by']) && in_array($_GET['sort_by'], $valid_sorts) ? $_GET['sort_by'] : 'timestamp';
	$sort_order = isset($_GET['sort_order']) && strtoupper($_GET['sort_order']) === 'ASC' ? 'ASC' : 'DESC';

	// Filters
	$admin = trim($_GET['admin'] ?? '');
	$start = trim($_GET['start'] ?? '');
	$end   = trim($_GET['end'] ?? '');
	$op_type = trim($_GET['op_type'] ?? '');
	$target_table = trim($_GET['target_table'] ?? '');

	$where = [];
	if ($admin !== '') {
		$where[] = "admin_user LIKE '%".$link->real_escape_string($admin)."%'";
	}
	if ($start !== '' && $end !== '') {
		$where[] = "DATE(timestamp) BETWEEN '".$link->real_escape_string($start)."' AND '".$link->real_escape_string($end)."'";
	}
	if ($op_type !== '') {
		if ($op_type === 'create') {
			$where[] = "(action LIKE 'Created%' OR action LIKE 'Added%' OR action LIKE 'Registered%' OR action LIKE 'Sent%')";
		} elseif ($op_type === 'update') {
			$where[] = "(action LIKE 'Updated%' OR action LIKE 'Modified%' OR action LIKE 'Edited%')";
		} elseif ($op_type === 'delete') {
			$where[] = "(action LIKE 'Deleted%' OR action LIKE 'Removed%')";
		}
	}
	if ($target_table !== '') {
		$where[] = "action LIKE '%".$link->real_escape_string($target_table)."%'";
	}
	$whereSQL = $where ? "WHERE ".implode(" AND ", $where) : "";

	// Calculate Today's Stats for the KPI cards
	// 1. Total activities today
	$resToday = $link->query("SELECT COUNT(*) AS total FROM audit_trail WHERE DATE(timestamp) = CURRENT_DATE");
	$todayTotal = $resToday ? $resToday->fetch_assoc()['total'] : 0;

	// 2. Most active admin operator today
	$resActive = $link->query("SELECT admin_user, COUNT(*) AS cnt FROM audit_trail WHERE DATE(timestamp) = CURRENT_DATE GROUP BY admin_user ORDER BY cnt DESC LIMIT 1");
	$topAdmin = $resActive && ($rowActive = $resActive->fetch_assoc()) ? htmlspecialchars($rowActive['admin_user']) . ' (' . $rowActive['cnt'] . ')' : 'None';

	// 3. Security alerts (Deletions) today
	$resDeletes = $link->query("SELECT COUNT(*) AS total FROM audit_trail WHERE DATE(timestamp) = CURRENT_DATE AND (action LIKE '%Delete%' OR action LIKE '%Remove%')");
	$todayDeletes = $resDeletes ? $resDeletes->fetch_assoc()['total'] : 0;

	// 4. Created today (Create / Add actions)
	$resCreated = $link->query("SELECT COUNT(*) AS total FROM audit_trail WHERE DATE(timestamp) = CURRENT_DATE AND (action LIKE 'Created%' OR action LIKE 'Added%' OR action LIKE 'Registered%' OR action LIKE 'Sent%')");
	$todayCreated = $resCreated ? $resCreated->fetch_assoc()['total'] : 0;

	// 5. Updated today (Update / Edit actions)
	$resUpdated = $link->query("SELECT COUNT(*) AS total FROM audit_trail WHERE DATE(timestamp) = CURRENT_DATE AND (action LIKE 'Updated%' OR action LIKE 'Modified%' OR action LIKE 'Edited%')");
	$todayUpdated = $resUpdated ? $resUpdated->fetch_assoc()['total'] : 0;

	// 6. Daily activity counts for Chart.js (Last 15 days with activity matching filters)
	$chartLabels = [];
	$chartValues = [];
	$resChart = $link->query("SELECT DATE(timestamp) as log_date, COUNT(*) as log_count FROM audit_trail $whereSQL GROUP BY log_date ORDER BY log_date DESC LIMIT 15");
	if ($resChart) {
		$chartRows = [];
		while ($rowChart = $resChart->fetch_assoc()) {
			$chartRows[] = $rowChart;
		}
		$chartRows = array_reverse($chartRows);
		foreach ($chartRows as $rowC) {
			$chartLabels[] = date('M d', strtotime($rowC['log_date']));
			$chartValues[] = (int)$rowC['log_count'];
		}
	}
	$labels_json = json_encode($chartLabels);
	$values_json = json_encode($chartValues);

	// Output Hidden Stats Container to feed back data to KPI cards
	echo '<div id="auditStatsData" style="display:none;" 
			data-today-total="'.$todayTotal.'" 
			data-today-created="'.$todayCreated.'" 
			data-today-updated="'.$todayUpdated.'" 
			data-top-admin="'.$topAdmin.'" 
			data-today-deletes="'.$todayDeletes.'"
			data-chart-labels="'.htmlspecialchars($labels_json).'"
			data-chart-values="'.htmlspecialchars($values_json).'"></div>';

	// Count total rows matching filters
	$countRes = $link->query("SELECT COUNT(*) AS total FROM audit_trail $whereSQL");
	$totalRows = $countRes ? $countRes->fetch_assoc()['total'] : 0;
	$totalPages = ceil($totalRows / $limit);

	// Fetch filtered and sorted records
	$res = $link->query("SELECT * FROM audit_trail $whereSQL ORDER BY $sort_by $sort_order LIMIT $limit OFFSET $offset");

	// Helper for header sort icons
	function getSortHeader($col, $label, $current_sort_by, $current_sort_order) {
		if ($col === $current_sort_by) {
			$icon = $current_sort_order === 'ASC' ? ' <i class="fas fa-sort-up ml-1 text-primary"></i>' : ' <i class="fas fa-sort-down ml-1 text-primary"></i>';
			$next_order = $current_sort_order === 'ASC' ? 'DESC' : 'ASC';
		} else {
			$icon = ' <i class="fas fa-sort text-muted ml-1 opacity-50"></i>';
			$next_order = 'DESC'; // Default to newest first
		}
		return '<th style="cursor:pointer; user-select:none;" onclick="changeSort(\''.$col.'\', \''.$next_order.'\')">'.$label.$icon.'</th>';
	}

	echo '<table class="table table-striped table-bordered">';
	echo '<thead><tr>';
	echo getSortHeader('id', 'ID', $sort_by, $sort_order);
	echo getSortHeader('admin_user', 'Operator', $sort_by, $sort_order);
	echo getSortHeader('action', 'Action Log Description', $sort_by, $sort_order);
	echo getSortHeader('ip_address', 'IP Address', $sort_by, $sort_order);
	echo getSortHeader('timestamp', 'Timestamp', $sort_by, $sort_order);
	echo '</tr></thead><tbody>';

	if ($res && $res->num_rows > 0) {
		global $db_tables;
		
		while($row = $res->fetch_assoc()){
			$badge_class = "badge-secondary";
			$action_type = "ACTION";
			$action_text = $row['action'];
			
			if (preg_match('/^(Created|Added|Registered|Sent)/i', $action_text)) {
				$badge_class = "badge-success";
				$action_type = "CREATE";
			} elseif (preg_match('/^(Updated|Modified|Edited)/i', $action_text)) {
				$badge_class = "badge-warning";
				$action_type = "UPDATE";
			} elseif (preg_match('/^(Deleted|Removed)/i', $action_text)) {
				$badge_class = "badge-danger";
				$action_type = "DELETE";
			} elseif (preg_match('/^(Logged|Login|Logout)/i', $action_text)) {
				$badge_class = "badge-info";
				$action_type = "SESSION";
			}

			// Detect target registry table badge
			$detected_table_badge = "";
			if (isset($db_tables) && is_array($db_tables)) {
				foreach ($db_tables as $tbl => $pk) {
					if (stripos($action_text, $tbl) !== false) {
						$detected_table_badge = '<span class="badge badge-dark text-uppercase px-2 py-1 mr-1" style="font-size: 9px; opacity:0.85;">'.$tbl.'</span>';
						break;
					}
				}
			}

			$action_badge = '<span class="badge '.$badge_class.' font-weight-bold px-2 py-1 mr-2" style="font-size: 9px; width: 62px; text-align: center;">'.$action_type.'</span>';

			echo '<tr>';
			echo '<td class="text-muted font-weight-bold" style="font-size: 13px;">#'.$row['id'].'</td>';
			echo '<td><span class="font-weight-bold text-primary" style="font-size: 13px;"><i class="fas fa-user-shield mr-1" style="font-size: 11px; opacity:0.7;"></i>'.htmlspecialchars($row['admin_user']).'</span></td>';
			echo '<td style="font-size: 13px;">'.$action_badge.$detected_table_badge.htmlspecialchars($row['action']).'</td>';
			echo '<td><code style="font-size: 12px; font-weight: bold;"><i class="fas fa-desktop mr-1 text-muted" style="font-size: 10px;"></i>'.$row['ip_address'].'</code></td>';
			echo '<td class="text-muted" style="font-size: 12px;"><i class="far fa-clock mr-1" style="font-size: 10px;"></i>'.$row['timestamp'].'</td>';
			echo '</tr>';
		}
	} else {
		echo '<tr><td colspan="5" class="text-center text-muted p-4"><i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>No audit logs matched filters.</td></tr>';
	}
	echo '</tbody></table>';

	// Pagination links
	echo '<nav><ul class="pagination justify-content-center">';
	if ($page > 1) {
		echo '<li class="page-item"><a class="page-link" href="#" onclick="loadAuditPage('.($page-1).'); return false;">Previous</a></li>';
	}
	for ($i = 1; $i <= $totalPages; $i++) {
		$active = ($i == $page) ? ' active' : '';
		echo '<li class="page-item'.$active.'"><a class="page-link" href="#" onclick="loadAuditPage('.$i.'); return false;">'.$i.'</a></li>';
	}
	if ($page < $totalPages) {
		echo '<li class="page-item"><a class="page-link" href="#" onclick="loadAuditPage('.($page+1).'); return false;">Next</a></li>';
	}
	echo '</ul></nav>';
?>
