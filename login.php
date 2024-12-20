<?php
// Include the database configuration file
include 'config.php';

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize user input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Query to check if the user exists in the database
    $query = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user information in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profile_picture'] = $user['profile_picture'];
            $_SESSION['user'] = [
                'id' =>   $user['id'], // Replace with dynamic session data
                'username' => $user['username']
            ];

            // Store user information in the session
            // echo 'sesion - '.$_SESSION['user_id'];

            // Redirect user based on their role
            if ($user['role'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location:dashboard.php');
            }
        } else {
            // Invalid password
            $error = 'Invalid username or password!';
        }
    } else {
        // User not found
        $error = 'Invalid username or password!';
    }
}
?>

