#### Quick Start

# ⚡ Quick Start – LGU Tabina CCDP

This guide helps you set up the **LGU Tabina Citizen-Centric Digital Platform (CCDP)** quickly.

---

## 1. Clone the Repository
git clone https://github.com/your-org/lgu-tabina-ccdp.git
cd lgu-tabina-ccdp

## 2. Install Dependencies
PHP 7.4–8.2 with mysqli and gd extensions enabled
MySQL 5.7+ or MariaDB 10.4+
Apache/IIS web server

## 3. Import Database
Import the backup file from the DATABASE/ directory
Apply unique constraints on ID card tables (Senior Citizens, PWD, Solo Parents)

## 4. Configure Environment
Update database connection in config.php
Ensure write permissions for QR code and upload directories

## 5. Run the Server
Start Apache/IIS
Access the portal via:

http://localhost/public_home.php
Admins log in via the admin dashboard

## 6. First Login
Create an Administrator account
Test citizen registration and verify QR code generation

## ✅ Done!
You now have the CCDP running locally.
For advanced deployment, see the main README.

---

