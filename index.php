<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
require_once 'auth.php';
require_once 'products.php';

$category = isset($_GET['category']) ? $_GET['category'] : null;
$products = get_all_products($category);
$categories = get_all_categories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AZU Luxury - Exclusive Collection</title>
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
                        <a class="nav-link" href="#products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#categories">Categories</a>
                    </li>
                    <?php if(is_logged_in()): ?>
                        <?php if(is_admin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin.php">Admin Panel</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">
                                <i class="fas fa-shopping-cart"></i> Cart
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>Welcome to AZU Luxury</h1>
                <p>Experience Exceptional Craftsmanship</p>
                <?php if(!is_logged_in()): ?>
                    <div class="mt-4">
                        <a href="register.php" class="btn btn-light btn-lg me-3">Join Now</a>
                        <a href="login.php" class="btn btn-light btn-lg">Sign In</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="category-nav-section">
        <div class="container">
            <div class="category-nav">
                <a href="index.php" class="category-link <?php echo !$category ? 'active' : ''; ?>">All Collections</a>
                <?php foreach($categories as $cat): ?>
                    <a href="index.php?category=<?php echo urlencode($cat['slug']); ?>" 
                       class="category-link <?php echo $category === $cat['slug'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php if($category): ?>
        <h2 class="category-title">
            <?php echo ucfirst($category); ?> Collection
        </h2>
    <?php endif; ?>

    <section class="products-section" id="products">
        <div class="container">
            <?php if(empty($products)): ?>
                <div class="text-center my-5">
                    <i class="fas fa-box-open fa-4x mb-3 text-muted"></i>
                    <h2>No Products Available</h2>
                    <p class="text-muted">Check back later for new arrivals in this collection.</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach($products as $product): ?>
                        <div class="col-md-4">
                            <div class="product-card">
                                <?php if($product['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                                    <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                                    <?php if(is_logged_in()): ?>
                                        <form action="cart.php" method="POST" class="d-inline">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-shopping-cart"></i> Add to Cart
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <a href="login.php" class="btn btn-secondary">
                                            <i class="fas fa-lock"></i> Login to Purchase
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <div class="chatbot">
        <div class="chatbot-header">
            <span>Customer Support</span>
            <i class="fas fa-chevron-up"></i>
        </div>
        <div class="chatbot-body">
            <div class="chat-messages">
                <div class="message bot">
                    Hello! How can I assist you today?
                </div>
            </div>
            <div class="chat-input">
                <input type="text" placeholder="Type your message...">
                <button><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('.chatbot-header').addEventListener('click', function() {
            document.querySelector('.chatbot').classList.toggle('open');
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-chevron-up');
            icon.classList.toggle('fa-chevron-down');
        });

        const chatInput = document.querySelector('.chat-input input');
        const chatButton = document.querySelector('.chat-input button');
        const chatMessages = document.querySelector('.chat-messages');

        function addMessage(message, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
            messageDiv.textContent = message;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function handleUserMessage(message) {
            addMessage(message, true);
            
            setTimeout(() => {
                let response;
                const lowerMessage = message.toLowerCase();
                
                if (lowerMessage.includes('hello') || lowerMessage.includes('hi')) {
                    response = "Hello! How can I assist you with our luxury collections today?";
                } else if (lowerMessage.includes('price') || lowerMessage.includes('cost')) {
                    response = "Our prices reflect the exceptional quality and craftsmanship of our pieces. For specific pricing, please browse our collections or contact our customer service.";
                } else if (lowerMessage.includes('delivery') || lowerMessage.includes('shipping')) {
                    response = "We offer worldwide shipping with special handling for our luxury items. Delivery times vary by location.";
                } else if (lowerMessage.includes('return') || lowerMessage.includes('refund')) {
                    response = "We have a 30-day return policy for all our items, ensuring your complete satisfaction.";
                } else {
                    response = "Thank you for your message. How else can I assist you with our luxury collections?";
                }
                addMessage(response);
            }, 500);
        }

        chatButton.addEventListener('click', () => {
            const message = chatInput.value.trim();
            if (message) {
                handleUserMessage(message);
                chatInput.value = '';
            }
        });

        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const message = chatInput.value.trim();
                if (message) {
                    handleUserMessage(message);
                    chatInput.value = '';
                }
            }
        });
    </script>
    
    <?php include 'footer.php'; ?>
</body>
</html>
