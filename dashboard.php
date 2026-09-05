<link href="style/dashboard.css?v=2.0.8" rel="stylesheet" type="text/css"/>

<?php  if ((($_SESSION["access"])=="Welfare")|| (($_SESSION["access"])=="Administrator")){ ?>
	<!-- Page Header -->
	<div class="col-12">
		<h3 class="dashboard-section-title">Social Services</h3>
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-blue">
					<h5 class="text-center"><i class="fa fa-person-cane"></i> SENIOR CITIZEN</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from senior");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="senior_grid.php"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="senior_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-green">
					<h5 class="text-center"><i class="fa fa-users"></i> INDIGENTS (4PS)</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from indigents");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="indigents_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="indigents_add_form.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>					
				</div>
			</div>
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-orange">
					<h5 class="text-center"><i class="fa fa-users"></i> SAP BENIFECIARY</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from sap_ben");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="sap_ben_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="sap_ben_add_form.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>										
				</div>
			</div>			
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-teal">
					<h5 class="text-center"><i class="fa fa-home"></i> Households</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from households");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="households_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a href="households_add_form.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>										
				</div>
			</div>		
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-indigo">
					<h5 class="text-center"><i class="fa fa-wheelchair"></i> PWD</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from pwd");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="pwd_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="pwd_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-purple">
					<h5 class="text-center"><i class="fa fa-child"></i> Daycare</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from kinder");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="kinder_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="kinder_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>					
				</div>
			</div>
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-rose">
					<h5 class="text-center"><i class="fa fa-user"></i> Solo Parent</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from solo_parent");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="solo_parent_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="solo_parent_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>										
				</div>
			</div>	
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-cyan">
					<h5 class="text-center"><i class="fa fa-certificate"></i> Indigent Cerificate</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from cert_indigency");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="cert_indigency_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="cert_indigency_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>															
				</div>
			</div>			
		</div>
	</div>
<?php } ?>
	
<?php  if ((($_SESSION["access"])=="Executive") || (($_SESSION["access"])=="Administrator")){  ?>

	<div class="col-12 mt-2">
		<h3 class="dashboard-section-title">Executive Services</h3>
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-blue">
					<h5 class="text-center"><i class="fa fa-award"></i> Mayor Clearance</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from clearances");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="mayor_clearance_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="mayor_clearance_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-green">
					<h5 class="text-center"><i class="fa fa-fish"></i> Fishing Permit</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from reg_fishing");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="reg_fishing_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="reg_fishing_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>					
				</div>
			</div>
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-orange">
					<h5 class="text-center"><i class="fa fa-motorcycle"></i> Permit to Operate</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from permit_operate");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="permit_operate_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="permit_operate_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>										
				</div>
			</div>			
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-teal">
					<h5 class="text-center"><i class="fa fa-bank"></i> Business Permit</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from permit_business");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="permit_business_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="permit_business_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>										
				</div>
			</div>		
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-indigo">
					<h5 class="text-center"><i class="fa fa-arrow-down"></i> Incoming Mail</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from messages");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="messages_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="messages_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-purple">
					<h5 class="text-center"><i class="fa fa-arrow-up"></i> Outgoing Mail</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from msgout");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="msgout_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="msgout_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>					
				</div>
			</div>
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-rose">
					<h5 class="text-center"><i class="fa fa-book"></i> Guest Log</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from visitors");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="visitors_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="visitors_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>										
				</div>
			</div>	
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="dashboard-card card-cyan">
					<h5 class="text-center"><i class="fa fa-id-card"></i> Employee ID SYS</h5>
					<?php					
						$counts=mysqli_query($link,"SELECT COUNT(*) as total from employees");
						$data=mysqli_fetch_assoc($counts);
					?>
					<div class="d-flex align-items-center justify-content-between w-100 mt-2">
						<a href="employees_grid.php" class="dashboard-icon-btn"><i class="fa fa-folder-open icon-btn" title="Open"></i></a>
						<span class="count-badge">
							<?php echo number_format($data['total']); ?>
						</span>
						<a rel="facebox" href="employees_add.php" class="dashboard-icon-btn"><i class="fa fa-plus icon-btn" title="Add"></i></a>
					</div>															
				</div>
			</div>			
		</div>
	</div>
	
<?php } ?>

