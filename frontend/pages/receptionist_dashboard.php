<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_patient'])) {
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $gender = $conn->real_escape_string($_POST['gender'] ?? '');
    $contact = $conn->real_escape_string($_POST['contact'] ?? '');
    $symptoms = $conn->real_escape_string($_POST['symptoms'] ?? '');
    
    $stmt = $conn->prepare("INSERT INTO patients (name, age, gender, contact, symptoms, visit_type, status, created_at) VALUES (?, ?, ?, ?, ?, 'walk-in', 'waiting', NOW())");
    $stmt->bind_param("sisss", $name, $age, $gender, $contact, $symptoms);
    $stmt->execute();
    $stmt->close();
    $success = "Patient registered successfully!";
}

$result = $conn->query("SELECT * FROM patients WHERE DATE(created_at) = CURDATE() ORDER BY created_at DESC");
$patients = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="receptionist-section">
    <h2>Register Walk-In Patient</h2>
    
    <?php if (isset($success)): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <form method="POST" class="patient-form">
        <div class="form-group">
            <label>Patient Name:</label>
            <input type="text" name="name" required>
        </div>
        
        <div class="form-group">
            <label>Age:</label>
            <input type="number" name="age" required>
        </div>
        
        <div class="form-group">
            <label>Gender:</label>
            <select name="gender" required>
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Contact Number:</label>
            <input type="text" name="contact" required>
        </div>
        
        <div class="form-group">
            <label>Symptoms:</label>
            <textarea name="symptoms" rows="3" required></textarea>
        </div>
        
        <button type="submit" name="add_patient">Register Patient</button>
    </form>
</div>
