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
	
	// Generate next SID
	$squery = "SELECT MAX(idn) FROM senior";
	$result = $link->query($squery);
	$values = $result->fetch_array();
	$lastID = $values[0] + 1;
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold text-white">
            <i class="fas fa-user-plus mr-2"></i>Add Senior SID: <b><?php echo $lastID; ?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="senior_add_proc.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="idn" value="0">  
            <input type="hidden" name="province" value="ZDS">   
            <input type="hidden" name="age" value="0">
            <input type="hidden" name="association" value="OSCA">

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

            <!-- Full Name Section -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" required name="name_1st" type="text" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" autofocus />
                </div>
                <div class="col-md-1 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">M.I.</label>
                    <input class="form-control" name="name_mid" type="text" placeholder="M.I." value="<?php echo htmlspecialchars($mid_name); ?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Family Name</label>
                    <input class="form-control" required name="name_fam" type="text" placeholder="Family Name" value="<?php echo htmlspecialchars($fam_name); ?>" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Civil Status</label>
                    <select class="form-control" required name="civilstatus">
                        <option value="" selected>Select Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widower">Widower</option>
                        <option value="Separated">Separated</option>
                    </select>
                </div>
            </div>

            <!-- Birth & Demographics -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Place of Birth</label>
                    <input class="form-control" name="birth_place" type="text" placeholder="Place of Birth" required />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date of Birth</label>
                    <input class="form-control" name="date_birth" type="date" value="<?php echo htmlspecialchars($birth_val); ?>" required />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Sex</label>
                    <select class="form-control" name="sex" required>
                        <option value="" selected>Select Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="form-row mb-3">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Emergency Contact Person</label>
                    <input class="form-control" name="contactperson" type="text" placeholder="Contact Person" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Relationship</label>
                    <select class="form-control" name="relationship">
                        <option value="" selected>Relationship</option>
                        <option value="N/A">N/A</option>
                        <option value="Offspring">Offspring</option>
                        <option value="Sibling">Sibling</option>
                        <option value="Spouse">Spouse</option>
                        <option value="Niece">Niece</option>
                        <option value="Nephew">Nephew</option>
                        <option value="Grandson">Grandson</option>
                        <option value="Granddaughter">Granddaughter</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Contact Number</label>
                    <input class="form-control" name="emergencyno" type="text" placeholder="Contact No." />
                </div>
            </div>

            <!-- Contact & Education & Pensioner -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Email Address</label>
                    <input class="form-control" name="emailadd" type="email" placeholder="Email Address" value="<?php echo htmlspecialchars($email_val); ?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Mobile Phone</label>
                    <input class="form-control" name="mobileno" type="text" placeholder="Mobile Phone" value="<?php echo htmlspecialchars($phone_val); ?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Pensioner Status</label>
                    <select class="form-control" name="pensioner" required>
                        <option value="" selected>Pensioner?</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Educational Attainment</label>
                    <input class="form-control" name="education" type="text" placeholder="Education" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Occupation</label>
                    <input class="form-control" name="occupation" type="text" placeholder="Occupation" />
                </div>
            </div>

            <!-- ID Registrations -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">OSCA ID Number</label>
                    <input class="form-control" name="assoc_id_no" placeholder="OSCA ID Number" value="<?php echo $lastID;?>" readonly />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">OSCA Reg. Date</label>
                    <input class="form-control" name="assoc_reg_date" type="date" required value="<?php echo date('Y-m-d');?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">NCSC-RRN Reg. Number</label>
                    <input class="form-control" name="ncsc_rrn" type="text" placeholder="Enter NCSC-RRN Reg Number" />
                </div>
            </div>
			
            <!-- Interviewer Details -->
            <div class="form-row mb-4">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Interviewer / Encoder</label>
                    <input class="form-control" name="interviewer" type="text" placeholder="Interviewer or Encoder Name" required />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Interview Date</label>
                    <input class="form-control" name="inter_date" type="date" required value="<?php echo date('Y-m-d');?>" />
                    <input name="ispicset" type="hidden" value="0">
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="bSave" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-1"></i> Submit Senior Record
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