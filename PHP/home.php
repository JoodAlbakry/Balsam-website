<?php
// بدء الجلسة
session_start();

// تحميل بيانات بنية الموقع من ملف XML
$xml = simplexml_load_file('website_structure_blsm.xml');
if (!$xml) {
    die('Error: Unable to load XML file.');
}

// التحقق من بيانات المستخدم المخزنة في الجلسة
$user = $_SESSION['user'] ?? null;

// حساب صلاحية إضافة المنتج وحالة المستخدم
$canAddProduct = $user && isset($user['stateVal']) && $user['stateVal'] == 1;
$status = $user['status'] ?? null;

// (اختياري) تخزين القيم في الجلسة لاستخدامها لاحقاً
$_SESSION['canAddProduct'] = $canAddProduct;
$_SESSION['status'] = $status;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $xml->webpages->header->title; ?> - Home</title>
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
    </header>

    <div class="containers">
        <div class="cards">
            <a href="product.php">
                <img src="img/(2).png" alt="Product Image" style="width:500px;height:500px;">
                <h2 class="card-title">Product</h2>
                <p>Explore our range of products</p>
            </a>
        </div>

        <?php if ($canAddProduct): ?>
    <div class="cards">
        <a href="add_product.php"> <!-- Link to the Add Product page -->
            <img src="img/Add_product.png" alt="Add Product Image" style="width:500px;height:500px;">
            <h2 class="card-title">Add Product</h2>
            <p>Add a new product to your catalog</p>
        </a>
    </div>
    <?php endif; ?>

    <div class="cards">
            <a href="chat.php">
                <img src="img/(4).png" alt="Ask Us Image" style="width:500px;height:500px;">
                <h2 class="card-title">Technical Support</h2>
                <p>Get help with techmical issues</p>
            </a>
        </div>

    <footer>
        <p><?php echo $xml->webpages->footer->text; ?></p> <!-- Display footer text from XML -->
    </footer>
</body>

</html>
