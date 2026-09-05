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
	$startyear = date("Y")-100;
	$endyear=date("Y"); 

	$months=array('','01-Jan','02-Feb','03-Mar','04-Apr','05-May','06-Jun','07-Jul','08-Aug', '09-Sep','10-Oct','11-Nov','12-Dec');					

	$squery = "SELECT MAX(idn) FROM pwd";
	$result = $link->query($squery);
	$values = $result->fetch_array();
	$lastID = $values[0]+1;
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold text-white">
            <i class="fas fa-wheelchair mr-2"></i>Add PWD - SID: <b><?php echo $lastID;?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="pwd_add_proc.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="idn" value="<?php echo $lastID;?>"/>
			<input type="hidden" name="province" value="ZDS"/>

            <!-- Full Name Section -->
            <div class="form-row mb-3">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" required name="name_1st" type="text" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" autofocus />
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">M.I.</label>
                    <select class="form-control" name="name_mid">
                        <option value="" <?php if (empty($mid_name)) echo 'selected'; ?>>MI</option>
                        <?php foreach(range('A','Z') as $char){ 
                            $sel = ($mid_name === $char || strpos($mid_name, $char) === 0) ? 'selected' : '';
                            echo "<option value='$char' $sel>$char</option>"; 
                        } ?>
                    </select>
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
            <!-- Disability & Sex & Civil Status -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Type of Disability</label>
                    <select class="form-control" required name="disability">
                        <option value="" selected>Select Disability</option>
                        <option value="Psychosocial">Psychosocial</option>
                        <option value="Chronic Illness">Chronic Illness</option>
                        <option value="Learning">Learning Disability</option>
                        <option value="Visual">Visual Disability</option>
                        <option value="Orthopedic">Orthopedic (Musculoskeletal)</option>
                        <option value="Mental">Mental/Intellectual</option>
                        <option value="Hearing">Hearing Disability</option>
                        <option value="Speech">Speech Impairment</option>
                        <option value="Multiple">Multiple Disabilities</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Sex</label>
                    <select class="form-control" required name="sex">
                        <option value="" selected>Select Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Civil Status</label>
                    <select class="form-control" required name="civilstatus">
                        <option value="" selected>Civil Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widower">Widower</option>
                        <option value="Separated">Separated</option>
                    </select>
                </div>
            </div>

            <!-- Birth Date & Birth Place -->
            <div class="form-row mb-3">
                <div class="col-md-8 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date of Birth</label>
                    <input class="form-control" type="date" required name="date_birth" value="<?php echo (!empty($bday_year) && !empty($bday_month) && !empty($bday_day)) ? sprintf('%04d-%02d-%02d', $bday_year, $bday_month, $bday_day) : ''; ?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Place of Birth</label>
                    <input class="form-control" name="birth_place" type="text" placeholder="Place of Birth" required />
                </div>
            </div>

            <!-- Contact Information & Association -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Email Address</label>
                    <input class="form-control" name="emailadd" type="email" placeholder="Email Address" value="<?php echo htmlspecialchars($email_val); ?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Mobile Phone Number</label>
                    <input class="form-control" name="mobileno" type="text" placeholder="Mobile Phone" value="<?php echo htmlspecialchars($phone_val); ?>" />
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Association Name</label>
                    <input class="form-control" name="association" type="text" placeholder="Association Name" required />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Position</label>
                    <select class="form-control" name="position">
                        <option value="" selected>Select Position</option>
                        <option value="President">President</option>
                        <option value="Vice President">Vice President</option>
                        <option value="Secretary">Secretary</option>
                        <option value="Treasurer">Treasurer</option>
                        <option value="Auditor">Auditor</option>
                        <option value="Business Manager">Business Manager</option>
                        <option value="P.I.O.">P.I.O.</option>
                        <option value="P.R.O.">P.R.O.</option>
                        <option value="Sgt. at Arms">Sgt. at Arms</option>
                        <option value="Member">Member</option>
                    </select>
                </div>
            </div>

            <!-- Education & Occupation -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Educational Attainment</label>
                    <input class="form-control" name="education" type="text" placeholder="Educational Attainment" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Occupational Skills</label>
                    <input class="form-control" name="occupation" type="text" placeholder="Occupational Skills" />
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
                    <select class="form-control" required name="relationship">
                        <option value="" selected>Select Relation</option>
                        <option value="Spouse">Spouse</option>
                        <option value="Father">Father</option>
                        <option value="Mother">Mother</option>
                        <option value="Sister">Sister</option>
                        <option value="Brother">Brother</option>
                        <option value="Relative">Relative</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Contact Number</label>
                    <input class="form-control" name="emergencyno" type="text" placeholder="Emergency Contact No." />
                </div>
            </div>

            <!-- Interviewer Details -->
            <div class="form-row mb-4">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Name of Interviewer</label>
                    <input class="form-control" required name="interviewer" type="text" placeholder="Name of Interviewer" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Interview Date</label>
                    <input class="form-control" name="date_interview" type="date" value="<?php echo date('Y-m-d'); ?>" required />
                </div>
            </div>

            <!-- Buttons Action Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="bSave" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-1"></i> Submit PWD Record
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