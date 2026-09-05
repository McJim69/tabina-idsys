<?php
	require("connect.php");
	require("header.php");
	require("menu.php");
?>
	
<?php		
		
	$value=$_GET['value'];
	
	$bar="";
	if($_GET["barangays"]!="All barangays" && $_GET["barangays"]!="")
	$bar=" and barangay='".$_GET["barangays"]."'";
	
	if(isset($_POST["b_search"])){
	$value=$_POST["t_search"];
	}
	
	$rec=200;
	$p=$_GET['page'];
	if($p>1){
		$to=$p*$rec;
		$from=$to-$rec;
		$i=$to+1-$rec;
	}
	else{
		$to=$rec;
		$from=0;
		$i=1;
		$p=1;
	}
			
	$ex=$link->query("select * from clearances where (
	    idn like'%".$value."%' or
		name_fam like'%".$value."%' or
		name_1st like'%".$value."%' or
		name_mid like'%".$value."%' or
		barangay like'%".$value."%' or
		purok like'%".$value."%' or		
		sex like'%".$value."%') $bar order by idn LIMIT $from,$to ");		

	$ex1=$link->query("select * from clearances where (
	    idn like'%".$value."%' or
		name_fam like'%".$value."%' or
		name_1st like'%".$value."%' or
		name_mid like'%".$value."%' or
		barangay like'%".$value."%' or
		purok like'%".$value."%' or
		sex like'%".$value."%') $bar order by idn");
	//
?>

<script>setActive("certclear");</script>
<script>setActive("clear");</script>
<script>setActive("clearlist");</script>

<script type="text/javascript" src="scripts/scrolltopcontrol.js"></script>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-primary" type="button" onclick="jump('mayor_clearance_grid.php')"><i class="fa fa-th tpad"></i> <x class="thid"> Card View</x></button>	
				<select class="swid spad bmargin btn btn-sm btn-outline-primary" onchange="if(this.value=='All barangays')jump('mayor_clearance_list.php'); else jump('mayor_clearance_list.php?barangays='+this.value+'')">
					<option>All barangays</option>
					<?php
						$ex2=$link->query("select barangay from clearances group by barangay order by barangay");										
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
							if($_GET["barangays"]===$rs2[0])
								echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="getID('t_search').value='';jump('mayor_clearance_list.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<a rel="facebox" href="mayor_clearance_add.php"><button class="bmargin btn btn-sm btn-outline-success" type="button"><i class="fa fa-plus tpad"></i> <x class="thid">Add Clearance</x></button></a>
				<button class="thid bmargin btn btn-sm btn-outline-info" type="button" onclick="printF()"><i class="fa fa-print tpad"></i> Print</button>			
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
                    <b style="font-size:16px;text-transform:uppercase">Mayor's Clearance Registry</b>
                    <br>MUNICIPALITY OF TABINA
                    <?php if(isset($_GET["barangays"]) && $_GET["barangays"]!="All barangays" && $_GET["barangays"]!="") echo "<br><b>Barangay ".htmlspecialchars($_GET["barangays"])."</b>"; ?>
                </td>
                <td><img src="images/osca_logo2.png" height="50"></td>
            </tr>
        </table>
    </div>

    <!-- Table Container Card -->
    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-header bg-primary text-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 font-weight-bold">
                <i class="fas fa-file-signature mr-2"></i><x class="thid">Mayor's</x> Clearance
            </h6>
            <span class="badge badge-light font-weight-bold py-1 px-2">Total: <?php echo number_format(mysqli_num_rows($ex1),0);?> Records</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
                <thead class="thead-dark text-uppercase font-weight-bold">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="26%">Applicant <x class="thid">Name</x></th>
                        <th width="8%" class="text-center">IDN</th>
                        <th width="10%" class="text-center">O.R. No</th>
                        <th width="10%" class="text-right">Amount</th>
                        <th width="5%" class="text-center">Sex</th>
                        <th width="10%" class="text-center"><x class="thid">Civil</x> Status</th>
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

                            echo "<tr class='tr-hover' style='cursor:pointer;' onclick=\"jump('mayor_clearance_pds.php?clearances=".$rs[0]."')\">";
                            echo "<td class='text-center font-weight-bold text-muted'>$i.</td>";
                            echo "<td class='font-weight-bold text-primary text-uppercase'>$fullname_disp</td>";
                            echo "<td class='text-center'><span class='badge badge-primary px-2 py-1'>".sprintf("%04d", $rs[0])."</span></td>";
                            echo "<td class='text-center font-weight-bold'>".($rs["isorno"] ? htmlspecialchars($rs["isorno"]) : "N/A")."</td>";
                            echo "<td class='text-right font-weight-bold text-success'>".(isset($rs["oramount"]) && $rs["oramount"] ? "₱".number_format((float)$rs["oramount"],2) : "N/A")."</td>";
                            echo "<td class='text-center font-weight-bold'>".($rs["sex"]=="Male" ? "<span class='text-info'>M</span>" : "<span class='text-danger'>F</span>")."</td>";
                            echo "<td class='text-center'>".($rs["civil_status"] ? htmlspecialchars($rs["civil_status"]) : "N/A")."</td>";
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
