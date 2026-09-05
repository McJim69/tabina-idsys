<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGU Tabina CCDP - System Documentation</title>
    <!-- Outfit Font & FontAwesome Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style/document.css">
</head>
<body>

<?php require("connect.php");?>

<!-- Sidebar Left Layout -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>LGU Tabina</h3>
            <p>System Documentation</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#overview" class="active"><i class="fas fa-info-circle"></i>Overview</a></li>
            <li><a href="#deployment"><i class="fas fa-server"></i>Deployment Guide</a></li>
            <li><a href="#architecture"><i class="fas fa-cubes"></i>System Architecture</a></li>
            <li><a href="#admins"><i class="fas fa-user-shield"></i>Admin User Guide</a></li>
            <li><a href="#citizens"><i class="fas fa-users"></i>Citizen Portal Guide</a></li>
            <li><a href="#communications"><i class="fas fa-comments"></i>Communication Features</a></li>
            <li><a href="#auditlogs"><i class="fas fa-history"></i>System Audit Logs</a></li>
            <li><a href="#devnotes"><i class="fas fa-code"></i>Developer Reference</a></li>
        </ul>
        <div class="sidebar-footer">
            CCDP Version 5.8.0
        </div>
    </div>

    <!-- Mobile Tabs (visible only on mobile/tablet) -->
    <div class="mobile-tabs">
        <a href="#overview" class="active">Overview</a>
        <a href="#deployment">Deployment</a>
        <a href="#architecture">Architecture</a>
        <a href="#admins">Admin Guide</a>
        <a href="#citizens">Citizen Guide</a>
        <a href="#communications">Communications</a>
        <a href="#auditlogs">Audit Logs</a>
        <a href="#devnotes">Developer</a>
    </div>

    <!-- Main Viewport Layout -->
    <div class="main-layout">
        <div class="top-navbar">
            <h2>Portal Administration Documentation</h2>
            <div style="display: flex; align-items: center;">
                <button id="theme-toggle" class="btn-theme" title="Toggle Theme" style="background: #f1f3f5; border: none; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #555; transition: all 0.2s; margin-right: 15px;"><i class="fas fa-moon"></i></button>
                <a href="index.php" class="btn-portal"><i class="fas fa-arrow-left mr-2"></i> <span>Back to Portal</span></a>
            </div>
        </div>

        <div class="content-viewport">

            <!-- Section 1: Overview -->
            <div id="overview" class="doc-section">
                <h1>Overview</h1>
                <p>Welcome to the official system documentation for the <strong>LGU Tabina Citizen-Centric Digital Platform (CCDP)</strong>. This digital platform was designed to streamline, automate, and secure key municipal services for residents of Tabina, Zamboanga del Sur.</p>
                <p>The system features an integrated citizen application portal alongside a robust administration module allowing municipal staff to review, manage, and print official documentation and ID cards.</p>

                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Key Milestones Achieved:</strong>
                        <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                            <li>Global AJAX CRUD Engine allowing instant record creation, read, update, and deletion across all database tables.</li>
                            <li>Sleek, real-time System Audit Logs dashboard with dynamic KPI cards, dropdown filters, column sorting, CSV exports, and interactive daily activity charts.</li>
                            <li>Strict SQL Mode compatibility with automatic empty date values to <code>NULL</code> conversion on insertion and updates.</li>
                            <li>Role-based application approval pipeline (authorized only for Administrator & Executive accounts).</li>
                            <li>Demographic pre-filling for all citizen forms to speed up the workflow.</li>
                            <li>Self-healing, on-the-fly QR code generation for official printouts.</li>
                            <li>Strict single-application constraints for ID Card services (Senior Citizens, PWD, Solo Parents) at the database level.</li>
                            <li>Modernized application password hashing using secure PHP hashing keys.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Section 2: Deployment Guide -->
            <div id="deployment" class="doc-section">
                <h1>Deployment Guide</h1>
                <p>Follow these steps to deploy or migrate the LGU Tabina CCDP server environment.</p>

                <h2>1. Server Dependencies</h2>
                <ul>
                    <li><strong>Operating System:</strong> Windows Server (using Apache via XAMPP) or Linux (Ubuntu Server).</li>
                    <li><strong>Web Server:</strong> Apache 2.4+ or IIS 10+.</li>
                    <li><strong>PHP Environment:</strong> PHP 7.4 to 8.2 (with <code>mysqli</code> and <code>gd</code> extensions enabled).</li>
                    <li><strong>Database Server:</strong> MySQL 5.7+ or MariaDB 10.4+.</li>
                </ul>

                <h2>2. Database Schema Setup</h2>
                <p>Import the database backup file located in the <code>DATABASE/</code> directory into your MySQL server. Verify that the unique constraints on the ID card tables are active:</p>
                <pre><code>ALTER TABLE senior ADD UNIQUE INDEX unique_user_id (user_id);
