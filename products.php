<?php
require_once 'db_config.php';

function get_all_products($category_slug = null) {
    global $conn;
    
    $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id";
            
    if ($category_slug) {
        $sql .= " WHERE c.slug = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $category_slug);
    } else {
        $stmt = $conn->prepare($sql);
    }
    
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
    
    $stmt = $conn->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug 
                           FROM products p 
                           LEFT JOIN categories c ON p.category_id = c.id 
                           WHERE p.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function add_product($name, $description, $price, $image_url, $category_id) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url, category_id) 
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image_url, $category_id);
    
    return $stmt->execute();
}

function update_product($id, $name, $description, $price, $image_url, $category_id) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE products 
                           SET name = ?, description = ?, price = ?, image_url = ?, category_id = ? 
                           WHERE id = ?");
    $stmt->bind_param("ssdsii", $name, $description, $price, $image_url, $category_id, $id);
    
    return $stmt->execute();
}

function delete_product($id) {
    global $conn;
    
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    return $stmt->execute();
}

function get_all_categories() {
    global $conn;
    
    $result = $conn->query("SELECT * FROM categories ORDER BY name");
    
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    return $categories;
}

function get_category($id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function add_category($name, $slug, $description) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $slug, $description);
    
    return $stmt->execute();
}

function create_default_categories() {
    global $conn;
    
    $default_categories = [
        ['name' => 'Men', 'slug' => 'men', 'description' => 'Luxury products for men'],
        ['name' => 'Women', 'slug' => 'women', 'description' => 'Luxury products for women']
    ];
    
    foreach ($default_categories as $category) {
        $stmt = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
        $stmt->bind_param("s", $category['slug']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            add_category($category['name'], $category['slug'], $category['description']);
        }
    }
}

create_default_categories();
?>
