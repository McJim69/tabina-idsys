<?php
	require("connect.php");

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
		$where[] = "action LIKE '%$target_table%'";
	}
	$whereSQL = $where ? "WHERE ".implode(" AND ", $where) : "";

	// Fetch records for CSV export
	$res = $link->query("SELECT * FROM audit_trail $whereSQL ORDER BY timestamp DESC");

	// Output headers to trigger download
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=audit_trail_export_' . date('Ymd_His') . '.csv');

	$output = fopen('php://output', 'w');

	// Column headers
	fputcsv($output, ['ID', 'Admin User', 'Action Description', 'IP Address', 'Timestamp']);

	if ($res && $res->num_rows > 0) {
		while($row = $res->fetch_assoc()){
			fputcsv($output, [
				$row['id'],
				$row['admin_user'],
				$row['action'],
				$row['ip_address'],
				$row['timestamp']
			]);
		}
	}
	fclose($output);
	exit;
?>