ALTER TABLE pwd ADD UNIQUE INDEX unique_user_id (user_id);
ALTER TABLE solo_parent ADD UNIQUE INDEX unique_user_id (user_id);</code></pre>

                <h2>3. Folder Write Permissions</h2>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Important:</strong> The web server must have write permissions for the QR code and user upload folders. Without these permissions, photo uploads and on-the-fly QR code generation will fail.
                    </div>
                </div>
                <p>Grant full read/write permission to the following directories:</p>
                <ul>
                    <li><code>images/users/</code></li>
                    <li><code>images/senior/qrcodes/</code></li>
                    <li><code>images/pwd/qrcodes/</code></li>
                    <li><code>images/solo_parent/qrcodes/</code></li>
                    <li><code>images/clearances/qrcodes/</code></li>
                    <li><code>images/cert_indigency/qrcodes/</code></li>
                    <li><code>images/permit_business/qrcodes/</code></li>
                    <li><code>images/reg_fishing/qrcodes/</code></li>
                    <li><code>images/permit_operate/qrcodes/</code></li>
                </ul>
            </div>

            <!-- Section 3: System Architecture -->
            <div id="architecture" class="doc-section">
                <h1>System Architecture</h1>
                <p>The LGU Tabina CCDP follows a modular, structured PHP architectural layout.</p>
                
                <h2>1. Database Model & Column Refactoring</h2>
                <p>The database (<code>idsystem_lgu</code>) consists of tables split by municipal office services. These include:</p>
                <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; border: 1px solid var(--border-color); border-radius: 8px; margin: 20px 0;">
                    <table style="margin: 0; border: none;">
                        <thead>
                            <tr>
                                <th style="border-top: none; border-left: none;">Table Name</th>
                                <th style="border-top: none;">Municipal Service Represented</th>
                                <th style="border-top: none;">Status Column</th>
                                <th style="border-top: none; border-right: none;">Constraints</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border-left: none;"><code>senior</code></td>
                                <td>OSCA Senior Citizen Card</td>
                                <td><code>status</code></td>
                                <td style="border-right: none;">Primary key `idn`, unique `user_id`</td>
                            </tr>
                            <tr>
                                <td style="border-left: none;"><code>pwd</code></td>
                                <td>PWD Registration Card</td>
                                <td><code>status</code></td>
                                <td style="border-right: none;">Primary key `idn`, unique `user_id`</td>
                            </tr>
                            <tr>
                                <td style="border-left: none;"><code>solo_parent</code></td>
                                <td>Solo Parent Card</td>
                                <td><code>status</code></td>
                                <td style="border-right: none;">Primary key `idn`, unique `user_id`</td>
                            </tr>
                            <tr>
                                <td style="border-left: none;"><code>cert_indigency</code></td>
                                <td>Indigent Certificate</td>
                                <td><code>app_status</code></td>
                                <td style="border-right: none;">Primary key `idn`, multiple per citizen allowed</td>
                            </tr>
                            <tr>
                                <td style="border-left: none;"><code>clearances</code></td>
                                <td>Mayor's Clearance document</td>
                                <td><code>status</code></td>
                                <td style="border-right: none;">Primary key `idn`, multiple per citizen allowed</td>
                            </tr>
                            <tr>
                                <td style="border-left: none;"><code>permit_business</code></td>
                                <td>Business Permit</td>
                                <td><code>status</code></td>
                                <td style="border-right: none;">Primary key `idn`, multiple per citizen allowed</td>
                            </tr>
                            <tr>
                                <td style="border-left: none;"><code>reg_fishing</code></td>
                                <td>Fishing Boat Permit</td>
                                <td><code>status</code></td>
                                <td style="border-right: none;">Primary key `idn`, multiple per citizen allowed</td>
                            </tr>
                            <tr>
                                <td style="border-left: none; border-bottom: none;"><code>permit_operate</code></td>
                                <td style="border-bottom: none;">Permit to Operate</td>
                                <td style="border-bottom: none;"><code>status</code></td>
                                <td style="border-bottom: none; border-right: none;">Primary key `idn`, multiple per citizen allowed</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2>2. Date Fields Consolidation</h2>
                <p>All legacy split date fields (separated into day, month, and year selects) have been consolidated into unified, single SQL <code>DATE</code> columns (e.g. <code>date_birth</code>, <code>assoc_reg_date</code>, <code>date_issued</code>, <code>date_or</code>) across all tables, ensuring date integrity, sorting efficiency, and form compliance.</p>

                <h2>3. Global AJAX CRUD Engine</h2>
                <p>A unified CRUD engine is integrated globally into the platform via <code>crud_functions.php</code> (backend endpoint handler) and <code>crud_functionjs.php</code> (frontend client library). It allows web components to execute standard <code>create</code>, <code>read</code>, <code>update</code>, and <code>delete</code> database actions on any table securely using asynchronous JavaScript XMLHttpRequests.</p>
            </div>

            <!-- Section 4: Admin User Guide -->
            <div id="admins" class="doc-section">
                <h1>Admin User Guide</h1>
                <p>This section is for LGU staff (Secretary, Social Workers, Executives, and Administrators).</p>

                <h2>1. Reviewing Applications</h2>
                <p>Administrative staff can access different grids (e.g. <code>senior_grid.php</code>, <code>pwd_grid.php</code>, etc.) to view citizen submissions. Each card lists key demographic details and offers standard management actions.</p>

                <h2>2. Approving & Denying Requests</h2>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        Only users logged in as <strong>Administrator</strong> or <strong>Executive</strong> will see the status dropdown modification tools on the grid panels.
                    </div>
                </div>
                <p>To change an application status:</p>
                <ol>
                    <li>Locate the application card in the grid.</li>
                    <li>Click the status dropdown pill (e.g. <code>Pending</code>) at the bottom.</li>
                    <li>Select <code>Approved</code> or <code>Denied</code>.</li>
                    <li>The system sends an AJAX query to <code>update_application_status.php</code>, saves the update, and displays a success flash.</li>
                </ol>

                <h2>3. Printing Cards & Certificates</h2>
                <p>Once an application reaches the <strong>Approved</strong> state, click the action overlay or the print buttons in the grids. This redirects to the unified print layout:</p>
                <ul>
                    <li><strong>ID Cards:</strong> Renders a high-fidelity credit-card dimensioned layout (337px x 213px) for front and back layouts containing the citizen's avatar photo and QR verification barcode.</li>
                    <li><strong>Official Certificates:</strong> Renders an A4 letterhead certificate featuring the official seals, text layouts, Mayor signatory lines, and authenticity verification stamp.</li>
                </ul>
            </div>

            <!-- Section 5: Citizen Portal Guide -->
            <div id="citizens" class="doc-section">
                <h1>Citizen Portal Guide</h1>
                <p>This guide explains the citizen workflow for utilizing the digital portal (<code>public_home.php</code> / <code>public_dashboard.php</code>).</p>

                <h2>1. Account Setup</h2>
                <p>Citizens sign up for a digital account and upload their profile photo. This photo is automatically used to render their official ID cards when approved.</p>

                <h2>2. Smart Form Auto-Filling</h2>
                <p>When applying for any service (e.g. Solo Parent ID), the portal automatically reads their authenticated user session and pre-populates their demographic and address fields in the application forms. This ensures zero data entry discrepancies.</p>

                <h2>3. Real-Time Tracking</h2>
                <p>On their dashboard, citizens see a tracking grid displaying all active applications. 
                When a municipal admin updates their status, the citizen's dashboard triggers a visual SweetAlert popup notifying them immediately upon their next access.</p>

                <h2>4. Single-Application Enforcements</h2>
                <p>For ID Card services, citizens are limited to a single application. If they attempt to register again, the system halts execution and informs them of their active profile. Multiple applications remain enabled for transactional permits and clearances.</p>
            </div>

            <!-- Section 6: Communication Features -->
            <div id="communications" class="doc-section">
                <h1>Communication Features</h1>
                <p>The CCDP platform integrates real-time communications for citizen engagement, administrative coordination, and announcements.</p>

                <h2>1. Private Message (1-on-1 Chat Messenger)</h2>
                <p>The integrated Messenger (<code>messenger.php</code> / <code>private_chat.php</code>) provides direct 1-on-1 private messaging between portal users (Admins, Executives, Welfare Staff, and Citizens).</p>
                <ul>
                    <li><strong>Active Users Search:</strong> Users can browse or search the listing of active registered accounts on the left sidebar list.</li>
                    <li><strong>Real-Time Delivery & Caching:</strong> Handles immediate delivery of chat messages and keeps track of sender/receiver states.</li>
                    <li><strong>Unread Count Badge:</strong> Generates a dynamic notification badge in the main navigation menu (<code>pm-unread-badge</code>) indicating any unread direct messages waiting for the user.</li>
                </ul>

                <h2>2. Group Chat (Live Chat Rooms)</h2>
                <p>The chat room module (<code>chat_rooms.php</code> / <code>room.php</code>) facilitates group-wide communications and discussion boards for general announcements or collaborative team tasks.</p>
                <ul>
                    <li><strong>Multiple Rooms Support:</strong> Admins can define different chat rooms for general public topics, staff coordination, or disaster alerts.</li>
                    <li><strong>Targeted Replies:</strong> Supports referencing specific past messages (using the <code>reply_to</code> database column) for threaded replies.</li>
                </ul>

                <h2>3. Message Board (Official Announcements Bulletin)</h2>
                <p>The administrative Message Board (<code>message_board.php</code>) acts as a broadcast bulletin where authorized users can post general messages or public service updates.</p>
                <ul>
                    <li><strong>Moderation:</strong> Admins can manage postings, view comments, and confirm announcements.</li>
                    <li><strong>Read confirmation:</strong> Logs view-counts and tracking times to audit when citizens engage with the bulletins.</li>
                </ul>
            </div>

            <!-- Section 7: System Audit Logs -->
            <div id="auditlogs" class="doc-section">
                <h1>System Audit Logs</h1>
                <p>The platform features a state-of-the-art administrative portal for system event monitoring (<code>audit_trail.php</code> / <code>audit_trail_data.php</code>).</p>
                
                <h2>1. Real-Time Event Polling</h2>
                <p>Features an AJAX-powered real-time toggle switch. When active, it polls and refreshes the event log table dynamically every 3 seconds, flashing a pulsing green dot indicator.</p>

                <h2>2. Highlighted Action Verb & Table Detection</h2>
                <p>The log viewer parses event descriptions, extracting actions (such as CREATE, UPDATE, DELETE, SESSION) and targeted tables (such as SENIOR, PWD, VISITORS, HOUSEHOLDS) to render color-coded contextual badges.</p>

                <h2>3. KPI Statistical Indicators</h2>
                <p>Five dynamic statistical panels provide a summary of today's activities:</p>
                <ul>
                    <li><strong>Logs Today:</strong> Aggregate events recorded since midnight.</li>
                    <li><strong>Created Today:</strong> Count of new demographic profiles or permits registered today.</li>
                    <li><strong>Updated Today:</strong> Count of changes or modifications to existing records.</li>
                    <li><strong>Top Operator Today:</strong> The administrative username with the highest daily action count.</li>
                    <li><strong>Deletions Today:</strong> Count of records deleted or removed from the registries.</li>
                </ul>

                <h2>4. Daily Activity History Chart</h2>
                <p>A right-pane chart card displays the daily logs activity trend for the last 15 days using Chart.js. The trend line redraws on page filters and automatically overrides ticks, grids, and legend colors to align with light or dark themes.</p>

                <h2>5. Advanced Filtering, Sorting, and CSV Export</h2>
                <ul>
                    <li><strong>Dropdown Filters:</strong> Drill down by operation type (Create, Update, Delete) and targeted database registry table.</li>
                    <li><strong>Interactive Column Headers:</strong> Toggles sorting ascending or descending on ID, Operator, IP Address, and timestamp.</li>
                    <li><strong>CSV Exporter:</strong> Allows administrators to export the filtered dataset into a downloadable spreadsheet file (<code>audit_trail_export.php</code>).</li>
                </ul>
            </div>

            <!-- Section 8: Developer Reference -->
            <div id="devnotes" class="doc-section">
                <h1>Developer Reference</h1>
                <p>Important implementation notes for developers maintaining the codebase.</p>

                <h2>1. Security & Hashing</h2>
                <p>Legacy plain-text passwords have been fully phased out. All registration processors (<code>users_register_public_proc.php</code>, <code>users_register_proc.php</code>) and profile editing panels hash passwords using PHP's native algorithms:</p>
                <pre><code>// Password Hashing
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Verification during authentication
if (password_verify($input_password, $stored_hash)) {
    // Access Granted
}</code></pre>

                <h2>2. Self-Healing QR Code Engine</h2>
                <p>The unified print layout contains a fallback mechanism to prevent broken images. If a QR code is missing on the disk (e.g. from data migrations), <code>print_application.php</code> generates it on-the-fly using <code>qrlib.php</code>:</p>
                <pre><code>// Dynamic check and generation in print_application.php
