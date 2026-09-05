<?php
	require("connect.php");
	
	$m = "";
	if (isset($_POST["login"])) {
		$user_escaped = mysqli_real_escape_string($link, trim($_POST["user"]));
		$pass_escaped = mysqli_real_escape_string($link, trim($_POST["pass"]));
		
		$ex = $link->query("SELECT * FROM users WHERE username='$user_escaped'");
		if ($rs = mysqli_fetch_array($ex)) {
			// Verify password (supports bcrypt hash and falls back to plain text check)
			$pwd_matches = false;
			if (substr($rs['password'], 0, 4) === '$2y$') {
				$pwd_matches = password_verify($pass_escaped, $rs['password']);
			} else {
				$pwd_matches = ($pass_escaped === $rs['password']);
			}
			
			if ($pwd_matches) {
				$exx = $link->query("SELECT * FROM validity WHERE validity > '".date("Y-m-d")."'");
				if ($rs1 = mysqli_fetch_array($exx)) {
					// Close any active database sessions for the old token on this device
					$old_token_escaped = mysqli_real_escape_string($link, session_id());
					$link->query("UPDATE users_sessions SET logout_time = NOW() WHERE session_token = '$old_token_escaped' AND logout_time IS NULL");

					// Regenerate session ID to prevent session reuse and collisions
					session_regenerate_id(true);

					$_SESSION["uno"]      = $rs[0];
					$_SESSION["user"]     = $rs["username"];
					$_SESSION["pass"]     = $rs["password"];					
					$_SESSION["Fname"]    = $rs["name_1st"];
					$_SESSION["Mname"]    = $rs["name_mid"];
					$_SESSION["Lname"]    = $rs["name_fam"];
					$_SESSION["access"]   = $rs["access"];
					$_SESSION["birth"]    = $rs["date_birth"];
					$_SESSION["email"]    = $rs["email"];
					$_SESSION["phone"]    = $rs["phone"];
					$_SESSION["purok"]    = $rs["purok"];
					$_SESSION["barangay"] = $rs["barangay"];
					$_SESSION["city_mun"] = $rs["city_mun"];
					$_SESSION["imgUrl"]   = $rs["imgUrl"];	
					$_SESSION["fullname"] = trim($rs['name_1st']." ".$rs['name_mid']." ".$rs['name_fam']);

					// Log session start
					$session_token = session_id();
					$_SESSION["session_token"] = $session_token;
					$ip_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
					$username_escaped = mysqli_real_escape_string($link, $rs["username"]);
					$token_escaped = mysqli_real_escape_string($link, $session_token);
					$ip_escaped = mysqli_real_escape_string($link, $ip_address);
					$link->query("INSERT INTO users_sessions (username, session_token, ip_address, login_time) VALUES ('$username_escaped', '$token_escaped', '$ip_escaped', NOW())");
					
					if ($_SESSION['access'] === "Private") {
						echo "<script>window.location='public_dashboard.php';</script>";
						exit;
					}else{
						echo "<script>window.location='index.php';</script>";
						exit;
					}
				} else {
					$m = "
					<div class='alert alert-danger shadow-sm border-0 d-flex align-items-center mb-4' role='alert' style='border-radius: 10px;'>
						<i class='fas fa-exclamation-triangle fa-2x mr-3'></i>
						<div>
							<strong class='d-block'>Access Denied!</strong>
							Your access validity period has expired.
						</div>
					</div>";
				}
			} else {
				$m = "
				<div class='alert alert-danger shadow-sm border-0 mb-4' role='alert' style='border-radius: 10px;text-align:center'>
					<div class='text-secondary text-center'>
						<i class='fa fa-user-lock'></i>
						<strong> &nbsp; Access Denied!</strong> <br><small>Invalid username or password.</small>
					</div>
					<hr class='my-2 border-dark-50'>
					<div class='small text-center text-dark'>
						Forgot Password? <br>
						<a rel='facebox' href='forget_pass_post.php' class='text-warning font-weight-bold text-underline ml-1'>
							<i class='fa fa-envelope'></i> Send Recovery Request
						</a>
					</div><hr>
					<div class='small text-center text-dark'>
						No Account? <br>
						<a href='users_register_public.php' class='text-warning font-weight-bold text-underline ml-1'>
							<i class='fa fa-user'></i> Register Here
						</a>
					</div>
				</div>";
			}
		} else {
			$m = "
			<div class='alert alert-danger shadow-sm border-0 mb-4' role='alert' style='border-radius: 10px;text-align:center'>
				<div class='text-secondary text-center'>
					<i class='fa fa-user-lock'></i>
					<strong> &nbsp; Access Denied!</strong> <br><small>Invalid username or password.</small>
				</div>
				<hr class='my-2 border-dark-50'>
				<div class='small text-center text-dark'>
					Forgot Password? <br>
					<a rel='facebox' href='forget_pass_post.php' class='text-warning font-weight-bold text-underline ml-1'>
						<i class='fa fa-envelope'></i> Send Recovery Request
					</a>
				</div><hr>
				<div class='small text-center text-dark'>
					No Account? <br>
					<a href='users_register_public.php' class='text-warning font-weight-bold text-underline ml-1'>
						<i class='fa fa-user'></i> Register Here
					</a>
				</div>
			</div>";
		}
	}
	
	include("header.php");
