<?php
require_once 'db_config.php';

function get_all_products() {
    global $conn;
    
    $sql = "SELECT * FROM products";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    return $products;
}

function get_product($id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function add_product($name, $description, $price, $image_url) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $price, $image_url);
    
    return $stmt->execute();
}

function update_product($id, $name, $description, $price, $image_url) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image_url, $id);
    
    return $stmt->execute();
}

function delete_product($id) {
    global $conn;
    
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    return $stmt->execute();
}

?>
