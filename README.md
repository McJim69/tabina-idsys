Overview
Welcome to the official system documentation for the LGU Tabina Citizen-Centric Digital Platform (CCDP). This digital platform was designed to streamline, automate, and secure key municipal services for residents of Tabina, Zamboanga del Sur.

The system features an integrated citizen application portal alongside a robust administration module allowing municipal staff to review, manage, and print official documentation and ID cards.

Key Milestones Achieved:
Global AJAX CRUD Engine allowing instant record creation, read, update, and deletion across all database tables.
Sleek, real-time System Audit Logs dashboard with dynamic KPI cards, dropdown filters, column sorting, CSV exports, and interactive daily activity charts.
Strict SQL Mode compatibility with automatic empty date values to NULL conversion on insertion and updates.
Role-based application approval pipeline (authorized only for Administrator & Executive accounts).
Demographic pre-filling for all citizen forms to speed up the workflow.
Self-healing, on-the-fly QR code generation for official printouts.
Strict single-application constraints for ID Card services (Senior Citizens, PWD, Solo Parents) at the database level.
Modernized application password hashing using secure PHP hashing keys.
Deployment Guide
Follow these steps to deploy or migrate the LGU Tabina CCDP server environment.

1. Server Dependencies
Operating System: Windows Server (using Apache via XAMPP) or Linux (Ubuntu Server).
Web Server: Apache 2.4+ or IIS 10+.
PHP Environment: PHP 7.4 to 8.2 (with mysqli and gd extensions enabled).
Database Server: MySQL 5.7+ or MariaDB 10.4+.
2. Database Schema Setup
Import the database backup file located in the DATABASE/ directory into your MySQL server. Verify that the unique constraints on the ID card tables are active:

ALTER TABLE senior ADD UNIQUE INDEX unique_user_id (user_id);
ALTER TABLE pwd ADD UNIQUE INDEX unique_user_id (user_id);
ALTER TABLE solo_parent ADD UNIQUE INDEX unique_user_id (user_id);
3. Folder Write Permissions
Important: The web server must have write permissions for the QR code and user upload folders. Without these permissions, photo uploads and on-the-fly QR code generation will fail.
Grant full read/write permission to the following directories:

images/users/
images/senior/qrcodes/
images/pwd/qrcodes/
images/solo_parent/qrcodes/
images/clearances/qrcodes/
images/cert_indigency/qrcodes/
images/permit_business/qrcodes/
images/reg_fishing/qrcodes/
images/permit_operate/qrcodes/
System Architecture
The LGU Tabina CCDP follows a modular, structured PHP architectural layout.

1. Database Model & Column Refactoring
The database (idsystem_lgu) consists of tables split by municipal office services. These include:

Table Name	Municipal Service Represented	Status Column	Constraints
senior	OSCA Senior Citizen Card	status	Primary key `idn`, unique `user_id`
pwd	PWD Registration Card	status	Primary key `idn`, unique `user_id`
solo_parent	Solo Parent Card	status	Primary key `idn`, unique `user_id`
cert_indigency	Indigent Certificate	app_status	Primary key `idn`, multiple per citizen allowed
clearances	Mayor's Clearance document	status	Primary key `idn`, multiple per citizen allowed
permit_business	Business Permit	status	Primary key `idn`, multiple per citizen allowed
reg_fishing	Fishing Boat Permit	status	Primary key `idn`, multiple per citizen allowed
permit_operate	Permit to Operate	status	Primary key `idn`, multiple per citizen allowed
2. Date Fields Consolidation
All legacy split date fields (separated into day, month, and year selects) have been consolidated into unified, single SQL DATE columns (e.g. date_birth, assoc_reg_date, date_issued, date_or) across all tables, ensuring date integrity, sorting efficiency, and form compliance.

3. Global AJAX CRUD Engine
A unified CRUD engine is integrated globally into the platform via crud_functions.php (backend endpoint handler) and crud_functionjs.php (frontend client library). It allows web components to execute standard create, read, update, and delete database actions on any table securely using asynchronous JavaScript XMLHttpRequests.