?>

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<link href="style/login.css" rel="stylesheet" type="text/css"/>

<!-- Top Navigation Banner -->
<header class="top-nav">
	<div class="container-fluid d-flex align-items-center justify-content-between px-3">
		<div class="d-flex align-items-center">
			<img src="images/logo.webp" height="36" class="mr-3" alt="Municipal Seal">
			<div>
				<b class="text-white" style="font-size: 16px; letter-spacing: 0.5px;">LGU TABINA CCDP</b>
				<span class="badge badge-primary ml-2 px-2 py-1 small">v5.7.4</span>
			</div>
		</div>
		<a href="public_home.php" class="btn btn-sm btn-outline-light d-none d-md-inline-block font-weight-semibold rounded-pill px-3">
			<i class="fas fa-globe mr-1"></i> Public Portal
		</a>
	</div>
</header>

<!-- Central Login Section -->
<div class="login-wrapper">
	<div class="login-card">
		<!-- Left Brand Panel -->
		<div class="login-left">
			<div class="my-auto py-3">
				<img src="images/logo.webp" class="seal-logo mb-3" alt="Tabina Municipal Seal">
				<h4 class="font-weight-bold text-white mb-1" style="letter-spacing: 1px;">LGU INFO SYSTEM
				<p class="small text-light opacity-50 mb-4">Citizen-Centric <x class="thid">Digital</x> Platform</p>
				
				<div class="my-4">
					<a href="public_home.php" class="btn-public btn-block">
						<i class="fas fa-chart-line mr-2"></i>Public Portal
					</a>
				</div>
			</div>

			<div class="small opacity-75">
				<i class="fas fa-shield-alt mr-1"></i> Secure Encrypted Official Portal
			</div>
		</div>

		<!-- Right Form Panel -->
		<div class="login-right" style="background-image: linear-gradient(225deg, rgba(195, 195, 195, 0.04) 0%, rgba(195, 195, 195, 0.04) 25%,rgba(234, 234, 234, 0.04) 25%, rgba(234, 234, 234, 0.04) 50%,rgba(107, 107, 107, 0.04) 50%, rgba(107, 107, 107, 0.04) 75%,rgba(132, 132, 132, 0.04) 75%, rgba(132, 132, 132, 0.04) 100%),linear-gradient(65deg, rgba(116, 116, 116, 0.04) 0%, rgba(116, 116, 116, 0.04) 25%,rgba(219, 219, 219, 0.04) 25%, rgba(219, 219, 219, 0.04) 50%,rgba(33, 33, 33, 0.04) 50%, rgba(33, 33, 33, 0.04) 75%,rgba(165, 165, 165, 0.04) 75%, rgba(165, 165, 165, 0.04) 100%),linear-gradient(251deg, rgba(38, 38, 38, 0.04) 0%, rgba(38, 38, 38, 0.04) 25%,rgba(223, 223, 223, 0.04) 25%, rgba(223, 223, 223, 0.04) 50%,rgba(35, 35, 35, 0.04) 50%, rgba(35, 35, 35, 0.04) 75%,rgba(203, 203, 203, 0.04) 75%, rgba(203, 203, 203, 0.04) 100%),linear-gradient(236deg, rgba(206, 206, 206, 0.04) 0%, rgba(206, 206, 206, 0.04) 25%,rgba(13, 13, 13, 0.04) 25%, rgba(13, 13, 13, 0.04) 50%,rgba(151, 151, 151, 0.04) 50%, rgba(151, 151, 151, 0.04) 75%,rgba(255, 255, 255, 0.04) 75%, rgba(255, 255, 255, 0.04) 100%),linear-gradient(260deg, rgba(133, 133, 133, 0.04) 0%, rgba(133, 133, 133, 0.04) 25%,rgba(169, 169, 169, 0.04) 25%, rgba(169, 169, 169, 0.04) 50%,rgba(91, 91, 91, 0.04) 50%, rgba(91, 91, 91, 0.04) 75%,rgba(74, 74, 74, 0.04) 75%, rgba(74, 74, 74, 0.04) 100%),linear-gradient(90deg, rgb(8, 35, 191),rgb(45, 136, 225));">
			<div class="mb-4">
				<h3 class="font-weight-bold mb-1">Sign In</h3>
				<p class="small">Enter your authorized account <x class="thid">credentials to proceed</x>.</p>
			</div>

			<?php echo $m; ?>

			<form method="post">
				<!-- Username -->
				<div class="input-icon-group">
					<input type="text" name="user" class="form-control form-control-custom" placeholder="Username" required autofocus autocomplete="username">
					<i class="fas fa-user"></i>
				</div>

				<!-- Password -->
				<div class="input-icon-group">
					<input type="password" name="pass" class="form-control form-control-custom" placeholder="Password" required autocomplete="current-password">
					<i class="fas fa-lock"></i>
				</div>

				<!-- Action Submit -->
				<button type="submit" name="login" class="btn btn-primary btn-block btn-login mt-4">
					<i class="fas fa-sign-in-alt mr-2"></i>Log In to System
				</button>
			</form>
		</div>
	</div>
</div>

<?php include("logo_slider2.php");?><br><br><br>

<?php include("footer1.php");?>

</body>

</html>