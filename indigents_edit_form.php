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
	if(isset($_GET["indigents"]) && $_GET["indigents"]!="")
		$mem=" and idn='".$_GET["indigents"]."' ";
										
	$ex = $link->query("select * from indigents where idn=idn $mem order by idn limit $from,$to ");

	while($rs = mysqli_fetch_array($ex)){
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-edit mr-2"></i>Edit 4Ps Indigent Profile - ID: <b><?php echo $rs[0];?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4">
        <form action="indigents_edit_proc.php" method="POST">
            <input type="hidden" name="idn" value="<?php echo $rs[0];?>"/>

            <div class="form-group mb-3 text-uppercase">
                <label class="small font-weight-bold text-muted mb-1">Full Name</label>
                <input class="form-control" required name="fullname" type="text" value="<?php echo htmlspecialchars($rs["fullname"]);?>" />
            </div>

            <div class="form-row mb-3">
                <!-- Municipal -->
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Municipality</label>
					<input class="form-control" required name="city_mun" type="text" value="<?php echo htmlspecialchars($rs["city_mun"]);?>" />
				</div>

                <!-- Barangay -->
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Barangay</label>
					<input class="form-control" required name="barangay" type="text" value="<?php echo htmlspecialchars($rs["barangay"]);?>" />
				</div>
                <!-- Purok -->
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Purok / District</label>
					<input class="form-control" name="purok" type="text" value="<?php echo htmlspecialchars($rs["purok"]);?>" />
                </div>		
            </div>

            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Period Covered</label>
                    <input class="form-control" name="period" type="text" value="<?php echo htmlspecialchars($rs["period"]);?>" />
                </div>

                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Amount Received</label>
                    <input class="form-control" name="amount" type="text" value="<?php echo htmlspecialchars($rs["amount"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date Received</label>
                    <input class="form-control" name="date_paid" type="text" value="<?php echo htmlspecialchars($rs["date_paid"]);?>" />
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="small font-weight-bold text-muted text-uppercase mb-1">Remarks</label>
                <input class="form-control" name="remarks" type="text" value="<?php echo htmlspecialchars($rs["remarks"]);?>" />
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Update 4Ps Record
                </button>
            </div>
        </form>
    </div>
</div>

<?php } ?>
