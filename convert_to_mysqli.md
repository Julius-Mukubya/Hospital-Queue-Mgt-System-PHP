# Conversion Guide: PDO to MySQLi

All page files have been updated to use mysqli instead of PDO.

## Key Changes:
- `$pdo` → `$conn`
- `$pdo->prepare()` → `$conn->prepare()`
- `$stmt->execute([params])` → `$stmt->bind_param()` then `$stmt->execute()`
- `$stmt->fetch()` → `$result->fetch_assoc()`
- `$stmt->fetchAll()` → `$result->fetch_all(MYSQLI_ASSOC)`
- `$pdo->query()` → `$conn->query()`

## File Structure:
```
backend/
  - config.php (mysqli connection)
  - auth.php (authentication helpers)
  - db_helper.php (database helpers)
  - logout.php
  - setup.php
  - database.sql

frontend/
  - index.php (login page)
  - dashboard.php (main dashboard)
  - assets/
    - css/
      - style.css
  - pages/
    - admin_dashboard.php
    - admin_users.php
    - admin_reports.php
    - doctor_dashboard.php
    - doctor_patients.php
    - receptionist_dashboard.php
    - view_patients.php
    - profile.php
```
