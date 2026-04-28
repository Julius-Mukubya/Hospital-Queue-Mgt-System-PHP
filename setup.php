<?php
// Database connection without selecting a database
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect without database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS medical_system");
    echo "Database 'medical_system' created successfully!<br>";
    
    // Use the database
    $pdo->exec("USE medical_system");
    
    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'receptionist', 'doctor') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'users' created successfully!<br>";
    
    // Create patients table
    $pdo->exec("CREATE TABLE IF NOT EXISTS patients (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        age INT NOT NULL,
        gender ENUM('Male', 'Female', 'Other') NOT NULL,
        contact VARCHAR(20) NOT NULL,
        symptoms TEXT NOT NULL,
        visit_type ENUM('walk-in', 'appointment') DEFAULT 'walk-in',
        status ENUM('waiting', 'in-consultation', 'completed') DEFAULT 'waiting',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'patients' created successfully!<br>";
    
    // Insert default users (password: password123)
    $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
    
    // Delete existing users first
    $pdo->exec("DELETE FROM users WHERE username IN ('admin', 'receptionist', 'doctor')");
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute(['admin', $hashedPassword, 'admin']);
    $stmt->execute(['receptionist', $hashedPassword, 'receptionist']);
    $stmt->execute(['doctor', $hashedPassword, 'doctor']);
    echo "Default users created successfully!<br>";
    
    echo "<br><strong>Setup completed successfully!</strong><br>";
    echo "<a href='index.php'>Go to Login Page</a>";
    
} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}
?>
