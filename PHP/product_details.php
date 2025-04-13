<?php
session_start();

// قراءة نوع المنتج ومعرف المنتج
$product_type = isset($_GET['type']) ? $_GET['type'] : null;
$product_id = isset($_GET['id']) ? $_GET['id'] : null;

// تحديد ملف JSON بناءً على النوع
$json_file = '';
switch ($product_type) {
    case 'donor':
        $json_file = 'donor.json';
        break;
    case 'lessor':
        $json_file = 'lessor.json';
        break;
    case 'seller':
        $json_file = 'saller.json';
        break;
    default:
        header("Location: home.php");
        exit;
}

$is_donor = ($product_type === 'donor'); // Flag to check if the product is from donor.json

// قراءة بيانات المنتج
$products = json_decode(file_get_contents($json_file), true);
if (!$products) {
    die('Error: Unable to load product data.');
}

// البحث عن المنتج
$product = null;
foreach ($products as $p) {
    if ($p['productId'] == $product_id) {
        $product = $p;
        break;
    }
}

// التحقق من وجود المنتج
if (!$product) {
    header("Location: home.php");
    exit;
}

// إضافة المنتج للسلة
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // الحصول على المدة من المستخدم (في حالة تأجير)
    $rental_period = isset($_POST['rental_period']) ? (int)$_POST['rental_period'] : 1;
    $updated_price = isset($_POST['updated_price']) ? (float)$_POST['updated_price'] : $product['productPrice']; // السعر المحدث

    // تحديث السعر بناءً على المدة
    $product['productPrice'] = $updated_price; // تحديث السعر

    // التحقق من وجود المنتج بالسلة مسبقاً
    $exists = false;
    foreach ($_SESSION['cart'] as $item) {
        if ($item['productId'] == $product_id) {
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        $_SESSION['cart'][] = $product;
        header("Location: shoppingCart.php");
        exit;
    } else {
        echo "<script>alert('This product is already in the cart.');</script>";
    }
}

// قراءة ملف XML
$xml = simplexml_load_file('website_structure_blsm.xml');
if (!$xml) {
    die('Error: Unable to load XML file.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['productName']); ?> - Product Details</title>
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

    <div class="container">
        <div class="card-product">
            <div class="product-container">
                <div>
                    <img class="product-image" src="<?php echo htmlspecialchars($product['productImage']); ?>" alt="Product Image">
                </div>
                <h1 class="product-title"><?php echo htmlspecialchars($product['productName']); ?></h1><br>
                <p id="product-info">
                    <?php echo nl2br(htmlspecialchars($product['productDescription'])); ?>
                </p>

                <!-- Additional Details for Lessor -->
                <?php if ($product_type === 'lessor') { ?>
                    <div class="product-details">
                        <label for="rental-period">Choose Rental Period:</label>
                        <select id="rental-period" name="rental_period">
                            <option value="1">1 Week</option>
                            <option value="2">2 Weeks</option>
                            <option value="3">3 Weeks</option>
                            <option value="4">4 Weeks</option>
                        </select>
                    </div>
                <?php } ?>

                <!-- Add to Cart Section -->
                <form method="POST">
                    <?php if (!$is_donor): ?> 
                        <p>SAR <span id="final-price"><?php echo htmlspecialchars($product['productPrice']); ?></span></p>
                        <input type="hidden" id="updated-price" name="updated_price" value="<?php echo $product['productPrice']; ?>" />
                    <?php else: ?>
                        <p>This product is available for donation.</p>
                    <?php endif; ?>

                    <button type="submit" class="add-to-cart-button" style="background-color: #ADD8E6; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer;">
                        Add to Cart
                    </button>
                </form>


                <div>
                    <a href="rating.php?productId=<?php echo $product['productId']; ?>" style="margin-left: 1100px; color:#265073; font-size: 25px;">Review</a>
                </div>
            </div>
        </div>

        <div>
            <button id="back-button" onclick="goBack()">Back to Products</button>
        </div>
    </div>

    <footer>
        <div>
            <p><?php echo $xml->webpages->footer->text; ?></p>
        </div>
    </footer>

    <script>
        function goBack() {
            window.history.back();
        }

        function updatePrice() {
            <?php if (!$is_donor): ?> 
                var rentalPeriod = document.getElementById("rental-period").value;
                var basePrice = <?php echo $product['productPrice']; ?>; // Base price per week
                var updatedPrice = basePrice * rentalPeriod;
                document.getElementById("final-price").textContent = "" + updatedPrice; // Show updated price
                document.getElementById("updated-price").value = updatedPrice; // Update hidden field
            <?php endif; ?>
        }


    // Update price when the rental period changes
    document.getElementById("rental-period").addEventListener("change", updatePrice);
    </script>
</body>
</html>
