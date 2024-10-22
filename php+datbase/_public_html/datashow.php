<?php
// Start the session
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "srv1666.hstgr.io";
$username = "u650100565_sreeteqlogs";
$password = "Vision@20245";
$dbname = "u650100565_iplogs";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection and display error if any
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all visitor data
$sql = "SELECT ip_address, visit_count, last_visited FROM visitors";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<h2>Visitor Data</h2>";
    echo "<table border='1' cellpadding='10'><tr><th>IP Address</th><th>Visit Count</th><th>Last Visited</th><th>Copy IP</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['ip_address'] . "</td>";
        echo "<td>" . $row['visit_count'] . "</td>";
        echo "<td>" . $row['last_visited'] . "</td>";
        echo "<td><button onclick=\"copyToClipboard('" . $row['ip_address'] . "')\">Copy</button></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No visitor data found.";
}

// Close the connection
$conn->close();

// JavaScript function to copy IP to clipboard
echo "<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Copied: ' + text);
    }, function(err) {
        alert('Failed to copy: ', err);
    });
}
</script>";
?>
