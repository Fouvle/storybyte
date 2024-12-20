<?php
include 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Initialize StoryAPI
$api = new StoryAPI($conn);

// Get published stories
$publishedStoriesJson = $api->getPublishedStories();
$publishedStories = json_decode($publishedStoriesJson, true);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Published Stories - StoryByte</title>
    <style>
        :root {
            --color-1: #C5C8A6; 
            --color-2: #DBDCA5; 
            --color-3: #F6EAC2; 
            --color-4: #FCF6D7; 
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(var(--color-1), var(--color-2), var(--color-3), var(--color-4));
            color: #333;
        }

        header {
            background-color: #333;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        .published {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 700px;
            margin: 50px auto;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            text-align: center;
        }

        .story-list {
            margin: 20px 0;
        }

        .story-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .story-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .story-title {
            font-size: 1.2em;
            margin-bottom: 5px;
        }

        .stats {
            font-size: 0.9em;
            color: #666;
        }

        .btn {
            padding: 8px 12px;
            font-size: 1em;
            color: white;
            background-color: #333;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin-left: 5px;
        }

        .btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <header>
        <a href="profile.php">Profile</a>
        <a href="drafts.php">Drafts</a>
        <a href="dashboard.php">Dashboard</a>
    </header>
    <div class="published">
        <h1>My Published Stories</h1>
        <div class="story-list">
            <?php if (empty($publishedStories)): ?>
                <p style="text-align: center;">You haven't published any stories yet.</p>
            <?php else: ?>
                <?php foreach ($publishedStories as $story): ?>
                    <div class="story-item" id="story-<?php echo htmlspecialchars($story['id']); ?>">
                        <div class="story-header">
                            <span class="story-title"><?php echo htmlspecialchars($story['title']); ?></span>
                            <div>
                                <a href="story.php?id=<?php echo htmlspecialchars($story['id']); ?>" class="btn">View</a>
                                <button class="btn" onclick="confirmDelete(<?php echo htmlspecialchars($story['id']); ?>, '<?php echo htmlspecialchars($story['title']); ?>')">Delete</button>
                            </div>
                        </div>
                        <p class="stats">
                            Likes: <?php echo htmlspecialchars($story['likes']); ?> | 
                            Comments: <?php echo htmlspecialchars($story['comments'] ? count(explode(',', $story['comments'])) : 0); ?> | 
                            Genre: <?php echo htmlspecialchars($story['genre_name'] ?? 'Uncategorized'); ?> |
                            Published: <?php echo date('M d, Y', strtotime($story['published_date'])); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        async function confirmDelete(storyId, title) {
            if (confirm(`Are you sure you want to delete "${title}"?`)) {
                try {
                    const response = await fetch('api.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=delete_story&story_id=${storyId}`
                    });

                    const result = await response.json();
                    
                    if (result.success) {
                        // Remove the story element from the DOM
                        document.getElementById(`story-${storyId}`).remove();
                        alert('Story deleted successfully!');
                    } else {
                        alert(result.error || 'Failed to delete story');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the story');
                }
            }
        }
    </script>
</body>
</html>
