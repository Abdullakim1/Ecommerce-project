<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'products.php';

if (!is_logged_in() || !is_admin()) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_product':
                $name = $_POST['name'] ?? '';
                $description = $_POST['description'] ?? '';
                $price = $_POST['price'] ?? '';
                $image_url = $_POST['image_url'] ?? '';
                $category_id = $_POST['category_id'] ?? null;
                
                if (empty($name) || empty($description) || empty($price)) {
                    $error = 'Please fill in all required fields';
                } else {
                    if (add_product($name, $description, $price, $image_url, $category_id)) {
                        $success = 'Product added successfully';
                    } else {
                        $error = 'Failed to add product';
                    }
                }
                break;
                
            case 'edit_product':
                $id = $_POST['id'] ?? '';
                $name = $_POST['name'] ?? '';
                $description = $_POST['description'] ?? '';
                $price = $_POST['price'] ?? '';
                $image_url = $_POST['image_url'] ?? '';
                $category_id = $_POST['category_id'] ?? null;
                
                if (empty($id) || empty($name) || empty($description) || empty($price)) {
                    $error = 'Please fill in all required fields';
                } else {
                    if (update_product($id, $name, $description, $price, $image_url, $category_id)) {
                        $success = 'Product updated successfully';
                    } else {
                        $error = 'Failed to update product';
                    }
                }
                break;
                
            case 'delete_product':
                $id = $_POST['id'] ?? '';
                
                if (empty($id)) {
                    $error = 'Invalid product ID';
                } else {
                    if (delete_product($id)) {
                        $success = 'Product deleted successfully';
                    } else {
                        $error = 'Failed to delete product';
                    }
                }
                break;
        }
    }
}

$products = get_all_products();
$categories = get_all_categories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-crown"></i>
                AZU Luxury
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">View Site</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Admin Panel</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Add New Product</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="admin.php">
                    <input type="hidden" name="action" value="add_product">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Image URL</label>
                        <input type="url" class="form-control" id="image_url" name="image_url">
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control" id="category_id" name="category_id">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Manage Products</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $product['id']; ?>">
                                            Edit
                                        </button>
                                        <form method="POST" action="admin.php" class="d-inline">
                                            <input type="hidden" name="action" value="delete_product">
                                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                
                                <div class="modal fade" id="editModal<?php echo $product['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="admin.php">
                                                    <input type="hidden" name="action" value="edit_product">
                                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                                    
                                                    <div class="mb-3">
                                                        <label for="edit_name<?php echo $product['id']; ?>" class="form-label">Product Name</label>
                                                        <input type="text" class="form-control" id="edit_name<?php echo $product['id']; ?>" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="edit_price<?php echo $product['id']; ?>" class="form-label">Price</label>
                                                        <input type="number" class="form-control" id="edit_price<?php echo $product['id']; ?>" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="edit_description<?php echo $product['id']; ?>" class="form-label">Description</label>
                                                        <textarea class="form-control" id="edit_description<?php echo $product['id']; ?>" name="description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="edit_image_url<?php echo $product['id']; ?>" class="form-label">Image URL</label>
                                                        <input type="url" class="form-control" id="edit_image_url<?php echo $product['id']; ?>" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>">
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="edit_category_id<?php echo $product['id']; ?>" class="form-label">Category</label>
                                                        <select class="form-control" id="edit_category_id<?php echo $product['id']; ?>" name="category_id">
                                                            <option value="">Select Category</option>
                                                            <?php foreach ($categories as $category): ?>
                                                                <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
