<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script> setActive("messages"); </script>
<script> setActive("msgin"); </script>

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<a href='messages_grid.php'><img src='images/back.png' height='30' title='Back'></a>
					&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
				<a href=""><img onclick='printF()' src='images/print2.png' height='30' title='Print'></a>
			</div>				
		</div>
	</div>
</div>

<div class="msgpad"><br><br><br><br></div>

<div class="container">
	<div class="row d-flex justify-content-center align-items-center flex-wrap" style="padding:30px 10px 10px 10px">
		<div class="col-lg-7">
			<?php		
				$rec=1;
					$p=$_GET['page'];
						if($p>1){
							$to=$rec;
							$from=($p*$rec)-$rec;
							$i=(($p-1)*$rec)+1;
						}
						else{
							$to=$rec;
							$from=0;
							$i=1;
							$p=1;
						}			
							
						$vis="";
						if($_GET["messages"]!="")
							$vis=" and idn='".$_GET["messages"]."' ";
																			
						$ex=$link->query("select * from messages where idn=idn $vis order by idn limit $from,$to ");
						
						while($rs=mysqli_fetch_array($ex)){
																				
						$ex=$link->query("select * from messages where messages.idn='$rs[0]' and messages.idn=messages.idn ");
										
						while(mysqli_fetch_array($ex)){
				
						$eee=$link->query("select * from messages l where idn='".$rs["idn"]."'");
						$rsm=mysqli_fetch_array($eee);
								
						$ex=$link->query("select * from messages where messages.idn='".$rs[0]."' and messages.idn=messages.idn ");
						
						while($rsm=mysqli_fetch_array($ex)){
						
							if(file_exists("images/messages/$rs[0].jpg")){
							echo"
								<div style='position:relative;cursor:pointer;padding-bottom:40px' onclick='printF()'>
									<img src='images/messages/$rs[0].jpg' style='width:100%;border:2px solid #bbb;border-radius:20px'/>							
									<div>
										<div class='thid' style='position:absolute;top:20px;right:-50px'>
											<img src='images/messages/qrcodes/$rs[0].png' width='50%'/>
										</div>
									</div>
								</div>";
							} else {
								echo"<br><br><br>No Image to print...<br><br>
									<a href='javascript:history.back()'><img class='image' src='images/back.png' height='40' title='Back' /></a>
								";
							}
						}
					}
				}
			?>
		</div>
	</div>
</div>		

<script>
	function printF(){
		$(".t_controls").css("display","none");
		$(".msgpad").css("display","none");

	window.print();
		$(".t_controls").css("display","block");
		$(".msgpad").css("display","block");
	}
</script>

</body>

</html>
