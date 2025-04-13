<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

$user = $_SESSION['user'] ?? null;
if (!$user) {
    echo "User not logged in.";
    exit();
}

$canAddProduct = $user && isset($user['stateVal']) && $user['stateVal'] == 1;
$status = $user['status'] ?? null;

if (!$canAddProduct) {
    echo "You do not have permission to add products.";
    exit();
}

$user_type = $status;

$is_active = true; 

if (!$is_active) {
    $status_message = "Sorry, your status has not been verified.";
    echo $status_message;
    exit();
}

if ($user_type !== 'Seller' && $user_type !== 'Vendors' && $user_type !== 'Donor') {
    echo "Invalid user type.";
    exit();
}

if (isset($_POST['product_name']) && isset($_POST['product_description']) && isset($_POST['product_image'])) {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_image = $_POST['product_image'];

    // إذا كان المستخدم 'seller' أو 'renter'، فيجب طلب إدخال السعر
    $product_price = '';
    if ($user_type == 'Seller' || $user_type == 'Vendors') {
        $product_price = $_POST['product_price'];
    } else {
        $product_price = 0; // لا حاجة للسعر في حالة 'donor'
    }

    // تحديد الملف المناسب لتخزين البيانات بناءً على نوع المستخدم
    $json_file = '';
    if ($user_type == 'Seller') {
        $json_file = 'saller.json';
    } elseif ($user_type == 'Vendors') {
        $json_file = 'lessor.json';
    } elseif ($user_type == 'Donor') {
        $json_file = 'donor.json';
    }

    // التأكد من إمكانية الكتابة على الملف
    if (!is_writable($json_file)) {
        echo "Error: The file is not writable.";
        exit();
    }

    // تحميل محتويات ملف JSON وتحويلها إلى مصفوفة
    $json_data = file_get_contents($json_file);
    $data = json_decode($json_data, true);
    if (!isset($data) || empty($data)) {
        $data = [];
    }

    // توليد معرف جديد للمنتج
    $new_productId = count($data);

    // إنشاء بيانات المنتج الجديد
    $new_product = [
        "productId" => $new_productId + 1,
        "productName" => $product_name,
        "productDescription" => $product_description,
        "productImage" => $product_image,
        "productPrice" => $product_price
    ];

    // إضافة المنتج إلى المصفوفة وحفظها في ملف JSON
    $data[] = $new_product;
    file_put_contents($json_file, json_encode($data, JSON_PRETTY_PRINT));

    echo "Product added successfully!";
    exit();
}

// تحميل بيانات بنية الموقع من ملف XML
$xml = simplexml_load_file('website_structure_blsm.xml');
if (!$xml) {
    die('Error: Unable to load XML file.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" type="text/css" href="css/styleReham.css">
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #EEE9DA;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            background-color: #6096B4;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #EEE9DA;
            font-size: 2.5em;
            margin: 0;
            padding: 0;
            font-weight: bold;
        }
        .container {
            width: 80%;
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 100px;
        }
        .form-group {
            background-color: #BDCDD6;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            width: 300px;
            padding: 20px;
            margin: 20px auto;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea {
            width: 60%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            display: block;
        }
        .form-group button {
            background-color: #265073;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 10px;
        }
        .form-group button:hover {
            background-color: #9AD0C2;
        }
        footer {
            background-color: #6096B4;
            text-align: center;
            padding: 10px;
            width: 100%;
            margin-top: 50px;
        }
        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <a href="<?php echo $xml->webpages->header->logo['link']; ?>" class="logo">
            <img id="logo" src="<?php echo $xml->webpages->header->logo['src']; ?>" 
                 alt="<?php echo $xml->webpages->header->logo['alt']; ?>">
        </a>
        <h1>Add a New Product</h1>
        <nav class="nav-icons">
            <?php foreach ($xml->webpages->header->nav->icon as $icon): ?>
                <a href="<?php echo $icon['link']; ?>">
                    <img src="<?php echo $icon['src']; ?>" alt="<?php echo $icon['alt']; ?>" style="width:40px;height:40px;">
                </a>
            <?php endforeach; ?>
        </nav>
    </header>

    <div class="container">
        <div class="form-group">
            <h2>Product Information</h2>
            <form id="productForm">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>

                <label for="product_description">Product Description:</label>
                <textarea id="product_description" name="product_description" required></textarea>

                <label for="product_image">Product Image:</label>
                <input type="text" id="product_image" name="product_image" required>

                <!-- عرض حقل السعر فقط للمستخدمين من نوع seller أو renter -->
                <?php if ($user_type == 'Seller' || $user_type == 'Vendors'): ?>
                    <label for="product_price">Product Price:</label>
                    <input type="number" id="product_price" name="product_price" required>
                <?php endif; ?>

                <button type="button" class="register-button" id="submitBtn">Add Product</button>
                <!-- زر الرجوع إلى الصفحة الرئيسية -->
                <button type="button" class="register-button" id="backBtn">Back to Home</button>
            </form>
        </div>
    </div>

    <script>
        // معالجة إرسال البيانات عبر AJAX عند الضغط على زر "Add Product"
        document.getElementById('submitBtn').addEventListener('click', function () {
            var productName = document.getElementById('product_name').value;
            var productDescription = document.getElementById('product_description').value;
            var productImage = document.getElementById('product_image').value;
            var productPrice = document.getElementById('product_price') ? document.getElementById('product_price').value : null;

            if (!productName || !productDescription || !productImage) {
                alert('Please fill all required fields.');
                return;
            }

            // التأكد من صحة السعر إذا كان مطلوباً للمستخدمين من نوع seller أو renter
            if ((<?php echo json_encode($user_type); ?> === 'Seller' || <?php echo json_encode($user_type); ?> === 'Vendors') && (!productPrice || isNaN(productPrice))) {
                alert('Please enter a valid product price.');
                return;
            }

            var formData = new FormData(document.getElementById('productForm'));

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true); // إرسال البيانات إلى نفس الصفحة
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                } else {
                    alert("Error: " + xhr.statusText);
                }
            };
            xhr.send(formData);
        });

        // زر الرجوع إلى الصفحة الرئيسية
        document.getElementById('backBtn').addEventListener('click', function () {
            window.location.href = "home.php";
        });
    </script>


</body>
</html>
