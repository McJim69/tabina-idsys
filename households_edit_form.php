<?php require("connect.php"); ?>

<?php
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
	if(isset($_GET["households"]) && $_GET["households"]!="")
		$mem=" and hhid='".$_GET["households"]."' ";
										
	$ex=$link->query("select * from households where hhid=hhid $mem order by hhid limit $from,$to ");
	
	while($rs=mysqli_fetch_array($ex)){
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-edit mr-2"></i>Edit Household <b><?php echo $rs[0];?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="households_edit_proc.php" method="POST">
            <input type="hidden" name="hhid" value="<?php echo $rs[0];?>"/>

            <div class="form-group mb-3">
                <label class="small font-weight-bold text-muted text-uppercase mb-1">Name of Household Head</label>
                <input class="form-control" required name="hh_name" type="text" value="<?php echo htmlspecialchars($rs["hh_name"]);?>" />
            </div>

            <!-- Address Section -->
            <div class="form-row mb-3">
                <!-- Municipal -->
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Municipality</label>
					<select class="form-control" id="municipal" name="city_mun">
					  <option value="<?php echo"".$rs["city_mun"]."";?>"><?php echo"".$rs["city_mun"]."";?></option>
					  <?php
					  $res = $link->query("SELECT DISTINCT municipal FROM districts ORDER BY municipal");
					  while ($row = mysqli_fetch_array($res)) {
						echo "<option value='{$row['municipal']}'>{$row['municipal']}</option>";
					  }
					  ?>
					</select>
				</div>

                <!-- Barangay -->
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Barangay</label>
					<select class="form-control" id="bario" name="barangay">
					  <option value="<?php echo"".$rs["barangay"]."";?>"><?php echo"".$rs["barangay"]."";?></option>
					</select>
				</div>
                <!-- Purok -->
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Purok / District</label>
					<select class="form-control" id="sitio" name="purok">
					  <option value="<?php echo"".$rs["purok"]."";?>"><?php echo"".$rs["purok"]."";?></option>
					</select>
                </div>		
            </div>

            <!-- Occupation & Sex -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Occupation</label>
                    <input class="form-control" name="hh_occupation" type="text" value="<?php echo htmlspecialchars($rs["hh_occupation"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Gender / Sex</label>
                    <select class="form-control" name="hh_sex">
                        <option value="Male" <?php if($rs["hh_sex"]==="Male") echo "selected";?>>Male</option>
                        <option value="Female" <?php if($rs["hh_sex"]==="Female") echo "selected";?>>Female</option>
                    </select>
                </div>
            </div>

            <!-- Birth Date & Contact -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Birth Date</label>
                    <input class="form-control" name="hh_birth" type="text" value="<?php echo htmlspecialchars($rs["hh_birth"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Contact Number</label>
                    <input class="form-control" name="hh_contact" type="text" value="<?php echo htmlspecialchars($rs["hh_contact"]);?>" />
                </div>
            </div>

            <!-- Religion & Ethnicity -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Religion</label>
                    <input class="form-control" name="hh_religion" type="text" value="<?php echo htmlspecialchars($rs["hh_religion"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Ethnicity</label>
                    <input class="form-control" name="hh_ethnicity" type="text" value="<?php echo htmlspecialchars($rs["hh_ethnicity"]);?>" />
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="small font-weight-bold text-muted text-uppercase mb-1">Date Verified</label>
                <input class="form-control" name="date_verified" type="text" value="<?php echo htmlspecialchars($rs["date_verified"]);?>" />
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Update Household Record
                </button>
            </div>
        </form>
    </div>
</div>

<?php } ?>