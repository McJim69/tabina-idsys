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
			$to=$rec;
			$from=($p*$rec)-$rec;
			$i=(($p-1)*$rec)+1;
		}else{
			$to=$rec;
			$from=0;
			$i=1;
			$p=1;
		}
									
	$ex=$link->query("select * from permit_business l where (
		l.idn like'%".$value."%' or
		l.name_fam like'%".$value."%' or		
		l.name_1st like'%".$value."%' or		
		l.name_mid like'%".$value."%' or		
		l.tradename like'%".$value."%' or		
		l.purok like'%".$value."%' or			
		l.barangay like'%".$value."%' or
		l.city_mun like'%".$value."%' or
		l.province like'%".$value."%') $bar order by idn DESC LIMIT $from,$to ");		

	$ex1=$link->query("select * from permit_business l where (
		l.idn like'%".$value."%' or
		l.name_fam like'%".$value."%' or		
		l.name_1st like'%".$value."%' or		
		l.name_mid like'%".$value."%' or		
		l.tradename like'%".$value."%' or		
		l.purok like'%".$value."%' or			
		l.barangay like'%".$value."%' or
		l.city_mun like'%".$value."%' or
		l.province like'%".$value."%') $bar order by idn DESC ");		
	//
?>

<script> setActive("permit"); </script>
<script> setActive("business"); </script>
<script> setActive("businesslist"); </script>

<script type="text/javascript" src="scripts/scrolltopcontrol.js"></script>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">			
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid"> Search</x></button>
				<button class="bmargin btn btn-sm btn-secondary" type="button" onclick="jump('permit_business_grid.php')"><i class="fa fa-th tpad"></i> <x class="thid"> Card View</x></button>
				<select class="swid spad bmargin btn btn-sm btn-outline-primary" onchange="if(this.value=='All barangays')jump('permit_business_list.php'); else jump('permit_business_list.php?barangays='+this.value+'')">
					<option>All barangays</option>
					<?php
						$ex2=$link->query("select barangay from permit_business group by barangay order by barangay");										
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
								if($_GET["barangays"]===$rs2[0])
								echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-info" type="button" onclick="getID('t_search').value='';jump('permit_business_list.php')"><i class="fa fa-sync tpad"></i> <x class="thid"> Refresh</x></button>
				<a rel="facebox" href='permit_business_add.php'><button class="bmargin btn btn-sm btn-success" type='button'><i class="fa fa-plus tpad"></i> <x class="thid"> Add Permit</x></button></a>
				<button class="thid bmargin btn btn-sm btn-secondary" type='button' onclick="printF()"><i class="fa fa-print tpad"></i> <x class="thid"> Print</x></button>	
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
                    <b style="font-size:16px;text-transform:uppercase">Business Permit Registry Directory</b>
                    <br>MUNICIPALITY OF TABINA
                    <?php if(isset($_GET["barangays"]) && $_GET["barangays"]!="All barangays" && $_GET["barangays"]!="") echo "<br><b>Barangay ".htmlspecialchars($_GET["barangays"])."</b>"; ?>
                </td>
                <td><img src="images/proudlypinoy.png" height="50"></td>
            </tr>
        </table>
    </div>

    <!-- Table Container Card -->
    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-header bg-primary text-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 font-weight-bold">
                <i class="fas fa-store mr-2"></i>Business <x class="thid">Permit (BP) Registry</x>
            </h6>
            <span class="badge badge-light font-weight-bold py-1 px-2">Total: <?php echo number_format(mysqli_num_rows($ex1),0);?> Records</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
                <thead class="thead-dark text-uppercase font-weight-bold">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="22%"><x class="thid">Business</x> Trade Name</th>
                        <th width="8%" class="text-center">BP ID</th>
                        <th width="18%">Nature <x class="thid">of Business</x></th>
                        <th width="18%">Proprietor <x class="thid">Name</x></th>
                        <th width="10%" class="text-center">O.R. No</th>
                        <th width="10%" class="text-right">Amount</th>
                        <th width="10%">Barangay</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $rep = "<b class='bg-warning px-1 rounded text-dark'>" . htmlspecialchars($value) . "</b>";
                    $ctr = 1;	
                    
                    while($rs=mysqli_fetch_array($ex)){
                        if($ctr<=$rec){
                            $owner = $rs["name_fam"] . ", " . $rs["name_1st"] . " " . $rs["name_mid"];
                            if ($value !== "") {
                                $trade_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($rs["tradename"]));
                                $owner_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($owner));
                                $barangay_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($rs["barangay"]));
                            } else {
                                $trade_disp = htmlspecialchars($rs["tradename"]);
                                $owner_disp = htmlspecialchars($owner);
                                $barangay_disp = htmlspecialchars($rs["barangay"]);
                            }

                            echo "<tr class='tr-hover' style='cursor:pointer;' onclick=\"jump('permit_business_pds.php?permit_business=".$rs[0]."')\">";
                            echo "<td class='text-center font-weight-bold text-muted'>$i.</td>";
                            echo "<td class='font-weight-bold text-primary text-uppercase'>$trade_disp</td>";
                            echo "<td class='text-center'><span class='badge badge-primary px-2 py-1'>".sprintf("%04d", $rs[0])."</span></td>";
                            echo "<td class='font-weight-bold text-dark'>".($rs["activity"] ? htmlspecialchars($rs["activity"]) : "N/A")."</td>";
                            echo "<td class='font-weight-bold text-uppercase'>$owner_disp</td>";
                            echo "<td class='text-center font-weight-bold'>".($rs["isorno"] ? htmlspecialchars($rs["isorno"]) : "N/A")."</td>";
                            echo "<td class='text-right font-weight-bold text-success'>".($rs["oramount"] ? "₱".number_format((float)$rs["oramount"],2) : "N/A")."</td>";
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
