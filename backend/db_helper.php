<?php
require_once 'config.php';

// Helper function to fetch all results
function fetchAll($query) {
    global $conn;
    $result = $conn->query($query);
    if ($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}

// Helper function to fetch single result
function fetchOne($query) {
    global $conn;
    $result = $conn->query($query);
    if ($result) {
        return $result->fetch_assoc();
    }
    return null;
}

// Helper function to execute query
function executeQuery($query) {
    global $conn;
    return $conn->query($query);
}
