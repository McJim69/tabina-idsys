<?php
	require("connect2.php");
	require("header.php");
	require("prefill_name.php");
	
	if (!isset($_SESSION['user'])) {
		echo '
		<script type="text/javascript">
			swal({
				title: "Not Logged In",
				text: "Please login to continue.",
				icon: "warning",
				button: "Login"
			}).then(function() {
				window.location.href = "login.php";
			});
		</script>';
	}
	elseif (empty($_SESSION["purok"]) or empty($_SESSION["phone"])) {
	$edit="\"Edit Profile\"";
		echo '
		<script type="text/javascript">
			swal({
				title: "Incomplete Account",
				text: "Please complete your account details to update. Click the button '.addslashes($edit).' below your profile details to continue.",
				icon: "warning",
				button: "Close"
			}).then(function() {
				window.location.href = "public_dashboard.php";
			});
		</script>';
	}
	
	$startyear = date("Y")-5;
	$endyear=date("Y"); 

	$months=array('','01-Jan','02-Feb','03-Mar','04-Apr','05-May','06-Jun','07-Jul','08-Aug', '09-Sep','10-Oct','11-Nov','12-Dec');				

	$squery = "SELECT MAX(idn) FROM permit_business";
	$result = $link->query($squery);
	$values = $result->fetch_array();
	$lastID = $values[0]+1;
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold text-white">
            <i class="fas fa-store mr-2"></i>Business Permit Form: <b><?php echo $lastID;?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="permit_business_add_proc.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="idn" value="<?php echo $lastID;?>" />
			<input type="hidden" name="province" value="ZDS" />
            
             <!-- Address Section -->
            <div class="form-row mb-3">
				<!-- Municipality -->
				<div class="col-md-4 mb-2">
					<label class="font-weight-bold text-muted small text-uppercase">Municipality</label>
					<select class="form-control" id="city_mun" name="city_mun">
					  <option value="">Municipal</option>
					  <?php
					  $res = $link->query("SELECT DISTINCT city_mun FROM districts ORDER BY city_mun");
					  while ($row = mysqli_fetch_array($res)) {
						echo "<option value='{$row['city_mun']}'>{$row['city_mun']}</option>";
					  }
					  ?>
					</select>
				</div>

				<!-- Barangay -->
				<div class="col-md-4 mb-2">
					<label class="font-weight-bold text-muted small text-uppercase">Barangay</label>
					<select class="form-control" id="barangay" name="barangay">
					  <option value="">Barangay</option>
					</select>
				</div>

				<!-- Purok -->
				<div class="col-md-4 mb-2">
					<label class="font-weight-bold text-muted small text-uppercase">Purok</label>
					<select class="form-control" id="purok" name="purok">
					  <option value="">Purok</option>
					</select>
				</div>
			</div>
			
			<!-- Business Name & Nature -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Business Name</label>
                    <input class="form-control" required name="tradename" type="text" placeholder="Business Trade Name" autofocus />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Nature of Business</label>
                    <input class="form-control" required name="activity" type="text" placeholder="Nature of Business" />
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Mode</label>
                    <select class="form-control" required name="is_mode">
                        <option value="" selected>Mode</option>
                        <option value="New">New</option>
                        <option value="Renewal">Renewal</option>
                    </select>
                </div>
            </div>

            <!-- Applicant Name -->
            <div class="form-row mb-3">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" required name="name_1st" type="text" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" />
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">M.I.</label>
                    <input class="form-control" name="name_mid" placeholder="M.I." maxlength="2" value="<?php echo htmlspecialchars($mid_name); ?>" />
                </div>
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Family Name</label>
                    <input class="form-control" required name="name_fam" type="text" placeholder="Family Name" value="<?php echo htmlspecialchars($fam_name); ?>" />
                </div>
            </div>

            <!-- Filing Date -->
            <div class="form-row mb-3">
                <div class="col-md-12 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">File Date</label>
                    <input class="form-control" name="date_issued" type="date" value="<?php echo date('Y-m-d'); ?>" required />
                </div>
            </div>

            <!-- Receipt & O.R. Date -->
            <div class="form-row mb-4">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">O.R. Number</label>
                    <input class="form-control" required type="text" name="isorno" placeholder="O.R. Number" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Amount Paid</label>
                    <input class="form-control" required type="text" name="oramount" placeholder="Amount Paid" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">O.R. Date</label>
                    <input class="form-control" name="date_or" type="date" value="<?php echo date('Y-m-d'); ?>" required />
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="bSave" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-1"></i> Submit Business Permit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Delegated event handling so it works inside Facebox
document.addEventListener('change', function(e) {
  // Municipal → Barangay
  if (e.target && e.target.id === 'city_mun') {
    let city_mun = e.target.value;
    fetch('get_options.php?type=barangay&city_mun=' + encodeURIComponent(city_mun))
      .then(res => res.json())
      .then(data => {
        let barangaySelect = document.getElementById('barangay');
        barangaySelect.innerHTML = '<option value="">Barangay</option>';
        data.forEach(item => {
          barangaySelect.innerHTML += '<option value="'+item+'">'+item+'</option>';
        });
        document.getElementById('purok').innerHTML = '<option value="">Purok</option>';
      });
  }

  // Barangay → Purok
  if (e.target && e.target.id === 'barangay') {
    let barangay = e.target.value;
    fetch('get_options.php?type=purok&barangay=' + encodeURIComponent(barangay))
      .then(res => res.json())
      .then(data => {
        let purokSelect = document.getElementById('purok');
        purokSelect.innerHTML = '<option value="">Purok</option>';
        data.forEach(item => {
          purokSelect.innerHTML += '<option value="'+item+'">'+item+'</option>';
        });
      });
  }
});
</script>
<?php require("prefill_address.php"); ?>