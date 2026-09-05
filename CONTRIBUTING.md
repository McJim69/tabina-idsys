## 🤝 Contribution Guidelines

We welcome contributions to improve the **LGU Tabina CCDP** project.  
Please follow these steps to ensure smooth collaboration:

1. **[Fork the repository](ca://s?q=How_to_fork_a_repository)** – create your own copy to work on.  
2. **[Create a feature branch](ca://s?q=How_to_create_a_feature_branch)** – use descriptive names (e.g. `feature/audit-logs-enhancement`).  
3. **[Commit changes](ca://s?q=Best_practices_for_git_commits)** – write clear, concise commit messages.  
4. **[Push to your fork](ca://s?q=How_to_push_to_git_fork)** – keep your branch updated with the latest main branch.  
5. **[Open a Pull Request](ca://s?q=How_to_open_a_pull_request)** – explain your changes and reference related issues.  

---

### Code Style
- Follow **PSR‑4 standards** for PHP.  
- Use **secure coding practices** (parameterized queries, password hashing).  
- Keep **README and documentation updated** when adding new features.  

---

### Reporting Issues
- Use the **[issue tracker](ca://s?q=How_to_use_issue_tracker)** for bugs, feature requests, or questions.  
- Provide detailed steps to reproduce problems.  

---

#### Quick Start

# ⚡ Quick Start – LGU Tabina CCDP

This guide helps you set up the **LGU Tabina Citizen-Centric Digital Platform (CCDP)** quickly.

---

## 1. Clone the Repository

##```bash
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