if (!file_exists($qr_path)) {
    include_once('qrlib/qrlib.php');
    // Build QR contents based on service data
    QRcode::png($qr_data, $qr_path);
}</code></pre>

                <h2>3. Strict SQL Mode Compliance</h2>
                <p>When forms submit empty date fields, MySQL strict SQL mode prevents saving empty strings (<code>''</code>) in <code>DATE</code> columns. Ensure all date inputs are processed using nullable conditional assignments:</p>
                <pre><code>// Formatting null fields
$date_val = !empty($_POST['date_field']) ? "'" . mysqli_real_escape_string($link, $_POST['date_field']) . "'" : "NULL";
$link->query("UPDATE table SET date_field = $date_val");</code></pre>

                <h2>4. Global AJAX CRUD Engine</h2>
                <p>Data mutations are centralized via <code>crud_functions.php</code> and dispatched asynchronously from client pages using the global helper functions in <code>crud_functionjs.php</code>:</p>
                <pre><code>// Client-side execution
updateRecord('visitors', id, { fullname: 'Juan Dela Cruz' }, function(response) {
    if (response.status === 'success') {
        location.reload();
    }
});</code></pre>
                <p>On the backend, parameters are dynamically mapped, filtered, and compiled into parameterized prepared SQL statement blocks to secure connection threads.</p>

                <h2>5. Media Processing & FFmpeg Integration</h2>
                <p>To avoid browser playback buffers and rendering lag in lists, uploaded video files are parsed dynamically using a local FFmpeg binary to extract image posters:</p>
                <pre><code>// Dynamic binary resolution and execution
