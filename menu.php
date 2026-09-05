<?php
	require('connect.php');
	//MAIN MENUS
	DEFINE('_HOME',        '<a id="home" href="index.php">DASHBOARD</a>');
	DEFINE('_CHAT',        '<a id="chat" title="Group Chat, Private Messages, and Message Board">CHAT <span id="pm-unread-badge" class="badge badge-danger badge-pill" style="display:none; font-size:10px; margin-left:5px; vertical-align:middle; padding:3px 6px;">0</span> &#9660;</a>');
	DEFINE('_MSWDO',       '<a id="social" title="Social Services">MSWDO &#9660;</a>');
	DEFINE('_ADMIN',       '<a id="admin" title="Administrator">ADMIN &#9660;</a>');
	DEFINE('_PUBLIC',      '<a id="public" href="public_home.php" target="_blank">PUBLIC VIEW</a>');
	DEFINE('_LOGOUT',      '<a id="logout" onclick=\'sessionEnd("gid")\' style="cursor:pointer">LOGOUT</a>');
	DEFINE('_PERMITS',     '<a id="permit" title="Permits and Licenses">PERMITS &#9660;</a>');
	DEFINE('_MESSAGES',    '<a id="messages" title="Messages">MESSAGES &#9660;</a>');
	DEFINE('_ANALYTICS',   '<a id="analytics">ANALYTICS &#9660;</a>');
	DEFINE('_CERTCLEAR',   '<a id="certclear" title="Certificates and Clearances">CERTS &#9660;</a>');
	DEFINE('_CHATLOBBY',   '<a id="chatlobby" href="chat_rooms.php">GROUP CHAT</a>');
	DEFINE('_PRIVATEDM',   '<a id="privatedm" href="messenger.php">PRIVATE MESSAGE</a>');
	DEFINE('_SOCIALSTATS', '<a id="socialstats" href="social_statistics.php">SOSSTATS</a>');

	//PWD
	DEFINE('_PWD',         '<a id="pwd">PWD &#9660;</a>');
	DEFINE('_PWDCARD',     '<a id="pwdcard" href="pwd_idcard.php">PWD ID CARD</a>');
	DEFINE('_PWDLIST',     '<a id="pwdlist" href="pwd_list.php">PWD LIST VIEW</a>');
	DEFINE('_PWDGRID',     '<a id="pwdgrid" href="pwd_grid.php">PWD GRID VIEW</a>');

	//Senior
	DEFINE('_SENIOR',      '<a id="senior">SENIOR &#9660;</a>');
	DEFINE('_SENIOR80UP',  '<a id="senior80up" href="senior_grid_80up.php">SC 80-UP</a>');
	DEFINE('_SENIORCARD',  '<a id="seniorcard" href="senior_idcard.php">SC IDCARD</a>');
	DEFINE('_SENIORLIST',  '<a id="seniorlist" href="senior_list.php">SC LISTVIEW</a>');
	DEFINE('_SENIORGRID',  '<a id="seniorgrid" href="senior_grid.php">SC CARDVIEW</a>');
	DEFINE('_SENIORADD',   '<a id="senioradd"  href="senior_add.php" rel="facebox">ADD SENIOR</a>');

	//SAP
	DEFINE('_SAP',         '<a id="sap">SAP LIST &#9660;</a>');
	DEFINE('_SAPLIST', 	   '<a id="saplist" href="sap_ben_list.php">SAP-BEN LIST VIEW</a>');
	DEFINE('_SAPGRID',     '<a id="sapgrid" href="sap_ben_grid.php">SAP-BEN GRID VIEW</a>');

	//4PS
	DEFINE('_4PS', 		   '<a id="4ps">INDIGENTS &#9660;</a>');
	DEFINE('_4PSLIST', 	   '<a id="4pslist" href="indigents_list.php">INDIGENTS LIST VIEW</a></li>');
	DEFINE('_4PSGRID',     '<a id="4psgrid" href="indigents_grid.php">INDIGENTS GRID VIEW</a></li>');

	//Solo Parent	
	DEFINE('_SOLO', 	   '<a id="solo">SOLO PARENT &#9660;</a>');
	DEFINE('_SOLOCARD',    '<a href="solo_parent_idcard.php" id="solocard">SOLO PARENT ID CARD</a>');
	DEFINE('_SOLOLIST',    '<a href="solo_parent_list.php" id="sololist">SOLO PARENT LIST VIEW</a>');
	DEFINE('_SOLOGRID',    '<a href="solo_parent_grid.php" id="sologrid">SOLO PARENT GRID VIEW</a>');
												
	//Kindergarten	
	DEFINE('_KINDER',      '<li><a id="kinder">KINDERGARTEN &#9660;</a>');	
	DEFINE('_KINDERCARD',  '<li><a href="kinder_idcard.php" id="kindercard">KINDERGARTEN ID</a>');
	DEFINE('_KINDERCERT',  '<li><a href="kinder_cert.php" id="kindercert">KINDER CERTIFICATE</a>');
	DEFINE('_KINDERLIST',  '<li><a href="kinder_list.php" id="kinderlist">KINDERGARTEN LIST</a>');
	DEFINE('_KINDERGRID',  '<li><a href="kinder_grid.php" id="kindergrid">KINDERGARTEN GRID</a>');

	//Households	
	DEFINE('_HOUSEHOLDS',  '<a id="household">HOUSEHOLDS &#9660;</a>');										
	DEFINE('_HHLIST',      '<a href="households_list.php" id="hhlist">HOUSEHOLD LIST VIEW</a>');
	DEFINE('_HHGRID',      '<a href="households_grid.php" id="hhgrid">HOUSEHOLD GRID VIEW</a>');
	DEFINE('_HHSURVEY',    '<a href="households_add_form.php" id="hhsurvey">HOUSEHOLD SURVEY</a>');
	DEFINE('_HHMEMLIST',   '<a href="households_mem_list.php" id="hhmemlist">HOUSEHOLD MEMBER LIST</a>');
	//Households Enumerators	
	DEFINE('_HHS',		   '<a id="household">HOUSEHOLDS &#9660;</a>');										
	DEFINE('_HHL',         '<a href="households_list.php" id="hhlist">HH LIST VIEW</a>');
	DEFINE('_HHG',         '<a href="households_grid.php" id="hhgrid">HH GRID VIEW</a>');
	DEFINE('_HHF',         '<a href="households_add_form.php" id="hhsurvey">HH SURVEY</a>');
	DEFINE('_HHM',         '<a href="households_mem_list.php" id="hhmemlist">HH MEMBER LIST</a>');
	DEFINE('_HHA',         '<a href="households_analytics.php" id="hhstats">HH ANALYTICS</a>');
												
	//Employees	
	DEFINE('_EMPLOYEES',   '<a id="employee">EMPLOYEES &#9660;</a>');									
	DEFINE('_EMPCARD',     '<a href="employees_idcard.php" id="empcard">EMPLOYEES ID CARD</a>');
	DEFINE('_EMPLIST',     '<a href="employees_list.php" id="emplist">EMPLOYEES LIST VIEW</a>');
	DEFINE('_EMPGRID',     '<a href="employees_grid.php" id="empgrid">EMPLOYEES GRID VIEW</a>');
	DEFINE('_WORKPASS',    '<a href="employees_workpass.php" id="emppass">EMPLOYEES WORKING PASS</a>');
	DEFINE('_OFFICETAG',   '<a href="employees_idtag.php" id="emptag">EMPLOYEES OFFICE TAG</a>');
												
	//Visitors		
	DEFINE('_VISITORS',    '<a id="visitor">VISITORS &#9660;</a>');									
	DEFINE('_LOGBOOK',     '<a rel="facebox" href="visitors_add.php" id="visitor">VISITORS REGISTER</a></li>');							
	DEFINE('_VISITLIST',   '<a href="visitors_list.php" id="visitlist">VISITORS LIST VIEW</a></li>');
	DEFINE('_VISITGRID',   '<a href="visitors_grid.php" id="visitgrid">VISITORS GRID VIEW</a></li>');
	DEFINE('_VISITSTAT',   '<a href="visitors_stats.php" id="visitstat">VISITORS ANALYTICS</a></li>');
										
	//Clearances
	DEFINE('_CLEARANCE',   '<a href="mayor_clearance_grid.php" id="clear">MAYORS CLEARANCE</a>');
	DEFINE('_CLEARLIST',   '<a href="mayor_clearance_list.php" id="clearlist">CLEARANCE LIST</a>');
	DEFINE('_CLEARGRID',   '<a href="mayor_clearance_grid.php" id="cleargrid">CLEARANCE GRID</a>');
										
	//Certificates
	DEFINE('_CERT', 	   '<a href="cert_indigency_grid.php" id="cert">IDIGENT CERTIFICATE</a>');
	DEFINE('_CERTLIST',    '<a href="cert_indigency_list.php" id="certlist">CERTIFICATE LIST</a>');
	DEFINE('_CERTGRID',    '<a href="cert_indigency_grid.php" id="certgrid">CERTIFICATE GRID</a>');
												
	//Permit Fishing
	DEFINE('_FISHING', 	   '<a href="reg_fishing_grid.php" id="fishing">FISHING PERMIT</a>');
	DEFINE('_FISHLIST',    '<a href="reg_fishing_list.php" id="fishlist">LIST VIEW</a>');
	DEFINE('_FISHGRID',    '<a href="reg_fishing_grid.php" id="fishgrid">GRID VIEW</a>');
												
	//Permit Business
	DEFINE('_BUSINESS',    '<a href="permit_business_grid.php" id="business">BUSINESS PERMIT</a>');
	DEFINE('_BUSINESSLIST','<a href="permit_business_list.php" id="businesslist">LIST VIEW</a>');
	DEFINE('_BUSINESSGRID','<a href="permit_business_grid.php" id="businessgrid">GRID VIEW</a>');
												
	//Permit to Operate
	DEFINE('_OPERATE',     '<a href="permit_operate_grid.php" id="operate">PERMIT TO OPERATE</a>');
	DEFINE('_OPERATELIST', '<a href="permit_operate_list.php" id="operatelist">LIST VIEW</a>');
	DEFINE('_OPERATEGRID', '<a href="permit_operate_grid.php" id="operategrid">GRID VIEW</a>');
												
	//Message Incomning
	DEFINE('_MSGIN',       '<a href="messages_grid.php" id="msgin">INCOMING MESSAGES</a>');
	DEFINE('_MSGINLIST',   '<a href="messages_list.php" id="msginlist">LIST VIEW</a>');
	DEFINE('_MSGINGRID',   '<a href="messages_grid.php" id="msgingrid">GRID VIEW</a>');
												
	//Message Outcomning
	DEFINE('_MSGOUT',      '<a href="msgout_grid.php" id="msgout">OUTGOING MESSAGES</a>');
	DEFINE('_MSGOUTLIST',  '<a href="msgout_list.php" id="msgoutlist">LIST VIEW</a>');
	DEFINE('_MSGOUTGRID',  '<a href="msgout_grid.php" id="msgoutgrid">GRID VIEW</a>');
												
	//Administrator
	DEFINE('_BACKUP',      '<a href="backup.php" id="backup">BACKUP</a>');
	DEFINE('_USERGRID',    '<a href="users_grid.php" id="usergrid">SYSTEM USERS</a>');
	DEFINE('_USERLIST',    '<a href="users_list.php" id="userlist">USERS LIST VIEW</a>');
	DEFINE('_MSGBOARD',    '<a href="message_board.php" id="msgboard">MESSAGE BOARD</a>');
	DEFINE('_DOCUMENT',    '<a href="documentation.php" id="document">DOCUMENTATION</a>');
	DEFINE('_SYSAUDIT',    '<a href="audit_trail.php" id="audit">SYSTEM LOGS</a>');

	//Analytics
	DEFINE('_SOSANALY',    '<a href="social_statistics.php" id="sosstats">SOCIAL ANALYTICS</a>');
	DEFINE('_SENANALY',    '<a href="senior_statistics.php" id="senstats">SENIOR ANALYTICS</a>');
	DEFINE('_REVANALY',    '<a href="revenue_analytics.php" id="revstats">REVENUE ANALYTICS</a>');
	DEFINE('_EMPANALY',    '<a href="employees_analytics.php" id="empstats">EMPLOYEES ANALYTICS</a>');
	DEFINE('_HHSANALY',    '<a href="households_analytics.php" id="hhstats">HOUSEHOLDS ANALYTICS</a>');
