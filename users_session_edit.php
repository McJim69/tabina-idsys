<?php
	require("connect.php");
	
	$ID=$_SESSION["uno"];

	if (isset($_POST["b_upImg_$ID"]) && isset($_FILES["b_file_$ID"]) && $_FILES["b_file_$ID"]["error"] == UPLOAD_ERR_OK) {
		$fileTmpPath = $_FILES["b_file_$ID"]["tmp_name"];
		$fileName = $_FILES["b_file_$ID"]["name"];
		$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
		
		$allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
		if (in_array($fileExt, $allowedExtensions)) {
			$newFileName = "user_" . $ID . "_" . time() . "." . $fileExt;
			$uploadFileDir = "images/users/";
			$dest_path = $uploadFileDir . $newFileName;
			
			if (move_uploaded_file($fileTmpPath, $dest_path)) {
				// Delete old picture if it exists
				if (!empty($_SESSION['imgUrl'])) {
					$oldFilePath = $uploadFileDir . $_SESSION['imgUrl'];
					if (file_exists($oldFilePath) && is_file($oldFilePath)) {
						unlink($oldFilePath);
					}
				}
				
				$newFileName_esc = mysqli_real_escape_string($link, $newFileName);
				$link->query("UPDATE users SET imgUrl='$newFileName_esc' WHERE uno='$ID'");
				$_SESSION['imgUrl'] = $newFileName;
				echo "<script>window.history.back();</script>";
				exit;
			}
		}
	}
?>

