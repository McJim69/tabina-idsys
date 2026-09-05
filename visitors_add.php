<?php
	require("connect.php");

	$startyear = date("Y")-5;
	$endyear=date("Y"); 

	$months=array('','01-Jan','02-Feb','03-Mar','04-Apr','05-May','06-Jun','07-Jul','08-Aug', '09-Sep','10-Oct','11-Nov','12-Dec');								

	$squery = "SELECT MAX(idn) FROM visitors";
	$result = $link->query($squery);
	$values = $result->fetch_array();
	$lastID = $values[0]+1;
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold text-white">
            <i class="fas fa-user-friends mr-2"></i>Add Visitor ID: <b><?php echo $lastID;?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="visitors_add_proc.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="idn" value="<?php echo $lastID;?>" />

            <!-- Visitor Full Name -->
            <div class="form-row mb-3">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" type="text" name="name_1st" placeholder="First Name" required autofocus />
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">M.I.</label>
                    <input class="form-control" type="text" name="name_mid" placeholder="M I" maxlength="2" />
                </div>
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Family Name</label>
                    <input class="form-control" type="text" name="name_fam" placeholder="Family Name" required />
                </div>
            </div>

            <!-- Position & Office -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Position / Designation</label>
                    <input class="form-control" type="text" name="position" placeholder="Position or Designation" required />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Office / Agency</label>
                    <input class="form-control" type="text" name="office" placeholder="Office or Agency Name" required />
                </div>
            </div>

            <!-- Address & Contact Information -->
            <div class="form-row mb-3">
                <div class="col-md-12 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Official Address</label>
                    <input class="form-control" type="text" name="address" placeholder="Official Address" required />
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Cellphone Number</label>
                    <input class="form-control" type="text" name="contact" placeholder="Cellphone Number" required />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Email Address</label>
                    <input class="form-control" type="email" name="emailadd" placeholder="Email Address" />
                </div>
            </div>

            <!-- Visit Schedule & Purpose -->
            <div class="form-row mb-3">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Visit Month</label>
                    <select class="form-control" required name="visit_month">
                        <option value=''>Month</option>
                        <?php for($i=1;$i<=12;$i++){ echo "<option value='$i'>$months[$i]</option>"; } ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Day From</label>
                    <select class="form-control" required name="visit_day_from">
                        <option value=''>From</option>
                        <?php for($i=1;$i<=31;$i++){ echo "<option value='$i'>$i</option>"; } ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Day To</label>
                    <select class="form-control" required name="visit_day_to">
                        <option value=''>To</option>
                        <?php for($i=1;$i<=31;$i++){ echo "<option value='$i'>$i</option>"; } ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Year</label>
                    <select class="form-control" required name="visit_year">
                        <option value=''>Year</option>
                        <?php for($i=$startyear;$i<=$endyear;$i++){ echo "<option value='$i'>$i</option>"; } ?>
                    </select>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="small font-weight-bold text-muted text-uppercase mb-1">Purpose of Visit</label>
                <input class="form-control" type="text" name="visit_purpose" placeholder="Purpose of Visit" required />
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="bSave" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-1"></i> Register Visitor
                </button>
            </div>
        </form>
    </div>
</div>