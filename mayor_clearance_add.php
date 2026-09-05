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

	$squery = "SELECT MAX(idn) FROM clearances";
	$result = $link->query($squery);
	$values = $result->fetch_array();
	$lastID = $values[0]+1;
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold text-white">
            <i class="fas fa-file-signature mr-2"></i>Mayor's Clearance (MC) Submit Form - ID: <b><?php echo $lastID;?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="mayor_clearance_add_proc.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="idn" value="<?php echo $lastID;?>" />
            <input type="hidden" name="province" value="Zamboanga del Sur"/>

            <!-- Applicant Full Name -->
            <div class="form-row mb-3">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" required name="name_1st" type="text" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" autofocus />
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">M.I.</label>
                    <select class="form-control" name="name_mid" />
                </div>
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Family Name</label>
                    <input class="form-control" required name="name_fam" type="text" placeholder="Family Name" value="<?php echo htmlspecialchars($fam_name); ?>" />
                </div>
            </div>

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
			
            <!-- Demographics -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Sex</label>
                    <select class="form-control" required name="sex">
                        <option value="" selected>Select Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Civil Status</label>
                    <select class="form-control" required name="civil_status">
                        <option value="" selected>Select Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widower">Widower</option>
                        <option value="Separated">Separated</option>
                    </select>
                </div>
            </div>

            <!-- Issue Date -->
            <div class="form-row mb-3">
                <div class="col-md-12 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Issue Date</label>
                    <input class="form-control" name="date_issued" type="date" value="<?php echo date('Y-m-d'); ?>" required />
                </div>
            </div>

            <!-- Receipt Information -->
            <div class="form-row mb-4">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">O.R. Number</label>
                    <input class="form-control" required type="text" name="issued_orno" placeholder="O.R. Number" />
                    <input type="hidden" name="issued_ormun" value="Tabina" />
                    <input type="hidden" name="issued_orprov" value="ZDS" />
                    <input type="hidden" name="ispicset" value="0" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">O.R. Month</label>
                    <select class="form-control" required name="issued_ormonth">
                        <option value=''>Month</option>
                        <?php for($i=1;$i<=12;$i++){ echo "<option value='$i'>$months[$i]</option>"; } ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">O.R. Day</label>
                    <select class="form-control" required name="issued_orday">
                        <option value=''>Day</option>
                        <?php for($i=1;$i<=31;$i++){ echo "<option value='$i'>$i</option>"; } ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">O.R. Year</label>
                    <select class="form-control" required name="issued_oryear">
                        <option value=''>Year</option>
                        <?php for($i=$startyear;$i<=$endyear;$i++){ echo "<option value='$i'>$i</option>"; } ?>
                    </select>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="bSave" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-1"></i> Submit Clearance
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