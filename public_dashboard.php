<?php
	require_once("connect2.php");
	require_once("header.php");
	
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

	if (isset($_SESSION['user'])) {
		$welco = "Mabuhay,";
	} else {
		$welco = "Mabuhay, Tabinians";
	}

	$birthDate = $_SESSION["date_birth"];;
	$birthDate = explode("-", $birthDate);
	$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-0):(date("Y")-$birthDate[0]));

	$post_msg_alert = '';
	if (isset($_POST['citizen_post_submit'])) {
		$Name = trim($_POST['name'] ?? '');
		$Email = trim($_POST['email'] ?? '');
		$Attention = trim($_POST['attention'] ?? 'All Citizens');
		$Subject = trim($_POST['subject'] ?? '');
		$Message = trim($_POST['message'] ?? '');

		if ($Subject === '' || $Message === '') {
			$post_msg_alert = "
			<div class='alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3' role='alert' style='border-radius:10px;'>
				<strong>Error!</strong> Subject and message content cannot be empty.
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>&times;</span>
				</button>
			</div>";
		} else {
			$Name = $Name === '' ? $_SESSION['fullname'] : $Name;
			$Email = $Email === '' ? $_SESSION['user'] . '@tabina.gov.ph' : $Email;
			$combined_content = $Subject . " ~ " . $Message;

			$stmt = $link->prepare("INSERT INTO message_board (msgb_from, msgb_email, msgb_attnto, msgb_content) VALUES (?, ?, ?, ?)");
			$stmt->bind_param("ssss", $Name, $Email, $Attention, $combined_content);
			
			if ($stmt->execute()) {
				$post_msg_alert = "
				<div class='alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3' role='alert' style='border-radius:10px;'>
					<strong>Success!</strong> Your post has been published to the community message board.
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
					</button>
				</div>";
			} else {
				$post_msg_alert = "
				<div class='alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3' role='alert' style='border-radius:10px;'>
					<strong>Error!</strong> There was an issue saving your post to the database.
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
					</button>
				</div>";
			}
			$stmt->close();
		}
	}
?>

<link href="style/public_dashboard.css?v=2.0.8" rel="stylesheet" type="text/css"/>

