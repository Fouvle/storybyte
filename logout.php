<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Respond with a success message
header('Content-Type: application/json');
echo json_encode(['message' => 'Logout successful.']);
?>
