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
	if(isset($_GET["hh_members"]) && $_GET["hh_members"]!="")
		$mem=" and hmid='".$_GET["hh_members"]."' ";
										
	$ex=$link->query("select * from hh_members where hmid=hmid $mem order by hmid limit $from,$to ");
	
	while($rs=mysqli_fetch_array($ex)){
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-user-edit mr-2"></i>Edit Household Member - ID: <b><?php echo $rs[0];?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="households_mem_edit_proc.php" method="POST">
            <input type="hidden" name="hmid" value="<?php echo $rs[0];?>"/>
            <input type="hidden" name="hm_belong" value="<?php echo htmlspecialchars($rs["hm_belong"]);?>"/>
            <input type="hidden" name="purok" value="<?php echo htmlspecialchars($rs["purok"]);?>"/>
            <input type="hidden" name="barangay" value="<?php echo htmlspecialchars($rs["barangay"]);?>"/>
            <input type="hidden" name="city_mun" value="<?php echo htmlspecialchars($rs["city_mun"]);?>"/>
            <input type="hidden" name="ispicset" value="0"/>

            <div class="form-group mb-3">
                <label class="small font-weight-bold text-muted text-uppercase mb-1">Household Member Full Name</label>
                <input class="form-control" required name="hm_name" type="text" value="<?php echo htmlspecialchars($rs["hm_name"]);?>" />
            </div>

            <!-- Gender, Birth Date & Education -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Gender / Sex</label>
                    <select class="form-control" required name="hm_sex">
                        <option value="Male" <?php if($rs["hm_sex"]==="Male") echo "selected";?>>Male</option>
                        <option value="Female" <?php if($rs["hm_sex"]==="Female") echo "selected";?>>Female</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date of Birth</label>
                    <input class="form-control" required name="hm_birth" type="date" value="<?php echo htmlspecialchars($rs["hm_birth"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Educational Attainment</label>
                    <input class="form-control" required name="hm_education" type="text" value="<?php echo htmlspecialchars($rs["hm_education"]);?>" />
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Currently Enrolled?</label>
                    <select class="form-control" required name="hm_enrolled">
                        <option value="Yes" <?php if($rs["hm_enrolled"]==="Yes") echo "selected";?>>Yes</option>
                        <option value="No" <?php if($rs["hm_enrolled"]==="No") echo "selected";?>>No</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Social Welfare / Benefits</label>
                    <input class="form-control" name="hm_social" type="text" value="<?php echo htmlspecialchars($rs["hm_social"]);?>" />
                </div>
            </div>

            <!-- Income Fields -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Main Income Source</label>
                    <input class="form-control" name="hm_main_income" type="text" value="<?php echo htmlspecialchars($rs["hm_main_income"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Secondary Income</label>
                    <input class="form-control" name="hm_second_income" type="text" value="<?php echo htmlspecialchars($rs["hm_second_income"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Estimated Total Income</label>
                    <input class="form-control" name="hm_estimated_income" type="text" value="<?php echo htmlspecialchars($rs["hm_estimated_income"]);?>" />
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="small font-weight-bold text-muted text-uppercase mb-1">Remarks</label>
                <input class="form-control" name="hm_remarks" type="text" value="<?php echo htmlspecialchars($rs["hm_remarks"]);?>" />
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Update Member Record
                </button>
            </div>
        </form>
    </div>
</div>

<?php } ?>