<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
	ini_set('log_errors', 1);
	ini_set('error_log', 'php-error.log');

    // Start session only if none exists
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

	require_once("config.php");
	
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $link->set_charset("utf8mb4");

    require_once("crud_functions.php");

    // Helper function to resolve FFmpeg binary dynamically in dev/production environments
    if (!function_exists('getFFmpegPath')) {
        function getFFmpegPath() {
            $paths = [
                __DIR__ . "/../../ffmpeg/bin/ffmpeg.exe", // local dev: projects/tabina-idsys/../../ffmpeg
                __DIR__ . "/../ffmpeg/bin/ffmpeg.exe",    // production: Server/www/../ffmpeg (i.e. Server/ffmpeg)
                __DIR__ . "/ffmpeg/bin/ffmpeg.exe"        // fallback: inside www root
            ];
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    return $path;
                }
            }
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $absPaths = [
                    "D:\\Server\\www\\ffmpeg\\bin\\ffmpeg.exe",
                    "D:\\Server\\ffmpeg\\bin\\ffmpeg.exe",
                    "C:\\Server\\ffmpeg\\bin\\ffmpeg.exe",
                    "C:\\Server\\www\\ffmpeg\\bin\\ffmpeg.exe"
                ];
                foreach ($absPaths as $p) {
                    if (file_exists($p)) {
                        return $p;
                    }
                }
            }
            return null;
        }
    }
    // Helper function to truncate long filenames nicely
    if (!function_exists('truncateFileName')) {
        function truncateFileName($filename, $maxLength = 25) {
            if (strlen($filename) <= $maxLength) {
                return $filename;
            }
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $name = pathinfo($filename, PATHINFO_FILENAME);
            $keep = $maxLength - strlen($ext) - 4; // 4 for "..." + separator
            if ($keep > 0) {
                return substr($name, 0, $keep) . "..." . $ext;
            } else {
                return substr($filename, 0, $maxLength - 3) . "...";
            }
        }
    }

    // Declare global highlight variables to prevent Undefined Variable notices in PDS/IDCard templates
    $val = '';
    $rep = '';
    $value = '';

    // Prevent Undefined Index notices and SQL Injection for common parameters globally
    $common_get_params = [
		'value', 
		'page', 
		'barangays', 
		'barangay', 
		'departments', 
		'positions', 
		'idn', 
		'access', 
		'municipality', 
		'pensioner', 
		'age', 
		'messages', 
		'messagesidn', 
		'reg_fishing', 
		'households', 
		'visitors', 
		'permit_business', 
		'permit_operate', 
		'solo_parent', 
		'kinder', 
		'pwd', 
		'senior', 
		'reg_fishingidn', 
		'employees', 
		'barios', 
		'user', 
		'municipals', 
		'remarks', 
		'sap_ben', 
		'indigents', 
		'ajax', 
		'ajax_users', 
		'msgout', 
		'cert_indigency', 
		'clearances', 
		'position', 
		'hhid', 
		'chat_room', 
		'users', 
		'me', 
		'hh_members', 
		'hmid', 
		'uno', 
		'sitios', 
		'sess', 
		'session', 
		'type', 
		'permit_operateidn', 
		'permit_businessidn', 
		'visitorsidn', 
		'solo_parentidn', 
		'kinderidn', 
		'pwdidn', 
		'senioridn', 
		'indigentsidn', 
		'sap_benidn', 
		'cert_indigencyidn'];
    foreach ($common_get_params as $param) {
        if (!isset($_GET[$param])) {
            $_GET[$param] = '';
        } else if (is_string($_GET[$param])) {
            $_GET[$param] = mysqli_real_escape_string($link, $_GET[$param]);
        }
    }
    if (!isset($_POST['t_search'])) {
        $_POST['t_search'] = '';
    } else if (is_string($_POST['t_search'])) {
        $_POST['t_search'] = mysqli_real_escape_string($link, $_POST['t_search']);
    }
    if (!isset($_POST['b_search'])) {
        $_POST['b_search'] = null;
    }

    // Perform auto-logout inactivity check after database connection is active
    require_once("auto-logout.php");

    // Run active session logging (adds guest and registered sessions to database)
    require_once("session_start.php");

    // Global security check: Disallow all administrative pages for non-logged users
    $allowed_pages = [
        'public_home.php',
        'public_home2.php',
        'explore_tabina.php',
        'lgu_profile.php',
        'disclaimer_modal.php',
        'login.php',
        'forget_pass_post.php',
        'forget_pass_post_proc.php',
        'forget_pass_msg.php',
        'logo_slider.php',
        'time.php',
        'time2.php',
        'logout.php',
        'connect.php'
    ];

    $current_script = basename($_SERVER['SCRIPT_NAME']);
    $current_php_self = basename($_SERVER['PHP_SELF']);

    if (!isset($_SESSION['user'])) {
        if (!in_array($current_script, $allowed_pages) && !in_array($current_php_self, $allowed_pages)) {
            header("Location: public_home.php");
            exit;
        }
    }
?>
