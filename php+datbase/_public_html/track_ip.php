<?php
// Start the session
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "srv1666.hstgr.io";
$username = "u650100565_iplogger";
$password = "Vision@20245";
$dbname = "u650100565_iplogsdata";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection and display error if any
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the visitor's IP address
$visitor_ip = $_SERVER['REMOTE_ADDR'];

// Check if IP is already in the database
$sql = "SELECT visit_count FROM visitors WHERE ip_address = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $visitor_ip);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    if ($result->num_rows > 0) {
        // IP exists, update visit count
        $row = $result->fetch_assoc();
        $new_count = $row['visit_count'] + 1;
        $update_sql = "UPDATE visitors SET visit_count = ?, last_visited = CURRENT_TIMESTAMP WHERE ip_address = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("is", $new_count, $visitor_ip);
        if (!$update_stmt->execute()) {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        // IP doesn't exist, insert new record
        $insert_sql = "INSERT INTO visitors (ip_address, visit_count) VALUES (?, 1)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("s", $visitor_ip);
        if (!$insert_stmt->execute()) {
            echo "Error inserting record: " . $conn->error;
        }
    }
} else {
    echo "Error retrieving record: " . $conn->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
