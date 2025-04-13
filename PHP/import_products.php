<?php
require 'conct.php';

// Define file paths
$sellerFile = 'saller.json';
$donorFile = 'donor.json';
$lessorFile = 'lessor.json';

// Function to import products
function importProducts($filePath, $type, $conn) {
    $data = json_decode(file_get_contents($filePath), true);
    if (!$data) {
        die("Failed to read JSON file: $filePath");
    }

    foreach ($data as $product) {
        $id = $product['productId'];
        $name = $product['productName'];
        $description = isset($product['productDescription']) && !empty($product['productDescription']) ? $product['productDescription'] : 'No description available';
        $image = isset($product['productImage']) ? $product['productImage'] : null;
        $price = isset($product['productPrice']) ? $product['productPrice'] : null;
        $rentalPeriod = isset($product['period']) ? $product['period'] : null;

        // Check if the product already exists in the database
        $stmt = $conn->prepare("SELECT COUNT(*) FROM product WHERE productId = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        // If the product doesn't exist, insert it
        if ($count == 0) {
            $stmt = $conn->prepare("INSERT INTO product (productId, productName, productDescription, productImage, productPrice, productType, rentalPeriod) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('isssdss', $id, $name, $description, $image, $price, $type, $rentalPeriod);
            $stmt->execute();
            echo "Product $name added successfully.<br>";
        } else {
            echo "Product $name already exists in the database.<br>";
        }
    }
}

// Call the import function for each file type
importProducts($sellerFile, 'sale', $conn);
importProducts($donorFile, 'donation', $conn);
importProducts($lessorFile, 'rent', $conn);