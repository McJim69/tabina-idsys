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
		
	$clear="";
	if(isset($_GET["cert_indigency"]) && $_GET["cert_indigency"]!="")
		$clear=" and idn='".$_GET["cert_indigency"]."' ";
										
	$ex = "select * from cert_indigency where idn=idn $clear order by idn limit $from,$to ";
	$res=$link->query($ex);

	while($row = $res->fetch_array()){
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-edit mr-2"></i>Edit Indigency Cert: #<b><?php echo $row["idn"];?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <div class="mb-3 text-center">
            <?php if(file_exists("images/cert_indigency/".$row["idn"].".jpg")): ?>
                <img src="images/cert_indigency/<?php echo $row["idn"]; ?>.jpg?<?php echo time(); ?>" class="img-thumbnail shadow-sm rounded mb-2" style="max-height: 140px; object-fit: cover;">
            <?php else: ?>
                <img src="images/back_ca.jpg" class="img-thumbnail shadow-sm rounded mb-2" style="max-height: 140px; object-fit: cover;">
            <?php endif; ?>
        </div>

        <form action="cert_indigency_edit_proc.php" method="POST">
            <input type="hidden" name="idn" value="<?php echo $row["idn"];?>"/>
            <input type="hidden" name="province" value="Zamboanga del Sur" />

            <!-- Full Name Section -->
            <div class="form-row mb-3">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" required name="name_1st" type="text" value="<?php echo htmlspecialchars($row["name_1st"]);?>" />
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">M.I.</label>
                    <input class="form-control" name="name_mid" type="text" value="<?php echo htmlspecialchars($row["name_mid"]);?>" maxlength="2" />
                </div>
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Family Name</label>
                    <input class="form-control" required name="name_fam" type="text" value="<?php echo htmlspecialchars($row["name_fam"]);?>" />
                </div>
            </div>

            <!-- Address Section -->
            <div class="form-row mb-3">
                <!-- Municipal -->
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Municipality</label>
                    <input class="form-control" required name="city_mun" type="text" value="<?php echo htmlspecialchars($row["city_mun"]);?>" />
				</div>

                <!-- Barangay -->
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Barangay</label>
                    <input class="form-control" required name="barangay" type="text" value="<?php echo htmlspecialchars($row["barangay"]);?>" />
				</div>
                <!-- Purok -->
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Purok / District</label>
                    <input class="form-control" required name="purok" type="text" value="<?php echo htmlspecialchars($row["purok"]);?>" />
                </div>	
            </div>

            <!-- Demographics & Status -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Sex</label>
                    <select class="form-control" required name="sex">
                        <option value="Male" <?php if($row["sex"]==="Male") echo "selected";?>>Male</option>
                        <option value="Female" <?php if($row["sex"]==="Female") echo "selected";?>>Female</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Civil Status</label>
                    <input class="form-control" name="status" type="text" value="<?php echo htmlspecialchars($row["status"]);?>" />
                </div>
            </div>

            <!-- Birth Date Section -->
            <div class="form-row mb-4">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date of Birth</label>
                    <input class="form-control" onfocus="(this.type='date')" required name="date_birth" type="text" value="<?php echo htmlspecialchars($row["date_birth"]);?>" />
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Update Record
                </button>
            </div>
        </form>
    </div>
</div>

<?php } ?>