Admin User Guide
This section is for LGU staff (Secretary, Social Workers, Executives, and Administrators).

1. Reviewing Applications
Administrative staff can access different grids (e.g. senior_grid.php, pwd_grid.php, etc.) to view citizen submissions. Each card lists key demographic details and offers standard management actions.

2. Approving & Denying Requests
Only users logged in as Administrator or Executive will see the status dropdown modification tools on the grid panels.
To change an application status:

Locate the application card in the grid.
Click the status dropdown pill (e.g. Pending) at the bottom.
Select Approved or Denied.
The system sends an AJAX query to update_application_status.php, saves the update, and displays a success flash.
3. Printing Cards & Certificates
Once an application reaches the Approved state, click the action overlay or the print buttons in the grids. This redirects to the unified print layout:

ID Cards: Renders a high-fidelity credit-card dimensioned layout (337px x 213px) for front and back layouts containing the citizen's avatar photo and QR verification barcode.
Official Certificates: Renders an A4 letterhead certificate featuring the official seals, text layouts, Mayor signatory lines, and authenticity verification stamp.
Citizen Portal Guide
This guide explains the citizen workflow for utilizing the digital portal (public_home.php / public_dashboard.php).

1. Account Setup
Citizens sign up for a digital account and upload their profile photo. This photo is automatically used to render their official ID cards when approved.

2. Smart Form Auto-Filling
When applying for any service (e.g. Solo Parent ID), the portal automatically reads their authenticated user session and pre-populates their demographic and address fields in the application forms. This ensures zero data entry discrepancies.

3. Real-Time Tracking
On their dashboard, citizens see a tracking grid displaying all active applications. When a municipal admin updates their status, the citizen's dashboard triggers a visual SweetAlert popup notifying them immediately upon their next access.

4. Single-Application Enforcements
For ID Card services, citizens are limited to a single application. If they attempt to register again, the system halts execution and informs them of their active profile. Multiple applications remain enabled for transactional permits and clearances.

Communication Features
The CCDP platform integrates real-time communications for citizen engagement, administrative coordination, and announcements.

1. Private Message (1-on-1 Chat Messenger)
The integrated Messenger (messenger.php / private_chat.php) provides direct 1-on-1 private messaging between portal users (Admins, Executives, Welfare Staff, and Citizens).

Active Users Search: Users can browse or search the listing of active registered accounts on the left sidebar list.
Real-Time Delivery & Caching: Handles immediate delivery of chat messages and keeps track of sender/receiver states.
Unread Count Badge: Generates a dynamic notification badge in the main navigation menu (pm-unread-badge) indicating any unread direct messages waiting for the user.
2. Group Chat (Live Chat Rooms)
The chat room module (chat_rooms.php / room.php) facilitates group-wide communications and discussion boards for general announcements or collaborative team tasks.

Multiple Rooms Support: Admins can define different chat rooms for general public topics, staff coordination, or disaster alerts.
Targeted Replies: Supports referencing specific past messages (using the reply_to database column) for threaded replies.
3. Message Board (Official Announcements Bulletin)
The administrative Message Board (message_board.php) acts as a broadcast bulletin where authorized users can post general messages or public service updates.

Moderation: Admins can manage postings, view comments, and confirm announcements.
Read confirmation: Logs view-counts and tracking times to audit when citizens engage with the bulletins.
System Audit Logs
The platform features a state-of-the-art administrative portal for system event monitoring (audit_trail.php / audit_trail_data.php).

1. Real-Time Event Polling
Features an AJAX-powered real-time toggle switch. When active, it polls and refreshes the event log table dynamically every 3 seconds, flashing a pulsing green dot indicator.

2. Highlighted Action Verb & Table Detection
The log viewer parses event descriptions, extracting actions (such as CREATE, UPDATE, DELETE, SESSION) and targeted tables (such as SENIOR, PWD, VISITORS, HOUSEHOLDS) to render color-coded contextual badges.

3. KPI Statistical Indicators
Five dynamic statistical panels provide a summary of today's activities:

