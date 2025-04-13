<?php
// Database connection
require 'conct.php'; 

// Check if the user is logged in
if (isset($_COOKIE['username'])) {
    $currentUser = $_COOKIE['username']; // Get username from the cookie
    $isAdmin = ($_SESSION['user']["username"] == 'Admin'); 
} else {
    // Redirect to login if not logged in
    header('Location: login.php', true, 302);
    exit();
}

// Handle message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the message is safely handled
    $message = htmlspecialchars($_POST['message']);
    
    // Prepare the statement
    if ($isAdmin) {
        // Admin message
        $stmt = $conn->prepare("INSERT INTO inquiries (username, problemDesciption) VALUES (?, ?)");
        $stmt->bind_param("ss", $adminUser, $message);
        $adminUser = 'Admin'; // Set the admin username
    } else {
        // User message
        $stmt = $conn->prepare("INSERT INTO inquiries (username, problemDesciption) VALUES (?, ?)");
        $stmt->bind_param("ss", $currentUser, $message);
    }

    $stmt->execute();
    $stmt->close();
}

// Retrieve all messages
$result = $conn->query("SELECT username, problemDesciption, created_at FROM inquiries ORDER BY created_at ASC");
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$conn->close();

// Load the website structure data from the 'website_structure.xml' file
$xml = simplexml_load_file('website_structure_blsm.xml');
if (!$xml) {
    die('Error: Unable to load XML file.');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AskUs Chat</title>
    <link rel="stylesheet" type="text/css" href="css/styleReham.css">
    <style>
        .chat-box {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background: #f9f9f9;
        }
        .messages {
            height: 300px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
            background: #fff;
            margin-bottom: 10px;
        }
        .message {
            margin-bottom: 10px;
        }
        .username {
            font-weight: bold;
            color: #007BFF;
        }
        .admin-message .username {
            color: #28a745; /* Green color for admin */
        }
        .timestamp {
            font-size: 0.8em;
            color: #999;
        }
        .input-group {
            display: flex;
            gap: 10px;
        }
        input[type="text"] {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 8px 12px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }

        footer {
            background-color: #6096B4;
            text-align: center;
            padding: 10px;
            width: 100%;
            bottom: 0;
            margin-top: 100px;
          }
    </style>
</head>
<body>

    <header class="navbar">
        <a href="<?php echo $xml->webpages->header->logo['link']; ?>" class="logo">
            <img id="logo" src="<?php echo $xml->webpages->header->logo['src']; ?>" 
                 alt="<?php echo $xml->webpages->header->logo['alt']; ?>">
        </a>
        <h1 class="spicea"><?php echo $xml->webpages->header->title; ?></h1>

        <nav class="nav-icons">
            <?php foreach ($xml->webpages->header->nav->icon as $icon): ?>
                <a href="<?php echo $icon['link']; ?>">
                    <img src="<?php echo $icon['src']; ?>" alt="<?php echo $icon['alt']; ?>" style="width:40px;height:40px;">
                </a>
            <?php endforeach; ?>
        </nav>
    </header>

    <div class="chat-box">
        <h2>AskUs Chat</h2>
        <div class="messages">
            <?php foreach ($messages as $msg): ?>
                <div class="message <?php echo ($msg['username'] == 'Admin') ? 'admin-message' : ''; ?>">
                    <span class="username"><?php echo htmlspecialchars($msg['username']); ?>:</span>
                    <span><?php echo htmlspecialchars($msg['problemDesciption']); ?></span>
                    <div class="timestamp"><?php echo date('H:i:s', strtotime($msg['created_at'])); ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Message Input Form -->
        <form method="POST">
            <div class="input-group">
                <input type="text" name="message" placeholder="Type a message" required>
                <button type="submit"><?php echo ($isAdmin) ? 'Respond as Admin' : 'Send'; ?></button>
            </div>
        </form>
    </div>

</body>
</html>
