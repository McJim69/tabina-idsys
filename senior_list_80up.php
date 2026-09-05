<?php
	require("connect.php"); 
	require("header.php");
	require("menu.php");
	
	$value=$_GET['value'];
	
	$bar="";
		if($_GET["barangay"]!="All barangays" && $_GET["barangay"]!="")
			$bar=" and barangay='".$_GET["barangay"]."'";

	$age="";
		if($_GET["age"]!="All ages" && $_GET["age"]!="")
			$age=" and age='".$_GET["age"]."'";

	$pen="";
		if($_GET["pensioner"]!="With pensions" && $_GET["pensioner"]!="")
			$pen=" and pensioner='".$_GET["pensioner"]."'";
		
	if(isset($_POST["b_search"])){
	$value=$_POST["t_search"];
	}
	
	$rec=20;
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

	$ex=$link->query("select * from senior where age >79 and (
	    idn like'%".$value."%' or
		name_fam like'%".$value."%' or
		name_1st like'%".$value."%' or
		name_mid like'%".$value."%' or
		mobileno like'%".$value."%' or
		purok like'%".$value."%' or
		barangay like'%".$value."%' or
		city_mun like'%".$value."%' or
		province like'%".$value."%' or	
		sex like'%".$value."%') $age $bar $pen order by name_fam LIMIT $from,$to ");				

	$ex1=$link->query("select * from senior where age >79 and (idn like'%".$value."%' or barangay like'%".$value."%') $bar $age $pen order by name_fam");				
?>

<script>setActive("social");</script>
<script>setActive("senior");</script>
<script>setActive("seniorlist");</script>

<script type="text/javascript" src="scripts/scrolltopcontrol.js"></script>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-success" type="button" onclick="getID('t_search').value='';jump('senior_list_80up.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<button class="bmargin btn btn-sm btn-outline-dark" type="button" onclick="jump('senior_grid_80up.php')"><i class="fa fa-th tpad"></i> <x class="thid">Card View</x></button>
				<select class="swid spad bmargin btn btn-sm btn-outline-dark" onchange="if(this.value=='All barangays')jump('senior_list_80up.php'); else jump('?barangay='+this.value)" >
					<option>Barangays</option>
					<?php
						$ex2=$link->query("select barangay from senior where age >79 and city_mun like'".$_GET["municipality"]."%' group by barangay order by barangay") or die(mysqli_error($link));
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
							if($_GET["barangay"]===$rs2[0])
							echo "selected";
							echo" >$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-primary" type="button" onclick="jump('senior_list.php')"><i class="fa fa-wheelchair tpad"></i> <x class='thid'>SC All</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='senior_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add</x></button></a>";
				?>			
				<select class="thid spad bmargin btn btn-sm btn-outline-primary" onchange="if(this.value=='All ages')jump('senior_list_80up.php'); else jump('?age='+this.value+'&barangay=<?php echo $_GET["barangay"];?>&pensioner=<?php echo $_GET["pensioner"];?>')" >
					<option>Ages</option>
					<?php
						$ex2=$link->query("select age from senior where age >79 and barangay like'".$_GET["barangay"]."%' and pensioner like'".$_GET["pensioner"]."%' group by age order by age") or die(mysqli_error($link));
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
							if($_GET["age"]===$rs2[0])
							echo "selected";
							echo" >$rs2[0]</option>";
						}
					?>
				</select>
				<select class="thid spad bmargin btn btn-sm btn-outline-secondary" onchange="if(this.value=='With pensions')jump('senior_list.php'); else jump('?pensioner='+this.value+'&barangay=<?php echo $_GET["barangay"];?>&age=<?php echo $_GET["age"];?>')" >
					<option>Pensioner</option>
					<?php
						$ex2=$link->query("select pensioner from senior where age >79 and barangay like'".$_GET["barangay"]."%' and age like'".$_GET["age"]."%' group by pensioner order by pensioner") or die(mysqli_error($link));
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
							if($_GET["pensioner"]===$rs2[0])
							echo "selected";
							echo" >$rs2[0]</option>";
						}
					?>
				</select>	
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('senior_statistics.php')"><i class="fa fa-chart-area tpad"></i> <x class="thid">View Stats</x></button>
				<button class="thid bmargin btn btn-sm btn-outline-secondary" type='button' onclick="printF()"><i class="fa fa-print tpad"></i> <x class="thid">Print</x></button>					
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

<!-- Table Spacer -->
<div class="list"></div>

