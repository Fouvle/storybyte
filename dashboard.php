<?php
// Include the database configuration file
include 'config.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}

// Function to fetch user stories
function getUserStories($userId) {
    global $conn;

    $query = "SELECT * FROM stories WHERE author_id = ? ORDER BY published_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    $stories = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $stories;
}

// Retrieve the logged-in user's data
$userId = $_SESSION['user']['id'];
$username = $_SESSION['user']['username'];

// Fetch the user's stories
$userStories = getUserStories($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - StoryByte</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 600px;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 30px;
            font-size: 1em;
            color: white;
            background-color: #333;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>Welcome back, <span id="username"><?php echo htmlspecialchars($username); ?></span>!</h1>
        <p>Your stories are waiting for you. What would you like to do today?</p>
        <div class="actions">
            <a href="main_landing.html" class="btn">Explore Stories</a>
            <a href="new-story.html" class="btn">Write a New Story</a>
            <a href="profile.html" class="btn">View Profile</a>
        </div>
        <h2>Your Stories</h2>
        <?php if (!empty($userStories)) { ?>
        <ul id="stories-list">
            <?php foreach ($userStories as $story) { ?>
                <li>
                    <?php echo htmlspecialchars($story['title']); ?> 
                    (Published on: 
                    <?php echo date('m/d/Y', strtotime($story['published_date'])); ?>)
                </li>
            <?php } ?>
        </ul>
        <?php } else { ?>
            <p>You have no stories yet.</p>
        <?php } ?>
    </div>
</body>
</html>