<div style="padding:15px">
<div class="container-fluid px-0">
    <!-- Premium Greeting Hero -->
	
    <div class="card citizen-hero-card mb-4 shadow-sm"><a style="text-decoration:none" href="public_home.php">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div style="z-index: 2; position: relative;">
                <span class="badge badge-pill badge-warning px-3 py-2 font-weight-bold mb-3 small" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24;">
                    <i class="fas fa-id-card mr-1"></i>Citizen Portal
                </span> &nbsp; 
                <span class="badge badge-pill badge-warning px-3 py-2 text-info font-weight-bold mb-3 small" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24;">
                    <i class="fas fa-id-card mr-1"></i>Back to Home
                </span>
                <h1 class="font-weight-bold text-white mb-2" style="font-size: 2rem; letter-spacing: -0.5px;">
                   <?php echo $welco; ?> <?php echo htmlspecialchars($_SESSION['fullname']); ?>!
                </h1>
                <p class="mb-0 text-white-50 small" style="max-width: 650px; line-height: 1.5;">
                    Welcome to your Tabina Local Government Unit digital dashboard. From here you can access online social welfare registrations, request clearances, post to the message board, and join real-time chat rooms.
                </p>
            </div>
            <div class="ml-auto d-none d-lg-block" style="opacity: 0.3; z-index: 1;">
                <img src="images/logo.png" height="130px"/>
            </div>
        </div></a>
    </div>

    <?php echo $post_msg_alert; ?>

    <!-- Main Grid Layout -->
    <div class="row">
        
        <!-- ========================================== -->
        <!-- LEFT SIDEBAR: PROFILE DETAILS & EDIT       -->
        <!-- ========================================== -->
        <div class="col-lg-3 col-md-4 mb-4">
		<?php if (isset($_SESSION['user'])) { ?>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-weight-bold mb-0 text-dark" style="font-size: 16px; letter-spacing: -0.2px;">
                    <i class="fas fa-user mr-2 text-primary"></i>User Profile
                </h5>
				<span onclick="sessionEnd('gid')" type="button" style="font-weight:bold;margin-right:5px;cursor:pointer"><i class="fa fa-sign-out text-primary"></i> Logout</span>
            </div>
            <div class="card profile-sidebar-card shadow-sm border-0 mb-4">
                <!-- Cover color block -->
                <div class="profile-cover"></div>
                <!-- User Avatar -->
				<a rel="facebox" href="users_session_edit.php">
                <div class="profile-avatar-wrapper">
				<?php
					if(file_exists("images/users/".$_SESSION["imgUrl"]) && !empty($_SESSION["imgUrl"])): 
						echo "<img src='images/users/".$_SESSION["imgUrl"]."?".time()."' class='profile-avatar' alt='Avatar'>";
					else: 
						echo"<img src='images/blank.jpg' class='profile-avatar' alt='Avatar'>";
					endif; 						
				?>
                </div></a>
                <!-- Profile details -->
                <div class="card-body text-center pt-2 px-3 pb-4">
                    <h5 class="font-weight-bold text-dark mb-1" style="font-size: 16px;"><?php echo htmlspecialchars($_SESSION['fullname']); ?></h5>
                    <p class="text-muted small mb-2">
						<?php
							if (empty($_SESSION['email']))
							echo"@".htmlspecialchars($_SESSION['user']).""; 
							else
							echo"".htmlspecialchars($_SESSION['email']).""; 
						?>
					</p>
                    <span class="badge badge-primary px-3 py-1 mb-4" style="border-radius: 10px; font-size: 11px;">
                        <i class="fas fa-user-tag mr-1"></i><?php echo $_SESSION['access']; ?>
                    </span>
                    
                    <hr class="my-3 opacity-50">
                    
                    <div class="text-left mb-4">
                        <div class="mb-2">
                            <span class="text-muted d-block small" style="font-size: 10px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Username</span>
                            <span class="text-dark font-weight-bold small"><?php echo htmlspecialchars($_SESSION['user']); ?></span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted d-block small" style="font-size: 10px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Contact Number</span>
                            <span class="text-dark text-lowercase font-weight-bold small"><?php echo $_SESSION['phone']; ?></span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted d-block small" style="font-size: 10px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Birthdate / Age</span>
                            <span class="text-dark font-weight-bold small"><?php echo $_SESSION['birth']; ?> / <?php echo $age; ?> Years Old</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted d-block small" style="font-size: 10px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Home Address</span>
                            <span class="text-dark font-weight-bold small"><?php echo $_SESSION['purok']; ?>, <?php echo $_SESSION['barangay']; ?>, <?php echo $_SESSION['city_mun']; ?>, ZDS</span>
                        </div>
                        <div>
                            <span class="text-muted d-block small" style="font-size: 10px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">System Access</span>
                            <span class="text-success font-weight-bold small"><i class="fas fa-check-circle mr-1"></i>Granted as <?php echo $_SESSION['access']; ?></span>
                        </div>
                    </div>
                    
                    <a rel="facebox" href="users_session_edit.php" class="btn btn-outline-primary btn-block btn-sm font-weight-bold py-2" style="border-radius: 10px;">
                        <i class="fas fa-user-edit mr-1"></i>Edit Profile
                    </a>
                </div>
            </div>
            
            <!-- Quick Link / System Stats -->
            <div class="card profile-sidebar-card shadow-sm border-0 p-3 mb-4">
                <h6 class="font-weight-bold text-dark mb-3" style="font-size: 13.5px;"><i class="fas fa-info-circle mr-2 text-info"></i>Citizen Quick Info</h6>
                <div class="small text-secondary">
                    <p class="mb-2">To update your address, age, or contact information, click the <b>Edit Profile</b> button above.</p>
                    <p class="mb-0">For issues with registrations, you may consult the Welfare officers directly in the Lobby chat room.</p>
                </div>
            </div>
			
			<?php } else { ?>
			
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-weight-bold mb-0 text-dark" style="font-size: 16px; letter-spacing: -0.2px;">
					<div type="button" style="font-weight:bold;margin-left:5px;cursor:pointer">Register or <span onclick="jump('login.php')">Login <i class="fa fa-sign-in"></i></span></div>            
				</h5>
            </div>
			<div class="card profile-sidebar-card shadow-sm border-0 mb-4">
				<div class="card mb-4 shadow-sm border-0 bg-primary quick-chat-banner">
					<a href="users_register_public.php" class="card-body p-3 d-flex align-items-center text-white text-decoration-none">
						<div class="mr-3 bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 18px;">
							<i class="fa fa-check"></i>
						</div>
						<div>
							<h5 class="font-weight-bold text-white mb-0" style="font-size: 14px;">Register Here</h5>
						</div>
					</a>
				</div>
				<div class="card-body pt-2 px-3 pb-4">
					<form action="users_register_public_proc.php" method="post" enctype="multipart/form-data" class="mb-0">
						<input type="hidden" name="access" value="Private">
						<input type="hidden" name="city_mun" value="">
						<input type="hidden" name="barangay" value="">
						<input type="hidden" name="purok" value="">
						<div class="form-row">
						  <div class="col-12">
							<div class="form-group">
							  <label><i class="fas fa-id-card mr-1"></i> Full Name</label>
							  <input class="form-control form-control-custom" type="text" name="fullname" placeholder="Fullname" required>
							</div>
						  </div>
						</div>
						<div class="form-row">
						  <div class="col-12 col-sm-6">
							<div class="form-group">
							  <label><i class="fas fa-user mr-1"></i> Username</label>
							  <input class="form-control" type="text" name="username" placeholder="Username" required>
							</div>
						  </div>
						  <div class="col-12 col-sm-6">
							<div class="form-group">
							  <label><i class="fas fa-lock mr-1"></i> Password</label>
							  <input class="form-control" type="password" name="password" placeholder="Password" required>
							</div>
						  </div>
						</div>
						<div class="form-row">
						  <div class="col-12 col-sm-6">
							<div class="form-group">
							  <label><i class="fas fa-envelope mr-1"></i> Email</label>
							  <input class="form-control" type="email" name="email" placeholder="Email (Optional)">
							</div>
						  </div>
						  <div class="col-12 col-sm-6">
							<div class="form-group">
							  <label><i class="fas fa-phone mr-1"></i> Phone</label>
							  <input class="form-control" type="text" name="phone" placeholder="Phone" required>
							</div>
						  </div>
						</div>
						<div class="form-row">
						  <div class="col-12 col-sm-6">
							<div class="form-group">
							  <label><i class="fas fa-calendar-alt mr-1"></i> Birthdate</label>
							  <input class="form-control" onfocus="(this.type='date')" name="birth" placeholder="Birthdate" required>
							</div>
						  </div>
						  <div class="col-12 col-sm-6">
							<div class="form-group">
							  <label><i class="fas fa-camera mr-1"></i> Profile Photo</label>
							  <input type="file" name="image" id="b_file" style="display:none" 
									 onchange="document.getElementById('fileBtn').value = this.files.length ? this.files[0].name : 'Select Picture';">
							  <input class="form-control" id="fileBtn" value="Select Picture" 
									 onclick="document.getElementById('b_file').click();" required>
							</div>
						  </div>
						</div>
						<div class="d-flex align-items-center justify-content-center" style="padding-top:10px">  
						  <button type="submit" name="submit" value="Submit" 
								  class="btn btn-success rounded-pill font-weight-bold px-4 shadow-sm">
							<i class="fas fa-check-circle mr-1"></i>Submit
						  </button>
						</div>
					</form>
				</div>
			</div>
            
			<!-- Quick Link / System Stats -->
			<div class="card profile-sidebar-card shadow-sm border-0 p-3 mb-4">
				<h6 class="font-weight-bold text-dark mb-3" style="font-size: 13.5px;"><i class="fas fa-info-circle mr-2 text-info"></i>Citizen Quick Info</h6>
				<div class="small text-secondary">
					<p class="mb-2">To continue our services please register or login if you have already have an account.</p>
					<p class="mb-0">For issues with registrations, you may consult the web administrators directly in the Lobby chat room.</p>
				</div>
			</div>			
			
			<?php } ?>
			
        </div>

        <!-- ========================================== -->
        <!-- CENTER PANEL: ONLINE SERVICES GRID         -->
        <!-- ========================================== -->
        <div class="col-lg-6 col-md-8 mb-4">
			
			<?php if (isset($_SESSION['uno'])): ?>
			<!-- Your Application(s) Section -->
			<div class="d-flex align-items-center justify-content-between mb-3">
				<h5 class="font-weight-bold mb-0 text-dark" style="font-size: 16px; letter-spacing: -0.2px;">
					<i class="fas fa-file-alt mr-2 text-primary"></i>Your Application(s)
				</h5>
			</div>
			
			<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
				<div class="card-body p-0">
					<?php
					$me_esc = mysqli_real_escape_string($link, $_SESSION['uno']);
					$app_query = "
						SELECT idn, 'OSCA Senior Citizen' AS service, status, timestamp FROM senior WHERE user_id = '$me_esc'
						UNION ALL
						SELECT idn, 'PWD Registration' AS service, status, timestamp FROM pwd WHERE user_id = '$me_esc'
						UNION ALL
						SELECT idn, 'Solo Parent Card' AS service, status, timestamp FROM solo_parent WHERE user_id = '$me_esc'
						UNION ALL
						SELECT idn, 'Indigent Certificate' AS service, app_status AS status, timestamp FROM cert_indigency WHERE user_id = '$me_esc'
						UNION ALL
						SELECT idn, 'Mayor\'s Clearance' AS service, status, timestamp FROM clearances WHERE user_id = '$me_esc'
						UNION ALL
						SELECT idn, 'Business Permit' AS service, status, timestamp FROM permit_business WHERE user_id = '$me_esc'
						UNION ALL
						SELECT idn, 'Fishing Boat Permit' AS service, status, timestamp FROM reg_fishing WHERE user_id = '$me_esc'
						UNION ALL
						SELECT idn, 'Permit to Operate' AS service, status, timestamp FROM permit_operate WHERE user_id = '$me_esc'
						ORDER BY timestamp DESC
					";
					$app_res = $link->query($app_query);
					if ($app_res && $app_res->num_rows > 0):
						$apps_data = [];
						while ($row = mysqli_fetch_array($app_res)) {
							$apps_data[] = $row;
						}
					?>
					<div class="table-responsive">
						<table class="table table-hover mb-0" style="font-size: 13px;">
							<thead class="bg-light text-secondary">
								<tr>
									<th class="border-0 py-3 px-4">Service</th>
									<th class="border-0 py-3 px-3">App ID</th>
									<th class="border-0 py-3 px-3">Date Applied</th>
									<th class="border-0 py-3 px-4 text-center">Status</th>
									<th class="border-0 py-3 px-4 text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($apps_data as $app): 
									$status_label = ucfirst(strtolower($app['status']));
									$badge_class = 'badge-secondary';
									if ($status_label === 'Pending') {
										$badge_class = 'badge-warning text-dark';
									} elseif ($status_label === 'Approved') {
										$badge_class = 'badge-success';
									} elseif ($status_label === 'Denied') {
										$badge_class = 'badge-danger';
									}
									$formatted_date = date('M d, Y h:i A', strtotime($app['timestamp']));
									
									$edit_url = '';
									$service_table = '';
									if ($app['service'] === 'OSCA Senior Citizen') {
										$service_table = 'senior';
										$edit_url = 'senior_edit_form.php?senior=' . urlencode($app['idn']);
									} elseif ($app['service'] === 'PWD Registration') {
										$service_table = 'pwd';
										$edit_url = 'pwd_edit_form.php?pwd=' . urlencode($app['idn']);
									} elseif ($app['service'] === 'Solo Parent Card') {
										$service_table = 'solo_parent';
										$edit_url = 'solo_parent_edit_form.php?solo_parent=' . urlencode($app['idn']);
									} elseif ($app['service'] === 'Indigent Certificate') {
										$service_table = 'cert_indigency';
										$edit_url = 'cert_indigency_edit_form.php?cert_indigency=' . urlencode($app['idn']);
									} elseif ($app['service'] === 'Mayor\'s Clearance') {
										$service_table = 'clearances';
										$edit_url = 'mayor_clearance_edit_form.php?clearances=' . urlencode($app['idn']);
									} elseif ($app['service'] === 'Business Permit') {
										$service_table = 'permit_business';
										$edit_url = 'permit_business_edit_form.php?permit_business=' . urlencode($app['idn']);
									} elseif ($app['service'] === 'Fishing Boat Permit') {
										$service_table = 'reg_fishing';
										$edit_url = 'reg_fishing_edit_form.php?reg_fishing=' . urlencode($app['idn']);
									} elseif ($app['service'] === 'Permit to Operate') {
										$service_table = 'permit_operate';
										$edit_url = 'permit_operate_edit_form.php?permit_operate=' . urlencode($app['idn']);
									}
								?>
								<tr>
									<td class="py-3 px-4 font-weight-bold text-dark"><?php echo htmlspecialchars($app['service']); ?></td>
									<td class="py-3 px-3 text-secondary font-weight-semibold">#<?php echo htmlspecialchars($app['idn']); ?></td>
									<td class="py-3 px-3 text-muted"><?php echo $formatted_date; ?></td>
									<td class="py-3 px-4 text-center">
										<span class="badge <?php echo $badge_class; ?> px-3 py-1 font-weight-bold" style="border-radius: 20px; font-size: 11px;">
											<?php echo htmlspecialchars($status_label); ?>
										</span>
									</td>
									<td class="py-3 px-4 text-center">
										<?php if ($status_label === 'Approved'): ?>
											<a href="print_application.php?service=<?php echo urlencode($service_table); ?>&id=<?php echo urlencode($app['idn']); ?>" target="_blank" class="btn btn-sm btn-outline-success py-1 px-3" style="border-radius: 20px; font-size: 11px; font-weight: 700;">
												<i class="fas fa-print mr-1"></i>Print
											</a>
										<?php elseif ($status_label === 'Pending' && $edit_url): ?>
											<a rel="facebox" href="<?php echo $edit_url; ?>" class="btn btn-sm btn-outline-primary py-1 px-3" style="border-radius: 20px; font-size: 11px; font-weight: 700;">
												<i class="fas fa-edit mr-1"></i>Edit
											</a>
										<?php else: ?>
											<span class="text-muted small">-</span>
										<?php endif; ?>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<div class="p-4 text-center text-muted">
						<i class="fas fa-folder-open mb-3 text-muted" style="font-size: 32px; opacity: 0.5;"></i>
						<p class="mb-0 small">You have not submitted any applications yet.</p>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; 
				if (!isset($_SESSION['user'])) 
				$required='<span style="padding-right:5px;color:orange"><small>( Login Required )</small></span>';
			?>

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-weight-bold mb-0 text-dark" style="font-size: 16px; letter-spacing: -0.2px;">
                    <i class="fas fa-hands-helping mr-2 text-primary"></i>Online LGU Services
                </h5>
				<?php echo $required;?>
			</div>

            <div class="row">
                <!-- 1. OSCA Senior Citizen Registration -->
                <div class="col-md-6 mb-3">
                    <a rel="facebox" href="senior_add.php" class="text-decoration-none">
                        <div class="card service-card p-3">
                            <div>
                                <div class="service-icon-wrapper bg-primary text-white">
                                    <i class="fas fa-blind"></i>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13.5px;">OSCA Application</h6>
                                <p class="text-muted small mb-0" style="font-size: 11px;">Register as a Senior Citizen to receive local pension benefits and support cards.</p>
                            </div>
                            <div class="text-right mt-3">
                                <span class="badge badge-light border text-primary font-weight-bold" style="font-size: 10px;">Apply &rarr;</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- 2. PWD ID Application -->
                <div class="col-md-6 mb-3">
                    <a rel="facebox" href="pwd_add.php" class="text-decoration-none">
                        <div class="card service-card p-3">
                            <div>
                                <div class="service-icon-wrapper bg-success text-white">
                                    <i class="fas fa-wheelchair"></i>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13.5px;">PWD Registration</h6>
                                <p class="text-muted small mb-0" style="font-size: 11px;">Apply for Person With Disability registry cards to receive national discounts.</p>
                            </div>
                            <div class="text-right mt-3">
                                <span class="badge badge-light border text-success font-weight-bold" style="font-size: 10px;">Apply &rarr;</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- 3. Solo Parent Card -->
                <div class="col-md-6 mb-3">
                    <a rel="facebox" href="solo_parent_add.php" class="text-decoration-none">
                        <div class="card service-card p-3">
                            <div>
                                <div class="service-icon-wrapper bg-info text-white">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13.5px;">Solo Parent Card</h6>
                                <p class="text-muted small mb-0" style="font-size: 11px;">Apply for the Solo Parent support program for social safety subsidies.</p>
                            </div>
                            <div class="text-right mt-3">
                                <span class="badge badge-light border text-info font-weight-bold" style="font-size: 10px;">Apply &rarr;</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- 4. Indigent Certificate -->
                <div class="col-md-6 mb-3">
                    <a rel="facebox" href="cert_indigency_add.php" class="text-decoration-none">
                        <div class="card service-card p-3">
                            <div>
                                <div class="service-icon-wrapper bg-warning text-dark">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13.5px;">Certificate of Indigency</h6>
                                <p class="text-muted small mb-0" style="font-size: 11px;">Request a verified LGU Certificate of Indigency for scholarship/medical aid.</p>
                            </div>
                            <div class="text-right mt-3">
                                <span class="badge badge-light border text-warning font-weight-bold" style="font-size: 10px; color: #d97706 !important;">Apply &rarr;</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- 5. Mayor's Clearance -->
                <div class="col-md-6 mb-3">
                    <a rel="facebox" href="mayor_clearance_add.php" class="text-decoration-none">
                        <div class="card service-card p-3">
                            <div>
                                <div class="service-icon-wrapper bg-danger text-white">
                                    <i class="fas fa-balance-scale"></i>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13.5px;">Mayor's Clearance</h6>
                                <p class="text-muted small mb-0" style="font-size: 11px;">Apply for a Mayor's Clearance certificate for employment requirements.</p>
                            </div>
                            <div class="text-right mt-3">
                                <span class="badge badge-light border text-danger font-weight-bold" style="font-size: 10px;">Apply &rarr;</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- 6. Business Permit -->
                <div class="col-md-6 mb-3">
                    <a rel="facebox" href="permit_business_add.php" class="text-decoration-none">
                        <div class="card service-card p-3">
                            <div>
                                <div class="service-icon-wrapper bg-secondary text-white">
                                    <i class="fas fa-store"></i>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13.5px;">Business Permit</h6>
                                <p class="text-muted small mb-0" style="font-size: 11px;">Register a new commercial business establishment and acquire permit tags.</p>
                            </div>
                            <div class="text-right mt-3">
                                <span class="badge badge-light border text-secondary font-weight-bold" style="font-size: 10px;">Apply &rarr;</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- 7. Fishing Boat Permit -->
                <div class="col-md-6 mb-3">
                    <a rel="facebox" href="reg_fishing_add.php" class="text-decoration-none">
                        <div class="card service-card p-3">
                            <div>
                                <div class="service-icon-wrapper bg-teal text-white" style="background-color: #0d9488 !important;">
                                    <i class="fas fa-ship"></i>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13.5px;">Fishing Boat Permit</h6>
                                <p class="text-muted small mb-0" style="font-size: 11px;">Register municipal fishing vessels, gears, and licensing approvals.</p>
                            </div>
                            <div class="text-right mt-3">
                                <span class="badge badge-light border text-success font-weight-bold" style="font-size: 10px; color: #0d9488 !important;">Apply &rarr;</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- 8. Permit to Operate -->
                <div class="col-md-6 mb-3">
                    <a rel="facebox" href="permit_operate_add.php" class="text-decoration-none">
                        <div class="card service-card p-3">
                            <div>
                                <div class="service-icon-wrapper bg-dark text-white">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13.5px;">Permit to Operate</h6>
                                <p class="text-muted small mb-0" style="font-size: 11px;">Apply for operational safety tags and municipal construction approvals.</p>
                            </div>
                            <div class="text-right mt-3">
                                <span class="badge badge-light border text-dark font-weight-bold" style="font-size: 10px;">Apply &rarr;</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- RIGHT SIDEBAR: MESSAGE BOARD & MESSAGING   -->
        <!-- ========================================== -->
        <div class="col-lg-3 col-md-12 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-weight-bold mb-0 text-dark" style="font-size: 16px; letter-spacing: -0.2px;">
                    <i class="fas fa-bullhorn mr-2 text-primary"></i>Public Bulletin
                </h5>
				<?php echo $required;?>		
            </div>
            <!-- Real-time Live Messenger Shortcut -->
            <div class="card mb-4 shadow-sm border-0 bg-primary quick-chat-banner">
                <a href="messenger.php" class="card-body p-3 d-flex align-items-center text-white text-decoration-none">
                    <div class="mr-3 bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 18px;">
                        <i class="fab fa-facebook-messenger"></i>
                    </div>
                    <div>
                        <h6 class="font-weight-bold text-white mb-0" style="font-size: 14px;">Live Chatroom</h6>
                        <p class="mb-0 text-white-50 small" style="font-size: 10.5px;">Open Messenger Portal</p>
                    </div>
                </a>
            </div>

            <!-- Write Announcement Post Card -->
            <div class="card right-sidebar-card shadow-sm p-3 mb-4">
                <h6 class="font-weight-bold text-dark mb-3" style="font-size: 14px;">
                    <i class="fas fa-pen-fancy text-primary mr-2"></i>Post News &bull; Notice &bull; Enquiry
                </h6>
                <?php if (isset($_SESSION['user'])) 
					echo'<form action="post_message_proc.php" method="post" class="mb-0">';
					else
					echo'<form action="session_status.php" method="post" class="mb-0">';
				?>
					<input type="hidden" name="name"  value="<?php echo htmlspecialchars($_SESSION['fullname']);?>">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['user']);?>@tabina.zambosur.net" class="text-lowercase">
                    <div class="form-group mb-2">
                        <label class="small font-weight-bold text-muted mb-1" style="font-size: 10.5px;">Attention:</label>
                        <input class="form-control form-control-citizen py-1" type="text" name="attention" placeholder="Message to..." required style="font-size: 12px; height:32px;">
                    </div>
                    <div class="form-group mb-2">
                        <label class="small font-weight-bold text-muted mb-1" style="font-size: 10.5px;">Subject:</label>
                        <input class="form-control form-control-citizen py-1" type="text" name="subject" required placeholder="Subject heading..." style="font-size: 12px; height:32px;">
                    </div>
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted mb-1" style="font-size: 10.5px;">Message:</label>
                        <textarea class="form-control form-control-citizen" name="message" rows="2" required placeholder="Write post message..." style="font-size: 12px;"></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block btn-sm font-weight-bold py-2 shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-paper-plane mr-1"></i>Publish Bulletin
                    </button>
                </form>
            </div>

            <!-- Message Board Live Feed -->
            <div class="card right-sidebar-card shadow-sm p-3">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h6 class="font-weight-bold text-dark mb-0" style="font-size: 14px;">
                        <i class="fas fa-bullhorn text-warning mr-2"></i>LGU Message Board
                    </h6>
					<?php if (!isset($_SESSION['user'])) { ?>
                    <a href="message_board_public.php" class="small font-weight-bold text-primary" style="font-size: 12px;">View All</a>
					<?php }else{ ?>	
                    <a href="message_board.php" class="small font-weight-bold text-primary" style="font-size: 12px;">View All</a>					
					<?php } ?>
				</div>

                <div class="announcements-feed">
                    <?php
                    $res = $link->query("SELECT * FROM message_board ORDER BY mbid DESC LIMIT 3");
                    if (!$res || $res->num_rows == 0) {
                        echo "<p class='text-muted small text-center my-3'>No announcements have been posted yet.</p>";
                    } else {
                        while ($row = $res->fetch_assoc()) {
                            $sender = htmlspecialchars($row['msgb_from'] ?? '');
                            $attention = htmlspecialchars($row['msgb_attnto'] ?? '');
                            
                            $combined = $row['msgb_content'] ?? '';
                            $parts = explode(" ~ ", $combined, 2);
                            $subject = htmlspecialchars($parts[0] ?? '');
                            $msg = htmlspecialchars($parts[1] ?? '');
                            
                            $date = date('l d.M.Y h:i A', strtotime($row['msgb_date']));

                            echo "<div class='border-bottom mb-3 pb-2'>";
                            echo "  <div class='d-flex align-items-center justify-content-between mb-1'>";
                            echo "    <span class='font-weight-bold text-primary' style='font-size: 11.5px;'>$sender</span>";
                            echo "    <span class='badge-attention bg-light border text-danger'>To: $attention</span>";
                            echo "  </div>";
                            echo "  <h6 class='font-weight-bold mb-1 text-dark' style='font-size: 12px;'>$subject</h6>";
                            echo "  <p class='text-secondary small mb-1' style='line-height: 1.3; font-size:11px; display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;'>$msg</p>";
                            echo "  <span class='text-muted' style='font-size: 9px;'><i class='far fa-clock mr-1'></i>$date</span>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>  
    </div>
</div></div>
<br>
<?php include_once("footer1.php");?>

<script type="text/javascript">
	function toggleTheme() {
		var currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
		var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
		
		if (newTheme === 'dark') {
			document.documentElement.setAttribute('data-theme', 'dark');
			localStorage.setItem('theme', 'dark');
			var icon = document.getElementById('theme-toggle-icon');
			if (icon) {
				icon.classList.remove('fa-moon');
				icon.classList.add('fa-sun');
			}
		} else {
			document.documentElement.removeAttribute('data-theme');
			localStorage.setItem('theme', 'light');
			var icon = document.getElementById('theme-toggle-icon');
			if (icon) {
				icon.classList.remove('fa-sun');
				icon.classList.add('fa-moon');
			}
		}
	}
</script>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		// Sync the dark mode button icon state on page load
		var initialTheme = localStorage.getItem('theme') || 'dark';
		var toggleIcon = document.getElementById('theme-toggle-icon');
		if (toggleIcon) {
			if (initialTheme === 'dark') {
				toggleIcon.classList.remove('fa-moon');
				toggleIcon.classList.add('fa-sun');
			} else {
				toggleIcon.classList.remove('fa-sun');
				toggleIcon.classList.add('fa-moon');
			}
		}

		// Dynamically add has-sub class to all li elements that contain a ul (submenus)
		$('#cssmenu li').has('ul').addClass('has-sub');

		// Clean up legacy text arrow symbols so CSS chevrons can style them cleanly
		$('#cssmenu a').each(function() {
			var html = $(this).html();
			html = html.replace(/▼|&#9660;|►|&#9658;/g, '');
			$(this).html(html);
		});

		// Insert responsive menu button dynamically if not present
		if ($('#menu-button').length === 0) {
			$('#cssmenu').prepend('<div id="menu-button">Menu</div>');
		}

		// Toggle top-level menu collapse (mobile)
		$('#menu-button').on('click', function(e) {
			e.stopPropagation();
			var menu = $(this).next('ul');
			if (menu.hasClass('open')) {
				menu.removeClass('open').hide();
				$(this).removeClass('menu-opened');
			} else {
				menu.addClass('open').show();
				$(this).addClass('menu-opened');
			}
		});

		// Auto hide menu when clicking/tapping outside or scrolling on mobile
		$(document).on('click touchstart', function(e) {
			if (!$(e.target).closest('#cssmenu').length) {
				var menu = $('#menu-button').next('ul');
				if (menu.hasClass('open')) {
					menu.removeClass('open').hide();
					$('#menu-button').removeClass('menu-opened');
				}
			}
		});

		$(window).on('scroll', function() {
			var menu = $('#menu-button').next('ul');
			if (menu.hasClass('open')) {
				menu.removeClass('open').hide();
				$('#menu-button').removeClass('menu-opened');
			}
		});

		// Setup submenus
		$('#cssmenu li.has-sub').prepend('<span class="submenu-button"></span>');
		$('#cssmenu li.has-sub .submenu-button').on('click', function() {
			$(this).toggleClass('submenu-opened');
			var submenu = $(this).siblings('ul');
			if (submenu.hasClass('open')) {
				submenu.removeClass('open').hide();
			} else {
				submenu.addClass('open').show();
			}
		});
	});

	function sessionEnd(gid){	
		if(confirm("Are you sure you want to Logout?")){
			window.location.href = 'logout.php';
		}
	}
</script>

<script>
jQuery(document).ready(function($) {
    <?php if (isset($apps_data) && !empty($apps_data)): ?>
    (function() {
        let apps = <?php echo json_encode($apps_data); ?>;
        let storedStatuses = JSON.parse(localStorage.getItem('lgu_app_statuses') || '{}');
        let updatedStatuses = {...storedStatuses};
        let alertsToShow = [];

        apps.forEach(function(app) {
            let key = app.service + '_' + app.idn;
            let currentStatus = app.status;

            if (storedStatuses[key] !== undefined) {
                if (storedStatuses[key] !== currentStatus) {
                    alertsToShow.push({
                        service: app.service,
                        status: currentStatus,
                        id: app.idn
                    });
                }
            }
            updatedStatuses[key] = currentStatus;
        });

        localStorage.setItem('lgu_app_statuses', JSON.stringify(updatedStatuses));

        // Display alerts sequentially using SweetAlert
        if (alertsToShow.length > 0) {
            function showNextAlert(index) {
                if (index >= alertsToShow.length) return;
                let alertInfo = alertsToShow[index];
                let title = "";
                let text = "";
                let icon = "";

                if (alertInfo.status === 'Approved') {
                    title = "Application Approved!";
                    text = "Your registration/permit for " + alertInfo.service + " (#" + alertInfo.id + ") has been approved. You can now print it from your dashboard!";
                    icon = "success";
                } else if (alertInfo.status === 'Denied') {
                    title = "Application Denied";
                    text = "Your registration/permit for " + alertInfo.service + " (#" + alertInfo.id + ") has been denied. Please review details or contact support.";
                    icon = "warning";
                } else {
                    title = "Application Updated";
                    text = "The status of your application for " + alertInfo.service + " (#" + alertInfo.id + ") is now " + alertInfo.status + ".";
                    icon = "info";
                }

                swal({
                    title: title,
                    text: text,
                    icon: icon,
                    button: "Close"
                }).then(function() {
                    showNextAlert(index + 1);
                });
            }
            showNextAlert(0);
        }
    })();
    <?php endif; ?>
});
</script>

</body>

</html>
