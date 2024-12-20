<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

// Get the story ID from URL if it exists (for editing)
$storyId = isset($_GET['id']) ? intval($_GET['id']) : null;

// Pass these variables to JavaScript
$pageData = [
    'userId' => $_SESSION['user_id'],
    'storyId' => $storyId,
    'username' => $_SESSION['username'] ?? '',
];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Drafts - StoryByte</title>
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

        .drafts {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 700px;
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
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .story-title {
            font-size: 1.2em;
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

        .new-story-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 15px 30px;
            font-size: 1em;
            color: white;
            background-color: #333;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .new-story-link:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="/" class="logo">StoryByte</a>
        <a href="profile.html" class="profile-button">Profile</a>
    </header>
    
    <h1>Your Drafts</h1>
    <div class="story-list">
        <!-- Drafts will be dynamically inserted here -->
    </div>

   

    <script>

        function confirmDelete(draftId, draftTitle) {
            console.log(draftId);
            if (confirm(`Are you sure you want to delete the draft titled "${draftTitle}"?`)) {
                deleteDraft(draftId);
            }
        }

        function deleteDraft(draftId) {
            fetch(`draft.php?action=delete_draft&id=${draftId}`, {
                method: 'GET',  // Assuming you're passing the ID via GET, as per the PHP code
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Draft deleted successfully!");
                    // Optionally, remove the draft from the UI
                    document.getElementById(`draft-${draftId}`).remove(); // Assuming each draft has a unique ID like "draft-8"
                } else {
                    alert("Failed to delete draft.");
                }
            })
            .catch(error => console.error("Error deleting draft:", error));
        }




        function saveAsDraft() {
            fetch('draft.php?action=get_drafts', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (Array.isArray(data) && data.length > 0) {
                    // console.log("Drafts retrieved successfully:", data);
                    // alert(`Retrieved ${data.length} drafts successfully! Check the console for details.`);
                    
                    // Populate drafts dynamically in your .story-list container
                    const storyList = document.querySelector('.story-list');
                    storyList.innerHTML = ''; // Clear existing drafts
                    data.forEach(draft => {
                        const draftItem = document.createElement('div');
                        draftItem.classList.add('story-item');
                        draftItem.innerHTML = `
                            <span class="story-title">${draft.content.substring(0, 30)}...</span>
                            <div>
                                <a href="edit-story.php?id=${draft.id}" class="btn">Edit</a>
                                <a href="drafts.php" class="btn" onclick="confirmDelete('${draft.id}')">Delete</a>
                            </div>
                        `;
                        storyList.appendChild(draftItem);
                    });
                } else {
                    alert("No drafts found for the user.");
                }
            })
            .catch(error => {
                console.error('Error fetching drafts:', error);
                alert("Failed to retrieve drafts.");
            });
        }

        // Fetch drafts when the page loads
        document.addEventListener('DOMContentLoaded', saveAsDraft);
    </script>
</body>
</html>
