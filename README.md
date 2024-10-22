# Project Documentation: IP Tracking and Visitor Analytics System

## Overview

This project involves setting up an IP tracking and visitor analytics system for a website hosted on Hostinger. The main functionalities include logging visitors' IP addresses, visit counts, and the last time they visited the website. The collected data is stored in a MySQL database and displayed on a custom PHP page for analysis. Additionally, the project ensures that `.html` pages are correctly redirected to `.php` to allow for dynamic processing.

### Features Implemented

- **Track Visitor IP Addresses**: Track and log visitors' IP addresses, visit counts, and last visit times.
- **Display Visitor Data**: Provide a PHP page (`visitor_data.php`) to view the logged visitor information along with a "Copy IP" feature.
- **URL Rewriting and Page Redirection**: Redirect `.html` requests to `.php` files using `.htaccess` to allow proper execution of PHP code.

## Steps Implemented

### 1. Database Setup

- Created a new MySQL database (`u650100565_iplogsdata`) and a user (`u650100565_iplogger`) via Hostinger's control panel.
- Set up a `visitors` table to store IP address, visit count, and last visited time using the following SQL script:
  ```sql
  CREATE TABLE IF NOT EXISTS visitors (
      ip_address VARCHAR(45) PRIMARY KEY,
      visit_count INT NOT NULL DEFAULT 1,
      last_visited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  );
  ```

### 2. PHP Code for IP Tracking

- Created a `track_ip.php` script to track visitor IP addresses and store the data in the MySQL database.
- **PHP Code**: Here is the key PHP code used for tracking and updating visitor information:
  ```php
  <?php
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
  $sql = "SELECT visit_count, last_visited FROM visitors WHERE ip_address = ?";
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
          $update_stmt->execute();
      } else {
          // IP doesn't exist, insert new record
          $insert_sql = "INSERT INTO visitors (ip_address, visit_count) VALUES (?, 1)";
          $insert_stmt = $conn->prepare($insert_sql);
          $insert_stmt->bind_param("s", $visitor_ip);
          $insert_stmt->execute();
      }
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
  ?>
  ```
- Included this tracking code in all relevant pages by adding the line `<?php include 'track_ip.php'; ?>` at the beginning of each page.

### 3. Display Visitor Data Page

- Created a new PHP page (`visitor_data.php`) to display all visitor data stored in the MySQL database.
- The page includes a "Copy IP" button for each entry to easily copy the IP address using JavaScript.
- **PHP Code** for `visitor_data.php`:
  ```php
  <?php
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
  ?>

  <script>
  function copyToClipboard(text) {
      navigator.clipboard.writeText(text).then(function() {
          alert('Copied: ' + text);
      }, function(err) {
          alert('Failed to copy: ', err);
      });
  }
  </script>
  ```

### 4. `.htaccess` Configuration

- Updated the `.htaccess` file to handle both `.html` and `.php` files to allow proper redirection and dynamic PHP processing.
- **Updated ****`.htaccess`**** code**:
  ```apache
  RewriteEngine On
  RewriteBase /

  # Redirect index.html to index.php if it exists
  RewriteRule ^index\.html$ index.php [L]

  # If a file or directory does not exist, rewrite to index.php
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.php [L]
  ```

## How to Deploy

1. **Upload PHP Files**: Ensure all `.php` files (e.g., `index.php`, `visitor_data.php`, `track_ip.php`) are uploaded to the server.
2. **Update ****`.htaccess`**: Replace the existing `.htaccess` file with the updated one to handle `.html` and `.php` redirects.
3. **Database Setup**: Use phpMyAdmin to create the `visitors` table in the `u650100565_iplogsdata` database using the provided SQL script.

## Future Improvements

- **Authentication for Visitor Data Page**: Add basic authentication to restrict access to the visitor data page (`visitor_data.php`).
- **Data Visualization**: Integrate charts to visualize visitor trends over time.
- **Email Notifications**: Send an email alert when a specific IP address exceeds a certain number of visits.

This documentation should help you understand the work done so far and make it easy to push the changes to GitHub. Let me know if you need anything else or further improvements!

this code done by using ai

