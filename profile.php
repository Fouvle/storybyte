<?php
require 'config.php';

header('Content-Type: application/json');

session_start();

// Replace with actual user authentication logic.
$user_id = $_SESSION['user_id'] ?? $user['id']; // Replace `1` with the logged-in user's ID.

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch user profile details
    $query = "SELECT  followers FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode([
            'followers' => $user['followers']
        ]);
    } else {
        echo json_encode(['error' => 'User not found.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
