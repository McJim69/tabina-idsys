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
	if(isset($_GET["solo_parent"]) && $_GET["solo_parent"]!="")
		$mem=" and idn='".$_GET["solo_parent"]."' ";
											
	$ex = $link->query("select * from solo_parent where idn=idn $mem order by idn limit $from,$to ");

	while($rs = mysqli_fetch_array($ex)){
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-edit mr-2"></i>Edit Solo Parent ID: <b><?php echo $rs[0];?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="solo_parent_edit_proc.php" method="POST">
            <input type="hidden" name="idn" value="<?php echo $rs[0];?>"/>
			<input type="hidden" name="province" value="<?php echo htmlspecialchars($rs["province"]);?>" />
			<input type="hidden" name="assoc_id_no" value="<?php echo htmlspecialchars($rs["assoc_id_no"]);?>" />

            <!-- Full Name Section -->
            <div class="form-row mb-3">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" required name="name_1st" type="text" value="<?php echo htmlspecialchars($rs["name_1st"]);?>" />
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">M.I.</label>
                    <input class="form-control" name="name_mid" type="text" value="<?php echo htmlspecialchars($rs["name_mid"]);?>" maxlength="2" />
                </div>
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Family Name</label>
                    <input class="form-control" required name="name_fam" type="text" value="<?php echo htmlspecialchars($rs["name_fam"]);?>" />
                </div>
            </div>
            <!-- Address Section -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Purok / District</label>
                    <input class="form-control" required name="purok" type="text" value="<?php echo htmlspecialchars($rs["purok"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Barangay</label>
                    <input class="form-control" required name="barangay" type="text" value="<?php echo htmlspecialchars($rs["barangay"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">City / Municipality</label>
                    <input class="form-control" required name="city_mun" type="text" value="<?php echo htmlspecialchars($rs["city_mun"]);?>" />
                </div>
            </div>
            <!-- Demographics & Status -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date of Birth</label>
                    <input class="form-control" onfocus="(this.type='date')" name="date_birth" type="text" value="<?php echo htmlspecialchars($rs["date_birth"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Sex</label>
                    <select class="form-control" required name="sex">
                        <option value="Male" <?php if($rs["sex"]==="Male") echo "selected";?>>Male</option>
                        <option value="Female" <?php if($rs["sex"]==="Female") echo "selected";?>>Female</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Civil Status</label>
                    <input class="form-control" name="civilstatus" type="text" value="<?php echo htmlspecialchars($rs["civilstatus"]);?>" />
                </div>
			</div>
            <!-- Contact Information -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Email Address</label>
                    <input class="form-control" name="email" type="email" value="<?php echo htmlspecialchars($rs["emailadd"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Mobile Phone</label>
                    <input class="form-control" name="mobileno" type="text" value="<?php echo htmlspecialchars($rs["mobileno"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Place of Birth</label>
                    <input class="form-control" name="birth_place" type="text" value="<?php echo htmlspecialchars($rs["birth_place"]);?>" />
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Association</label>
                    <input class="form-control" name="association" type="text" value="<?php echo htmlspecialchars($rs["association"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Position</label>
                    <input class="form-control" name="position" type="text" value="<?php echo htmlspecialchars($rs["position"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date Applied</label>
                    <input class="form-control" type="date" name="date_assoc_reg" value="<?php echo htmlspecialchars($rs["date_assoc_reg"]);?>" />
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
			<!-- Education & Occupation -->
			<div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Education</label>
                    <input class="form-control" name="education" type="text" value="<?php echo htmlspecialchars($rs["education"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Occupation</label>
                    <input class="form-control" name="occupation" type="text" value="<?php echo htmlspecialchars($rs["occupation"]);?>" />
                </div>
            </div>
            <!-- Interviewer Details -->
            <div class="form-row mb-4">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Interviewer</label>
                    <input class="form-control" name="interviewer" type="text" value="<?php echo htmlspecialchars($rs["interviewer"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date Interviewed</label>
                    <input class="form-control" type="date" name="date_interview" value="<?php echo htmlspecialchars($rs["date_interview"]);?>" />
                </div>
            </div>
            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Update Solo Parent
                </button>
            </div>
        </form>
    </div>
</div>

<?php } ?>