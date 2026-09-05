<?php 
	require_once('connect.php');
	require('header.php');
	require('menu.php');	
?>

<script>setActive('home');</script>

<body style="background:#f8f9fa">

<link href="fonts/style.css" rel="stylesheet" type="text/css"/>

<div class="container py-4" style="margin-top:50px">
	<!-- Modern Sophisticated Header Banner -->
	<div class="card shadow-sm border-0 mb-4" style="background: linear-gradient(135deg, #113f67 0%, #38598b 100%); color: #ffffff; border-radius: 16px; overflow: hidden; position: relative;">
		<!-- Subtle decorative background bubbles -->
		<div style="position: absolute; width: 150px; height: 150px; background: rgba(255, 255, 255, 0.05); border-radius: 50%; top: -50px; left: -50px;"></div>
		<div style="position: absolute; width: 250px; height: 250px; background: rgba(255, 255, 255, 0.03); border-radius: 50%; bottom: -100px; right: -50px;"></div>
		
		<div class="card-body py-4 px-4">
			<?php if (($_SESSION["access"])=="Private"){ echo "<script>window.location='public_dashboard.php';</script>";?>
			<?php } else if (($_SESSION["access"])=="Senior"){ ?>
				<a href="public_home.php">
				<div class="d-flex align-items-center justify-content-center flex-wrap">
					<!-- Logo -->
					<div class="p-2 bg-white rounded-circle shadow-sm mr-md-4 mb-3 mb-md-0" style="width: 75px; height: 75px; display: flex; align-items: center; justify-content: center; border: 3px solid rgba(255,255,255,0.25);">
						<img src="images/osca_logo_tabina.png?<?php date("h:i:s");?>" alt="Seal" style="max-height: 73px; max-width: 73px;" />
					</div>
					<!-- Title Text -->
					<div class="text-center text-md-left">
						<h2 class="mb-1 font-weight-bold text-white text-uppercase" style="letter-spacing: 1px; font-size: 24px; text-shadow: 0 2px 4px rgba(0,0,0,0.15);">
							Office of Senior Citizens Affair
						</h2>
						<p class="mb-0 small" style="color: #cbd5e0; font-size: 14px; letter-spacing: 0.5px;">
							<i class="fas fa-landmark mr-1 text-warning"></i> Municipality of Tabina, Zamboanga del Sur, 7034 PH
						</p>
					</div>
				</div></a>
				
			<?php } else if (($_SESSION["access"])=="Welfare"){ ?>
				<a href="public_home.php">
				<div class="d-flex align-items-center justify-content-center flex-wrap">
					<!-- Logo -->
					<div class="p-2 bg-white rounded-circle shadow-sm mr-md-4 mb-3 mb-md-0" style="width: 75px; height: 75px; display: flex; align-items: center; justify-content: center; border: 3px solid rgba(255,255,255,0.25);">
						<img src="images/DSWD.jpg" alt="Seal" style="max-height: 50px; max-width: 50px;" />
					</div>
					<!-- Title Text -->
					<div class="text-center text-md-left">
						<h2 class="mb-1 font-weight-bold text-white text-uppercase" style="letter-spacing: 1px; font-size: 24px; text-shadow: 0 2px 4px rgba(0,0,0,0.15);">
							Municipal Social Services Division
						</h2>
						<p class="mb-0 small" style="color: #cbd5e0; font-size: 14px; letter-spacing: 0.5px;">
							<i class="fas fa-landmark mr-1 text-warning"></i>Municipality of Tabina, Zamboanga del Sur, 7034 PH
						</p>
					</div>
				</div></a>
				
			<?php } else { ?>
				<a href="public_home.php">
				<div class="d-flex align-items-center justify-content-center flex-wrap">
					<!-- Logo -->
					<div class="p-2 bg-white rounded-circle shadow-sm mr-md-4 mb-3 mb-md-0" style="width: 75px; height: 75px; display: flex; align-items: center; justify-content: center; border: 3px solid rgba(255,255,255,0.25);">
						<img src="images/favicon.png" alt="Seal" style="max-height: 75px; max-width: 75px;" />
					</div>
					<!-- Title Text -->
					<div class="text-center text-md-left">
						<h2 class="mb-1 font-weight-bold text-white text-uppercase" style="letter-spacing: 1px; font-size: 24px; text-shadow: 0 2px 4px rgba(0,0,0,0.15);">
							Citizen-Centric Digital Platform
						</h2>
						<p class="mb-0 small" style="color: #cbd5e0; font-size: 14px; letter-spacing: 0.5px;">
							<i class="fas fa-landmark mr-1 text-warning"></i> Local Government Unit of Tabina, Zamboanga del Sur, 7034 PH
						</p>
					</div>
				</div></a>
			<?php } ?>
		</div>
	</div>

	<!-- Login Status Alert Banner -->
	<div class="row justify-content-center mb-4">
		<div class="col-md-8 col-lg-6">
			<div class="card shadow-lg border-0 rounded-lg">
				<div class="card-body py-2 px-3 d-flex align-items-center justify-content-between flex-wrap">
					<div class="d-flex align-items-center my-1">
						<a rel="facebox" href="users_session_edit.php" style="text-decoration:none" title="Show Profile">						
						<?php
							if(file_exists("images/users/".$_SESSION["imgUrl"]) && !empty($_SESSION["imgUrl"])): 
								echo "<img src='images/users/".$_SESSION["imgUrl"]."' style='border:1px solid #bbb;width:35px;height:35px;border-radius:50%;padding:0;margin:0'/>";
							else: 
								echo"<img src='images/blank.jpg' style='border:1px solid #bbb;width:35px;height:35px;border-radius:50%;padding:0;margin:0'/>";
							endif; 						
						?>
						<strong class="text-dark small mr-1"> &nbsp;<?php echo $_SESSION['user']; ?></strong>
						<span class="badge badge-primary"><?php echo $_SESSION['access']; ?></span>
						</a>
					</div>
					<div class="my-1">
						<button type="button" onclick="sessionEnd('gid')" class="btn btn-sm btn-link text-danger font-weight-bold p-0 border-0" style="text-decoration:none;">
							<i class="fas fa-sign-out-alt mr-1"></i>Logout?
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
		<div style="text-aling:center">
			<h2 class="font-weight-bold text-dark mb-1">
				<i class="fas fa-cog mr-2 text-primary"></i>Dashboard
			</h2>
		</div>
	</div>

	<!-- Main Dashboard -->
	<div class="row">
		<div class="col-12">
			<?php include("dashboard.php");?>
		</div>
	</div>
</div>

</body>

<?php include("footer.php");?>	

</html>

<script type='text/javascript'>
	function sessionEnd(gid){	
		if(confirm("Are you sure you want to Logout?")){
			window.location.href = 'logout.php';
		}
	}	
</script>