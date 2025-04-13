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
    <title><?php echo $xml->webpages->header->title; ?> - Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/styleJood.css">
    <style>
        body {
            font-family: 'Georgia', serif;
            font-size: 23px;
            padding: 8px;
            background-color: #EEE9DA;
            height: 10vh;
            display: flex;
            flex-direction: column;
            padding: 150px 150px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            padding: 10px 20px 10px 20px;
            background-color: #6096B4;
        }

        .navbar h1 {
            color: #EEE9DA;
        }

        .spicea {
            text-align: center;
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .container {
            background-color: #BDCDD6;
            padding: 5px 20px 15px 20px;
            border: 1px solid lightgrey;
            border-radius: 3px;
            margin-bottom: 100px;
            margin-top: 900px;
            margin-left: 100px;
        }

        input[type=text] {
            width: 95%;
            margin-bottom: 20px;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .btn {
            background-color: #6096B4;
            color: white;
            padding: 12px;
            margin: 10px 0;
            border: none;
            width: 100%;
            border-radius: 3px;
            cursor: pointer;
            font-size: 17px;
        }

        .warning-message {
            display: none;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 3px;
            text-align: center;
            font-size: 18px;
        }

        @media (max-width: 800px) {
            .row {
                flex-direction: column-reverse;
            }

            .col-25 {
                margin-bottom: 20px;
            }
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

    <h2>PAYMENT PAGE</h2>

    <div id="warning" class="warning-message">
        Service Coming Soon!
    </div>

    <div class="row">
        <div class="col-75">
            <div class="container">
                <form onsubmit="showWarning(event)">
                    <div class="row">
                        <div class="col-50">
                            <h3>Billing Address</h3>
                            <label for="fname"><i class="fa fa-user"></i> Full Name</label>
                            <input type="text" id="fname" name="firstname" placeholder="John M. Doe">
                            <label for="email"><i class="fa fa-envelope"></i> Email</label>
                            <input type="text" id="email" name="email" placeholder="john@example.com">
                            <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
                            <input type="text" id="adr" name="address" placeholder="542 W. 15th Street">
                            <label for="city"><i class="fa fa-institution"></i> City</label>
                            <input type="text" id="city" name="city" placeholder="New York">
                        </div>
                        <div class="col-50">
                            <h3>Payment</h3>
                            <label for="cname">Name on Card</label>
                            <input type="text" id="cname" name="cardname" placeholder="John More Doe">
                            <label for="ccnum">Credit card number</label>
                            <input type="text" id="ccnum" name="cardnumber" placeholder="1111-2222-3333-4444">
                            <label for="expmonth">Exp Month</label>
                            <input type="text" id="expmonth" name="expmonth" placeholder="September">
                        </div>
                    </div>
                    <input type="submit" value="Continue to checkout" class="btn">
                </form>
            </div>
        </div>
    </div>

    <script>
        function showWarning(event) {
            event.preventDefault(); // Prevent form submission
            const warning = document.getElementById("warning");
            warning.style.display = "block"; // Show the warning message
        }
    </script>

</body>

</html>
