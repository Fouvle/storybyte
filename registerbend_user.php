<?php
// Include database connection
include 'config.php';

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'] ;
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role ='user';
    
    // Sanitize and validate inputs
    if (empty($full_name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid email address."]);
        exit();
    }

    if ($password !== $confirm_password) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
        exit();
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(["status" => "error", "message" => "Username or email already exists."]);
        exit();
    }

    // Insert the new user into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users ( username, email, password) VALUES ( ?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["status" => "success", "message" => "User added successfully."]);
        header("location:user-login.html");
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to add user."]);
    }

    $stmt->close();
    $conn->close();
}
?>
