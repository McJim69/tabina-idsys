# LGU Tabina Citizen-Centric Digital Platform (CCDP)  
![PHP](https://img.shields.io/badge/PHP-7.4%20--%208.2-blue?logo=php)  
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange?logo=mysql)  
![MariaDB](https://img.shields.io/badge/MariaDB-10.4%2B-lightblue?logo=mariadb)  
![Apache](https://img.shields.io/badge/Apache-2.4%2B-red?logo=apache)  
![License](https://img.shields.io/badge/License-LGU%20Tabina-green)

---

## ✨ Overview
The **LGU Tabina CCDP** streamlines, automates, and secures municipal services for residents of Tabina, Zamboanga del Sur.  
It features an integrated citizen portal and a robust administration module.

---

## 🚀 Key Features
- **[Global AJAX CRUD Engine](ca://s?q=Explain_Global_AJAX_CRUD_Engine)**  
- **[System Audit Logs](ca://s?q=Explain_System_Audit_Logs)**  
- **[Role-Based Approval](ca://s?q=Explain_Role_Based_Approval)**  
- **[Self-Healing QR Codes](ca://s?q=Explain_Self_Healing_QR_Codes)**  
- **[Secure Password Hashing](ca://s?q=Explain_Password_Hashing_in_PHP)**  

---

## 📦 Deployment Guide
- Standard PHP/MySQL environment (PHP 7.4–8.2, MySQL/MariaDB).  
- Apply **unique constraints** on ID card tables.  
- Ensure **read/write access** for QR code directories and user upload folders.  

---

## 🏛 System Architecture
- **[Database Model](ca://s?q=Explain_Database_Model_in_CCDP)**  
- **[Date Fields Consolidation](ca://s?q=Explain_Date_Fields_Consolidation)**  
- **[Global CRUD Engine](ca://s?q=Explain_Global_CRUD_Engine)**  

---

## 👩‍💼 Admin User Guide
- Review applications via admin grids.  
- Approve/Deny requests (Admin/Executive only).  
- Print ID cards and certificates with QR verification.  

---

## 👨‍👩‍👧 Citizen Portal Guide
- Account setup with profile photo.  
- Smart form auto‑filling.  
- Real‑time tracking with notifications.  
- Single‑application enforcement for ID cards.  

---

## 💬 Communication Features
- Private Messenger (1‑on‑1).  
- Group Chat Rooms.  
- Message Board for announcements.  

---

## 📊 System Audit Logs
- Real‑time polling.  
- Action verb & table detection.  
- KPI indicators.  
- 15‑day activity chart.  
- Filtering, sorting, CSV export.  

---

## 🛠 Developer Reference
- Password hashing.  
- QR code engine fallback.  
- SQL strict mode compliance.  
- FFmpeg integration.  
- Print layouts.  
- Security guard for AJAX routing.  

---

## 🤝 Contribution Guidelines
- **[Fork the repository](ca://s?q=How_to_fork_a_repository)**  
- **[Create a feature branch](ca://s?q=How_to_create_a_feature_branch)**  
- **[Commit changes](ca://s?q=Best_practices_for_git_commits)**  
- **[Push to your fork](ca://s?q=How_to_push_to_git_fork)**  
- **[Open a Pull Request](ca://s?q=How_to_open_a_pull_request)**  

---

## 📜 Code of Conduct
- Respect & inclusivity.  
- Clear collaboration.  
- Responsibility in coding & documentation.  
- Enforcement by admins/maintainers.  

---

## 📝 Release Notes
### v1.0.0 – Initial Release  
- Core deployment, CRUD engine, secure hashing, QR codes.  

### v1.1.0 – Feature Enhancements  
- Audit Logs Dashboard, Smart Form Auto‑Filling, role‑based approval.  

### v1.2.0 – Communication Suite  
- Private Messenger, Group Chat Rooms, Message Board.  

### v1.3.0 – Developer Improvements  
- SQL DATE consolidation, FFmpeg integration, strict SQL compliance.  

---

## 🔒 Security Policy
- Report vulnerabilities via the **[issue tracker](ca://s?q=How_to_use_issue_tracker)**.  
- Do not disclose publicly until patched.  
- Follow responsible disclosure practices.  
- Security fixes prioritized in upcoming releases.  

---

## 📆 Changelog
- **Added** audit logs dashboard.  
- **Improved** QR code generation fallback.  
- **Fixed** strict SQL mode date handling.  
- **Enhanced** communication suite with group chat.  

---

## 📄 License
Maintained by **LGU Tabina**  
For inquiries, contact the developer:
 - admin@mcjim-server.com
 - https://mcjim-server.com

---

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

---
