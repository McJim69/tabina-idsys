<?php
	require("connect.php");
	require("header.php");
	require("menu.php");
	
	$value=$_GET['value'];
	
	if($_GET["municipality"]!="All municipality" && $_GET["municipality"]!="")
		$mun=" and city_mun='".$_GET["municipality"]."'";
		
	$bar="";
	if($_GET["barangay"]!="All barangays" && $_GET["barangay"]!="")
		$bar=" and barangay='".$_GET["barangay"]."'";
		
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
	
	$ex1=$link->query("select * from indigents where (fullname like'%".$value."%' or barangay like'%".$value."%') $bar ");			
				
	$ex=$link->query("select * from indigents where (fullname like'%".$value."%' or barangay like'%".$value."%') $bar order by fullname LIMIT $from,$to ");			
?>

<script>setActive("social");</script>
<script>setActive("4ps");</script>
<script>setActive("4pslist");</script>

<script type="text/javascript" src="scripts/scrolltopcontrol.js"></script>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Search 4Ps..." type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('indigents_grid.php')"><i class="fa fa-th tpad"></i> <x class="thid">Card View</x></button>
				<button class="thid bmargin btn btn-sm btn-outline-secondary" type='button' onclick="printF()"><i class="fa fa-print"></i> Print</button>	
				<select class="swid spad bmargin btn btn-sm btn-outline-primary" onchange="if(this.value=='All barangays')jump('indigents_list.php'); else jump('?municipality=<?php echo $_GET["municipality"]; ?>&barangay='+this.value)" >
					<option>All Barangays</option>
					<?php
						$ex2=$link->query("select barangay from indigents group by barangay order by barangay");										
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
								if($_GET["barangays"]===$rs2[0])
								echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="getID('t_search').value='';jump('indigents_list.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='indigents_add_form.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add 4Ps</x></button></a>";
				?>			
				<button class="thid bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('indigents_duplicates.php')"><i class="fa fa-users tpad"></i> <x class="thid">Duplicated</x></button>
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

<!-- Table Body Spacer -->
<div class="list"></div>

<div class="container-fluid px-4 py-3">
    <!-- Print Header -->
    <div style="text-align:center;display:none" id="head" class="mb-4">
        <table align="center" style="margin:0 auto;">
            <tr>
                <td><img src="images/seal.png" height="50"></td>
                <td class="px-3 text-center">
                    <b style="font-size:16px;text-transform:uppercase">List of 4Ps Indigent Beneficiaries</b>
                    <br>MUNICIPALITY OF TABINA
                    <?php if(isset($_GET["barangay"]) && $_GET["barangay"]!="All barangays" && $_GET["barangay"]!="") echo "<br><b>Barangay ".htmlspecialchars($_GET["barangay"])."</b>"; ?>
                </td>
                <td><img src="images/DSWD.png" height="50"></td>
            </tr>
        </table>
    </div>

    <!-- Table Container Card -->
    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-header bg-primary text-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 font-weight-bold">
                <i class="fas fa-hands-helping mr-2"></i>4Ps List
            </h6>
            <span class="badge badge-light font-weight-bold py-1 px-2">Total: <?php echo number_format(mysqli_num_rows($ex1),0);?> Records</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
                <thead class="thead-dark text-uppercase font-weight-bold">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="28%">Full Name</th>
                        <th width="8%" class="text-center">IDN</th>
                        <th width="14%">Period</th>
                        <th width="12%" class="text-right">Amount</th>
                        <th width="12%" class="text-center">Date</th>
                        <th width="10%">Barangay</th>
                        <th width="12%">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $rep = "<b class='bg-warning px-1 rounded text-dark'>" . htmlspecialchars($value) . "</b>";
                    $ctr = 1;	
                    
                    while($rs=mysqli_fetch_array($ex)){
                        if($ctr<=$rec){
                            if ($value !== "") {
                                $fullname_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($rs["fullname"]));
                                $barangay_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($rs["barangay"]));
                            } else {
                                $fullname_disp = htmlspecialchars($rs["fullname"]);
                                $barangay_disp = htmlspecialchars($rs["barangay"]);
                            }

                            echo "<tr class='tr-hover' style='cursor:pointer;' onclick=\"jump('indigents_pds.php?indigents=".$rs[0]."')\">";
                            echo "<td class='text-center font-weight-bold text-muted'>$i.</td>";
                            echo "<td class='font-weight-bold text-primary text-uppercase'>$fullname_disp</td>";
                            echo "<td class='text-center'><span class='badge badge-primary px-2 py-1'>".sprintf("%04d", $rs[0])."</span></td>";
                            echo "<td class='font-weight-bold'>".($rs["period"] ? htmlspecialchars($rs["period"]) : "N/A")."</td>";
                            echo "<td class='text-right font-weight-bold text-success'>".($rs["amount"] ? "₱".number_format((float)$rs["amount"],2) : "N/A")."</td>";
                            echo "<td class='text-center'>".($rs["date_paid"] ? htmlspecialchars($rs["date_paid"]) : "N/A")."</td>";
                            echo "<td class='font-weight-bold'>$barangay_disp</td>";
                            echo "<td class='small text-muted'>".($rs["remarks"] ? htmlspecialchars($rs["remarks"]) : "N/A")."</td>";
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