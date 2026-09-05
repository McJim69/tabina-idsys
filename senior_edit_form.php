<?php 
	require("connect.php"); 

	$rec=1;
	$p=isset($_GET['page']) ? $_GET['page'] : 1;
	if($p>1){
		$to=$rec;
		$from=($p*$rec)-$rec;
	}else{
		$to=$rec;
		$from=0;
		$p=1;
	}			
			
	$mem="";
	if(isset($_GET["senior"]) && $_GET["senior"]!="")
		$mem=" and idn='".$_GET["senior"]."' ";
												
	$ex = $link->query("select * from senior where idn=idn $mem order by idn limit $from,$to ");

	while($rs = mysqli_fetch_array($ex)){
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-edit mr-2"></i>Edit Senior - ID: <b><?php echo $rs[0];?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="senior_edit_proc.php" method="POST">
            <input type="hidden" name="idn" value="<?php echo $rs[0];?>"/>

            <!-- Full Name Section -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" required name="name_1st" type="text" value="<?php echo htmlspecialchars($rs["name_1st"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Middle Name</label>
                    <input class="form-control" name="name_mid" type="text" value="<?php echo htmlspecialchars($rs["name_mid"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Family Name</label>
                    <input class="form-control" required name="name_fam" type="text" value="<?php echo htmlspecialchars($rs["name_fam"]);?>" />
                </div>
            </div>

            <!-- Address Section -->
            <div class="form-row mb-3">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">City / Municipality</label>
                    <select class="form-control" id="city_mun" name="city_mun" required>
                        <option value="<?php echo htmlspecialchars($rs["city_mun"]);?>"><?php echo htmlspecialchars($rs["city_mun"]);?></option>
                        <?php
                            $res_city = $link->query("SELECT DISTINCT city_mun FROM districts ORDER BY city_mun");
                            while ($row_city = mysqli_fetch_array($res_city)) {
                                if ($row_city['city_mun'] !== $rs['city_mun']) {
                                    echo "<option value='" . htmlspecialchars($row_city['city_mun']) . "'>" . htmlspecialchars($row_city['city_mun']) . "</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Barangay</label>
                    <select class="form-control" id="barangay" name="barangay" required>
                        <option value="<?php echo htmlspecialchars($rs["barangay"]);?>"><?php echo htmlspecialchars($rs["barangay"]);?></option>
                        <?php
                            $res_brgy = $link->query("SELECT DISTINCT barangay FROM districts WHERE city_mun = '" . mysqli_real_escape_string($link, $rs['city_mun']) . "' ORDER BY barangay");
                            while ($row_brgy = mysqli_fetch_array($res_brgy)) {
                                if ($row_brgy['barangay'] !== $rs['barangay']) {
                                    echo "<option value='" . htmlspecialchars($row_brgy['barangay']) . "'>" . htmlspecialchars($row_brgy['barangay']) . "</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Purok / District</label>
                    <select class="form-control" id="purok" name="purok" required>
                        <option value="<?php echo htmlspecialchars($rs["purok"]);?>"><?php echo htmlspecialchars($rs["purok"]);?></option>
                        <?php
                            $res_purok = $link->query("SELECT DISTINCT purok FROM districts WHERE barangay = '" . mysqli_real_escape_string($link, $rs['barangay']) . "' ORDER BY purok");
                            while ($row_purok = mysqli_fetch_array($res_purok)) {
                                if ($row_purok['purok'] !== $rs['purok']) {
                                    echo "<option value='" . htmlspecialchars($row_purok['purok']) . "'>" . htmlspecialchars($row_purok['purok']) . "</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Province</label>
                    <input class="form-control" required name="province" type="text" value="<?php echo htmlspecialchars($rs["province"]);?>" />
                </div>
            </div>

            <!-- Birth Date & Age -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Place of Birth</label>
                    <input class="form-control" name="birth_place" type="text" value="<?php echo htmlspecialchars($rs["birth_place"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date of Birth</label>
                    <input class="form-control" name="date_birth" type="date" value="<?php echo htmlspecialchars($rs["date_birth"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Age</label>
                    <input class="form-control bg-light" name="age" type="text" value="<?php echo htmlspecialchars($rs["age"]);?>" readonly />
                </div>
            </div>

            <!-- Contact & Demographics -->
            <div class="form-row mb-3">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Sex</label>
                    <select class="form-control" required name="sex">
                        <option value="Male" <?php if($rs["sex"]==="Male") echo "selected";?>>Male</option>
                        <option value="Female" <?php if($rs["sex"]==="Female") echo "selected";?>>Female</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Civil Status</label>
                    <input class="form-control" name="civilstatus" type="text" value="<?php echo htmlspecialchars($rs["civilstatus"]);?>" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Education</label>
                    <input class="form-control" name="education" type="text" value="<?php echo htmlspecialchars($rs["education"]);?>" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Occupation</label>
                    <input class="form-control" name="occupation" type="text" value="<?php echo htmlspecialchars($rs["occupation"]);?>" />
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Email Address</label>
                    <input class="form-control" name="emailadd" type="email" value="<?php echo htmlspecialchars($rs["emailadd"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Mobile Phone</label>
                    <input class="form-control" name="mobileno" type="text" value="<?php echo htmlspecialchars($rs["mobileno"]);?>" />
                </div>
            </div>

            <!-- Association & ID -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">OSCA Association Name</label>
                    <input class="form-control" name="association" type="text" value="<?php echo htmlspecialchars($rs["association"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Position</label>
                    <input class="form-control" name="position" type="text" value="<?php echo htmlspecialchars($rs["position"]);?>" />
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">OSCA ID Number</label>
                    <input class="form-control" name="assoc_id_no" type="text" value="<?php echo htmlspecialchars($rs["assoc_id_no"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">OSCA Reg. Date</label>
                    <input class="form-control" name="assoc_reg_date" type="text" value="<?php echo htmlspecialchars($rs["assoc_reg_date"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Pensioner Status</label>
                    <select class="form-control" name="pensioner">
                        <option value="Yes" <?php if($rs["pensioner"]==="Yes") echo "selected";?>>Yes</option>
                        <option value="No" <?php if($rs["pensioner"]==="No") echo "selected";?>>No</option>
                    </select>
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-12 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">NCSC-RRN Reg. Number</label>
                    <input class="form-control" name="ncsc_rrn" type="text" value="<?php echo htmlspecialchars($rs["ncsc_rrn"]);?>" />
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Emergency Contact</label>
                    <input class="form-control" name="contactperson" type="text" value="<?php echo htmlspecialchars($rs["contactperson"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Relationship</label>
                    <input class="form-control" name="relationship" type="text" value="<?php echo htmlspecialchars($rs["relationship"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Contact Number</label>
                    <input class="form-control" name="emergencyno" type="text" value="<?php echo htmlspecialchars($rs["emergencyno"]);?>" />
                </div>
            </div>

            <!-- Encoder Details -->
            <div class="form-row mb-4">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Encoder / Interviewer</label>
                    <input class="form-control" name="interviewer" type="text" value="<?php echo htmlspecialchars($rs["interviewer"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Interview Date</label>
                    <input class="form-control" name="inter_date" type="text" value="<?php echo htmlspecialchars($rs["inter_date"]);?>" />
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Update Senior Profile
                </button>
            </div>
        </form>
    </div>
</div>

<script>
if (typeof addressDropdownsInitialized === 'undefined') {
    addressDropdownsInitialized = true;
    document.addEventListener('change', function(e) {
      if (e.target && e.target.id === 'city_mun') {
        let city_mun = e.target.value;
        fetch('get_options.php?type=barangay&city_mun=' + encodeURIComponent(city_mun))
          .then(res => res.json())
          .then(data => {
            let barangaySelect = document.getElementById('barangay');
            if (barangaySelect) {
                barangaySelect.innerHTML = '<option value="">Barangay</option>';
                data.forEach(item => {
                  barangaySelect.innerHTML += '<option value="'+item+'">'+item+'</option>';
                });
            }
            let purokSelect = document.getElementById('purok');
            if (purokSelect) purokSelect.innerHTML = '<option value="">Purok</option>';
          });
      }

      if (e.target && e.target.id === 'barangay') {
        let barangay = e.target.value;
        fetch('get_options.php?type=purok&barangay=' + encodeURIComponent(barangay))
          .then(res => res.json())
          .then(data => {
            let purokSelect = document.getElementById('purok');
            if (purokSelect) {
                purokSelect.innerHTML = '<option value="">Purok</option>';
                data.forEach(item => {
                  purokSelect.innerHTML += '<option value="'+item+'">'+item+'</option>';
                });
            }
          });
      }
    });
}
</script>
<?php } ?>