Logs Today: Aggregate events recorded since midnight.
Created Today: Count of new demographic profiles or permits registered today.
Updated Today: Count of changes or modifications to existing records.
Top Operator Today: The administrative username with the highest daily action count.
Deletions Today: Count of records deleted or removed from the registries.
4. Daily Activity History Chart
A right-pane chart card displays the daily logs activity trend for the last 15 days using Chart.js. The trend line redraws on page filters and automatically overrides ticks, grids, and legend colors to align with light or dark themes.

5. Advanced Filtering, Sorting, and CSV Export
Dropdown Filters: Drill down by operation type (Create, Update, Delete) and targeted database registry table.
Interactive Column Headers: Toggles sorting ascending or descending on ID, Operator, IP Address, and timestamp.
CSV Exporter: Allows administrators to export the filtered dataset into a downloadable spreadsheet file (audit_trail_export.php).
Developer Reference
Important implementation notes for developers maintaining the codebase.

1. Security & Hashing
Legacy plain-text passwords have been fully phased out. All registration processors (users_register_public_proc.php, users_register_proc.php) and profile editing panels hash passwords using PHP's native algorithms:

// Password Hashing
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Verification during authentication
if (password_verify($input_password, $stored_hash)) {
    // Access Granted
}
2. Self-Healing QR Code Engine
The unified print layout contains a fallback mechanism to prevent broken images. If a QR code is missing on the disk (e.g. from data migrations), print_application.php generates it on-the-fly using qrlib.php:

// Dynamic check and generation in print_application.php
if (!file_exists($qr_path)) {
    include_once('qrlib/qrlib.php');
    // Build QR contents based on service data
    QRcode::png($qr_data, $qr_path);
}
3. Strict SQL Mode Compliance
When forms submit empty date fields, MySQL strict SQL mode prevents saving empty strings ('') in DATE columns. Ensure all date inputs are processed using nullable conditional assignments:

// Formatting null fields
$date_val = !empty($_POST['date_field']) ? "'" . mysqli_real_escape_string($link, $_POST['date_field']) . "'" : "NULL";
$link->query("UPDATE table SET date_field = $date_val");
4. Global AJAX CRUD Engine
Data mutations are centralized via crud_functions.php and dispatched asynchronously from client pages using the global helper functions in crud_functionjs.php:

// Client-side execution
updateRecord('visitors', id, { fullname: 'Juan Dela Cruz' }, function(response) {
    if (response.status === 'success') {
        location.reload();
    }
});
On the backend, parameters are dynamically mapped, filtered, and compiled into parameterized prepared SQL statement blocks to secure connection threads.

5. Media Processing & FFmpeg Integration
To avoid browser playback buffers and rendering lag in lists, uploaded video files are parsed dynamically using a local FFmpeg binary to extract image posters:

// Dynamic binary resolution and execution
$ffmpeg = getFFmpegPath();
if ($ffmpeg && file_exists($filePath)) {
    $absVideo = realpath($filePath);
    $absThumb = __DIR__ . '/' . $thumbPath;
    $cmd = "\"$ffmpeg\" -y -i \"$absVideo\" -ss 00:00:00.500 -vframes 1 -f image2 \"$absThumb\" > NUL 2>&1";
    exec($cmd);
}
6. Print-Optimized Layouts
Official print views utilize specific CSS @media print rule overrides to hide sidebars, buttons, and navigation elements while sizing cards to exact ISO ID-1 physical paper measurements:

@media print {
    .no-print, .sidebar, .top-navbar, button {
        display: none !important;
    }
    .card-print-area {
        width: 86mm;  /* standard card dimensions */
        height: 54mm;
        page-break-inside: avoid;
    }
}
7. CRUD Direct Access Security Guard
A strict authentication guard is enforced at the entry point of the global AJAX routing script. Unauthenticated direct requests are rejected with a 401 Unauthorized header:

if (basename($_SERVER['SCRIPT_FILENAME']) === 'crud_functions.php') {
    if (!isset($_SESSION['user'])) {
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
        exit;
    }
}
