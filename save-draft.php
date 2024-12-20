<?php
// Database connection
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $story_content = $_POST['content'] ?? '';
    $title = $_POST['title'] ?? null;
    $user_id = $_POST['user_id'] ?? null;
    $story_id = $_POST['story_id'] ?? null; // For updating an existing draft

    if (empty($story_content) || empty($user_id)) {
        echo json_encode(['error' => 'Story content and user ID are required.']);
        exit;
    }

    if ($story_id) {
        // Update existing draft
        $stmt = $pdo->prepare("
            UPDATE stories 
            SET content = :content, title = :title, updated_at = NOW() 
            WHERE id = :story_id AND user_id = :user_id AND status = 'draft'
        ");
        $success = $stmt->execute([
            'content' => $story_content,
            'title' => $title,
            'story_id' => $story_id,
            'user_id' => $user_id
        ]);
    } else {
        // Insert new draft
        $stmt = $pdo->prepare("
            INSERT INTO stories (user_id, title, content, status, created_at, updated_at) 
            VALUES (:user_id, :title, :content, 'draft', NOW(), NOW())
        ");
        $success = $stmt->execute([
            'user_id' => $user_id,
            'title' => $title,
            'content' => $story_content
        ]);
    }

    if ($success) {
        echo json_encode(['success' => 'Draft saved successfully.']);
    } else {
        echo json_encode(['error' => 'Failed to save draft.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
