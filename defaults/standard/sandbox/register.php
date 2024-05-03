<?php

// Database connection details (replace with your own)
$db_name = "users.db";
$db_user = ""; // Leave empty for SQLite
$db_pass = ""; // Leave empty for SQLite

try {
  // Connect to SQLite database
  $conn = new PDO("sqlite:$db_name");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Check if table exists, if not create it
  $sql = "CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE
  )";
  $conn->exec($sql);

  // Get form data
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash password
  $email = $_POST["email"];

  // Validate data (example, check for empty fields)
  if (empty($username) || empty($password) || empty($email)) {
    echo "Please fill in all fields.";
    exit;
  }

  // Insert data into database
  $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(":username", $username);
  $stmt->bindParam(":password", $password);
  $stmt->bindParam(":email", $email);
  $stmt->execute();

  echo "Registration successful!";

} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}

$conn = null; // Close connection

?>