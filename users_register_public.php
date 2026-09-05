<?php 
	require("connect2.php");
	require("header.php");
?>

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
						<i class="fas fa-chart-line mr-2"></i>Public Page
					</a>
				</div>
			</div>

			<div class="small opacity-75">
				<i class="fas fa-shield-alt mr-1"></i> Secure Encrypted Official Portal
			</div>
		</div>

		<!-- Right Form Panel -->
		<div class="login-right" style="background-image: linear-gradient(225deg, rgba(195, 195, 195, 0.04) 0%, rgba(195, 195, 195, 0.04) 25%,rgba(234, 234, 234, 0.04) 25%, rgba(234, 234, 234, 0.04) 50%,rgba(107, 107, 107, 0.04) 50%, rgba(107, 107, 107, 0.04) 75%,rgba(132, 132, 132, 0.04) 75%, rgba(132, 132, 132, 0.04) 100%),linear-gradient(65deg, rgba(116, 116, 116, 0.04) 0%, rgba(116, 116, 116, 0.04) 25%,rgba(219, 219, 219, 0.04) 25%, rgba(219, 219, 219, 0.04) 50%,rgba(33, 33, 33, 0.04) 50%, rgba(33, 33, 33, 0.04) 75%,rgba(165, 165, 165, 0.04) 75%, rgba(165, 165, 165, 0.04) 100%),linear-gradient(251deg, rgba(38, 38, 38, 0.04) 0%, rgba(38, 38, 38, 0.04) 25%,rgba(223, 223, 223, 0.04) 25%, rgba(223, 223, 223, 0.04) 50%,rgba(35, 35, 35, 0.04) 50%, rgba(35, 35, 35, 0.04) 75%,rgba(203, 203, 203, 0.04) 75%, rgba(203, 203, 203, 0.04) 100%),linear-gradient(236deg, rgba(206, 206, 206, 0.04) 0%, rgba(206, 206, 206, 0.04) 25%,rgba(13, 13, 13, 0.04) 25%, rgba(13, 13, 13, 0.04) 50%,rgba(151, 151, 151, 0.04) 50%, rgba(151, 151, 151, 0.04) 75%,rgba(255, 255, 255, 0.04) 75%, rgba(255, 255, 255, 0.04) 100%),linear-gradient(260deg, rgba(133, 133, 133, 0.04) 0%, rgba(133, 133, 133, 0.04) 25%,rgba(169, 169, 169, 0.04) 25%, rgba(169, 169, 169, 0.04) 50%,rgba(91, 91, 91, 0.04) 50%, rgba(91, 91, 91, 0.04) 75%,rgba(74, 74, 74, 0.04) 75%, rgba(74, 74, 74, 0.04) 100%),linear-gradient(90deg, rgb(8, 35, 191),rgb(45, 136, 225));">
			<div class="mb-4 text-white">
				<h3 class="font-weight-bold mb-1">Create Account</h3>
				<p class="small">Register your account to continue <x class="thid">avail our services</x>.</p>
			</div>
			<form action="users_register_public_proc.php" method="post" enctype="multipart/form-data" class="mb-0">
			<input type="hidden" name="uno" value="$uid" />  
			<input type="hidden" name="access" value="Private" />            
			<input type="hidden" name="city_mun" value="" />            
			<input type="hidden" name="barangay" value="" />            
			<input type="hidden" name="purok" value="" />            

            <!-- Full Name -->
			<div class="form-row">
				<div class="col-md-5">
					<div class="form-group text-white">
						<label>First Name</label>
						<input class="form-control" type="text" name="name_1st" placeholder="First Name" required style="margin-top:-5px">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group text-white">
						<label>M.I.</label>
						<select class="form-control" type="text" name="name_mid" style="margin-top:-5px">
                        <option value="" <?php if (empty($mid_name)) echo 'selected'; ?>>MI</option>
                        <?php foreach(range('A','Z') as $char){ 
                            $sel = ($mid_name === $char || strpos($mid_name, $char) === 0) ? 'selected' : '';
                            echo "<option value='$char' $sel>$char</option>"; 
                        } ?>
						</select>
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group text-white">
						<label>Family Name</label>
						<input class="form-control" type="text" name="name_fam" placeholder="Fullname" required style="margin-top:-5px">
					</div>
				</div>
			</div>
            <!-- Username / Password -->
			<div class="form-row">
				<div class="col-md-6">			
					<div class="form-group text-white">
						<label>Username</label>
						<input class="form-control" type="text" name="username" placeholder="Username" required style="margin-top:-5px">
					</div>
				</div>
				<div class="col-md-6">			
					<div class="form-group text-white">
						<label>Password</label>
						<input class="form-control" type="text" name="password" placeholder="Password" required style="margin-top:-5px">
					</div>
				</div>
			</div>
			<!-- Contact / Birthdate -->
			<div class="form-row">
				<div class="col-md-6">
					<div class="form-group text-white">
						<label>Phone Number</label>
						<input class="form-control" type="text" name="phone" placeholder="Phone Number" required style="margin-top:-5px">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group text-white">
						<label>Email Address</label>
						<input class="form-control" type="email" name="email" placeholder="Email Address" required style="margin-top:-5px">
					</div>
				</div>
			</div>
            <!-- Birth Date / Profile Picture -->
			<div class="form-row">
				<div class="col-md-6">
					<div class="form-group text-white">
						<label>Birth Date</label>
						<input class="form-control" onfocus="(this.type='date')" name="date_birth" placeholder="Birth Date" required style="margin-top:-5px">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group text-white">
						<label>Profile Picture</label>
						<input type="file" name="image" id="b_file" style="display:none" 
							   onchange="document.getElementById('fileBtn').value = this.files.length ? this.files[0].name : 'Select Picture';">
						<input class="form-control" id="fileBtn" value="Select Picture" 
							   onclick="document.getElementById('b_file').click();" required style="margin-top:-5px">
					</div>
				</div>
			</div>
            <!-- Button Action Footer -->
            <div class="d-flex align-items-center justify-content-center mt-2">
                <button type="submit" name="submit" value="Submit" class="btn btn-success rounded-pill font-weight-bold px-4 shadow-sm">
                    <i class="fas fa-check-circle mr-1"></i>Register Account
                </button>
            </div>
			</form>
		</div>
	</div>
</div>

<?php include("footer1.php");?>

</body>

</html>