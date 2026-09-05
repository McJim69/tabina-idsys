<?php
	require("connect2.php");
	
	if (!isset($_SESSION['user'])) {
		die("Access Denied: Please login first.");
	}

	$service = isset($_GET['service']) ? trim($_GET['service']) : '';
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

	$allowed_tables = [
		'senior' => 'OSCA Senior Citizen',
		'pwd' => 'PWD Registration',
		'solo_parent' => 'Solo Parent Card',
		'cert_indigency' => 'Indigent Certificate',
		'clearances' => "Mayor's Clearance",
		'permit_business' => 'Business Permit',
		'reg_fishing' => 'Fishing Boat Permit',
		'permit_operate' => 'Permit to Operate'
	];

	if (!array_key_exists($service, $allowed_tables) || $id <= 0) {
		die("Invalid Request parameters.");
	}

	$cardType = [
		'senior' => 'Senior Citizen',
		'pwd' => 'Person With Disability',
		'solo_parent' => 'Solo Parent',
	];	

	// Fetch application record
	$status_col = ($service === 'cert_indigency') ? 'app_status' : 'status';
	$stmt = $link->prepare("SELECT * FROM `$service` WHERE idn = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$res = $stmt->get_result();
	$app = $res->fetch_assoc();

	if ($app) {
		if (!empty($app['date_issued']) && $app['date_issued'] !== '0000-00-00') {
			$issued_time = strtotime($app['date_issued']);
			$app['is_day'] = date('d', $issued_time);
			$app['is_month'] = date('m', $issued_time);
			$app['is_year'] = date('Y', $issued_time);
		} else {
			$app['is_day'] = '';
			$app['is_month'] = '';
			$app['is_year'] = '';
		}
	}
	if (!$app) {
		die("Application record not found.");
	}

	// Security: Citizens can only print their own approved applications
	if ($_SESSION['access'] === 'Private') {
		if (intval($app['user_id']) !== intval($_SESSION['uno'])) {
			die("Access Denied: You do not own this application.");
		}
		if (strtolower($app[$status_col]) !== 'approved') {
			die("Access Denied: This application is not yet approved.");
		}
	}

	// Fetch citizen profile picture from users table
	$user_stmt = $link->prepare("SELECT imgUrl, name_1st, name_mid, name_fam FROM users WHERE uno = ?");
	$user_stmt->bind_param("i", $app['user_id']);
	$user_stmt->execute();
	$user_res = $user_stmt->get_result();
	$user_profile = $user_res->fetch_assoc();

	$avatar_path = "images/blank.jpg";
	if ($user_profile && !empty($user_profile['imgUrl']) && file_exists("images/users/" . $user_profile['imgUrl'])) {
		$avatar_path = "images/users/" . $user_profile['imgUrl'];
	}

	// Setup signature path dynamically based on service
	$signature_folder = 'images/' . $service . '/signatures/';
	if ($service === 'solo_parent') {
		$signature_path = "images/solo_parent/signatures/" . $id . ".png";
		if (!file_exists($signature_path)) {
			if (file_exists("images/pwd/signatures/" . $id . ".png")) {
				$signature_path = "images/pwd/signatures/" . $id . ".png";
			}
		}
	} else {
		$signature_path = $signature_folder . $id . ".png";
	}

	// Setup QR code path dynamically based on service
	$qr_folder = 'images/' . $service . '/qrcodes/';
	if ($service === 'solo_parent') {
		$qr_path = "images/solo_parent/qrcodes/" . $id . ".png";
		if (!file_exists($qr_path)) {
			if (file_exists("images/pwd/qrcodes/" . $id . ".png")) {
				$qr_path = "images/pwd/qrcodes/" . $id . ".png";
			}
		}
	} else {
		$qr_path = $qr_folder . $id . ".png";
	}

	// Generate QR code on the fly if it is missing
	if (!file_exists($qr_path)) {
		$dir = ($service === 'solo_parent') ? 'images/solo_parent/qrcodes/' : $qr_folder;
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		
		if (file_exists('qrlib/qrlib.php')) {
			include_once('qrlib/qrlib.php');
			
			$qr_data = "";
			$qr_name = trim(($app['name_1st'] ?? '') . " " . ($app['name_mid'] ?? '') . " " . ($app['name_fam'] ?? ''));
			if ($service === 'senior') {
				$qr_data = $qr_name . "\nSCA ID No: " . ($app['assoc_id_no'] ?? '') . "\nSystem ID: " . $id;
			} else if ($service === 'pwd') {
				$qr_data = $qr_name . "\nID Number: " . str_pad($id, 5, '0', STR_PAD_LEFT);
			} else if ($service === 'solo_parent') {
				$qr_data = $qr_name . "\nID Number: " . str_pad($id, 5, '0', STR_PAD_LEFT);
				$qr_path = "images/solo_parent/qrcodes/" . $id . ".png";
			} else if ($service === 'cert_indigency') {
				$qr_data = $qr_name . "\nCOI-" . $id . "-" . date("md-Y", strtotime($app['timestamp'] ?? 'now'));
			} else if ($service === 'clearances') {
				$qr_data = $qr_name . "\nMC-" . $id . "-" . ($app['isorno'] ?? '');
			} else if ($service === 'permit_business') {
				$qr_data = ($app['tradename'] ?? '') . "\nBP-" . $id . "-" . ($app['is_day'] ?? '') . "-" . ($app['is_month'] ?? '') . "-" . ($app['is_year'] ?? '');
			} else if ($service === 'reg_fishing') {
				$qr_data = "Name of FV: " . ($app['tradename'] ?? '') . "\nOperator: " . $qr_name . "\nMFVR No: " . $id . " Engine SN: " . ($app['enginesn'] ?? '') . " Address: " . ($app['purok'] ?? '') . " " . ($app['barangay'] ?? '') . " " . ($app['city_mun'] ?? '');
			} else if ($service === 'permit_operate') {
				$qr_data = ($app['tradename'] ?? '') . "\nPO-" . $id . "-" . ($app['is_day'] ?? '') . "-" . ($app['is_month'] ?? '') . "-" . ($app['is_year'] ?? '');
			}
			
			if (!empty($qr_data)) {
				QRcode::png($qr_data, $qr_path);
			}
		}
	}

	// Fallback to placeholder if still missing
	if (!file_exists($signature_path)) {
		$signature_path = "images/no_signature.png";
	}	
	if (!file_exists($qr_path)) {
		$qr_path = "images/no_qrcode.png";
	}
	
	$fullname = isset($app['name_1st']) ? trim($app['name_1st'] . " " . ($app['name_mid'] ?? '') . " " . $app['name_fam']) : (isset($user_profile['name_1st']) ? trim($user_profile['name_1st'] . " " . ($user_profile['name_mid'] ?? '') . " " . $user_profile['name_fam']) : '');
	$purok = $app['purok'] ?? '';
	$barangay = $app['barangay'] ?? '';
	$city_mun = $app['city_mun'] ?? 'Tabina';
	$province = $app['province'] ?? 'Zamboanga del Sur';

	$is_id_card = in_array($service, ['senior', 'pwd', 'solo_parent']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Print - <?php echo $allowed_tables[$service]; ?></title>
	<!-- Fonts & Icons -->
	<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<link rel="stylesheet" href="style/print-application.css">
</head>
<body>

	<!-- Toolbar for user navigation (hidden on print) -->
	<div class="no-print-toolbar"><a href="public_dashboard.php" style="text-decoration:none">
		<button class="btn btn-secondary" onclick="window.history.back();">
			<i class="fas fa-arrow-left mr-2"></i>Back to Portal
		</button></a>
		<button class="btn btn-primary" onclick="window.print();">
			<i class="fas fa-print mr-2"></i>Print / Save to PDF
		</button>
	</div>

	<!-- Main print layout viewport container -->
	<div class="print-container">
		<?php if ($is_id_card): ?>
			<!-- Render front and back ID cards side-by-side -->
			<div class="id-card-wrap">
				<!-- FRONT SIDE -->
				<div class="id-card front">
					<!-- Banner header -->
					<div class="card-header-banner theme-<?php echo $service; ?>">
						<img src="images/logo.png" alt="Municipal Seal">
						<div class="title-wrap">
							<h4><?php echo $allowed_tables[$service]; ?></h4>
							<p>Municipality of Tabina, ZDS</p>
						</div>
					</div>
					<!-- Card Body -->
					<div class="card-body-content" style="position:relative">
						<!-- User avatar photo -->
						<div class="avatar-container">
							<img src="<?php echo $avatar_path; ?>" alt="Citizen Photo">
						</div>
						<div class="signature-container">
							<img src="<?php echo $signature_path; ?>?<?php echo time(); ?>">
						</div>
						<div class="signature-text">
							Card Holder Signature
						</div>

						<!-- Details -->
						<div class="details-container">
							<div class="detail-row">
								<h2><?php echo htmlspecialchars($fullname); ?></h2>
								<p><?php echo $cardType[$service]; ?> ID Card</p>
							</div>
							<div class="detail-row" style="font-size:7px">
								<div>
									<span><i class="fa fa-birthday-cake" style="width:10px"></i> Birth of Date: <b><?php echo htmlspecialchars(!empty($app['date_birth']) && $app['date_birth'] !== '0000-00-00' ? date('m/d/Y', strtotime($app['date_birth'])) : 'N/A'); ?></b></span>
								</div>
								<div>
									<span><i class="fa fa-user-md" style="width:10px"></i> Age: <b><?php 
										$age_val = '';
										if (isset($app['age'])) {
											$age_val = $app['age'];
										} elseif (!empty($app['date_birth']) && $app['date_birth'] !== '0000-00-00') {
											$birthDate_arr = explode("-", $app['date_birth']);
											$birth_year = intval($birthDate_arr[0]);
											$birth_month = intval($birthDate_arr[1]);
											$birth_day = intval($birthDate_arr[2]);
											$age_val = (date("md", date("U", mktime(0, 0, 0, $birth_month, $birth_day, $birth_year))) > date("md") ? ((date("Y") - $birth_year) - 0) : (date("Y") - $birth_year));
										}
										echo htmlspecialchars($age_val);
									?></b> Years Old</span>									
								</div>
								<div>
									<span><i class="fa fa-registered" style="width:10px"></i> Date Registered: <b><?php 
										$reg_date = '';
										if (isset($app['assoc_reg_date'])) {
											$reg_date = $app['assoc_reg_date'];
										} elseif (isset($app['date_assoc_reg'])) {
											$reg_date = $app['date_assoc_reg'];
										}
										echo htmlspecialchars(!empty($reg_date) && $reg_date !== '0000-00-00' ? date('m/d/Y', strtotime($reg_date)) : 'N/A');
									?></b></span>									
								</div>
								<div>
									<span><i class="fa fa-check" style="width:10px"></i> Valid Until: <b>June 30, 2027</b></span>									
								</div>
							</div>
							<div class="meta-grid">
								<div class="meta-item">
									<strong>Citizen ID No.</strong>
									<span>#<?php echo str_pad($id, 5, '0', STR_PAD_LEFT); ?></span>
								</div>
								<div class="meta-item">
									<strong>Sex / Civil Status</strong>
									<span><?php echo htmlspecialchars($app['sex'] ?? 'M'); ?> / <?php echo htmlspecialchars($app['civilstatus'] ?? 'Single'); ?></span>
								</div>
								<div class="meta-item" style="grid-column: span 2;">
									<strong>Address</strong>
									<span style="font-size: 7px;"><?php echo htmlspecialchars("Purok " . $purok . ", " . $barangay . ", " . $city_mun); ?>, ZDS</span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- BACK SIDE -->
				<div class="id-card back">
					<p class="back-instructions">
						<b>CONDITIONS:</b> This card is non-transferable and remains the property of the Municipality of Tabina. Any abuse, alteration, or misuse of this card will result in immediate cancellation of benefits. Please report lost cards immediately to the municipal office.
					</p>
					
					<div class="back-middle">
						<div class="emergency-contact-box">
							<h5>In case of emergency:</h5>
							<p style="font-size: 8px; font-weight: 700; color: #111;"><?php echo htmlspecialchars($app['contactperson'] ?? 'LGU Office'); ?></p>
							<p style="font-size: 7px; color: #555;"><?php echo htmlspecialchars($app['relationship'] ?? 'Guardian'); ?></p>
							<p style="font-size: 8px; font-weight: 700; color: #333; margin-top: 3px;"><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($app['emergencyno'] ?? 'N/A'); ?></p>
						</div>
						
						<div class="qr-box">
							<img src="<?php echo $qr_path; ?>" alt="Verification QR Code">
						</div>
					</div>

					<div class="back-signatory-footer">
						<div class="signature-line" style="text-align: center;">
							<img src="images/buletic_sign.png">
							<div style="font-size:8px;margin-bottom:-13px"><b>SARAH O. CABANTE</b></div>
							<span style="border-top: 1px solid #444; font-size: 6px; color: #888;">MSWD Officer</span>
						</div>
						<div class="signature-line">
							<img src="images/mayor_bader.png">
							<div style="font-size:8px;margin-bottom:-13px"><b>HON. JUHAINE A. MALACO</b></div>
							<span style="border-top: 1px solid #444; font-size: 6px;">Municipal Mayor</span>
						</div>
					</div>
				</div>
			</div>

		<?php else: ?>
			<!-- Render certificate/permit document layout -->
			<div class="cert-layout">
				<div class="cert-border-accent">
					
					<!-- Header Letterhead -->
					<div class="cert-header">
						<img src="images/logo.png" class="lgu-seal" alt="LGU Seal">
						<p class="sub-header">Republic of the Philippines</p>
						<p class="sub-header" style="font-weight: 700;">Province of Zamboanga del Sur</p>
						<p class="sub-header" style="font-weight: 700;">Municipality of Tabina</p>
						<h3 class="office-name">Office of the Municipal Mayor</h3>
					</div>

					<!-- Title -->
					<div class="cert-title-container">
						<h1>
							<?php 
								if ($service === 'cert_indigency') echo "Certificate of Indigency";
								elseif ($service === 'clearances') echo "Mayor's Clearance";
								elseif ($service === 'permit_business') echo "Business Permit";
								elseif ($service === 'reg_fishing') echo "Fishing Vessel License";
								elseif ($service === 'permit_operate') echo "Permit to Operate";
							?>
						</h1>
						<hr>
					</div>

					<!-- Body Text Content -->
					<div class="cert-body">
						<p class="salutation">TO WHOM IT MAY CONCERN:</p>
						
						<?php if ($service === 'cert_indigency'): ?>
							<p class="indent">
								This is to certify that <strong><?php echo htmlspecialchars($fullname); ?></strong>, of legal age, civil status <strong><?php echo htmlspecialchars($app['status'] ?? 'Single'); ?></strong>, and a resident of <strong><?php echo htmlspecialchars("Purok " . $purok . ", Barangay " . $barangay . ", Tabina, Zamboanga del Sur"); ?></strong>, is known to this office as belonging to an indigent family in this municipality.
							</p>
							<p class="indent">
								This certification is being issued upon the request of the above-named person for <strong>social welfare assistance, educational support, medical aid</strong>, or any other legal purposes it may serve best.
							</p>
						<?php elseif ($service === 'clearances'): ?>
							<p class="indent">
								This is to certify that <strong><?php echo htmlspecialchars($fullname); ?></strong>, a law-abiding citizen residing at <strong><?php echo htmlspecialchars("Purok " . $purok . ", Barangay " . $barangay . ", Tabina, Zamboanga del Sur"); ?></strong>, has applied for a Mayor's Clearance from this municipality.
							</p>
							<p class="indent">
								Based on local records and agency verification, the subject individual has <strong>no derogatory records</strong> and is of good moral character. This clearance is hereby issued for employment, local business registration, travel, or other valid legal requirements.
							</p>
						<?php elseif ($service === 'permit_business'): ?>
							<p class="indent">
								Pursuant to the Revenue Code of the Municipality of Tabina, a Business Permit is hereby granted to <strong><?php echo htmlspecialchars($app['tradename'] ?? 'LGU Business'); ?></strong>, owned and operated by <strong><?php echo htmlspecialchars($fullname); ?></strong>, located at <strong><?php echo htmlspecialchars("Purok " . $purok . ", Barangay " . $barangay . ", Tabina, Zamboanga del Sur"); ?></strong>.
							</p>
							<p class="indent">
								This permit allows the operation of <strong><?php echo htmlspecialchars($app['activity'] ?? 'General Business'); ?></strong> services within the municipal boundaries, subject to compliance with local ordinances, sanitary regulations, fire safety codes, and business taxation laws.
							</p>
						<?php elseif ($service === 'reg_fishing'): ?>
							<p class="indent">
								In accordance with the Fishery Ordinance of Tabina, license and registration are hereby granted to <strong><?php echo htmlspecialchars($fullname); ?></strong>, owner of the fishing vessel <strong><?php echo htmlspecialchars($app['tradename'] ?? 'Local Vessel'); ?></strong>, registered at <strong><?php echo htmlspecialchars("Purok " . $purok . ", Barangay " . $barangay . ", Tabina"); ?></strong>.
							</p>
							<p class="indent">
								This license permits the operation of municipal fishing activities using approved gears, subject to strict adherence to marine environmental conservation laws and fishery regulations.
							</p>
						<?php elseif ($service === 'permit_operate'): ?>
							<p class="indent">
								A Permit to Operate is hereby officially granted to <strong><?php echo htmlspecialchars($app['tradename'] ?? 'Local Operation'); ?></strong>, represented by <strong><?php echo htmlspecialchars($fullname); ?></strong>, located at <strong><?php echo htmlspecialchars("Purok " . $purok . ", Barangay " . $barangay . ", Tabina"); ?></strong>.
							</p>
							<p class="indent">
								This permit certifies compliance with municipal safety inspectoral procedures, local business policy guidelines, and structural operating criteria for the activity of <strong><?php echo htmlspecialchars($app['activity'] ?? 'General Utility'); ?></strong>.
							</p>
						<?php endif; ?>

						<p class="indent">
							Issued this <strong><?php echo date('jS'); ?></strong> day of <strong><?php echo date('F'); ?></strong>, Year <strong><?php echo date('Y'); ?></strong>, at the Municipal Hall, Tabina, Zamboanga del Sur, Philippines.
						</p>
					</div>

					<!-- Signatory & Verification Footer -->
					<div class="cert-footer">
						<div class="cert-qr-wrap">
							<img src="<?php echo $qr_path; ?>" alt="Verification QR Code">
							<span>Scan to Verify Authenticity<br>ID: #<?php echo str_pad($id, 5, '0', STR_PAD_LEFT); ?></span>
						</div>
						
						<div class="cert-signatory">
							<p class="name">HON. JUHAINE A. MALACO</p>
							<p class="title">Municipal Mayor</p>
						</div>
					</div>

				</div>
			</div>
		<?php endif; ?>
	</div>

	<script>
		// Automatically open printer dialog when loaded
		window.addEventListener('load', function() {
			// Small delay to ensure styles and images (including QR codes) are fully rendered
			setTimeout(function() {
				window.print();
			}, 500);
		});
	</script>
</body>
</html>
