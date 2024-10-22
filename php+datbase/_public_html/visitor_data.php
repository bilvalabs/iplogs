<?php
// Database connection parameters
$servername = "srv1666.hstgr.io";
$username = "u650100565_iplogsall";
$password = "Vision@20245";
$dbname = "u650100565_iplogs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch visitor data
$sql = "SELECT * FROM visitors";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Visitor Data</h2>";
    echo "<table border='1'><tr><th>IP Address</th><th>Visit Count</th><th>Last Visited</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['ip_address'] . "</td><td>" . $row['visit_count'] . "</td><td>" . $row['last_visited'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No visitor data found.";
}

$conn->close();
?>
