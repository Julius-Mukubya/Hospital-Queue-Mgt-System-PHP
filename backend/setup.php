<?php
// Database connection without selecting a database
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect without database
    $conn = new mysqli($host, $username, $password);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Create database
    $conn->query("CREATE DATABASE IF NOT EXISTS medical_system");
    echo "Database 'medical_system' created successfully!<br>";
    
    // Select the database
    $conn->select_db("medical_system");
    
    // Create users table
    $conn->query("CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'receptionist', 'doctor') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'users' created successfully!<br>";
    
    // Create patients table
    $conn->query("CREATE TABLE IF NOT EXISTS patients (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        age INT NOT NULL,
        gender ENUM('Male', 'Female', 'Other') NOT NULL,
        contact VARCHAR(20) NOT NULL,
        symptoms TEXT NOT NULL,
        visit_type ENUM('walk-in', 'appointment') DEFAULT 'walk-in',
        status ENUM('waiting', 'confirmed', 'in-consultation', 'completed', 'cancelled') DEFAULT 'waiting',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'patients' created successfully!<br>";
    
    // Insert default users (password: password123)
    $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
    
    // Delete existing users first
    $conn->query("DELETE FROM users WHERE username IN ('admin', 'receptionist', 'doctor')");
    
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    
    $username = 'admin';
    $role = 'admin';
    $stmt->bind_param("sss", $username, $hashedPassword, $role);
    $stmt->execute();
    
    $username = 'receptionist';
    $role = 'receptionist';
    $stmt->bind_param("sss", $username, $hashedPassword, $role);
    $stmt->execute();
    
    $username = 'doctor';
    $role = 'doctor';
    $stmt->bind_param("sss", $username, $hashedPassword, $role);
    $stmt->execute();
    
    $stmt->close();
    echo "Default users created successfully!<br>";
    
    echo "<br><strong>Setup completed successfully!</strong><br>";
    echo "<a href='../frontend/index.php'>Go to Login Page</a>";
    
    $conn->close();
    
} catch (Exception $e) {
    die("Setup failed: " . $e->getMessage());
}
?>