<div class="container-fluid px-4 py-3">
	<!-- Print Header -->
    <div style="text-align:center;display:none" id="head" class="mb-4">
        <table align="center" style="margin:0 auto;">
            <tr>
                <td><img src="images/logo.webp" height="50"></td>
                <td class="px-3 text-center">
                    <b style="font-size:16px;text-transform:uppercase">List of Senior Citizens</b>
                    <br>MUNICIPALITY OF TABINA
                    <?php
                        if(isset($_GET["barangay"]) && $_GET["barangay"]!="All barangays" && $_GET["barangay"]!="") echo "<br><b>Barangay ".htmlspecialchars($_GET["barangay"])."</b>";
                        if(isset($_GET["pensioner"]) && $_GET["pensioner"]!="With pensions" && $_GET["pensioner"]!="") echo "<br>With Pension: <b>".htmlspecialchars($_GET["pensioner"])."</b>";
                        if(isset($_GET["age"]) && $_GET["age"]!="All ages" && $_GET["age"]!="") echo "<br>Years Old: <b>".htmlspecialchars($_GET["age"])."</b>";
                    ?>
                </td>
                <td><img src="images/osca_logo_tabina.png" height="50"></td>
            </tr>
        </table>
    </div>

<!-- Table Container Card -->
    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-header bg-danger text-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 font-weight-bold">
                <i class="fas fa-users mr-2"></i>Senior 80+ 
            </h6>
            <span class="badge badge-light font-weight-bold py-1 px-2">Total: <?php echo number_format(mysqli_num_rows($ex1),0);?> Records</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
                <thead class="thead-dark text-uppercase font-weight-bold">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="24%">Name</th>
                        <th width="8%" class="text-center">OSCA ID</th>
                        <th width="6%" class="text-center">Pension</th>
                        <th width="10%" class="text-center">NCSC</th>
                        <th width="4%" class="text-center">Sex</th>
                        <th width="10%" class="text-center">Birth Date</th>
                        <th width="5%" class="text-center">Age</th>
                        <th width="10%">Contact</th>
                        <th width="11%">Purok</th>	
                        <th width="11%">Barangay</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $rep = "<b class='bg-warning px-1 rounded text-dark'>" . htmlspecialchars($value) . "</b>";
                    $ctr = 1;	
                    
                    while($rs=mysqli_fetch_array($ex)){
                        if($ctr<=$rec){
                            $fullname = $rs["name_fam"] . ", " . $rs["name_1st"] . " " . $rs["name_mid"];
                            if ($value !== "") {
                                $fullname_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($fullname));
                                $birth_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars((!empty($rs["date_birth"]) && $rs["date_birth"] !== "0000-00-00" ? date("m/d/Y", strtotime($rs["date_birth"])) : "N/A")));
                                $barangay_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($rs["barangay"]));
                            } else {
                                $fullname_disp = htmlspecialchars($fullname);
                                $birth_disp = htmlspecialchars((!empty($rs["date_birth"]) && $rs["date_birth"] !== "0000-00-00" ? date("m/d/Y", strtotime($rs["date_birth"])) : "N/A"));
                                $barangay_disp = htmlspecialchars($rs["barangay"]);
                            }

                            echo "<tr class='tr-hover' style='cursor:pointer;' onclick=\"jump('senior_pds.php?senior=".$rs[0]."')\">";
                            echo "<td class='text-center font-weight-bold text-muted'>$i.</td>";
                            echo "<td class='font-weight-bold text-danger text-uppercase'>$fullname_disp</td>";
                            echo "<td class='text-center'><span class='badge badge-danger px-2 py-1'>".sprintf("%04d", $rs["assoc_id_no"])."</span></td>";
                            echo "<td class='text-center'>".($rs["pensioner"]==="Yes" ? "<span class='badge badge-success'>Yes</span>" : "<span class='badge badge-secondary'>No</span>")."</td>";
                            echo "<td class='text-center text-muted'>".($rs["ncsc_rrn"] ? htmlspecialchars($rs["ncsc_rrn"]) : "N/A")."</td>";
                            echo "<td class='text-center font-weight-bold'>".($rs["sex"]=="Male" ? "<span class='text-info'>M</span>" : "<span class='text-danger'>F</span>")."</td>";
                            echo "<td class='text-center'>$birth_disp</td>";
                            echo "<td class='text-center font-weight-bold'>".$rs["age"]."</td>";
                            echo "<td>".($rs["mobileno"] ? htmlspecialchars($rs["mobileno"]) : "N/A")."</td>";
                            echo "<td>".($rs["purok"] ? htmlspecialchars($rs["purok"]) : "N/A")."</td>";
                            echo "<td class='font-weight-bold'>$barangay_disp</td>";
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

<!-- Print Function -->
<script>
	function printF(){
		getID('head').style.display='block';
		getID('topcontrol').style.display='none';
		$(".t_controls").css("display","none");
		$(".hid").css("display","none");		
		$(".list").css("display","none");
		
	window.print();
		getID('head').style.display='none';
		getID('topcontrol').style.display='block';
		$(".t_controls").css("display","block");
		$(".hid").css("display","table-cell");
		$(".list").css("display","block");		
	}
</script>
