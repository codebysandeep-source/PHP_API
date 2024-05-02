<?php

require_once "config.php"; // Include config.php to initialize $conn

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


// GET - Login user
function LoginUser($conn, $username, $password) {
    // Prepare the SQL query to select the user with the given username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    
    // Execute the query
    $stmt->execute([$username]);
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if a user with the given username was found
    if ($result) {
        // Verify the password
        if (password_verify($password, $result["password"])) {
            // Return success response with token
            return array(
                "status" => true,
                "message" => "Login successful",
                "token" => generateToken($result["user_id"], $result["username"], "this_is_my_secret_key")
            );
        } else {
            // Password is incorrect
            return array(
                "status" => false,
                "message" => "Incorrect password",
                "token"=> ""
            );
        }
    } else {
        // User with the given username was not found
        return array(
            "status" => false,
            "message" => "User not found",
            "token"=> ""
        );
    }
}

// Function to generate token
function generateToken($user_id, $username, $key) {
    // Generate a unique token using user ID, username, and current time
    $token = base64_encode(uniqid($user_id . $username . time(), true));

    // Create a cryptographic hash of the token using HMAC SHA256 and the provided key
    $hash = hash_hmac('sha256', $token, $key);

    // Append the hash to the token to create a unique random token
    $random_token = $token . $hash;

    return $random_token;
}



// Handle HTTP requests
$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case "GET":
        try{
            $data = json_decode(file_get_contents("php://input"), true);
            $response = LoginUser($conn, $data["username"], $data["password"]);
            http_response_code(200);    
            echo json_encode($response);
        }
        catch(PDOException $e) {  
            http_response_code(500);
            echo json_encode(array("status" => false, "message" => $e->getMessage()));
        }
        break;
    case "POST":
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
        break;
    default:
        http_response_code(500);
        echo json_encode(array("status"=> false,"message"=> "Method not allowed"));
        break;
}