?>

<link href='style/menu.css' rel='stylesheet' type='text/css'/>

<div id='menu_' class='menu_div' style='z-index:1000; display: flex; justify-content: space-between; align-items: center; padding: 0 20px;'>	
	<?php if($_SESSION["access"]!=""){ ?>
	<a href='public_home.php' class='d-flex align-items-center text-white text-decoration-none hover-opacity' style='height: 100%; transition: opacity 0.2s;'>
		<img src='images/favicon.png' height='26px' style='margin-right: 8px;'/>
		<span class='font-weight-bold d-none d-sm-inline' style='font-size: 13.5px; letter-spacing: 0.5px;'>LGU TABINA</span>
	</a>
	<?php } else { ?>
	<div style='width: 40px;' class='d-none d-md-block'></div>
	<?php } ?>
	<div id='cssmenu'>
		<!--Not Logged (Show Version)-->
		<?php if($_SESSION["access"]==""){ include("time.php");?>

		<div class='version1' style='position:fixed;top:15px;right:20px;color:#bbb;z-index:9999'>LGU Info System v2.0.1</div>
		<table style='color:#FFF'>
			<td style='border:0'><img src='images/seal.png' height='33px'/> </td>
			<td style='font-size:16px;border:0'> &nbsp; LGU-Tabina Information System v2.0.1</td>
		</table>
		<?php } ?>
		
		<!-- Session for Guest -->
		<?php if ((($_SESSION["access"])=="Guest") || (($_SESSION["access"])=="Employees")){ ?>
		<ul>
			<li><?php echo _HOME;?></li>
			<li><?php echo _LOGBOOK;?></li>
			<li><?php echo _CHAT;?>
				<ul>
					<li><?php echo _CHATLOBBY;?></li>
					<li><?php echo _MSGBOARD;?></li>
					<li><?php echo _PRIVATEDM;?></li>
				</ul>
			</li>
			<li><?php echo _LOGOUT;?></li>
		</ul>
		<?php } ?>
		
		<!-- Session for Administrator -->
		<?php if($_SESSION["access"]=="Administrator"){ ?>
		<ul>
			<li><?php echo _HOME;?></li> 
			<li><?php echo _ANALYTICS;?>
				<ul>
					<li><?php echo _SOSANALY;?></li>
					<li><?php echo _SENANALY;?></li>
					<li><?php echo _VISITSTAT;?></li>
					<li><?php echo _REVANALY;?></li>
					<li><?php echo _EMPANALY;?></li>
					<li><?php echo _HHSANALY;?></li>
				</ul>
			</li>
			<li><?php echo _MSWDO;?>
				<ul>
					<li><?php echo _PWD;?>
						<ul>
							<li><?php echo _PWDCARD;?></li>
							<li><?php echo _PWDLIST;?></li>
							<li><?php echo _PWDGRID;?></li>
						</ul>
					</li>
					<li><?php echo _SENIOR;?>
						<ul>
							<li><?php echo _SENIOR80UP;?></li>
							<li><?php echo _SENIORCARD;?></li>
							<li><?php echo _SENIORLIST;?></li>
							<li><?php echo _SENIORGRID;?></li>
						</ul>
					</li>
					<li><?php echo _SAP;?>
						<ul>
							<li><?php echo _SAPLIST;?></li>
							<li><?php echo _SAPGRID;?></li>
						</ul>
					</li>
					<li><?php echo _4PS;?>
						<ul>
							<li><?php echo _4PSLIST;?></li>
							<li><?php echo _4PSGRID;?></li>
						</ul>
					</li>
					<li><?php echo _SOLO;?>
						<ul>
							<li><?php echo _SOLOCARD;?></li>
							<li><?php echo _SOLOLIST;?></li>
							<li><?php echo _SOLOGRID;?></li>
						</ul>
					</li>
					<li><?php echo _HOUSEHOLDS;?>
						<ul>
							<li><?php echo _HHSURVEY;?></li>
							<li><?php echo _HHLIST;?></li>
							<li><?php echo _HHGRID;?></li>
							<li><?php echo _HHMEMLIST;?></li>
						</ul>
					</li>
					<li><?php echo _KINDER;?>
						<ul>
							<li><?php echo _KINDERCARD;?></li>
							<li><?php echo _KINDERCERT;?></li>
							<li><?php echo _KINDERLIST;?></li>
							<li><?php echo _KINDERGRID;?></li>
						</ul>
					</li>
				</ul>
			</li>
			<li><?php echo _EMPLOYEES;?>
				<ul>
					<li><?php echo _EMPCARD;?></li>
					<li><?php echo _EMPLIST;?></li>
					<li><?php echo _EMPGRID;?></li>
					<li><?php echo _OFFICETAG;?></li>
				</ul>
			</li>
			<li><?php echo _VISITORS;?>
				<ul>
					<li><?php echo _LOGBOOK;?></li>
					<li><?php echo _VISITLIST;?></li>
					<li><?php echo _VISITGRID;?></li>
					<li><?php echo _VISITSTAT;?></li>
				</ul>
			</li>
			<li><?php echo _CERTCLEAR;?>
				<ul>
					<li><?php echo _CLEARANCE;?>
						<ul>
							<li><?php echo _CLEARLIST;?></li>
							<li><?php echo _CLEARGRID;?></li>
						</ul>
					</li>
					<li><?php echo _CERT;?>
						<ul>
							<li><?php echo _CERTLIST;?></li>
							<li><?php echo _CERTGRID;?></li>
						</ul>
					</li>
				</ul>
			</li>
			<li><?php echo _PERMITS;?>
				<ul>
					<li><?php echo _FISHING;?>
						<ul>
							<li><?php echo _FISHLIST;?></li>
							<li><?php echo _FISHGRID;?></li>
						</ul>
					</li>
					<li><?php echo _BUSINESS;?>
						<ul>
							<li><?php echo _BUSINESSLIST;?></li>
							<li><?php echo _BUSINESSGRID;?></li>
						</ul>
					</li>
					<li><?php echo _OPERATE;?>
						<ul>
							<li><?php echo _OPERATELIST;?></li>
							<li><?php echo _OPERATEGRID;?></li>
						</ul>
					</li>
				</ul>
			</li>
			<li><?php echo _MESSAGES;?>
				<ul>
					<li><?php echo _MSGIN;?>
						<ul>
							<li><?php echo _MSGINLIST;?></li>
							<li><?php echo _MSGINGRID;?></li>
						</ul>
					</li>
					<li><?php echo _MSGOUT;?>
						<ul>
							<li><?php echo _MSGOUTLIST;?></li>
							<li><?php echo _MSGOUTGRID;?></li>
						</ul>
					</li>
				</ul>
			</li>
			<li><?php echo _CHAT;?>
				<ul>
					<li><?php echo _CHATLOBBY;?></li>
					<li><?php echo _MSGBOARD;?></li>
					<li><?php echo _PRIVATEDM;?></li>
				</ul>
			</li>
			<li><?php echo _ADMIN;?>
				<ul>
					<li><?php echo _BACKUP;?></li>
					<li><?php echo _SYSAUDIT;?></li>
					<li><?php echo _USERGRID;?></li>
					<li><?php echo _MSGBOARD;?></li>
					<li><?php echo _DOCUMENT;?></li>
				</ul>
			</li>
		</ul>

		<?php } ?>
		
		<!-- Session for Social Welfare	-->
		<?php if($_SESSION["access"]=="Welfare"){?>
		<ul>
			<li><?php //echo _HOME;?></li>
			<li><?php echo _HOUSEHOLDS;?>
				<ul>
					<li><?php echo _HHSURVEY;?></li>
					<li><?php echo _HHLIST;?></li>
					<li><?php echo _HHGRID;?></li>
					<li><?php echo _HHSANALY;?></li>
					<li><?php echo _HHMEMLIST;?></li>
				</ul>
			</li>
			<li><?php echo _KINDER;?>
				<ul>
					<li><?php echo _KINDERCARD;?></li>
					<li><?php echo _KINDERCERT;?></li>
					<li><?php echo _KINDERLIST;?></li>
					<li><?php echo _KINDERGRID;?></li>
				</ul>
			</li>
			<li><?php echo _4PS;?>
				<ul>
					<li><?php echo _4PSLIST;?></li>
					<li><?php echo _4PSGRID;?></li>
				</ul>
			</li>
			<li><?php echo _PWD;?>
				<ul>
					<li><?php echo _PWDCARD;?></li>
					<li><?php echo _PWDLIST;?></li>
					<li><?php echo _PWDGRID;?></li>
				</ul>
			</li>
			<li><?php echo _SAP;?>
				<ul>
					<li><?php echo _SAPLIST;?></li>
					<li><?php echo _SAPGRID;?></li>
				</ul>
			</li>
			<li><?php echo _SENIOR;?>
				<ul>
					<li><?php echo _SENIOR80UP;?></li>
					<li><?php echo _SENIORCARD;?></li>
					<li><?php echo _SENIORLIST;?></li>
					<li><?php echo _SENIORGRID;?></li>
					<li><?php echo _SENANALY;?></li>
				</ul>
			</li>
			<li><?php echo _SOLO;?>
				<ul>
					<li><?php echo _SOLOCARD;?></li>
					<li><?php echo _SOLOLIST;?></li>
					<li><?php echo _SOLOGRID;?></li>
				</ul>
			</li>
			<li><?php echo _CHAT;?>
				<ul>
					<li><?php echo _CHATLOBBY;?></li>
					<li><?php echo _MSGBOARD;?></li>
					<li><?php echo _PRIVATEDM;?></li>
				</ul>
			</li>
			<li><?php echo _BACKUP;?></li>
			<li><?php echo _DOCUMENT;?></li>
		</ul>
		<?php } ?>
		
		<!-- Session for Executive Staff -->
		<?php if($_SESSION["access"]=="Executive"){?>
		<ul>
			<li><?php echo _HOME;?></li>
			<li><?php echo _ANALYTICS;?>
				<ul>
					<li><?php echo _SOSANALY;?></li>
					<li><?php echo _SENANALY;?></li>
					<li><?php echo _VISITSTAT;?></li>
					<li><?php echo _REVANALY;?></li>
					<li><?php echo _EMPANALY;?></li>
					<li><?php echo _HHSANALY;?></li>
				</ul>
			</li>
			<li><?php echo _EMPLOYEES;?>
				<ul>
					<li><?php echo _EMPCARD;?></li>
					<li><?php echo _EMPLIST;?></li>
					<li><?php echo _EMPGRID;?></li>									
				</ul>
			</li>
			<li><?php echo _VISITORS;?>
				<ul>
					<li><?php echo _LOGBOOK;?></li>
					<li><?php echo _VISITLIST;?></li>
					<li><?php echo _VISITGRID;?></li>
					<li><?php echo _VISITSTAT;?></li>
				</ul>
			</li>
			<li><?php echo _CERTCLEAR;?>
				<ul>
					<li><?php echo _CLEARANCE;?>
						<ul>
							<li><?php echo _CLEARLIST;?></li>
							<li><?php echo _CLEARGRID;?></li>										
						</ul>
					</li>
					<li><?php echo _CERT;?>
						<ul>
							<li><?php echo _CERTLIST;?></li>
							<li><?php echo _CERTGRID;?></li>
						</ul>
					</li>
				</ul>
			</li>	
			<li><?php echo _PERMITS;?>
				<ul>
					<li><?php echo _FISHING;?>
						<ul>
							<li><?php echo _FISHLIST;?></li>
							<li><?php echo _FISHGRID;?></li>
						</ul>
					</li>
					<li><?php echo _BUSINESS;?>
						<ul>
							<li><?php echo _BUSINESSLIST;?></li>
							<li><?php echo _BUSINESSGRID;?></li>
						</ul>
					</li>
					<li><?php echo _OPERATE;?>
						<ul>
							<li><?php echo _OPERATELIST;?></li>
							<li><?php echo _OPERATEGRID;?></li>
						</ul>
					</li>
				</ul>
			</li>
			<li><?php echo _MESSAGES;?>
				<ul>
					<li><?php echo _MSGIN;?>
						<ul>
							<li><?php echo _MSGINLIST;?></li>
							<li><?php echo _MSGINGRID;?></li>
						</ul>
					</li>
					<li><?php echo _MSGOUT;?>
						<ul>
							<li><?php echo _MSGOUTLIST;?></li>
							<li><?php echo _MSGOUTGRID;?></li>
						</ul>
					</li>
				</ul>
			</li>
			<li><?php echo _CHAT;?>
				<ul>
					<li><?php echo _CHATLOBBY;?></li>
					<li><?php echo _MSGBOARD;?></li>
					<li><?php echo _PRIVATEDM;?></li>
				</ul>
			</li>
			<li><?php echo _BACKUP;?></li>							
			<li><?php echo _DOCUMENT;?></li>
		</ul>						
		<?php } ?>

		<!-- Session for Senior Citizens -->
		<?php if($_SESSION["access"]=="Senior"){ ?>
		<ul>
			<li><?php echo _HOME;?></li>
			<li><?php echo _SENIORGRID;?></li>		
			<li><?php echo _SENIORLIST;?></li>
			<li><?php echo _SENIOR80UP;?></li>
			<li><?php echo _SENIORCARD;?></li>
			<li><?php echo _SENANALY;?></li>
			<li><?php echo _SENIORADD;?></li>
			<li><?php echo _CHAT;?>
				<ul>
					<li><?php echo _CHATLOBBY;?></li>
					<li><?php echo _MSGBOARD;?></li>
					<li><?php echo _PRIVATEDM;?></li>
				</ul>	
			</li>
			<li><?php echo _BACKUP;?></li>
			<li><?php echo _DOCUMENT;?></li>
		</ul>
		<?php } ?>
			
		<!-- //Session for Enumerators -->
		<?php if($_SESSION["access"]=="Enumerator"){ ?>
		<ul>
			<li><?php echo _HOME;?></li>	
			<li><?php echo _HHF;?></li>	
			<li><?php echo _HHL;?></li>	
			<li><?php echo _HHG;?></li>	
			<li><?php echo _HHM;?></li>	
			<li><?php echo _HHA;?></li>	
			<li><?php echo _CHAT;?>
				<ul>
					<li><?php echo _CHATLOBBY;?></li>
					<li><?php echo _MSGBOARD;?></li>
					<li><?php echo _PRIVATEDM;?></li>
				</ul>
			</li>
			<li><?php echo _DOCUMENT;?></li>
		</ul>
	<?php } ?>
	</div>
	<button id='theme-toggle-btn' class='btn btn-sm btn-link text-white shadow-none' onclick='toggleTheme()' style='font-size: 16px; border: none; background: transparent; cursor: pointer; padding: 5px 10px;' title='Toggle Dark/Light Mode'>
		<i id='theme-toggle-icon' class='fas fa-moon'></i>
	</button>
