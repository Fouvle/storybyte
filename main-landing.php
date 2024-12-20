<?php
include 'config.php';

session_start();

// Handle incoming request
$action = $_GET['action'] ?? null;


if ($action === 'fetch') {

    fetchStories($conn);
} elseif ($action === 'like') {

    likeStory($conn);
} elseif ($action === 'comment') {
    submitComment($conn);
}

function fetchStories($conn) {

    $genre = $_GET['genre_id'] ?? '';
    $order = $_GET['order'] ?? 'published_date DESC';


    // Whitelist for order to prevent SQL injection
    $allowedOrders = ['published_date ASC', 'published_date DESC', 'likes DESC'];
    if (!in_array($order, $allowedOrders)) {
        $order = 'published_date DESC';
    }


// this query needs to join genre so that you can retrieve the ac
    $query = "SELECT stories.*, genres.name AS genre_name
FROM stories
JOIN genres ON genres.id = stories.genre_id
WHERE genre_id = ?
ORDER BY ? ";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die(json_encode(['error' => 'Query preparation failed', 'message' => $conn->error]));
    }

    $stmt->bind_param('ss', $genre, $order);
    $stmt->execute();

    
    $result = $stmt->get_result();

    $stories = [];

    while ($row = $result->fetch_assoc()) {
        $stories[] = $row;

    }

    echo json_encode($stories);
}

function likeStory($conn) {
    $storyId = $_POST['storyId'];

    $query = "UPDATE stories SET likes = likes + 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $storyId);
    $stmt->execute();

    echo json_encode(['success' => true]);
}

function submitComment($conn) {
    $storyId = $_POST['storyId'];
    $userId = $_POST['userId']; // In a real app, fetch this from the session
    $comment = $_POST['comment'];

    $query = "INSERT INTO comments (story_id, user_id, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iis', $storyId, $userId, $comment);
    $stmt->execute();

    echo json_encode(['success' => true]);
}
?>
