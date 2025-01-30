<?php
require_once "config.php";
require_once "products.php";

$json_file = __DIR__ . '/data/products.json';
if (!file_exists($json_file)) {
    die("Products JSON file not found!");
}

$json_data = json_decode(file_get_contents($json_file), true);
if (!$json_data || !isset($json_data['products'])) {
    die("Invalid JSON data!");
}

$categories = ['men', 'women'];
foreach ($categories as $category) {
    $stmt = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $name = ucfirst($category);
        $description = "Luxury products for " . strtolower($name);
        $stmt = $conn->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $category, $description);
        $stmt->execute();
    }
}

$imported = 0;
$errors = [];

foreach ($json_data['products'] as $product) {
    $stmt = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
    $stmt->bind_param("s", $product['category']);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    
    if (!$category) {
        $errors[] = "Category not found for product: " . $product['name'];
        continue;
    }
    
    $stmt = $conn->prepare("SELECT id FROM products WHERE name = ?");
    $stmt->bind_param("s", $product['name']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = "Product already exists: " . $product['name'];
        continue;
    }
    
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url, category_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsi", 
        $product['name'],
        $product['description'],
        $product['price'],
        $product['image_url'],
        $category['id']
    );
    
    if ($stmt->execute()) {
        $imported++;
    } else {
        $errors[] = "Error importing product: " . $product['name'] . " - " . $stmt->error;
    }
}

echo "Import completed!\n";
echo "Successfully imported: " . $imported . " products\n";

if (!empty($errors)) {
    echo "\nErrors encountered:\n";
    foreach ($errors as $error) {
        echo "- " . $error . "\n";
    }
}
?>