</div>
	
<script type="text/javascript">
	function toggleTheme() {
		var currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
		var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
		
		if (newTheme === 'dark') {
			document.documentElement.setAttribute('data-theme', 'dark');
			localStorage.setItem('theme', 'dark');
			var icon = document.getElementById('theme-toggle-icon');
			if (icon) {
				icon.classList.remove('fa-moon');
				icon.classList.add('fa-sun');
			}
		} else {
			document.documentElement.removeAttribute('data-theme');
			localStorage.setItem('theme', 'light');
			var icon = document.getElementById('theme-toggle-icon');
			if (icon) {
				icon.classList.remove('fa-sun');
				icon.classList.add('fa-moon');
			}
		}
	}
</script>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		// Sync the dark mode button icon state on page load
		var initialTheme = localStorage.getItem('theme') || 'dark';
		var toggleIcon = document.getElementById('theme-toggle-icon');
		if (toggleIcon) {
			if (initialTheme === 'dark') {
				toggleIcon.classList.remove('fa-moon');
				toggleIcon.classList.add('fa-sun');
			} else {
				toggleIcon.classList.remove('fa-sun');
				toggleIcon.classList.add('fa-moon');
			}
		}

		// Dynamically add has-sub class to all li elements that contain a ul (submenus)
		$('#cssmenu li').has('ul').addClass('has-sub');

		// Clean up legacy text arrow symbols so CSS chevrons can style them cleanly
		$('#cssmenu a').each(function() {
			var html = $(this).html();
			html = html.replace(/▼|&#9660;|►|&#9658;/g, '');
			$(this).html(html);
		});

		// Insert responsive menu button dynamically if not present
		if ($('#menu-button').length === 0) {
			$('#cssmenu').prepend('<div id="menu-button">Menu</div>');
		}

		// Toggle top-level menu collapse (mobile)
		$('#menu-button').on('click', function(e) {
			e.stopPropagation();
			var menu = $(this).next('ul');
			if (menu.hasClass('open')) {
				menu.removeClass('open').hide();
				$(this).removeClass('menu-opened');
			} else {
				menu.addClass('open').show();
				$(this).addClass('menu-opened');
			}
		});

		// Auto hide menu when clicking/tapping outside or scrolling on mobile
		$(document).on('click touchstart', function(e) {
			if (!$(e.target).closest('#cssmenu').length) {
				var menu = $('#menu-button').next('ul');
				if (menu.hasClass('open')) {
					menu.removeClass('open').hide();
					$('#menu-button').removeClass('menu-opened');
				}
			}
		});

		$(window).on('scroll', function() {
			var menu = $('#menu-button').next('ul');
			if (menu.hasClass('open')) {
				menu.removeClass('open').hide();
				$('#menu-button').removeClass('menu-opened');
			}
		});

		// Setup submenus
		$('#cssmenu li.has-sub').prepend('<span class="submenu-button"></span>');
		$('#cssmenu li.has-sub .submenu-button').on('click', function() {
			$(this).toggleClass('submenu-opened');
			var submenu = $(this).siblings('ul');
			if (submenu.hasClass('open')) {
				submenu.removeClass('open').hide();
			} else {
				submenu.addClass('open').show();
			}
		});
	});

	function sessionEnd(gid){	
		if(confirm("Are you sure you want to Logout?")){
			window.location.href = 'logout.php';
		}
	}
