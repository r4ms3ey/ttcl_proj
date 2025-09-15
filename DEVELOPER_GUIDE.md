# Developer Guide: Employee Attendance Management System

This guide explains the code structure, main files, and logic flow for developers working on the system.

---

## Project Structure Overview

- `index.php`, `login.php`, `logout.php`: Entry points for authentication and routing.
- `attendance_db.sql`: MySQL database schema.
- `README.md`: Project overview and setup instructions.
- `backend/`
  - `api/`: RESTful endpoints for resources (attendance, documents, announcements, departments, field workers).
  - `controllers/`: Business logic for each resource. Controllers validate requests, enforce rules, and interact with models/services.
  - `models/`: Represent database tables. Each model provides CRUD methods and encapsulates SQL queries.
  - `services/`: Reusable logic (group scheduling, location/IP validation, file uploads).
  - `lib/`: Third-party libraries (e.g., TCPDF for PDF generation).
  - `middlewares/`: (If present) Request filtering and authentication logic.
  - `uploads/`: User-uploaded documents and templates.
- `config/Database.php`: Database connection logic.
- `frontend/`
  - `admin/`: Admin dashboard and management pages (attendance, workers, departments, documents, announcements).
  - `field_worker/`: Field worker dashboard, attendance, profile, document upload.

---

## Backend Flow

### API Endpoints
- Each file in `backend/api/` handles HTTP requests for a resource.
- Example: `attendance_api.php` processes actions like `checkIn`, `checkOut`, and returns JSON.
- Endpoints validate input, call controllers, and return responses.

### Controllers
- Controllers (e.g., `AttendanceController.php`, `AuthController.php`) contain business logic.
- Example: `AttendanceController::checkIn()`
  - Validates group schedule, checks if already checked in, verifies location/IP.
  - Calls `Attendance::checkIn($userId)` to update DB.

### Models
- Models (e.g., `Attendance.php`, `User.php`) represent DB tables.
- Provide static methods for CRUD operations.
- Example: `Attendance::getAll($search, $department, $date)` fetches filtered attendance records.

### Services
- Encapsulate reusable logic.
- Example: `GroupService::isTodayAllowed($userId)` checks if a worker’s group is scheduled for today.

---

## Frontend Flow

### Admin Panel
- PHP pages render UI and fetch data via AJAX/fetch to backend APIs.
- Example: `attendance.php` loads records from `attendance_api.php` and displays them.
- JavaScript files (e.g., `dashboard.js`) update UI widgets and handle user actions.

### Field Worker Panel
- JS files (e.g., `attendance.js`, `profile.js`) handle check-in/out, profile completion, document upload.
- Use browser APIs (e.g., geolocation) and send requests to backend.

---

## Example: Attendance Check-In
1. Field worker clicks "Check-In" in frontend (`attendance.js`).
2. JS gets geolocation, sends POST to `/backend/api/attendance_api.php?action=checkIn`.
3. `AttendanceController::checkIn()` validates and updates DB.
4. Response is returned and UI is updated.

---

## File-by-File Explanation

### Entry Points
- `index.php`: Main landing page, handles login routing.
- `login.php`: Login form and authentication logic.
- `logout.php`: Destroys session and redirects to login.

### Backend
- `backend/api/attendance_api.php`: Handles attendance actions (check-in, check-out, set location).
- `backend/api/document_api.php`: Handles document upload and retrieval.
- `backend/api/announcement_api.php`: Handles announcements CRUD.
- `backend/controllers/AttendanceController.php`: Attendance business logic.
- `backend/controllers/AuthController.php`: Login/logout logic.
- `backend/controllers/FieldWorkerController.php`: Field worker profile, password, document, and CRUD logic.
- `backend/models/Attendance.php`: Attendance DB operations.
- `backend/models/User.php`: User DB operations.
- `backend/models/FieldWorker.php`: Field worker DB operations.
- `backend/services/GroupService.php`: Group scheduling logic.
- `backend/services/LocationService.php`: Location/IP validation.
- `backend/services/UploadService.php`: File upload logic.

### Frontend
- `frontend/admin/attendance.php`: Admin attendance management UI.
- `frontend/admin/workers.php`: Admin worker management UI.
- `frontend/admin/departments.php`: Admin department management UI.
- `frontend/admin/documents.php`: Admin document management UI.
- `frontend/admin/announcements.php`: Admin announcements UI.
- `frontend/field_worker/attendance.js`: Field worker attendance logic (check-in/out).
- `frontend/field_worker/profile.js`: Field worker profile management.
- `frontend/field_worker/documents.js`: Field worker document upload.

---

## How to Extend or Edit Features
- To add a new feature, create a new API endpoint, controller method, and model logic as needed.
- Update frontend JS and PHP pages to interact with new backend logic.
- Follow existing patterns for validation, error handling, and UI updates.

---

## Tips for Developers
- Always validate input on both frontend and backend.
- Use services for reusable logic.
- Keep controllers focused on business rules.
- Use models for all DB access.
- Test API endpoints with tools like Postman.

---

For further details, refer to inline comments in each file and the README.md for setup and system overview.
