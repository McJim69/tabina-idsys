<?php
	require_once("connect.php");
	require_once("header.php");
	require_once("menu.php");	
?>

<script>setActive("admin");</script>
<script>setActive("backup");</script>

<!-- Header Section -->
<div class="container mt-4 pt-3">
<?php if (($_SESSION["access"])=="Senior"){ 
	echo "
		<div class='text-center my-4'>
			<h1 class='font-weight-bold text-uppercase' style='font-family: \"Airborne Regular\", sans-serif; font-size: 70px; color: maroon; text-shadow: 1px 1px 2px red, 0 0 1em blue, 0 0 0.2em blue;'>
				O S C A
			</h1>
		</div>";
	}else{
	echo "
		<div class='text-center my-4' style='font-size:40px'>
			<i class='fa fa-database'></i> Backup & Restore
		</div>";
	}
?>
</div>

<!-- Backup & Restore Cards Form -->
<form method="post" enctype="multipart/form-data" class="container my-4 no-print">
	<div class="row justify-content-center">
		<!-- Backup Card -->
		<div class="col-md-5 mb-4">
			<div class="card h-100 shadow-sm border-primary">
				<div class="card-body text-center d-flex flex-column justify-content-between p-4">
					<div>
						<div class="text-primary mb-3">
							<i class="fas fa-database fa-3x"></i>
						</div>
						<h4 class="card-title font-weight-bold text-dark">Database Backup</h4>
						<p class="card-text text-secondary small px-2">
							Download a compressed archive of your system database. Includes all tables, structural definitions, and current data records.
						</p>
					</div>
					<div class="mt-4">
						<button type="submit" name="backup" class="btn btn-primary btn-lg btn-block font-weight-bold rounded-pill shadow-xs" onclick="return confirm('Execute Backup Now?')">
							<i class="fas fa-download mr-2"></i>Backup Now
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Restore Card -->
		<div class="col-md-5 mb-4">
			<div class="card h-100 shadow-sm border-success">
				<div class="card-body text-center d-flex flex-column justify-content-between p-4">
					<div>
						<div class="text-success mb-3">
							<i class="fas fa-file-upload fa-3x"></i>
						</div>
						<h4 class="card-title font-weight-bold text-dark">Database Restore</h4>
						<p class="card-text text-secondary small px-2">
							Upload a previously saved `.sql.gz` or `.sql` backup file to restore database records. Note: This will overwrite existing data.
						</p>
					</div>
					<div class="mt-4">
						<button type="button" class="btn btn-success btn-lg btn-block font-weight-bold rounded-pill shadow-xs" onclick="$('#file').click();">
							<i class="fas fa-upload mr-2"></i>Restore Backup
						</button>
						<input type="file" name="file" id="file" onchange="$('#upload').click();" style="display:none"/>
						<input type="hidden" name="upload" value="1"/>
						<input type="submit" value="Submit" id="upload" style="display:none"/>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<div class="container my-3">
	<div class="row">	
		<div class="col-lg-12">

			<?php

			if(isset($_POST["backup"])){

				require_once("config.php");
		
				if (!defined("BACKUP_DIR"))   define("BACKUP_DIR", 'BACKUP'); 
				if (!defined("TABLES"))       define("TABLES", '*'); 

				//define("TABLES", 'table1, table2, table3'); // Partial backup
				define("CHARSET", 'utf8');
				define("GZIP_BACKUP_FILE", true); // Set to false if you want plain SQL backup files (not gzipped)
				define("DISABLE_FOREIGN_KEY_CHECKS", true); // Set to true if you are having foreign key constraint fails
				define("BATCH_SIZE", 1000); // Batch size when selecting rows from database in order to not exhaust system memory
											// Also number of rows per INSERT statement in backup file
				class Backup_Database {
					var $dbHost;
					var $dbUser;
					var $dbPass;
					var $dbName;
					var $charset;
					var $conn;
					var $backupDir;
					var $backupFile;
					var $gzipBackupFile;
					var $output;
					var $disableForeignKeyChecks;
					var $batchSize;

					public function __construct($dbHost, $dbUser, $dbPass, $dbName, $charset = 'utf8') {
						$this->host                    = $dbHost;
						$this->username                = $dbUser;
						$this->passwd                  = $dbPass;
						$this->dbName                  = $dbName;
						$this->charset                 = $charset;
						$this->conn                    = $this->initializeDatabase();
						$this->backupDir               = BACKUP_DIR ? BACKUP_DIR : '.';
						$this->backupFile              = 'backup-'.$this->dbName.'-'.date("Ymd_His", time()).'.sql';
						$this->gzipBackupFile          = defined('GZIP_BACKUP_FILE') ? GZIP_BACKUP_FILE : true;
						$this->disableForeignKeyChecks = defined('DISABLE_FOREIGN_KEY_CHECKS') ? DISABLE_FOREIGN_KEY_CHECKS : true;
						$this->batchSize               = defined('BATCH_SIZE') ? BATCH_SIZE : 1000; // default 1000 rows
						$this->output                  = '';
					}

					protected function initializeDatabase() {
						try {
							$conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
							if (mysqli_connect_errno()) {
								throw new Exception('ERROR connecting database: ' . mysqli_connect_error());
								die();
							}
							if (!mysqli_set_charset($conn, $this->charset)) {
								mysqli_query($conn, 'SET NAMES '.$this->charset);
							}
						} catch (Exception $e) {
							print_r($e->getMessage());
							die();
						}
						return $conn;
					}

					public function backupTables($tables = '*') {
						try {
							if($tables == '*') {
								$tables = array();
								$result = mysqli_query($this->conn, 'SHOW TABLES');
								while($row = mysqli_fetch_row($result)) {
									$tables[] = $row[0];
								}
							} else {
								$tables = is_array($tables) ? $tables : explode(',', str_replace(' ', '', $tables));
							}
							$sql = 'CREATE DATABASE IF NOT EXISTS `'.$this->dbName."`;\n\n";
							$sql .= 'USE `'.$this->dbName."`;\n\n";

							if ($this->disableForeignKeyChecks === true) {
								$sql .= "SET foreign_key_checks = 0;\n\n";
							}

							foreach($tables as $table) {
								$this->obfPrint("Backing up `".$table."` table...".str_repeat('.', 50-strlen($table)), 0, 0);
								$sql .= 'DROP TABLE IF EXISTS `'.$table.'`;';
								$row = mysqli_fetch_row(mysqli_query($this->conn, 'SHOW CREATE TABLE `'.$table.'`'));
								$sql .= "\n\n".$row[1].";\n\n";
								$row = mysqli_fetch_row(mysqli_query($this->conn, 'SELECT COUNT(*) FROM `'.$table.'`'));
								$numRows = $row[0];
								$numBatches = intval($numRows / $this->batchSize) + 1; // Number of while-loop calls to perform
								for ($b = 1; $b <= $numBatches; $b++) {

									$query = 'SELECT * FROM `' . $table . '` LIMIT ' . ($b * $this->batchSize - $this->batchSize) . ',' . $this->batchSize;
									$result = mysqli_query($this->conn, $query);
									$realBatchSize = mysqli_num_rows ($result); // Last batch size can be different from $this->batchSize
									$numFields = mysqli_num_fields($result);
									if ($realBatchSize !== 0) {
										$sql .= 'INSERT INTO `'.$table.'` VALUES ';
										for ($i = 0; $i < $numFields; $i++) {
											$rowCount = 1;
											while($row = mysqli_fetch_row($result)) {
												$sql.='(';
												for($j=0; $j<$numFields; $j++) {
													if (isset($row[$j])) {
														$row[$j] = addslashes($row[$j]);
														$row[$j] = str_replace("\n","\\n",$row[$j]);
														$row[$j] = str_replace("\r","\\r",$row[$j]);
														$row[$j] = str_replace("\f","\\f",$row[$j]);
														$row[$j] = str_replace("\t","\\t",$row[$j]);
														$row[$j] = str_replace("\v","\\v",$row[$j]);
														$row[$j] = str_replace("\a","\\a",$row[$j]);
														$row[$j] = str_replace("\b","\\b",$row[$j]);
														if ($row[$j] == 'true' or $row[$j] == 'false' or preg_match('/^-?[0-9]+$/', $row[$j]) or $row[$j] == 'NULL' or $row[$j] == 'null') {
															$sql .= $row[$j];
														} else {
															$sql .= '"'.$row[$j].'"' ;
														}
													} else {
														$sql.= 'NULL';
													}
				 
													if ($j < ($numFields-1)) {
														$sql .= ',';
													}
												}
				 
												if ($rowCount == $realBatchSize) {
													$rowCount = 0;
													$sql.= ");\n"; //close the insert statement
												} else {
													$sql.= "),\n"; //close the row
												}
				 
												$rowCount++;
											}
										}
				 
										$this->saveFile($sql);
										$sql = '';
									}
								}
				 
								$sql.="\n\n";
								$this->obfPrint('SUCCESS!');
							}
							if ($this->disableForeignKeyChecks === true) {
								$sql .= "SET foreign_key_checks = 1;\n";
							}
							$this->saveFile($sql);
							if ($this->gzipBackupFile) {
								$this->gzipBackupFile();
							} else {
								$this->obfPrint('Backup file succesfully saved to ' . $this->backupDir.'/'.$this->backupFile, 1, 1);
							}
						} catch (Exception $e) {
							print_r($e->getMessage());
							return false;
						}
						return true;
					}

					protected function saveFile(&$sql) {
						if (!$sql) return false;
						try {
							if (!file_exists($this->backupDir)) {
								mkdir($this->backupDir, 0777, true);
							}
							file_put_contents($this->backupDir.'/'.$this->backupFile, $sql, FILE_APPEND | LOCK_EX);
						} catch (Exception $e) {
							print_r($e->getMessage());
							return false;
						}
						return true;
					}

					protected function gzipBackupFile($level = 9) {
						if (!$this->gzipBackupFile) {
							return true;
						}
						$source = $this->backupDir . '/' . $this->backupFile;
						$dest =  $source . '.gz';
						$this->obfPrint('Gzipping Backup File to ' . $dest . '.....', 1, 0);
						$mode = 'wb' . $level;
						if ($fpOut = gzopen($dest, $mode)) {
							if ($fpIn = fopen($source,'rb')) {
								while (!feof($fpIn)) {
									gzwrite($fpOut, fread($fpIn, 1024 * 256));
								}
								fclose($fpIn);
							} else {
								return false;
							}
							gzclose($fpOut);
							@unlink($source);
						} else {
							return false;
						}
				 
						$this->obfPrint('SUCCESS!');

						echo '<div class="text-center mt-3">';
						echo '<a href="'.$dest.'"><button class="btn btn-lg btn-success font-weight-bold px-4 rounded-pill shadow-sm" type="button"><i class="fas fa-file-download mr-2"></i>Download Backup</button></a>';
						echo '</div>';

						return $dest;
					}

					public function obfPrint ($msg = '', $lineBreaksBefore = 0, $lineBreaksAfter = 1) {
						if (!$msg) {
							return false;
						}
						if ($msg != 'SUCCESS!' and $msg != 'FAILED!') {
							$msg = date("Y-m-d H:i:s") . ' - ' . $msg;
						}
						$output = '';
						if (php_sapi_name() != "cli") {
							$lineBreak = "<br />";
						} else {
							$lineBreak = "\n";
						}
						if ($lineBreaksBefore > 0) {
							for ($i = 1; $i <= $lineBreaksBefore; $i++) {
								$output .= $lineBreak;
							}                
						}
						$output .= $msg;
						if ($lineBreaksAfter > 0) {
							for ($i = 1; $i <= $lineBreaksAfter; $i++) {
								$output .= $lineBreak;
							}                
						}
						$this->output .= str_replace('<br />', '\n', $output);
						echo $output;
						if (php_sapi_name() != "cli") {
							if( ob_get_level() > 0 ) {
								ob_flush();
							}
						}
						$this->output .= " ";
						flush();
					}
					public function getOutput() {
						return $this->output;
					}
					
				}

				error_reporting(E_ALL);
				// Set script max execution time
				set_time_limit(900); // 15 minutes
				if (php_sapi_name() != "cli") {
					echo '<div class="card bg-dark text-white shadow my-4 no-print border-0">';
					echo '<div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2 px-3">';
					echo '<span class="font-weight-bold"><i class="fas fa-terminal mr-2"></i>Console Log</span>';
					echo '<a href="backup.php" class="btn btn-sm btn-light font-weight-bold">Close</a>';
					echo '</div>';
					echo '<div class="card-body" style="font-family: Consolas, monospace; font-size: 13px; line-height: 1.6; max-height: 350px; overflow-y: auto; background: #121212; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; padding: 20px;">';
				}
				$backupDatabase = new Backup_Database(DB_HOST, DB_USER, DB_PASS, DB_NAME, CHARSET);
				$result = $backupDatabase->backupTables(TABLES, BACKUP_DIR) ? 'SUCCESS!' : 'FAILED!';
				// $backupDatabase->obfPrint('Backup result: ' . $result, 1);
				// Use $output variable for further processing, for example to send it by email
				$output = $backupDatabase->getOutput();
				if (php_sapi_name() != "cli") {
					echo '</div></div><br>';
				}
			}

			//RESTORE
				if(isset($_POST["upload"])){
					$uploadSuccess = false;
					$file = "";
					$uploadErrorMsg = "";

					if (isset($_FILES['file'])) {
						if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
							$uploadedName = $_FILES['file']['name'];
							$ext = strtolower(pathinfo($uploadedName, PATHINFO_EXTENSION));
							
							if ($ext === 'gz') {
								$file = "backup-sql.sql.gz";
							} else {
								$file = "backup-sql.sql";
							}

							if (move_uploaded_file($_FILES['file']['tmp_name'], "BACKUP/" . $file)) {
								$uploadSuccess = true;
							} else {
								$uploadErrorMsg = "Failed to save the uploaded file to BACKUP/ directory. Check directory write permissions.";
							}
						} else {
							$errorCodes = array(
								UPLOAD_ERR_INI_SIZE   => "The uploaded file exceeds the upload_max_filesize directive in php.ini (currently set to 100M).",
								UPLOAD_ERR_FORM_SIZE  => "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form.",
								UPLOAD_ERR_PARTIAL    => "The uploaded file was only partially uploaded.",
								UPLOAD_ERR_NO_FILE    => "No file was selected or uploaded.",
								UPLOAD_ERR_NO_TMP_DIR => "Server is missing a temporary folder for uploads.",
								UPLOAD_ERR_CANT_WRITE => "Failed to write the uploaded file to disk.",
								UPLOAD_ERR_EXTENSION  => "A server PHP extension blocked the file upload."
							);
							$errCode = $_FILES['file']['error'];
							$uploadErrorMsg = isset($errorCodes[$errCode]) ? $errorCodes[$errCode] : "Unknown upload error (Code: $errCode).";
						}
					} else {
						$uploadErrorMsg = "No file data received by the server.";
					}

					if (!$uploadSuccess) {
						echo '<div class="alert alert-danger font-weight-bold my-4 no-print"><i class="fas fa-exclamation-circle mr-2"></i>Database Restore Failed: ' . htmlspecialchars($uploadErrorMsg) . '</div>';
					} else {

						require_once("config.php");
			
						if (!defined("BACKUP_DIR")) define("BACKUP_DIR", 'BACKUP');
						if (!defined("BACKUP_FILE")) define("BACKUP_FILE", $file);
						if (!defined("CHARSET")) define("CHARSET", 'utf8');
						if (!defined("DISABLE_FOREIGN_KEY_CHECKS")) define("DISABLE_FOREIGN_KEY_CHECKS", true);

						//The Restore_Database class
						class Restore_Database {
							var $dbHost;
							var $dbUser;
							var $dbPass;
							var $dbName;
							var $charset;
							var $conn;
							var $disableForeignKeyChecks;

							function __construct($dbHost, $dbUser, $dbPass, $dbName, $charset = 'utf8') {
								$this->host                    = $dbHost;
								$this->username                = $dbUser;
								$this->passwd                  = $dbPass;
								$this->dbName                  = $dbName;
								$this->charset                 = $charset;
								$this->disableForeignKeyChecks = defined('DISABLE_FOREIGN_KEY_CHECKS') ? DISABLE_FOREIGN_KEY_CHECKS : true;
								$this->conn                    = $this->initializeDatabase();
								$this->backupDir               = defined('BACKUP_DIR') ? BACKUP_DIR : '.';
								$this->backupFile              = defined('BACKUP_FILE') ? BACKUP_FILE : null;
							}

							function __destructor() {
								if ($this->disableForeignKeyChecks === true) {
									mysqli_query($this->conn, 'SET foreign_key_checks = 1');
								}
							}

							protected function initializeDatabase() {
								try {
									$conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
									if (mysqli_connect_errno()) {
										throw new Exception('ERROR connecting database: ' . mysqli_connect_error());
										die();
									}
									if (!mysqli_set_charset($conn, $this->charset)) {
										mysqli_query($conn, 'SET NAMES '.$this->charset);
									}
									if ($this->disableForeignKeyChecks === true) {
										mysqli_query($conn, 'SET foreign_key_checks = 0');
									}
								} catch (Exception $e) {
									print_r($e->getMessage());
									die();
								}
								return $conn;
							}

							public function restoreDb() {
								try {
									$sql = '';
									$multiLineComment = false;
									$backupDir = $this->backupDir;
									$backupFile = $this->backupFile;
									$backupFileIsGzipped = substr($backupFile, -3, 3) == '.gz' ? true : false;
									if ($backupFileIsGzipped) {
										if (!$backupFile = $this->gunzipBackupFile()) {
											throw new Exception("ERROR: couldn't gunzip backup file " . $backupDir . '/' . $backupFile);
										}
									}
									$handle = fopen($backupDir . '/' . $backupFile, "r");
									if ($handle) {
										while (($line = fgets($handle)) !== false) {
											$line = ltrim(rtrim($line));
											if (strlen($line) > 1) { // avoid blank lines
												$lineIsComment = false;
												if (preg_match('/^\/\*/', $line)) {
													$multiLineComment = true;
													$lineIsComment = true;
												}
												if ($multiLineComment or preg_match('/^\/\//', $line)) {
													$lineIsComment = true;
												}
												if (!$lineIsComment) {
													$sql .= $line;
													if (preg_match('/;$/', $line)) {
														// execute query
														if(mysqli_query($this->conn, $sql)) {
															if (preg_match('/^CREATE TABLE `([^`]+)`/i', $sql, $tableName)) {
																$this->obfPrint("Table succesfully created: `" . $tableName[1] . "`");
															}
															$sql = '';
														} else {
															throw new Exception("ERROR: SQL execution error: " . mysqli_error($this->conn));
														}
													}
												} else if (preg_match('/\*\/$/', $line)) {
													$multiLineComment = false;
												}
											}
										}
										fclose($handle);
									} else {
										throw new Exception("ERROR: couldn't open backup file " . $backupDir . '/' . $backupFile);
									} 
								} catch (Exception $e) {
									print_r($e->getMessage());
									return false;
								}
								if ($backupFileIsGzipped) {
									@unlink($backupDir . '/' . $backupFile);
								}
								return true;
							}

							protected function gunzipBackupFile() {
								$bufferSize = 4096; // read 4kb at a time
								$error = false;
								$source = $this->backupDir . '/' . $this->backupFile;
								$dest = $this->backupDir . '/' . date("Ymd_His", time()) . '_' . substr($this->backupFile, 0, -3);
								$this->obfPrint('Gunzipping backup file ' . $source . '... ', 1, 1);
								if (file_exists($dest)) {
									if (!@unlink($dest)) {
										$this->obfPrint("ERROR: Could not delete existing temp file: " . $dest);
										return false;
									}
								}
								if (!$srcFile = @gzopen($this->backupDir . '/' . $this->backupFile, 'rb')) {
									$this->obfPrint("ERROR: Could not open gzipped source file: " . $this->backupDir . '/' . $this->backupFile);
									return false;
								}
								if (!$dstFile = @fopen($dest, 'wb')) {
									$this->obfPrint("ERROR: Could not open destination file for writing: " . $dest);
									@gzclose($srcFile);
									return false;
								}
								while (!@gzeof($srcFile)) {
									$chunk = @gzread($srcFile, $bufferSize);
									if ($chunk === false) {
										$this->obfPrint("ERROR: Failed to read from gzipped source file.");
										@fclose($dstFile);
										@gzclose($srcFile);
										return false;
									}
									if(@fwrite($dstFile, $chunk) === false) {
										$this->obfPrint("ERROR: Failed to write gunzipped chunk to destination file.");
										@fclose($dstFile);
										@gzclose($srcFile);
										return false;
									}
								}
								@fclose($dstFile);
								@gzclose($srcFile);
								return str_replace($this->backupDir . '/', '', $dest);
							}

							public function obfPrint ($msg = '', $lineBreaksBefore = 0, $lineBreaksAfter = 1) {
								if (!$msg) {
									return false;
								}
								$msg = date("Y-m-d H:i:s") . ' - ' . $msg;
								$output = '';
								if (php_sapi_name() != "cli") {
									$lineBreak = "<br />";
								} else {
									$lineBreak = "\n";
								}
								if ($lineBreaksBefore > 0) {
									for ($i = 1; $i <= $lineBreaksBefore; $i++) {
										$output .= $lineBreak;
									}                
								}
								$output .= $msg;
								if ($lineBreaksAfter > 0) {
									for ($i = 1; $i <= $lineBreaksAfter; $i++) {
										$output .= $lineBreak;
									}                
								}
								if (php_sapi_name() == "cli") {
									$output .= "\n";
								}
								echo $output;
								if (php_sapi_name() != "cli") {
									ob_flush();
								}
								flush();
							}
						}
						
						error_reporting(E_ALL);
						set_time_limit(900); // 15 minutes
						if (php_sapi_name() != "cli") {
							echo '<div class="card bg-dark text-white shadow my-4 no-print border-0">';
							echo '<div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2 px-3">';
							echo '<span class="font-weight-bold"><i class="fas fa-terminal mr-2"></i>Database Console Log</span>';
							echo '<a href="backup.php" class="btn btn-sm btn-light font-weight-bold">Close</a>';
							echo '</div>';
							echo '<div class="card-body" style="font-family: Consolas, monospace; font-size: 13px; line-height: 1.6; max-height: 350px; overflow-y: auto; background: #121212; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; padding: 20px;">';
						}
						$restoreDatabase = new Restore_Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
						$result = $restoreDatabase->restoreDb(BACKUP_DIR, BACKUP_FILE) ? 'SUCCESS!' : 'FAILED!';
						$restoreDatabase->obfPrint("Restoration result: ".$result, 1);
						if (php_sapi_name() != "cli") {
							echo '</div></div><br>';
							@unlink("BACKUP/" . BACKUP_FILE);
						}
					}
				}
			?>
					
		</div>
	</div>
</div>

<?php require("footer.php");?>
<?php //require("users_profile.php");?>

</body>

</html>

<script type="text/javascript">	
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
</script>