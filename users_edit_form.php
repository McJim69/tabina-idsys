<?php 
	require("connect.php");

	if(($_SESSION["user"]=="")or($_SESSION["access"]=="Users")){
		header('location:index.php');
	}
?>
	
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
				
	$users="";
	if(isset($_GET["users"]) && $_GET["users"]!="")
		$users=" and uno='".$_GET["users"]."' ";
		
	$ex=$link->query("select * from users where uno=uno $users order by uno limit $from,$to ");
		
	while($rs=mysqli_fetch_array($ex)){

	$fullname = trim($rs['name_1st']." ".$rs['name_mid']." ".$rs['name_fam']);
?>

<div class="card border-0 shadow-none" style="width: 100%; max-width: 550px; background: #ffffff; border-radius: 16px; overflow: hidden;">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-user-edit mr-2"></i><?php echo htmlspecialchars($rs["fullname"]);?>-<?php echo htmlspecialchars($rs["access"]);?> &nbsp; &nbsp; &nbsp; 
        </h5>
        <button type="button" class="close text-dark opacity-100" onclick="jQuery(document).trigger('close.facebox')" aria-label="Close" style="outline:none;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4 bg-light">
        <div class="mb-3 text-center">
            <?php if(file_exists("images/users/".$rs["imgUrl"]) && !empty($rs["imgUrl"])): ?>
                <img src="images/users/<?php echo $rs["imgUrl"]; ?>?<?php echo time(); ?>" class="img-thumbnail rounded-circle shadow-sm mb-2" style="width: 100px; height: 100px; object-fit: cover;">
            <?php else: ?>
                <img src="images/blank.jpg" class="img-thumbnail rounded-circle shadow-sm mb-2" style="width: 100px; height: 100px; object-fit: cover;">
            <?php endif; ?>
        </div>

        <form action="users_edit_form_proc.php" method="POST" class="mb-0">
            <input type="hidden" name="uno" value="<?php echo $rs[0];?>"/>
            <div class="form-group mb-3">
                <label class="font-weight-bold text-dark small mb-1">
                    <i class="fas fa-user-shield text-warning mr-1"></i>Access Level
                </label>
                <select class="form-control bg-white text-dark border" name="access" required style="height: 38px; color: #333 !important; background-color: #fff !important;">
                    <option value="<?php echo $rs["access"];?>"><?php echo $rs["access"];?></option>
                    <option value="Administrator" <?php if($rs["access"]==="Administrator") echo "selected";?>>Administrator</option>
                    <option value="Welfare"       <?php if($rs["access"]==="Welfare")       echo "selected";?>>Social Worker</option>
                    <option value="Enumerator"    <?php if($rs["access"]==="Enumerator")    echo "selected";?>>Enumerator</option>
                    <option value="Executive"     <?php if($rs["access"]==="Executive")     echo "selected";?>>Executive</option>
                    <option value="Employees"     <?php if($rs["access"]==="Employees")     echo "selected";?>>Employees</option>
					<option value="Private"       <?php if($rs["access"]==="Private")       echo "selected";?>>Private</option>
                    <option value="Guest"         <?php if($rs["access"]==="Guest")         echo "selected";?>>Guest</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="font-weight-bold text-dark small mb-1">
                    <i class="fas fa-user text-warning mr-1"></i>Username
                </label>
                <input class="form-control bg-white text-dark border" name="username" type="text" value="<?php echo htmlspecialchars($rs["username"]);?>" required style="height: 38px; color: #333 !important; background-color: #fff !important;">
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold text-dark small mb-1">
                    <i class="fas fa-lock text-warning mr-1"></i>Password
                </label>
                <input class="form-control bg-white text-dark border" name="password" type="password" placeholder="Leave blank to keep current password" style="height: 38px; color: #333 !important; background-color: #fff !important;">
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-between border-top pt-3 mt-2">
                <button type="button" class="btn btn-outline-secondary px-3" onclick="jQuery(document).trigger('close.facebox')">
                    <i class="fas fa-times mr-1"></i>Cancel
                </button>
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i>Update User
                </button>
            </div>
        </form>
    </div>
</div>
<?php } ?>