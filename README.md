# LGU Tabina Citizen-Centric Digital Platform (CCDP)

Welcome to the official documentation for the **LGU Tabina CCDP**.  
This platform streamlines, automates, and secures municipal services for residents of Tabina, Zamboanga del Sur.


## ✨ Overview
The CCDP integrates a citizen portal with an administration module to manage applications, permits, and certificates.


## 🚀 Key Features
- **[Global AJAX CRUD Engine](ca://s?q=Explain_Global_AJAX_CRUD_Engine)** – instant record operations across all tables.  
- **[System Audit Logs](ca://s?q=Explain_System_Audit_Logs)** – real-time KPIs, filters, sorting, CSV exports, activity charts.  
- **[Role-Based Approval](ca://s?q=Explain_Role_Based_Approval)** – restricted to Administrator & Executive accounts.  
- **[Self-Healing QR Codes](ca://s?q=Explain_Self_Healing_QR_Codes)** – generated on-the-fly for official printouts.  
- **[Secure Password Hashing](ca://s?q=Explain_Password_Hashing_in_PHP)** – modern PHP hashing algorithms.  


## 📦 Deployment Guide
### Server Dependencies
- Standard PHP/MySQL environment (PHP 7.4–8.2, MySQL/MariaDB).  
- Compatible with common web servers (Apache/IIS).  

### Database Schema Setup
- Apply **unique constraints** on ID card tables (Senior Citizens, PWD, Solo Parents).  

### Folder Permissions
- Ensure **read/write access** for QR code directories and user upload folders.  


## 🏛 System Architecture
- **Database Model** – modular tables per municipal service.  
- **Date Fields Consolidation** – unified SQL DATE columns.  
- **Global CRUD Engine** – centralized backend scripts with secure AJAX endpoints.  


## 👩‍💼 Admin User Guide
- **Review Applications** – access citizen submissions via admin grids.  
- **Approve/Deny Requests** – restricted to Admin/Executive accounts.  
- **Print Cards & Certificates** – ISO ID‑1 cards and A4 certificates with seals & QR codes.  


## 👨‍👩‍👧 Citizen Portal Guide
- **Account Setup** – citizens upload profile photo for ID cards.  
- **Smart Form Auto-Filling** – pre-populated demographic data.  
- **Real-Time Tracking** – instant notifications on status updates.  
- **Single-Application Enforcement** – one ID card per citizen.  


## 💬 Communication Features
- **Private Messenger** – 1‑on‑1 chat with real-time delivery.  
- **Group Chat Rooms** – multiple rooms for announcements & coordination.  
- **Message Board** – official bulletin with moderation & read tracking.  


## 📊 System Audit Logs
- Real-time polling every few seconds.  
- Action verb & table detection with badges.  
- KPI indicators (Created Today, Updated Today, Top Operator, etc.).  
- 15-day activity chart with trend visualization.  
- Advanced filtering, sorting, and CSV export.  


## 🛠 Developer Reference
- **Password Hashing** – secure PHP algorithms.  
- **QR Code Engine** – auto-regeneration fallback.  
- **SQL Mode Compliance** – NULL-safe date handling.  
- **FFmpeg Integration** – video thumbnails for uploads.  
- **Print Layouts** – CSS `@media print` rules.  
- **Security Guard** – strict authentication for AJAX routing.  


## 📄 License
Maintained by **LGU Tabina**.  
For inquiries, contact the municipal IT office.
