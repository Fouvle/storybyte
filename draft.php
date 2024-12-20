<?php
include 'config.php';
session_start();



class StoryAPI {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    public function saveDraft() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            return json_encode(['error' => 'User not authenticated']);
        }
    
        $content = $_POST['content'] ?? '';
        $word_count = str_word_count($content);
    
        if ($word_count > 300) {
            http_response_code(400);
            return json_encode(['error' => 'Story exceeds 300 words']);
        }
    
        // Generate timestamps
        $user_id = $_SESSION['user_id'];
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
    
        // Insert draft into the database
        $stmt = $this->conn->prepare("
            INSERT INTO drafts (content, created_at, updated_at, user_id, word_count) 
            VALUES (?, ?, ?, ?, ?)
        ");
    
        $stmt->bind_param("sssii", $content, $created_at, $updated_at, $user_id, $word_count);
    
        if ($stmt->execute()) {
            $draft_id = $this->conn->insert_id; // Get auto-incremented ID
    
            // Update user's draft_id if needed
            $updateUser = $this->conn->prepare("
                UPDATE users 
                SET draft_id = ? 
                WHERE id = ?
            ");
            $updateUser->bind_param("ii", $draft_id, $user_id);
            $updateUser->execute();
    
            return json_encode([
                'success' => true,
                'message' => 'Draft saved successfully',
                'draft_id' => $draft_id
            ]);
        } else {
            http_response_code(500);
            return json_encode(['error' => 'Failed to save draft']);
        }
    }

    // public function saveDraft() {
    //     if (!isset($_SESSION['user_id'])) {
    //         http_response_code(401);
    //         return json_encode(['error' => 'User not authenticated']);
    //     }
    
    //     $draftId = $_POST['draft_id'] ?? '';
    //     $content = $_POST['content'] ?? '';
        
    //     // Update the draft in the database
    //     $stmt = $this->conn->prepare("
    //         UPDATE drafts 
    //         SET content = ?, updated_at = NOW() 
    //         WHERE id = ? AND user_id = ?
    //     ");
    //     $stmt->bind_param("sii", $content, $draftId, $_SESSION['user_id']);
        
    //     if ($stmt->execute()) {
    //         return json_encode(['success' => true, 'message' => 'Draft updated successfully']);
    //     } else {
    //         http_response_code(500);
    //         return json_encode(['error' => 'Failed to update draft']);
    //     }
    // }
    

    // public function getDrafts() {
    //     if (!isset($_SESSION['user_id'])) {
    //         http_response_code(401);
    //         return json_encode(['error' => 'User not authenticated']);
    //     }
        
    //     $stmt = $this->conn->prepare("
    //         SELECT id, content, word_count, created_at, updated_at 
    //         FROM drafts 
    //         WHERE user_id = ? 
    //         ORDER BY updated_at DESC
    //     ");
        
    //     $user_id = $_SESSION['user_id'];
    //     $stmt->bind_param("i", $user_id);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
        
    //     $drafts = [];
    //     while ($row = $result->fetch_assoc()) {
    //         $drafts[] = $row;
    //     }
        
    //     return json_encode($drafts);
    // }

    public function getDrafts() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'User not authenticated']);
            return;
        }
    
        $stmt = $this->conn->prepare("
            SELECT id, content, word_count, created_at, updated_at 
            FROM drafts 
            WHERE user_id = ? 
            ORDER BY updated_at DESC
        ");
        $user_id = $_SESSION['user_id'];
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $drafts = [];
        while ($row = $result->fetch_assoc()) {
            $drafts[] = $row;
        }
    
        http_response_code(200);
        echo json_encode($drafts);
    }
    
    
    public function getPublishedStories() {
        $stmt = $this->conn->prepare("
            SELECT s.id, s.title, s.content, s.likes, s.comments,
                   s.author_name, s.created_at, s.published_date,
                   g.name as genre_name
            FROM stories s 
            LEFT JOIN genres g ON s.genre_id = g.id 
            WHERE s.is_published = 1 
            ORDER BY s.published_date DESC
        ");
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stories = [];
        while ($row = $result->fetch_assoc()) {
            $stories[] = $row;
        }
        
        return json_encode($stories);
    }
    
    public function getGenres() {
        $stmt = $this->conn->prepare("
            SELECT id, name 
            FROM genres 
            ORDER BY name
        ");
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $genres = [];
        while ($row = $result->fetch_assoc()) {
            $genres[] = $row;
        }
        
        return json_encode($genres);
    }
    
    public function getDraftById() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'User not authenticated']);
            return;
        }
    
        $draftId = isset($_GET['id']) ? intval($_GET['id']) : null;
        if (!$draftId) {
            http_response_code(400);
            echo json_encode(['error' => 'Draft ID is required']);
            return;
        }
    
        // Prepare and execute query to get the draft by ID
        $stmt = $this->conn->prepare("
            SELECT id, content 
            FROM drafts 
            WHERE id = ? AND user_id = ?
        ");
        $stmt->bind_param("ii", $draftId, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $draft = $result->fetch_assoc();
            echo json_encode($draft);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Draft not found']);
        }
    }

    // Method to delete a draft
    public function deleteDraft() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'User not authenticated']);
            return;
        }

        $draftId = isset($_GET['id']) ? intval($_GET['id']) : null;
        if (!$draftId) {
            http_response_code(400);
            echo json_encode(['error' => 'Draft ID is required']);
            return;
        }

        // Prepare and execute the query to delete the draft by ID
        $stmt = $this->conn->prepare("
            DELETE FROM drafts 
            WHERE id = ? AND user_id = ?
        ");
        $stmt->bind_param("ii", $draftId, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Draft deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete draft']);
        }
    }

}



// Handle API requests
$api = new StoryAPI($conn);
header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');
switch ($action) {
    case 'save_draft':
        echo $api->saveDraft();
        break;
    case 'publish':
        echo $api->publishStory();
        break;
    case 'get_drafts':
        echo $api->getDrafts();
        break;
    case 'get_published':
        echo $api->getPublishedStories();
        break;
    case 'get_genres':
        echo $api->getGenres();
        break;
    case 'edit_story':
        // Ensure the ID is provided
        $storyId = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($storyId) {
            echo $api->getStoryById($storyId);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Story ID is required']);
        }
        break;
    case 'delete_draft':
        echo $api->deleteDraft();
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
}
?>