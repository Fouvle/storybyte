<?php
// Enable error reporting for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include 'config.php';

$alert = '';
$content = '';
$draft_id = '';

// Check if draft_id is provided in URL
if (isset($_GET['id'])) {
    $draft_id = intval($_GET['id']);
    
    // If form was submitted (POST request)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_content = $_POST['content'];
        
        $query = "UPDATE drafts SET content = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $new_content, $draft_id);
        
        if ($stmt->execute()) {
            $alert = '<div class="alert alert-success">Draft updated successfully.</div>';
        } else {
            $alert = '<div class="alert alert-error">Failed to update draft.</div>';
        }
        $stmt->close();
    }
    
    // Load current draft content
    $query = "SELECT content FROM drafts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $draft_id);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $draft = $result->fetch_assoc();
            $content = htmlspecialchars($draft['content']);
        } else {
            $alert = '<div class="alert alert-error">Draft not found.</div>';
        }
    } else {
        $alert = '<div class="alert alert-error">Failed to fetch draft.</div>';
    }
    $stmt->close();
} else {
    $alert = '<div class="alert alert-error">Draft ID is required.</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Draft - StoryByte</title>
    <style>
        :root {
            --color-1: #C5C8A6;
            --color-2: #DBDCA5;
            --color-3: #F6EAC2;
            --color-4: #FCF6D7;
            --text-color: #333;
            --button-color: #333;
            --button-hover-color: #555;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: linear-gradient(var(--color-1), var(--color-2), var(--color-3), var(--color-4));
            color: var(--text-color);
        }

        .header {
            background-color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .logo {
            font-size: 1.5em;
            font-weight: bold;
            text-decoration: none;
            color: var(--text-color);
        }

        .profile-button {
            padding: 8px 16px;
            background-color: var(--button-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .profile-button:hover {
            background-color: var(--button-hover-color);
        }

        .main-content {
            padding-top: 80px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 600px;
            margin: 20px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2em;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        textarea, input {
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            padding: 10px;
            font-size: 1em;
            background-color: var(--button-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--button-hover-color);
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            color: white;
        }

        .alert-success {
            background-color: #4CAF50;
        }

        .alert-error {
            background-color: #FF6B6B;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="/" class="logo">StoryByte</a>
        <a href="profile.html" class="profile-button">Profile</a>
    </header>

    <main class="main-content">
        <div class="form-container">
            <h1>Edit Draft</h1>

            <?php echo $alert; ?>

            <form method="POST">
                <input type="hidden" name="draft_id" value="<?php echo htmlspecialchars($draft_id); ?>">
                <textarea name="content" rows="10" placeholder="Write your story here..." required><?php echo $content; ?></textarea>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </main>
</body>
</html>