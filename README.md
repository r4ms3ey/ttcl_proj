# Employee Attendance Management System

A web-based system for managing employee attendance with role-based access, check-in/out tracking, group scheduling, and document uploads.

## 🔧 Tech Stack

- **Frontend**: HTML, CSS, JavaScript, jQuery
- **Backend**: PHP
- **Database**: MySQL

## 📁 File Structure

```
ttcl_proj/
├── attendance_db.sql
├── backend/
│   ├── api/
│   │   ├── admindocument_api.php
│   │   ├── announcement_api.php
│   │   ├── attendance_api.php
│   │   ├── attendance_api_debug.log
│   │   ├── department_api.php
│   │   ├── department_list.php
│   │   ├── document_api.php
│   │   ├── fieldworker_api.php
│   │   ├── fieldworker_password_api.php
│   │   └── fieldworker_profile_api.php
│   ├── controllers/
│   │   ├── AttendanceController.php
│   │   └──AuthController.php
│   ├── models/
│   │   ├── AdminDocument.php
│   │   ├── Announcement.php
│   │   ├── Attendance.php
│   │   ├── Department.php
│   │   ├── Document.php
│   │   ├── FieldWorker.php
│   │   ├── IPLog.php
│   │   ├── Settings.php
│   │   └── User.php
│   ├── services/
│   │   ├── GroupService.php
│   │   ├── LocationService.php
│   │   └── UploadService.php
│   └── uploads/
├── config/
│   └── Database.php
├── frontend/
│   ├── admin/
│   │   ├── admin.css
│   │   ├── announcements.php
│   │   ├── attendance.php
│   │   ├── dashboard.js
│   │   ├── dashboard.php
│   │   ├── dashboard_stats.php
│   │   ├── departments.php
│   │   ├── documents.php
│   │   ├── export_csv.php
│   │   └── workers.php
│   └── field_worker/
│       ├── announcements.js
│       ├── attendance.js
│       ├── auth_redirect.php
│       ├── checkin.js
│       ├── complete_password.php
│       ├── complete_profile.php
│       ├── dashboard.php
│       ├── documentation.js
│       ├── documents.js
│       ├── password.css
│       ├── password.js
│       ├── profile.css
│       ├── profile.js
│       ├── status.js
│       └── style.css
├── index.php
├── login.php
├── logout.php
└── README.md
```

## ✅ Features

- Admin and field worker login
- Check-in and check-out functionality
- Group attendance logic by scheduled days
- Document upload (certificates/registration)
- Department and group assignment
- IP/Location control (mandatory)

## 🚀 Setup Instructions

1. **Clone the repo**
   ```bash
   git clone https://github.com/your-username/attendance-system.git
   cd attendance-system
   ```

2. **Create a database**
   - Import the provided `attendance_db.sql` file into MySQL.
   - Update `config/Database.php` with your DB credentials.

3. **Run locally**
   - Place the project in your web root (e.g., `htdocs` for XAMPP or `/var/www/html` for Apache).
   - Access via browser: `http://localhost/attendance-system/index.php`

## 🧠 Group Attendance Logic

- Each field worker is assigned to a group (e.g., Group A, Group B) with a specific start date and a set of allowed attendance days (e.g., Mon, Wed, Fri).
- The system checks the current day against the assigned group's schedule and the worker's start date.
- If today is not a valid group day or is before the worker's start date, check-in is blocked and the worker is notified.
- Group assignment and allowed days are managed by the admin in the backend.
- Attendance records are filtered and displayed according to group, department, and worker start date in the admin dashboard.
- All group and schedule logic is enforced both in the backend (PHP) and reflected in the frontend UI (JavaScript).

## 📄 Document Upload Rules

- Allowed types: `certificate`, `registration`
- Status: `uploaded` / `missing`
- Extra fields: `purpose`, `comment`

## 🔒 IP/Location Tracking

- IP and timestamp are logged during each check-in/check-out.
- Location matching is enforced by comparing against assigned base station IP.

## 🙋 Common Issues

- **Login fails**: Check password is hashed in DB using `password_hash`.
- **Check-in blocked**: Group might not be scheduled today or not assigned.

## 📫 Contact

For issues, email `isaacshaban54@gmail.com` or open an issue in the repo.