$ffmpeg = getFFmpegPath();
if ($ffmpeg && file_exists($filePath)) {
    $absVideo = realpath($filePath);
    $absThumb = __DIR__ . '/' . $thumbPath;
    $cmd = "\"$ffmpeg\" -y -i \"$absVideo\" -ss 00:00:00.500 -vframes 1 -f image2 \"$absThumb\" > NUL 2>&1";
    exec($cmd);
}</code></pre>

                <h2>6. Print-Optimized Layouts</h2>
                <p>Official print views utilize specific CSS <code>@media print</code> rule overrides to hide sidebars, buttons, and navigation elements while sizing cards to exact ISO ID-1 physical paper measurements:</p>
                <pre><code>@media print {
    .no-print, .sidebar, .top-navbar, button {
        display: none !important;
    }
    .card-print-area {
        width: 86mm;  /* standard card dimensions */
        height: 54mm;
        page-break-inside: avoid;
    }
}</code></pre>

                <h2>7. CRUD Direct Access Security Guard</h2>
                <p>A strict authentication guard is enforced at the entry point of the global AJAX routing script. Unauthenticated direct requests are rejected with a <code>401 Unauthorized</code> header:</p>
                <pre><code>if (basename($_SERVER['SCRIPT_FILENAME']) === 'crud_functions.php') {
    if (!isset($_SESSION['user'])) {
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
        exit;
    }
}</code></pre>

				<!-- Footer -->
				<div align="center" style="margin-top:35px">	
					Citizen-Centric Digital Platform &copy; <?php echo date("Y");?> &bull; LGU Tabina &bull; Powered by McJim Cyberworks
				</div>
            </div>
        </div>
    </div>

    <!-- ScrollSpy / Navigation scripts & Theme toggler -->
    <script>
        document.querySelectorAll('.sidebar-menu a, .mobile-tabs a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Clear active class from menu and tabs
                document.querySelectorAll('.sidebar-menu a, .mobile-tabs a').forEach(a => a.classList.remove('active'));
                
                // Match and active the corresponding anchor
                const targetId = this.getAttribute('href');
                document.querySelectorAll(`a[href="${targetId}"]`).forEach(a => a.classList.add('active'));

                const targetElement = document.querySelector(targetId);
                
                window.scrollTo({
                    top: targetElement.offsetTop - 150,
                    behavior: 'smooth'
                });
            });
        });

        // Theme Toggle Script
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeIcon = themeToggleBtn.querySelector('i');
        
        function syncThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.className = 'fas fa-sun';
                themeToggleBtn.style.color = '#fff';
                themeToggleBtn.style.background = '#2b2b2b';
            } else {
                themeIcon.className = 'fas fa-moon';
                themeToggleBtn.style.color = '#555';
                themeToggleBtn.style.background = '#f1f3f5';
            }
        }

        // Get initial theme from root or localStorage
        let currentTheme = localStorage.getItem('theme') || document.documentElement.getAttribute('data-theme') || 'light';
        document.documentElement.setAttribute('data-theme', currentTheme);
        syncThemeIcon(currentTheme);

        themeToggleBtn.addEventListener('click', () => {
            let newTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            syncThemeIcon(newTheme);
            
            // Also notify dashboard / global components if they listen for localStorage updates
            window.dispatchEvent(new Event('storage'));
        });
    </script>
</body>
</html>