<?php  if (($_SESSION["access"])=="Administrator"){ ?>

	<div class="col-12 mt-2">
		<h3 class="dashboard-section-title">System Administration</h3>
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="admin-card">
					<a href="backup.php">
						<i class="fa fa-database icon-btn text-primary" title="Backup"></i>&nbsp;
						BACKUP
					</a>
				</div>
			</div>		
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="admin-card">
					<a href="users_grid.php">
						<i class="fa fa-users icon-btn text-primary" title="Admins"></i>&nbsp;
						ADMINS
					</a>
				</div>
			</div>		
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="admin-card" title="Message Board">
					<a href="audit_trail.php">
						<i class="fa fa-cog icon-btn text-primary" aria-hidden="true"></i>&nbsp;
						SYSLOG
					</a>
				</div>
			</div>		
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="admin-card" onclick="sessionEnd('gid')" title="Logout" title="Logout">
						<i class="fa fa-sign-out icon-btn text-primary" aria-hidden="true"></i>&nbsp;
						LOGOUT
				</div>
			</div>		
		</div>	
	</div>	
<?php 
//	include("social_statistics3.php");
//	include("households_analytics2.php");
//	include("employees_analytics2.php");
//	include("revenue_analytics2.php");
//	include("senior_statistics2.php");
	} 
?>

<?php  
	if (($_SESSION["access"]) == "Administrator") { 
		echo"";

	} elseif (($_SESSION["access"]) == "Welfare") { 
		include("social_statistics2.php");	
		include("households_analytics2.php");
		include("senior_statistics2.php");
		
	} elseif (($_SESSION["access"]) == "Senior") { 
?> 
	<div class="col-12 mt-2">
		<div class="row" style="margin-top:-33px">
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="admin-card">
					<a href="senior_grid.php" class="btn btn-outline-primary mr-2">
						<i class="fas fa-th mr-1"></i> CARD VIEW
					</a>
				</div>
			</div>								
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="admin-card">
					<a href="senior_list.php" class="btn btn-outline-primary mr-2">
						<i class="fa fa-list mr-1"></i> LIST VIEW
					</a>
				</div>
			</div>		
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="admin-card">
					<a href="senior_list_80up.php" class="btn btn-outline-primary mr-2">
						<i class="fa fa-wheelchair mr-1"></i> SENIOR 80 UP
					</a>
				</div>
			</div>		
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<div class="admin-card">
					<a rel="facebox" href="senior_add.php" class="btn btn-outline-primary mr-2">
						<i class="fa fa-plus mr-1"></i> ADD SENIOR
					</a>
				</div>
			</div>		
		</div>
	</div>

<?php 
	include("senior_statistics2.php");
	} elseif ((($_SESSION["access"])=="Guest") || (($_SESSION["access"])=="Employees")){ 
	echo'
	<div class="container d-flex justify-content-center align-items-center">
		<div class="row">
			<div class="col-lg-12">
				<div class="admin-card" style="border:1px solid #bbb">
					<a rel="facebox" href="visitors_add.php" class="btn btn-outline-primary mr-2">
						<i class="fas fa-plus mr-1"></i> ADD Guest Details <x class="thid"> &nbsp; for Certificate of Appearance Purposes (CA)</x>
					</a>
				</div>
			</div>								
		</div>
	</div>';	
	include("visitors_stats2.php");

	} elseif (($_SESSION["access"]) == "Enumerator") { 	
	include("households_analytics2.php");

	} else{ 
	include("social_statistics3.php");
	include("households_analytics2.php");
	include("employees_analytics2.php");
	include("revenue_analytics2.php");
	}
?>
