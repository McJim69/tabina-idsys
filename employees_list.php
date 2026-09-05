<?php
	require("connect.php");
	require("header.php");
	require("menu.php");

	$value=$_GET['value'];
	
	$dep="";
	if($_GET["departments"]!="All departments" && $_GET["departments"]!="")
	$dep=" and department='".$_GET["departments"]."'";
				
	$pos="";
		if($_GET["positions"]!="All Positions" && $_GET["positions"]!="")
			$pos=" and position='".$_GET["positions"]."'";

	$bar="";
		if($_GET["barangays"]!="All barangays" && $_GET["barangays"]!="")
			$bar=" and barangay='".$_GET["barangays"]."'";
	
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
		
	$ex = $link->query("select * from employees l where (
		l.idn like'%".$value."%' or
		l.name_fam like'%".$value."%' or
		l.name_1st like'%".$value."%' or
		l.name_mid like'%".$value."%' or
		l.department like'%".$value."%' or		
		l.agency like'%".$value."%' or
		l.position like'%".$value."%' or
		l.purok like'%".$value."%' or
		l.barangay like'%".$value."%') $dep $pos $bar order by name_fam LIMIT $from,$to ");			

	$ex1 = $link->query("select * from employees l where (
		l.idn like'%".$value."%' or
		l.name_fam like'%".$value."%' or
		l.name_1st like'%".$value."%' or
		l.name_mid like'%".$value."%' or
		l.department like'%".$value."%' or		
		l.agency like'%".$value."%' or
		l.position like'%".$value."%' or
		l.purok like'%".$value."%' or
		l.barangay like'%".$value."%') $dep $pos $bar order by name_fam ");			
	//
?>


<script>setActive("employee");</script>
<script>setActive("employees");</script>
<script>setActive("employeelist");</script>

<script type="text/javascript" src="scripts/scrolltopcontrol.js"></script>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input class="bmargin btn btn-sm btn-outline-primary swid" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('employees_grid.php')"><i class="fa fa-th tpad"></i> <x class="thid">Grid</x></button>
				<button class="thid bmargin btn btn-sm btn-outline-secondary" type='button' onclick="printF()"><i class="fa fa-print"></i> Print</button>
				<select class="spad swid bmargin btn btn-sm btn-outline-dark" onchange="if(this.value=='All departments')jump('employees_list.php'); else jump('employees_list.php?departments='+this.value+'&positions=<?php echo $_GET["positions"];?>&barangays=<?php echo $_GET["barangays"];?>')">
					<option>All departments</option>
					<?php
						$ex2 = $link->query("select department from employees where position='".$_GET["positions"]."' group by department order by department");
						if($_GET["positions"]=="" || $_GET["positions"]=="All positions")
						$ex2 = $link->query("select department from employees group by department order by department");										
						while($rs2 = mysqli_fetch_array($ex2)){					
							echo"<option ";
								if($_GET["departments"]==="$rs2[0]")
							echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="getID('t_search').value='';jump('employees_list.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='employees_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add</x></button></a>";
				?>			
				<select class="thid bmargin btn btn-sm btn-outline-primary swid" onchange="jump('?departments=<?php echo $_GET["departments"];?>&barangays=<?php echo $_GET["barangays"];?>&positions='+this.value)">
					<option>All positions</option>
					<?php
						$ex2 = $link->query("select position from employees where department='".$_GET["departments"]."' group by position order by position");
						if($_GET["departments"]=="" || $_GET["departments"]=="All departments")
						$ex2 = $link->query("select position from employees group by position order by position");										
						while($rs2 = mysqli_fetch_array($ex2)){					
							echo"<option ";
							if($_GET["positions"]==="$rs2[0]")
							echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<select class="thid bmargin btn btn-sm btn-outline-secondary" onchange="jump('?departments=<?php echo $_GET["departments"];?>&positions=<?php echo $_GET["positions"];?>&barangays='+this.value)">
					<option>All barangays</option>
					<?php
						$ex2 = $link->query("select barangay from employees where position='".$_GET["positions"]."' and department='".$_GET["departments"]."' group by barangay order by barangay");
						if($_GET["positions"]=="" || $_GET["positions"]=="All positions")
						$ex2 = $link->query("select barangay from employees group by barangay order by barangay");										
						while($rs2 = mysqli_fetch_array($ex2)){					
							echo"<option ";
							if($_GET["barangays"]==="$rs2[0]")
							echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
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
                <td><img src="images/seal.png" height="50"></td>
                <td class="px-3 text-center">
                    <b style="font-size:16px;text-transform:uppercase">List of Municipal Employees</b>
                    <br>Municipality of Tabina, Zamboanga del Sur
                    <?php if(isset($_GET["departments"]) && $_GET["departments"]!="All departments" && $_GET["departments"]!="") echo "<br>Department: <b>".htmlspecialchars($_GET["departments"])."</b>"; ?>
                </td>
                <td><img src="images/osca_logo2.png" height="50"></td>
            </tr>
        </table>
    </div>

    <!-- Table Container Card -->
    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-header bg-primary text-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 font-weight-bold">
                <i class="fas fa-id-badge mr-2"></i>Employees List
            </h6>
            <span class="badge badge-light font-weight-bold py-1 px-2">Total: <?php echo number_format(mysqli_num_rows($ex1),0);?> Records</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
                <thead class="thead-dark text-uppercase font-weight-bold">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="22%">Name</th>
                        <th width="7%" class="text-center">IDN</th>
                        <th width="15%">Position</th>
                        <th width="15%">Office</th>
                        <th width="10%">Contact</th>
                        <th width="13%">Purok</th>	
                        <th width="14%">Barangay</th>
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
                                $barangay_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($rs["barangay"]));
                            } else {
                                $fullname_disp = htmlspecialchars($fullname);
                                $barangay_disp = htmlspecialchars($rs["barangay"]);
                            }

                            echo "<tr class='tr-hover' style='cursor:pointer;' onclick=\"jump('employees_pds.php?employees=".$rs[0]."')\">";
                            echo "<td class='text-center font-weight-bold text-muted'>$i.</td>";
                            echo "<td class='font-weight-bold text-primary text-uppercase'>$fullname_disp</td>";
                            echo "<td class='text-center'><span class='badge badge-primary px-2 py-1'>".sprintf("%04d", $rs[0])."</span></td>";
                            echo "<td class='font-weight-bold text-dark'>".($rs["position"] ? htmlspecialchars($rs["position"]) : "N/A")."</td>";
                            echo "<td><span class='badge badge-light border text-dark font-weight-normal px-2 py-1'>".($rs["department"] ? htmlspecialchars($rs["department"]) : "N/A")."</span></td>";
                            echo "<td>".($rs["contact"] ? htmlspecialchars($rs["contact"]) : "N/A")."</td>";
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

<!-- Print Handler Script -->
<script>
	function printF(){
		if(getID('topcontrol')) getID('topcontrol').style.display='none';
		getID('head').style.display='block';
		$(".t_controls").css("display","none");
		$(".hid").css("display","none");		
		$(".list").css("display","none");
		
	    window.print();
		if(getID('topcontrol')) getID('topcontrol').style.display='block';
		getID('head').style.display='none';
		$(".t_controls").css("display","block");
		$(".hid").css("display","table-cell");
		$(".list").css("display","block");		
	}
</script>
