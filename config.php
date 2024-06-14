<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pc_designer";

// Skapa anslutning
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrollera anslutning
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
