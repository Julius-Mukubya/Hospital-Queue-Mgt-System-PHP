# Medical System - Hospital Queue Management System

A PHP-based medical system for managing patient queues with role-based access for Admin, Receptionist, and Doctor.

## Project Structure

```
backend/
├── config.php          # Database configuration (mysqli)
├── auth.php            # Authentication helpers
├── db_helper.php       # Database helper functions
├── logout.php          # Logout handler
├── setup.php           # Database setup script
└── database.sql        # Database schema

frontend/
├── index.php           # Login page
├── dashboard.php       # Main dashboard
├── assets/
│   └── css/
│       └── style.css   # Styles
└── pages/
    ├── admin_dashboard.php
    ├── admin_users.php
    ├── admin_reports.php
    ├── doctor_dashboard.php
    ├── doctor_patients.php
    ├── receptionist_dashboard.php
    ├── view_patients.php
    └── profile.php
```

## Setup Instructions

1. Place the project in your web server directory (e.g., `htdocs/hqms2`)

2. Run the setup script:
   ```
   http://localhost/hqms2/backend/setup.php
   ```

3. Access the application:
   ```
   http://localhost/hqms2/frontend/index.php
   ```

## Default Login Credentials

- **Admin**: username: `admin`, password: `password123`
- **Receptionist**: username: `receptionist`, password: `password123`
- **Doctor**: username: `doctor`, password: `password123`

## Features

### Receptionist
- Register walk-in patients
- View all patients
- Confirm patient presence
- Cancel patient visits

### Doctor
- View confirmed patients queue
- Start consultations
- Complete consultations
- View all patient records

### Admin
- Dashboard with statistics
- Manage users (add/delete)
- View reports and analytics
- Monitor system activity

### All Users
- Profile management
- Change password
- Logout with confirmation modal

## Technologies Used

- PHP (mysqli)
- MySQL
- HTML/CSS
- JavaScript
- Session-based authentication

## Database

- Database name: `medical_system`
- Tables: `users`, `patients`
- Connection: mysqli

## Security Features

- Password hashing (bcrypt)
- Prepared statements (SQL injection prevention)
- Session management
- Role-based access control
- Input sanitization
