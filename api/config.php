<?php

// Connect to the database
$servername = "localhost";
$server_port = 3308;
$dbname = "php_api";
$username = "root"; 
$password = ""; 

// Create a PDO instance
try {
    $conn = new PDO("mysql:host=$servername;port=$server_port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array("status" => false, "message" => "Database connection failed: " . $e->getMessage()));
    exit;
}

// Set headers to allow cross-origin resource sharing (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");