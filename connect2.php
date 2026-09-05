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
?>
