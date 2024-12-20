<?php
// Database connection
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $story_id = $_POST['story_id'] ?? null;
    $user_id = $_POST['user_id'] ?? null;

    if (empty($story_id) || empty($user_id)) {
        echo json_encode(['error' => 'Story ID and user ID are required.']);
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE stories 
        SET status = 'published', published_at = NOW() 
        WHERE id = :story_id AND user_id = :user_id AND status = 'draft'
    ");
    $success = $stmt->execute([
        'story_id' => $story_id,
        'user_id' => $user_id
    ]);

    if ($success) {
        echo json_encode(['success' => 'Story published successfully.']);
    } else {
        echo json_encode(['error' => 'Failed to publish story.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
