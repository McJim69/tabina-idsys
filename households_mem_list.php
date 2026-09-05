<?php
	require("connect.php");
	require("header.php");
	require("menu.php");

	$value = isset($_GET['value']) ? trim($_GET['value']) : (isset($_POST['t_search']) ? trim($_POST['t_search']) : "");
	$value_escaped = mysqli_real_escape_string($link, $value);
		
	$mun="";
	if(isset($_GET["municipality"]) && $_GET["municipality"]!="All municipality" && $_GET["municipality"]!="")
		$mun=" and city_mun='".mysqli_real_escape_string($link, $_GET["municipality"])."'";

	$bar="";
	if(isset($_GET["barangays"]) && $_GET["barangays"]!="All barangays" && $_GET["barangays"]!="")
		$bar=" and barangay='".mysqli_real_escape_string($link, $_GET["barangays"])."'";
		
	$rec=20;
	$p=isset($_GET['page']) ? $_GET['page'] : 1;
	if($p>1){
		$to=$p*$rec;
		$from=$to-$rec;
		$i=$from+1;
	}else{
		$to=$rec;
		$from=0;
		$i=1;
		$p=1;
	}
									
	$ex=$link->query("select * from hh_members where 
	   (hm_name like'%".$value_escaped."%' or
		purok like'%".$value_escaped."%' or
		barangay like'%".$value_escaped."%' or
		hm_birth like'%".$value_escaped."%' or
		hm_belong like'%".$value_escaped."%' or
		hm_remarks like'%".$value_escaped."%') $mun $bar order by hm_name LIMIT $from,$to ");	
	
	$ex1=$link->query("select * from hh_members where 
	   (hm_name like'%".$value_escaped."%' or
		purok like'%".$value_escaped."%' or
		barangay like'%".$value_escaped."%' or
		hm_birth like'%".$value_escaped."%' or
	    hm_belong like'%".$value_escaped."%' or
		hm_remarks like'%".$value_escaped."%') $mun $bar order by hm_name ");	
?>

<script>setActive("household");</script>
<script>setActive("hhmemlist");</script>

<script type="text/javascript" src="scripts/scrolltopcontrol.js"></script>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Search HH member..." type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-danger" type="button" onclick="jump('households_grid.php')"><i class="fa fa-th tpad"></i> <x class="thid">Grid View</x></button>		
				<select class="swid spad bmargin btn btn-sm btn-outline-primary" onchange="if(this.value=='All barangays')jump('households_mem_list.php'); else jump('households_mem_list.php?barangays='+this.value+'')">
					<option>Barangays</option>
					<?php
						$ex2=$link->query("select barangay from hh_members where city_mun like'".$_GET["municipality"]."%' group by barangay order by barangay") or die(mysqli_error($link));
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
							if($_GET["barangay"]===$rs2[0])
							echo "selected";
							echo" >$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-success" type="button" onclick="getID('t_search').value='';jump('households_mem_list.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<button class="bmargin btn btn-sm btn-outline-info" type='button' onclick="printF()"><i class="fa fa-print tpad"></i> <x class="thid">Print</x></button>					
			</div>				
		</div>
		<div class="row">
			<div class="col d-flex justify-content-center">
				<?php require("pageNAV.php");?>
			</div>	
		</div>
	</div>
</div>

<!-- Table Spacer -->
<div class="list"></div>

<div class="container-fluid px-4 py-3">
    <!-- Print Header -->
    <div style="text-align:center;display:none" id="head" class="mb-4">
        <table align="center" style="margin:0 auto;">
            <tr>
                <td><img src="images/seal.png" height="50"></td>
                <td class="px-3 text-center">
                    <b style="font-size:16px;text-transform:uppercase">Household Members Directory</b>
                    <br>MUNICIPALITY OF TABINA
                    <?php if(isset($_GET["barangays"]) && $_GET["barangays"]!="All barangays" && $_GET["barangays"]!="") echo "<br><b>Barangay ".htmlspecialchars($_GET["barangays"])."</b>"; ?>
                </td>
                <td><img src="images/dswd.png" height="50"></td>
            </tr>
        </table>
    </div>

    <!-- Table Container Card -->
    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-header bg-primary text-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 font-weight-bold">
                <i class="fas fa-users-cog mr-2"></i>HH Members
            </h6>
            <span class="badge badge-light font-weight-bold py-1 px-2"><?php echo number_format(mysqli_num_rows($ex1),0);?> Records</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
                <thead class="thead-dark text-uppercase font-weight-bold">
                    <tr>
                        <th width="4%"  class="text-center">#</th>
                        <th width="24%">Name</th>
                        <th width="8%"  class="text-center">HMID</th>
						<th width="4%">HEAD</th>
                        <th width="15%">Education</th>
                        <th width="5%"  class="text-center">Sex</th>
                        <th width="10%" class="text-center">Birth</th>
                        <th width="10%">Income</th>
                        <th width="10%">Purok</th>	
                        <th width="10%">Barangay</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $rep = "<b class='bg-warning px-1 rounded text-dark'>" . htmlspecialchars($value) . "</b>";
                    $ctr = 1;	
                    
                    while($rs=mysqli_fetch_array($ex)){
                        if($ctr<=$rec){
                            if ($value !== "") {
                                $name_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($rs["hm_name"]));
                                $barangay_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($rs["barangay"]));
                            } else {
                                $name_disp = htmlspecialchars($rs["hm_name"]);
                                $barangay_disp = htmlspecialchars($rs["barangay"]);
                            }

                            echo "<tr class='tr-hover' style='cursor:pointer;' onclick=\"jump('households_pds.php?households=".$rs["hm_belong"]."')\">";
                            echo "<td class='text-center font-weight-bold text-muted'>$i.</td>";
                            echo "<td class='font-weight-bold text-primary text-uppercase'>$name_disp</td>";
                            echo "<td class='text-center'><span class='badge badge-primary px-2 py-1'>".sprintf("%04d", $rs[0])."</span></td>";
                            echo "<td>".$rs["hm_belong"]."</td>";
                            echo "<td class='font-weight-bold text-dark'>".($rs["hm_education"] ? htmlspecialchars($rs["hm_education"]) : "N/A")."</td>";
                            echo "<td class='text-center font-weight-bold'>".($rs["hm_sex"]=="Male" ? "<span class='text-info'>M</span>" : "<span class='text-danger'>F</span>")."</td>";
                            echo "<td class='text-center'>".($rs["hm_birth"] ? htmlspecialchars($rs["hm_birth"]) : "N/A")."</td>";
                            echo "<td>".($rs["hm_main_income"] ? htmlspecialchars($rs["hm_main_income"]) : "N/A")."</td>";
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
