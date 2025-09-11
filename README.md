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


## 🧩 Code-Level Architecture & Flow

### Backend Structure

- **API Endpoints (`backend/api/`)**: Each PHP file exposes RESTful endpoints for a specific resource (attendance, documents, announcements, departments, field workers). Endpoints accept GET/POST requests and return JSON responses.
   - Example: `attendance_api.php?action=checkIn` (POST) expects `{ user_id, latitude, longitude }` and returns `{ success, error }`.

- **Controllers (`backend/controllers/`)**: Handle business logic and validation. They call model methods and services, process input, and return results.
   - `AttendanceController.php`: Manages check-in/check-out, validates group schedule, location/IP, and updates attendance records.
   - `AuthController.php`: Handles login/logout, session management, redirects based on user role.
   - `FieldWorkerController.php`: Manages profile completion, password changes, document uploads, worker CRUD operations.

- **Models (`backend/models/`)**: Represent database tables and provide static methods for CRUD operations.
   - Example: `Attendance.php` has methods like `checkIn($userId)`, `hasCheckedInToday($userId)`, `getAll($search, $department, $date)`.
   - `User.php`, `FieldWorker.php`, `Document.php`, etc. encapsulate DB logic for their respective entities.

- **Services (`backend/services/`)**: Encapsulate reusable logic.
   - `GroupService.php`: Checks if a worker’s group is scheduled for today.
   - `LocationService.php`: Validates IP/location for check-in/out.
   - `UploadService.php`: Handles file uploads and validation.

### Frontend Structure

- **Admin Panel (`frontend/admin/`)**: PHP pages for dashboard, attendance, workers, departments, documents, announcements. Use JavaScript to call backend APIs via AJAX/fetch.
   - Example: `attendance.php` loads records by calling `attendance_api.php?action=list` and displays them in a table.
   - `dashboard.js` fetches stats and updates UI widgets.

- **Field Worker Panel (`frontend/field_worker/`)**: Dashboard, attendance, profile, documents. JS files handle check-in/out, profile completion, document upload.
   - Example: `attendance.js` uses browser geolocation, sends check-in/out requests to backend, and handles responses.

### Example Flow: Field Worker Check-In

1. Field worker clicks "Check-In" in the frontend (`attendance.js`).
2. JS gets geolocation, sends POST to `/backend/api/attendance_api.php?action=checkIn` with user ID, latitude, longitude.
3. `AttendanceController::checkIn()` validates group schedule, checks if already checked in, validates IP/location.
4. If valid, `Attendance::checkIn($userId)` creates a new attendance record in DB.
5. Response `{ success: true }` is returned to frontend, which updates UI.

### Example: Document Upload

1. Field worker selects a file and document type in frontend.
2. JS sends POST with file to `/backend/api/document_api.php?action=upload`.
3. `FieldWorkerController::uploadDocument()` validates and saves file, updates DB status.
4. Response indicates success/failure.

### Error Handling

- Backend returns `{ success: false, error: "..." }` for failed operations.
- Frontend displays error messages and disables UI actions as needed.

---

## 📫 Contact

For issues, email `isaacshaban54@gmail.com` or open an issue in the repo.
