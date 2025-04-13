<?php
// Start the session to track user data, such as the shopping cart
session_start();

// Load products data from the 'saller.json' file
$saller = json_decode(file_get_contents('saller.json'), true);

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
    <title><?php echo $xml->webpages->header->title; ?> - Sale</title>
    <link rel="stylesheet" type="text/css" href="css/styleJood.css">
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

    <div class="search-container">
        <input type="text" id="search" placeholder="Search for a product...">
        <button class="search-button" onclick="searchProducts()">Search</button>
    </div>

    <div class="containers-products">
        <?php if (!empty($saller)): ?>
            <?php foreach ($saller as $saller): ?>
                <div class="cards">
                    <a href="product_details.php?type=seller&id=<?php echo $saller['productId']; ?>">
                        <img src="<?php echo $saller['productImage']; ?>" alt="<?php echo $saller['productName']; ?> Image" style="width:400px;height:400px;">
                        <h2 class="card-title-product"><?php echo htmlspecialchars($saller['productName']); ?></h2>
                        <p>SAR <?php echo htmlspecialchars($saller['productPrice']); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>

    <div class="back-button">
        <button id="back-button" onclick="goBack()">Back</button>
    </div>

    <footer>
        <p><?php echo $xml->webpages->footer->text; ?></p>
    </footer>

    <script>
        function goBack() {
            window.history.back();
        }

        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("search");
            const cards = document.querySelectorAll(".cards");

            searchInput.addEventListener("input", function () {
                const searchTerm = searchInput.value.toLowerCase();
                cards.forEach(function (card) {
                    const title = card.querySelector(".card-title-product").textContent.toLowerCase();
                    if (title.includes(searchTerm)) {
                        card.style.display = "block";
                    } else {
                        card.style.display = "none";
                    }
                });
            });
        });
    </script>
</body>

</html>