<div class="card border-0 shadow-none" style="width: 100%; max-width: 550px; background: #ffffff; border-radius: 16px; overflow: hidden;">
	<!-- Modal Header -->
	<div class="card-header bg-warning py-3 px-4 d-flex align-items-center justify-content-between">
		<h5 class="mb-0 font-weight-bold">
			<i class="fas fa-user-edit mr-2"></i><?php echo $_SESSION["fullname"];?> <x class="thid">- <?php echo $_SESSION["access"];?></x> &nbsp; &nbsp; &nbsp; &nbsp;
		</h5>
		<button type="button" class="close opacity-100" onclick="jQuery(document).trigger('close.facebox')" aria-label="Close" style="outline:none;">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<!-- Modal Body -->
	<div class="card-body p-4 bg-light">
		<div class="mb-3 text-center">
			<?php 
				if(file_exists("images/users/".$_SESSION["imgUrl"]) && !empty($_SESSION["imgUrl"])): 
					echo"<img src='images/users/".$_SESSION["imgUrl"]."?".time()."' class='image img-thumbnail shadow-sm mb-2' style='border-radius:15px; width: 133px; height: 133px; object-fit: cover;'>";
				else: 
					echo"<img src='images/blank.jpg' class='img-thumbnail shadow-sm mb-2' style='border-radius:15px; width: 133px; height: 133px; object-fit: cover;'>";
				endif; 
				echo"
				<div id='div_browse_$ID'>
					<form action='users_session_edit.php' method='POST' enctype='multipart/form-data' style='display:inline;'>
						<input type='file' name='b_file_$ID' id='b_file_$ID' style='display:none' onchange=\"if(this.value!='')$('#b_upImg_$ID').click();\"> 
						<input type='submit' name='b_upImg_$ID' id='b_upImg_$ID' value='Upload' style='display:none'> 
						<input type='button' class='btn btn-sm btn-outline-danger rounded-pill' value='Change Picture' onclick=\"$('#b_file_$ID').click();\">
					</form>
				</div>";
			?>
		</div>
		<form action="users_session_edit_proc.php" method="POST" class="mb-0" onsubmit="return updateUser()">
			<input type="hidden" name="uno" value="<?php echo $ID;?>">
			<input type="hidden" name="access" value="<?php echo htmlspecialchars($_SESSION["access"]);?>">
			<div class="form-row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="font-weight-bold small mb-1">
							<i class="fas fa-id-card text-warning mr-1"></i>First Name
						</label>
						<input class="form-control bg-light border" required name="name_1st" type="text" value="<?php echo htmlspecialchars($_SESSION["Fname"]);?>">
					</div>
				</div>			
				<div class="col-md-4">
					<div class="form-group">
						<label class="font-weight-bold small mb-1">
							<i class="fas fa-id-card text-warning mr-1"></i>Middle Initial
						</label>
						<input class="form-control bg-light border" required name="name_mid" type="text" value="<?php echo htmlspecialchars($_SESSION["Mname"]);?>">
					</div>
				</div>			
				<div class="col-md-4">
					<div class="form-group">
						<label class="font-weight-bold small mb-1">
							<i class="fas fa-id-card text-warning mr-1"></i>Family Name
						</label>
						<input class="form-control bg-light border" required name="name_fam" type="text" value="<?php echo htmlspecialchars($_SESSION["Lname"]);?>">
					</div>
				</div>			
			<div class="form-row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="font-weight-bold small mb-1">
							<i class="fas fa-user text-warning mr-1"></i>Username
						</label>
						<input class="form-control bg-light border" required name="username" type="text" value="<?php echo htmlspecialchars($_SESSION["user"]);?>">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="font-weight-bold small mb-1">
							<i class="fas fa-lock text-warning mr-1"></i>Password
						</label>
						<input class="form-control bg-light border" name="password" type="password" placeholder="Leave blank to keep current password">
					</div>
				</div>
			</div>
			<div class="form-row">	
				<div class="col-md-4">
					<div class="form-group">
						<label class="font-weight-bold small mb-1">
							<i class="fas fa-lock text-warning mr-1"></i>Birth Date
						</label>
						<input class="form-control bg-light border" required name="date_birth" onfocus="(this.type='date')" required value="<?php echo htmlspecialchars($_SESSION["birth"]);?>">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="font-weight-bold small mb-1">
							<i class="fas fa-lock text-warning mr-1"></i>Email Address
						</label>
						<input class="form-control bg-light border" name="email" type="email" placeholder="(Optional)" value="<?php echo htmlspecialchars($_SESSION["email"]);?>">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group mb-4">
						<label class="font-weight-bold small mb-1">
							<i class="fas fa-lock text-warning mr-1"></i>Phone Number
						</label>
						<input class="form-control bg-light border" required name="phone" type="text" value="<?php echo htmlspecialchars($_SESSION["phone"]);?>">
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="font-weight-bold small mb-1">
							<i class="fas fa-lock text-warning mr-1"></i>Municipality
						</label>
						<select class="form-control bg-light border" required id="city_mun" name="city_mun">
							<option value="<?php echo $_SESSION["city_mun"];?>"><?php echo $_SESSION["city_mun"];?></option>
							<?php
								$res = $link->query("SELECT DISTINCT city_mun FROM districts ORDER BY city_mun");
								while ($row = mysqli_fetch_array($res)) {
									echo "<option value='{$row['city_mun']}'>{$row['city_mun']}</option>";
								}
							?>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="font-weight-bold small mb-1">
							<i class="fas fa-lock text-warning mr-1"></i>Barangay
						</label>
						<select class="form-control bg-light border" required id="barangay" name="barangay">
							<option value="<?php echo $_SESSION["barangay"];?>"><?php echo $_SESSION["barangay"];?></option>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group mb-4">
						<label class="font-weight-bold small mb-1">
							<i class="fas fa-lock text-warning mr-1"></i>Purok
						</label>
						<select class="form-control bg-light border" required id="purok" name="purok">
							<option value="<?php echo $_SESSION["purok"];?>"><?php echo $_SESSION["purok"];?></option>
						</select>
					</div>
				</div>
			</div>
            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-between">
                <button type="button" class="btn btn-outline-secondary px-3" onclick="jQuery(document).trigger('close.facebox')">
                    <i class="fas fa-times mr-1"></i>Cancel
                </button>
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
	function updateUser() {
		return confirm("Updating account details requires you to relogin. Are you sure?");
	}
</script>

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