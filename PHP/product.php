<?php
// Start the session to track user data, such as the shopping cart
session_start();

// Load the website structure data from the 'website_structure.xml' file
$xml = simplexml_load_file('website_structure_blsm.xml');
if (!$xml) {
    die('Error: Unable to load XML file.');
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $xml->webpages->header->title; ?> - Pruducts</title>
    <link rel="stylesheet" type="text/css" href="css/styleReham.css">
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
    </header>>



    <div class="containers">
        <div class="cards">
            <a href="donation.php">
                <img src="img/p (2).png" alt="donation Image" style="width: 300px;height: 300px;">
                <h2 class="card-title">Donation</h2>
                <p>If you have medical devices that are no longer in use but can still benefit others, By donating your medical devices, you can help bridge the gap for healthcare providers in need and make a positive impact on patient care.</p>
            </a>
        </div>

        <div class="cards">
            <a href="sale.php">
                <img src="img/p (1).png" alt="sale Image" style="width: 300px;height: 300px;">
                <h2 class="card-title">Sale</h2>
                <p> Looking to sell your medical devices?  Whether you are an individual seller or a medical facility looking to offload surplus equipment, our platform provides visibility and accessibility to interested buyers.</p>
            </a>
        </div>

        <div class="cards">
            <a href="rent.php">
                <img src="img/p (3).png" alt="rent Image" style="width: 300px;height: 300px;">
                <h2 class="card-title">Rent</h2>
                <p>For those who prefer a flexible and cost-effective approach, our leasing section allows you to explore options for renting or leasing medical devices. This option is ideal for healthcare organizations seeking access to advanced equipment without the commitment of a full purchase.</p>
            </a>
        </div>

    </div>

    <footer>
        <p><?php echo $xml->webpages->footer->text; ?></p> <!-- Display footer text from XML -->
    </footer>
</body>

</html>
