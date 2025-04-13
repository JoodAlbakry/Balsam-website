<?php
session_start();

// قراءة بيانات XML
$xml = simplexml_load_file('website_structure_blsm.xml');
if (!$xml) {
    die('Error: Unable to load XML file.');
}

// قراءة بيانات JSON
$saller_data = json_decode(file_get_contents('saller.json'), true);
$lessor_data = json_decode(file_get_contents('lessor.json'), true);
$donor_data = json_decode(file_get_contents('donor.json'), true);

// دمج جميع بيانات JSON في قائمة واحدة
$products = array_merge($saller_data ?: [], $lessor_data ?: [], $donor_data ?: []);

// تهيئة السلة إذا لم تكن موجودة
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// معالجة الطلبات لإضافة المنتجات إلى السلة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $rental_period = isset($_POST['rental_period']) ? (int)$_POST['rental_period'] : 1;

    // البحث عن المنتج في قائمة المنتجات
    foreach ($products as $product) {
        if ($product['productId'] == $product_id) {
            $product['productPrice'] *= $rental_period; // تحديث السعر بناءً على مدة التأجير
            $_SESSION['cart'][] = $product; // إضافة المنتج إلى السلة
            break;
        }
    }

    header("Location: shoppingCart.php");
    exit;
}

// معالجة الطلبات لحذف المنتج من السلة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    // البحث عن المنتج في السلة وإزالته
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['productId'] == $product_id) {
            unset($_SESSION['cart'][$key]); // إزالة المنتج من السلة
            break;
        }
    }

    // إعادة ترتيب السلة بعد الحذف
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    header("Location: shoppingCart.php");
    exit;
}

// معالجة الطلب لحذف جميع المنتجات من السلة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
    header("Location: shoppingCart.php");
    exit;
}

// حساب السعر الإجمالي
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['productPrice'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $xml->webpages->header->title; ?> - Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* Styles */
        body {
            font-family: 'Georgia', serif;
            font-size: 23px;
            background-color: #EEE9DA; 
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 150px 150px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            padding: 10px 0px 10px 20px;
            background-color: #6096B4;
        }

        .navbar h1 {
            color: #EEE9DA;
        }

        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .spicea {
            text-align: center;
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        h2 {
            text-align: center;
            color: #6096B4;
        }

        .container {
            background-color: #BDCDD6;
            padding: 50px 70px 50px 70px;
            border: 1px solid lightgrey;
            border-radius: 33px;
            margin-bottom: 100px;
        }

        .btn {
            background-color: #6096B4;
            color: #EEE9DA;
            padding: 17px;
            margin: 10px 0;
            border: none;
            width: 100%;
            border-radius: 3px;
            cursor: pointer;
            font-size: 22px;
        }

        .btn:hover {
            background-color: #265073;
        }

        a {
            color: #6096B4;
        }

        hr {
            border: 2px solid lightgrey;
            color: #6096B4;
        }

        span.price {
            float: right;
            color: #6096B4;
        }

        footer {
            text-align: center;
            padding: 10px;
            width: 100%;
            bottom: 0;
        }

        footer p {
            margin: 0;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .cart-item img {
            max-width: 150px;
            max-height: 150px;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <a href="home.php" class="logo">
            <img id="logo" src="img/Logo.png" alt="Balsam Logo" width="190" height="85">
        </a>
        <h1 class="spicea">BALSAM</h1>
    </header>

    <h2>SHOPPING CART</h2>

    <div class="container">
        <h3>Cart <span class="productPrice" style="color:black"><i class="fa fa-shopping-cart"></i> <b><?php echo count($_SESSION['cart']); ?></b></span></h3>
        <?php if (!empty($_SESSION['cart'])) { ?>
            <?php foreach ($_SESSION['cart'] as $item) { ?>
                <div class="cart-item">
                    <div>
                        <img src="<?php echo htmlspecialchars($item['productImage']); ?>" alt="Product Image">
                        <p><?php echo htmlspecialchars($item['productName']); ?></p>
                    </div>
                    <span class="price">SAR <?php echo htmlspecialchars($item['productPrice']); ?></span>
                    <!-- زر الحذف -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $item['productId']; ?>">
                        <button type="submit" name="remove_from_cart" class="btn" style="background-color: #265073;">Remove</button>
                    </form>
                </div>
            <?php } ?>
            <hr>
            <p><b>Total</b> <span class="productPrice" style="color:black"><b>SAR <?php echo $total_price; ?></b></span></p>
            <p>
                <a href="payment page.php">
                    <button class="btn">Go To Payment Page</button>
                </a>
            </p>
            <form method="POST">
                <button type="submit" name="clear_cart" class="btn" style="background-color: grey;">Clear All Products</button>
            </form>
        <?php } else { ?>
            <p>Your cart is empty.</p>
        <?php } ?>
    </div>

    <footer>
        <p><?php echo $xml->webpages->footer->text; ?></p>
    </footer>
</body>
</html>