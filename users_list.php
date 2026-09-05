<?php
	require("connect.php");
	require("header.php");
	require("menu.php");

	$value=$_GET['value'];
	
	$acc="";
	if($_GET["access"]!="All Access" && $_GET["access"]!="")
	$acc=" and access='".$_GET["access"]."'";
	
	if(isset($_POST["b_search"])){
		$value=$_POST["t_search"];
	}

	$rec=200;
	$p=$_GET['page'];
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
					
	$ex=$link->query("select * from users where 
	   (access like'%".$value."%' or		
		name_1st like'%".$value."%' or
		name_mid like'%".$value."%' or
		name_fam like'%".$value."%' or
		username like'%".$value."%' or
		password like'%".$value."%') $acc order by uno LIMIT $from,$to ");		

	$ex1=$link->query("select * from users where 
	   (access like'%".$value."%' or		
		name_1st like'%".$value."%' or
		name_mid like'%".$value."%' or
		name_fam like'%".$value."%' or
		username like'%".$value."%' or
		password like'%".$value."%') $acc order by uno ");		
	//
?>

<script>setActive("admin");</script>
<script>setActive("userlist");</script>

<script type="text/javascript" src="scripts/scrolltopcontrol.js"></script>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">	
				<input  class="swid bmargin btn btn-sm btn-outline-primary" name="t_search" id="t_search" value="<?php echo $value;?>" placeholder="Type a keyword"/>
				<button class="bmargin btn btn-sm btn-primary" type='submit' name='b_search'><i class="fa fa-search tpad"></i>  <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-secondary" type='button' onclick="jump('users_grid.php')"><i class="fa fa-th tpad"></i> <x class="thid">Card View</x></button>
				<select class="swid spad bmargin btn btn-sm btn-outline-primary" onchange="if(this.value=='All access')jump('users_list.php'); else jump('users_list.php?access='+this.value+'')">
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
		<div class="row">
			<div class="col d-flex justify-content-center">
				<?php require("pageNAV.php");?>
			</div>	
		</div>
	</div>
</div>

</form>

<div class="list"></div>

<div class="container-fluid px-4 py-3">	
    <!-- Print Header -->
    <div style="text-align:center;display:none" id="head" class="mb-4">
        <table align="center" style="margin:0 auto;">
            <tr>
                <td><img src="images/seal.png" height="50"></td>
                <td class="px-3 text-center">
                    <b style="font-size:16px;text-transform:uppercase">System Accounts Directory</b>
                    <br>MUNICIPALITY OF TABINA
                </td>
                <td><img src="images/osca_logo2.png" height="50"></td>
            </tr>
        </table>
    </div>

    <!-- Table Container Card -->
    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-header bg-dark text-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 font-weight-bold text-white">
                <i class="fas fa-user-shield mr-2"></i>System User <x class="thid">Accounts</x>
            </h6>
            <span class="badge badge-light font-weight-bold py-1 px-2">Total: <?php echo number_format(mysqli_num_rows($ex1),0);?> Records</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
                <thead class="thead-dark text-uppercase font-weight-bold">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Photo</th>
                        <th><x class="thid">Full</x> Name</th>
                        <th class="text-center">Access <x class="thid">Level</x></th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $rep = "<b class='bg-warning px-1 rounded text-dark'>" . htmlspecialchars($value) . "</b>";
                    $ctr = 1;	
                    
                    while($rs=mysqli_fetch_array($ex)){
						
						$fullname = trim($rs['name_1st']." ".$rs['name_mid']." ".$rs['name_fam']);

                        if($ctr<=$rec){
                            if ($value !== "") {
                                $fullname_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($fullname));
                                $username_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($rs["username"]));
                            } else {
                                $fullname_disp = htmlspecialchars($fullname);
                                $username_disp = htmlspecialchars($rs["username"]);
                            }

                            echo "<tr class='tr-hover' id='tr_".$rs[0]."'>";
                            echo "<td class='text-center font-weight-bold text-muted'>$i.</td>";
                            echo "<td class='text-center'>";
                            if(file_exists("images/users/".$rs["imgUrl"]) && !empty($rs["imgUrl"])){
                                echo "<img src='images/users/".$rs["imgUrl"]."' class='rounded-circle shadow-sm' style='width:36px;height:36px;object-fit:cover;'>";
                            }else{
                                echo "<img src='images/blank.jpg' class='rounded-circle shadow-sm' style='width:36px;height:36px;object-fit:cover;'>";
                            }
                            echo "</td>";
                            echo "<td class='font-weight-bold text-dark text-uppercase'>$fullname_disp</td>";
                            echo "<td class='text-center'><span class='badge badge-primary px-2 py-1'>".htmlspecialchars($rs["access"])."</span></td>";
                            echo "<td class='font-weight-bold text-info'>$username_disp</td>";
                            echo "<td><div class='row'>";
                            echo "<a rel='facebox' href='users_edit_form.php?users=".$rs[0]."' class='btn btn-sm btn-outline-warning'><i class='fas fa-edit'></i></a> &nbsp; ";
                            echo "<a class='btn btn-sm btn-outline-danger' onclick=\"deleteRecord('users', '".$rs[0]."', 'tr_".$rs[0]."')\"><i class='fas fa-trash-alt'></i></a>";
                            echo "</div></td>";
                            echo "</tr>";
                        }
                        $i++;
                        $ctr++;
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Print Handler Script -->
<script>
	function printF(){
		getID('head').style.display='block';
		if(getID('topcontrol')) getID('topcontrol').style.display='none';
		$(".t_controls").css("display","none");
		$(".hid").css("display","none");		
		$(".list").css("display","none");
		
	    window.print();
		getID('head').style.display='none';
		if(getID('topcontrol')) getID('topcontrol').style.display='block';
		$(".t_controls").css("display","block");
		$(".hid").css("display","table-cell");
		$(".list").css("display","block");		
	}
</script>

<?php require("crud_functionjs2.php");?>