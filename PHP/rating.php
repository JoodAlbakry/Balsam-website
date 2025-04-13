<?php
// Database connection
require 'conct.php'; 

// Check if the user is logged in
if (isset($_SESSION['user']["username"])) {
    $currentUser = $_SESSION['user']["username"];
} else {
    // Redirect to login if not logged in
    header('Location: login.php', true, 302);
    exit();
}

// Retrieve the productID (for example, passed as a GET parameter)
$productID = isset($_GET['productId']) ? $_GET['productId'] : 0;

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = isset($_POST['productRating']) ? (int)$_POST['productRating'] : 0; // Ensure rating is an integer
    $comment = htmlspecialchars($_POST['comment']);
    $reviewDate = date('Y-m-d H:i:s');
    
    // Validate productID
    if (empty($productID) || !is_numeric($productID)) {
        die("Invalid productID.");
    }

    // Prepare the SQL statement for inserting the review
    $stmt = $conn->prepare("INSERT INTO reviews (productID, reviewDate, productRating, comment, username) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiss", $productID, $reviewDate, $rating, $comment, $currentUser);

    // Execute the query and close the statement
    if ($stmt->execute()) {
        echo "Review added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Retrieve all reviews for the current product
$stmt = $conn->prepare("SELECT * FROM reviews WHERE productID = ? ORDER BY reviewDate DESC");
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();
$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}
$stmt->close();
$conn->close();

// Load the website structure data from the 'website_structure.xml' file
$xml = simplexml_load_file('website_structure_blsm.xml');
if (!$xml) {
    die('Error: Unable to load XML file.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Reviews</title>
    <link rel="stylesheet" type="text/css" href="css/reveiw.css">
    <style>
        .reviews {
            height: 300px;
            border: 1px solid #ccc;
            padding: 10px;
            background: #fff;
            margin-bottom: 10px;
            
        }
        .review {
            margin-bottom: 10px;
            color: black;
        }
        .username {
            font-weight: bold;
            color: #007BFF;
        }
        .timestamp {
            font-size: 0.8em;
            color: #999;
        }
        .input-group > span:hover ~ span{
            display: flex;
            gap: 10px;
        }

        input[type="text"], textarea {
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
        .star {
            font-size: 24px;
            cursor: pointer;
        }
        .filled {
            color: gold;
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

<div class="main-container">
    <div class="comments-container">

        <h2>Comments</h2>
        <form method="POST">
            <div class="input-group">
                <textarea style="width: auto;" name="comment" placeholder="Write a comment..." required></textarea>
            </div>
            <div class="input-group">
                <div class="rating" id="rating-stars">
                    <span class="star" data-value="5">&#9734;</span>
                    <span class="star" data-value="4">&#9734;</span>
                    <span class="star" data-value="3">&#9734;</span>
                    <span class="star" data-value="2">&#9734;</span>
                    <span class="star" data-value="1">&#9734;</span>
                </div>
                <input type="hidden" name="productRating" id="productRating" value="0">
                <button id="back-button" type="submit">Submit</button>
            </div>
        </form>
    </div>

    <div class="comments-container">
        <h2>Recent Reviews</h2>
        <div class="reviews">
            <?php foreach ($reviews as $review): ?>
                <div class="review">
                    <span class="username"><?php echo htmlspecialchars($review['username']); ?>:</span>
                    <p style="color: black;"><?php echo htmlspecialchars($review['comment']); ?></p>
                    <p style="color: gold;">Rating: <?php echo $review['productRating']; ?>/5</p>
                    <div class="timestamp"><?php echo date('H:i:s', strtotime($review['reviewDate'])); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('productRating');

    stars.forEach((star, index) => {
        star.addEventListener("click", () => {
            const rating = 5 - index; // Get rating value based on the clicked star

            // Set the value to the hidden input
            ratingInput.value = rating;

            // Update star styles to reflect the selected rating
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.textContent = "★"; // Fill the star
                    s.classList.add('filled');
                } else {
                    s.textContent = "☆"; // Empty star
                    s.classList.remove('filled');
                }
            });

            // Optional: Show an alert or feedback message to the user
            alert("You rated " + rating + " out of 5.");
        });
    });
});
</script>

</body>
</html>