</script>

<style>
	#pm-toast-body img {
		width: 20px;
		height: 20px;
		vertical-align: middle;
		margin: 0 2px;
	}
</style>

<!-- Real-Time PM Toast Notifications -->
<div id="pm-notification-toast" class="pm-toast-theme" style="position: fixed; bottom: 20px; right: 20px; z-index: 99999; display: none; max-width: 320px; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-radius: 12px; transition: opacity 0.4s ease, transform 0.4s ease; opacity: 0; transform: translateY(20px);">
  <div style="display: flex; align-items: center; padding: 12px 16px;">
    <img id="pm-toast-avatar" src="images/users/blank.jpg" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; margin-right: 12px; border: 2px solid #007bff;">
    <div style="flex-grow: 1; overflow: hidden; margin-right: 10px;">
      <div id="pm-toast-sender" class="font-weight-bold pm-toast-sender-text" style="font-size: 13px; line-height: 1.2;">Name</div>
      <div id="pm-toast-body" class="pm-toast-body-text" style="font-size: 12px; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px;">Message...</div>
    </div>
    <a id="pm-toast-link" href="#" class="btn btn-sm btn-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; padding: 0; text-decoration: none;">
      <i class="fas fa-reply" style="font-size: 11px; color: white;"></i>
    </a>
  </div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		var latestMsgId = 0;
		var isFirstPoll = true;

		function checkNotifications() {
			$.ajax({
				url: 'check_pm_notifications.php?_t=' + new Date().getTime(),
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					// 1. Update CHAT unread count badges in menu
					var count = parseInt(response.unread_count) || 0;
					var badge = $('#pm-unread-badge');
					
					// Also support highlighting submenus if they have unread badge
					var dmBadge = $('#pm-dm-badge');
					if (dmBadge.length === 0) {
						// Add a badge next to DIRECT MESSAGES submenu item if not exists
						$('#privatedm').append(' <span id="pm-dm-badge" class="badge badge-danger ml-auto font-weight-bold" style="font-size: 9px; display: none;"></span>');
						dmBadge = $('#pm-dm-badge');
					}

					if (count > 0) {
						badge.text(count).show();
						dmBadge.text(count).show();
					} else {
						badge.hide();
						dmBadge.hide();
					}

					// 2. Process messages to trigger Toast notifications
					var messages = response.unread_messages || [];
					var currentMaxId = latestMsgId;

					messages.forEach(function(msg) {
						var msgId = parseInt(msg.id);
						if (msgId > latestMsgId) {
							if (msgId > currentMaxId) {
								currentMaxId = msgId;
							}
							
							// Trigger toast notification (skip on first initial load poll to prevent spamming old unread DMs)
							if (!isFirstPoll) {
								showPMToast(msg);
							}
						}
					});

					latestMsgId = currentMaxId;
					isFirstPoll = false;
				},
				error: function(err) {
					console.log('Error checking PM notifications', err);
				}
			});
		}

		function showPMToast(msg) {
			var toast = $('#pm-notification-toast');
			$('#pm-toast-avatar').attr('src', msg.avatar);
			$('#pm-toast-sender').text(msg.fullname);
			$('#pm-toast-body').html(msg.message);
			$('#pm-toast-link').attr('href', 'messenger.php?user=' + encodeURIComponent(msg.sender));
			
			// Show animation
			toast.css({
				'display': 'block',
				'opacity': '0',
				'transform': 'translateY(20px)'
			});
			
			// Force reflow
			toast[0].offsetHeight;
			
			toast.css({
				'opacity': '1',
				'transform': 'translateY(0)'
			});

			// Auto hide after 6 seconds
			if (window.pmToastTimeout) {
				clearTimeout(window.pmToastTimeout);
			}
			window.pmToastTimeout = setTimeout(function() {
				toast.css({
					'opacity': '0',
					'transform': 'translateY(20px)'
				});
				setTimeout(function() {
					toast.hide();
				}, 400);
			}, 6000);
		}

		// Run check immediately on load, then poll every 4 seconds
		checkNotifications();
		setInterval(checkNotifications, 4000);
	});
</script>	