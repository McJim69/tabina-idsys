<?php
	require("connect.php");
	require("header.php");
	require("menu.php");

	$value = isset($_GET['value']) ? trim($_GET['value']) : (isset($_POST['t_search']) ? trim($_POST['t_search']) : "");
	$value_escaped = mysqli_real_escape_string($link, $value);
	
	$bar="";
	if(isset($_GET["barangays"]) && $_GET["barangays"]!="All barangays" && $_GET["barangays"]!="")
		$bar=" and barangay='".mysqli_real_escape_string($link, $_GET["barangays"])."'";
	
	$rec=1000;
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
		
	$ex=$link->query("select * from kinder where 
	   (idn like'%".$value_escaped."%' or
		name_fam like'%".$value_escaped."%' or
		name_1st like'%".$value_escaped."%' or
		name_mid like'%".$value_escaped."%' or
		barangay like'%".$value_escaped."%' or
		purok like'%".$value_escaped."%') $bar order by idn LIMIT $from,$to ");	
		
	$ex1=$link->query("select * from kinder where 
	   (idn like'%".$value_escaped."%' or
		name_fam like'%".$value_escaped."%' or
		name_1st like'%".$value_escaped."%' or
		name_mid like'%".$value_escaped."%' or
		barangay like'%".$value_escaped."%' or
		purok like'%".$value_escaped."%') $bar order by idn ");	
?>

<script>setActive("kinder");</script>
<script>setActive("social");</script>
<script>setActive("kinderlist");</script>

<script type="text/javascript" src="scripts/scrolltopcontrol.js"></script>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Search Kindergarten..." type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class='thid'>Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('kinder_grid.php')"><i class="fa fa-th tpad"></i> <x class='thid'>Card View</x></button>
				<button class="thid bmargin btn btn-sm btn-outline-secondary" type='button' onclick="printF()"><i class="fa fa-print"></i> Print</button>	
				<select class="swid spad bmargin btn btn-sm btn-outline-primary" onchange="if(this.value=='All barangays')jump('kinder_list.php'); else jump('kinder_list.php?barangays='+this.value+'')">
					<option>All Barangays</option>
					<?php
						$ex2=$link->query("select barangay from kinder group by barangay order by barangay");										
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
								if($_GET["barangays"]===$rs2[0])
								echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="getID('t_search').value='';jump('kinder_list.php')"><i class="fa fa-sync tpad"></i> <x class='thid'>Refresh</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='kinder_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add Kinder</x></button></a>";
				?>			
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
                    <b style="font-size:16px;text-transform:uppercase">List of Kindergarten</b>
                    <br>MUNICIPALITY OF TABINA
                    <?php if(isset($_GET["barangays"]) && $_GET["barangays"]!="All barangays" && $_GET["barangays"]!="") echo "<br><b>Barangay ".htmlspecialchars($_GET["barangays"])."</b>"; ?>
                </td>
                <td><img src="images/osca_logo2.png" height="50"></td>
            </tr>
        </table>
    </div>

    <!-- Table Container Card -->
    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-header text-white py-3 d-flex align-items-center justify-content-between" style="background-color: #20c997;">
            <h6 class="mb-0 font-weight-bold">
                <i class="fas fa-child mr-2"></i>Kindergarten
            </h6>
            <span class="badge badge-light font-weight-bold py-1 px-2">Total: <?php echo number_format(mysqli_num_rows($ex1),0);?> Records</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
                <thead class="thead-dark text-uppercase font-weight-bold">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="24%">Name</th>
                        <th width="8%" class="text-center">IDN</th>
                        <th width="16%">Parent</th>
                        <th width="5%" class="text-center">Sex</th>
                        <th width="10%" class="text-center">Birth</th>
                        <th width="11%">Contact</th>
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
                            $birth_str = "";
                            if (!empty($rs["date_birth"]) && $rs["date_birth"] !== "0000-00-00") {
                                $birth_str = date("m/d/Y", strtotime($rs["date_birth"]));
                            }
                            if ($value !== "") {
                                $fullname_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($fullname));
                                $birth_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($birth_str));
                                $barangay_disp = str_ireplace(htmlspecialchars($value), $rep, htmlspecialchars($rs["barangay"]));
                            } else {
                                $fullname_disp = htmlspecialchars($fullname);
                                $birth_disp = htmlspecialchars($birth_str);
                                $barangay_disp = htmlspecialchars($rs["barangay"]);
                            }

                            echo "<tr class='tr-hover' style='cursor:pointer;' onclick=\"jump('kinder_pds.php?kinder=".$rs[0]."')\">";
                            echo "<td class='text-center font-weight-bold text-muted'>$i.</td>";
                            echo "<td class='font-weight-bold text-dark text-uppercase'>$fullname_disp</td>";
                            echo "<td class='text-center'><span class='badge badge-success px-2 py-1'>".sprintf("%04d", $rs[0])."</span></td>";
                            echo "<td class='font-weight-bold'>".($rs["parent"] ? htmlspecialchars($rs["parent"]) : "N/A")."</td>";
                            echo "<td class='text-center font-weight-bold'>".($rs["sex"]=="Male" ? "<span class='text-info'>M</span>" : "<span class='text-danger'>F</span>")."</td>";
                            echo "<td class='text-center'>$birth_disp</td>";
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
