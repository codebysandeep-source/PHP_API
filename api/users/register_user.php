<?php

require_once "../config.php"; // Include config.php to initialize $conn

// Post - Register new user
function RegisterUser($conn, $data) {
    try {
        $stmt = $conn->prepare("CALL register_user(?, ?, ?)"); //Stored Procedure : register_user(fullname,username,password)
        $stmt->execute(
        [
            $data["fullname"],
            $data["username"],
            $data["password"]
        ]
        );
        return array("status" => true, "message" => "User created successfully");
    } 
    catch (PDOException $e) {
        return array("status" => false, "message" => $e->getMessage());
    }
}

// Handle HTTP requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {   
    try{
        $data = json_decode(file_get_contents("php://input"), true);
        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        $response = RegisterUser($conn, $data);
        http_response_code(200);    
        echo json_encode($response);
    }
    catch(PDOException $e) {  
        http_response_code(500);
        echo json_encode(array("status" => false, "message" => $e->getMessage()));
    }
} else {
    http_response_code(500);
    echo json_encode(array("status"=> false,"message"=> "Method not allowed"));
}