<?php
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script>setActive("admin");</script>
<script>setActive("usergrid");</script>

<link href="style/grid-cards.css?v=2.0.8" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">	
				<input  class="swid bmargin btn btn-sm btn-outline-primary" name="t_search" id="t_search" value="<?php echo $value;?>" placeholder="Type a keyword"/>
				<button class="bmargin btn btn-sm btn-primary" type='submit' name='b_search'><i class="fa fa-search tpad"></i>  <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-secondary" type='button' onclick="jump('users_list.php')"><i class="fa fa-list tpad"></i> <x class="thid">List View</x></button>
				<select class="swid spad bmargin btn btn-sm btn-outline-primary" onchange="if(this.value=='All access')jump('users_grid.php'); else jump('users_grid.php?access='+this.value+'')">
					<option>All access</option>
					<?php
						$ex2=$link->query("select access from users group by access order by access");										
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
								if($_GET["access"]===$rs2[0])
								echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>				
				<a rel="facebox" href="forget_pass_msg.php"><button class="bmargin btn btn-sm btn-success" type='button'><i class="fa fa-envelope tpad"></i> <x class="thid">PW Recovery REQ</x></button></a>	
				<button class="bmargin btn btn-sm btn-primary" type='button' onclick="printF()"><i class="fa fa-print tpad"></i> <x class="thid">Print</x></button>
			</div>				
		</div>
	</div>
</div>

<div class="spid"></div>

<div class="container py-4 grid">
	<div class="row"> 
		<?php
			$value = $_GET['value'];
			
			$acc="";
			if($_GET["access"]!="All Access" && $_GET["access"]!="") {
				$acc=" and access='".$_GET["access"]."'";
			}
			
			if($_POST["b_search"] !== null){
				$value=$_POST["t_search"];
			}
			
			$rec=200;
			$p = $_GET['page'] != '' ? intval($_GET['page']) : 1;
			if($p>1){
				$to=$p*$rec;
				$from=$to-$rec;
				$i=$to+1-$rec;
			}else{
				$to=$rec;
				$from=0;
				$i=1;
				$p=1;
			}
			
			$ex=$link->query("select * from users where (
				access   like'%".$value."%' or		
				name_1st like'%".$value."%' or
				name_mid like'%".$value."%' or
				name_fam like'%".$value."%' or
				username like'%".$value."%' or
				password like'%".$value."%') $acc order by uno LIMIT $from,$to ");
				
			$ex1=$link->query("select * from users where (
				access like'%".$value."%' or		
				name_1st like'%".$value."%' or
				name_mid like'%".$value."%' or
				name_fam like'%".$value."%' or
				username like'%".$value."%' or
				password like'%".$value."%') $acc order by uno ");

			$search_val = strtoupper(isset($_POST["t_search"]) ? $_POST["t_search"] : $value);
			$rep="<b style='color:#0014d0;background:#ffa0a0'>$search_val</b>";
			$is_admin = ($_SESSION["access"] !== "");
				
			while($rs=mysqli_fetch_array($ex)){
				$photo_path = 'images/user.png';
				if(!empty($rs['imgUrl']) && file_exists("images/users/".$rs['imgUrl'])){
					$photo_path = "images/users/".$rs['imgUrl']."?" . date("h:i:s");
				}
					
				$fullname = trim($rs['name_1st']." ".$rs['name_mid']." ".$rs['name_fam']);
					
				?>
				<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" id="div_<?php echo $rs[0]; ?>">
					<div class="card user-card h-100 shadow-sm border-2">
						<div class="user-card-img-container">
							<!-- Index Badge -->
							<span class="badge badge-dark position-absolute m-2" style="top: 5px; left: 0; z-index: 10; font-size: 14px; opacity: 0.8;">#<?php echo $i; ?></span>
							
							<!-- Image -->
							<img src="<?php echo $photo_path; ?>" alt="User Photo" />
							
							<!-- Hover actions overlay -->
							<div class="user-hover-actions">
								<?php if ($is_admin): ?>
									<a rel="facebox" href="users_edit_form.php?users=<?php echo $rs[0]; ?>" class="btn btn-sm btn-warning btn-block my-1">
										<i class="fas fa-edit mr-1"></i> Edit Access
									</a>
									<button type="button" class="btn btn-sm btn-danger btn-block my-1" onclick="deleteRecord('users', <?php echo $rs[0]; ?>, 'div_<?php echo $rs[0]; ?>')">
										<i class="fas fa-trash-alt mr-1"></i> Remove User
									</button>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="card-body p-3 d-flex flex-column justify-content-between">
							<div>
								<!-- Fullname -->
								<h6 class="font-weight-bold mb-1 text-uppercase text-dark text-truncate">
									<?php echo $fullname; ?>
								</h6>
								
								<!-- Access level -->
								<div class="small font-weight-bold text-primary mb-2 text-truncate">
									<i class="fas fa-user-shield mr-1"></i>Access: <span class="text-success"><?php echo $rs["access"]; ?></span>
								</div>
								
								<hr class="my-2">

								<div class="small text-muted mb-1 text-truncate">
									Username: <strong class="text-dark"><?php echo $rs["username"]; ?></strong>
								</div>
								
								<div class="small text-muted mb-1 text-truncate">
									Password: <strong class="text-danger"><?php echo $rs["password"]; ?></strong>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				$i++;	
			}
		?>
	</div>
</div>
</form>

</div>

<?php include("footerNAV.php");?>
<?php include("crud_functionjs.php");?>

</body>

</html>
