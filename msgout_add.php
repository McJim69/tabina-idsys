<?php
	require("connect.php");
	
	$startyear = date("Y")-5;
	$endyear=date("Y"); 

	$months=array('','01-Jan','02-Feb','03-Mar','04-Apr','05-May','06-Jun','07-Jul','08-Aug', '09-Sep','10-Oct','11-Nov','12-Dec');		

	$squery = "SELECT MAX(idn) FROM msgout";
	$result = $link->query($squery);
	$values = $result->fetch_array();
	$lastID = $values[0]+1;
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold text-white">
            <i class="fas fa-paper-plane mr-2"></i>Outgoing Message: <b><?php echo $lastID;?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="msgout_add_proc.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="idn" value="<?php echo $lastID;?>" />

            <!-- From & Office -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Message From</label>
                    <input class="form-control" required name="msg_from" type="text" placeholder="Message From" autofocus />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Sender Office</label>
                    <input class="form-control" required name="msg_office" type="text" placeholder="Office / Department" />
                </div>
            </div>

            <!-- To & Attention -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Message To</label>
                    <input class="form-control" required name="msg_to" type="text" placeholder="Recipient Name / Agency" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Attention Office</label>
                    <input class="form-control" required name="msg_attn" type="text" placeholder="Attention Office" />
                </div>
            </div>

            <!-- Message Date -->
            <div class="form-row mb-3">
                <div class="col-md-12 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Message Date</label>
                    <input class="form-control" name="date_msg" type="date" value="<?php echo date('Y-m-d'); ?>" required />
                </div>
            </div>

            <!-- Message Content -->
            <div class="form-group mb-3">
                <label class="small font-weight-bold text-muted text-uppercase mb-1">Message Content</label>
                <textarea class="form-control" rows="4" name="msg_content" placeholder="Type message contents here..." required></textarea>
            </div>

            <!-- Confirmation Date -->
            <div class="form-row mb-3">
                <div class="col-md-12 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Confirmation Date</label>
                    <input class="form-control" name="date_confirm" type="date" value="<?php echo date('Y-m-d'); ?>" required />
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-row mb-4">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Contact Person</label>
                    <input class="form-control" required name="contact_person" type="text" placeholder="Contact Person" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Contact Number</label>
                    <input class="form-control" required name="contact_number" type="text" placeholder="Contact Number" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Email Address</label>
                    <input class="form-control" required name="contact_email" type="email" placeholder="Email Address" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Postal Address</label>
                    <input class="form-control" required name="contact_postal" type="text" placeholder="Postal Address" />
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="bSave" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-paper-plane mr-1"></i> Send Outgoing Message
                </button>
            </div>
        </form>
    </div>
</div>