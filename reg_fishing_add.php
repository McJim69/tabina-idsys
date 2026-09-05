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

	$squery = "SELECT MAX(idn) FROM reg_fishing";
	$result = $link->query($squery);
	$values = $result->fetch_array();
	$lastID = $values[0]+1;
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold text-white">
            <i class="fas fa-ship mr-2"></i>MFVR Form - System ID: <b><?php echo $lastID;?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="reg_fishing_add_proc.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="idn" value="<?php echo $lastID;?>" />
			<input type="hidden" name="province" value="ZDS" />
            <!-- Owner Full Name -->
            <div class="form-row mb-3">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" required name="name_1st" type="text" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" autofocus />
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

            <!-- Registration Type & Date -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Registration Type</label>
                    <select class="form-control" required name="regtype">
                        <option value="" selected>Select Registration</option>
                        <option value="Initial">Initial</option>				
                        <option value="New CN">New CN</option>
                        <option value="Renew CN">Renew CN</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Registration Date</label>
                    <input class="form-control" name="date_issued" type="date" value="<?php echo date('Y-m-d'); ?>" required />
                </div>
            </div>

            <!-- Vessel Details -->
            <div class="form-row mb-3">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Vessel Trade Name</label>
                    <input class="form-control" required name="tradename" type="text" placeholder="Vessel Name" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Vessel Type</label>
                    <select class="form-control" required name="fvtype">
                        <option value="" selected>Vessel Type</option>
                        <option value="Motorized">Motorized</option>
                        <option value="Non-Motorized">Non-Motorized</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">FV Color</label>
                    <input class="form-control" required name="fvcolor" type="text" placeholder="FV Color" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Service Type</label>
                    <select class="form-control" required name="service_type">
                        <option value="" selected>Service Type</option>
                        <option value="Private">Private</option>				
                        <option value="Fishing">Fishing</option>
                        <option value="For Hire">For Hire</option>
                        <option value="Multipurpose">Multipurpose</option>
                    </select>
                </div>
            </div>

            <!-- Built Details -->
            <div class="form-row mb-3">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Builder Name</label>
                    <input class="form-control" type="text" name="builder" placeholder="Builder Name" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Place Built</label>
                    <input class="form-control" required type="text" name="build_place" placeholder="Place Built" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Year Built</label>
                    <select class="form-control" required name="build_year">
                        <option value=''>Year</option>
                        <?php for($i=$startyear;$i<=$endyear;$i++){ echo "<option value='$i'>$i</option>"; } ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Hull Material</label>
                    <select class="form-control" required name="build_hull">
                        <option value="" selected>Hull Material</option>
                        <option value="Wood">Wood</option>				
                        <option value="Steel">Steel</option>
                        <option value="Fiber">Fiber</option>
                        <option value="Plastic">Plastic</option>
                        <option value="Combine">Combine</option>
                    </select>
                </div>
            </div>

            <!-- Dimensions & Engine -->
            <div class="form-row mb-3">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Length (Mtrs)</label>
                    <input class="form-control" required type="text" name="lenght" placeholder="Length" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Breadth (Mtrs)</label>
                    <input class="form-control" required type="text" name="breadth" placeholder="Breadth" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Depth (Mtrs)</label>
                    <input class="form-control" required type="text" name="depth" placeholder="Depth" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Home Port</label>
                    <input class="form-control" required type="text" name="homeport" placeholder="Home Port" />
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Engine Make</label>
                    <input class="form-control" required type="text" name="enginemake" placeholder="Engine Make" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Engine S/N</label>
                    <input class="form-control" type="text" name="enginesn" placeholder="Engine SN" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Horse Power</label>
                    <input class="form-control" required type="text" name="enginehp" placeholder="Horse Power" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">No. of Cylinders</label>
                    <input class="form-control" required type="text" name="engcylinder" placeholder="Cylinders" />
                </div>
            </div>

            <!-- Crew & Gear & O.R. -->
            <div class="form-row mb-4">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">No. of Crew</label>
                    <input class="form-control" required type="text" name="crewno" placeholder="Crew Count" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Fishing Gear</label>
                    <input class="form-control" required type="text" name="gearused" placeholder="Gear Used" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">O.R. Number</label>
                    <input class="form-control" required type="text" name="isorno" placeholder="O.R. No." />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Amount Paid</label>
                    <input class="form-control" required type="text" name="oramount" placeholder="Amount Paid" />
                </div>
            </div>

            <input type="hidden" name="engineno" value="1" />
            <input type="hidden" name="former_owner" value="" />
            <input type="hidden" name="former_vname" value="" />
            <input type="hidden" name="description" value="Fishing Boat" />
            <input type="hidden" name="coastgno" value="" />
            <input type="hidden" name="date_or" value="<?php echo date('Y-m-d');?>" />

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="bSave" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-1"></i> Submit Vessel Registry
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