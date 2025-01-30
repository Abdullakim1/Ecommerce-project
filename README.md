# AZU Luxury E-Commerce Platform

A modern luxury e-commerce platform built with PHP and MySQL, featuring user authentication, product management, responsive design, and an interactive chatbot.

## Features

### User Features
- User registration and authentication with MySQL database
- Responsive product catalog with category filtering
- Shopping cart functionality
- Interactive chatbot for customer support
- Modern, luxury-focused UI with Bootstrap 5

### Admin Features
- Comprehensive admin panel for:
  - Product management (Add/Edit/Delete)
  - Category management
  - User management
  - Order tracking

### Technical Features
- PHP 8.3 backend with RESTful APIs
- MySQL database for secure data storage
- Responsive frontend using HTML5, CSS3, and JavaScript
- jQuery for enhanced interactivity
- Bootstrap 5 for modern UI components
- Font Awesome for professional icons
- Real-time chat support system

## Prerequisites

- PHP 8.3 or higher
- MySQL Server
- Web server (Apache/Nginx)
- PHP MySQL extension

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd ecommerce
```

2. Configure your web server to point to the project directory

3. Update database configuration in `db_config.php`:
```php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'luxury_user');
define('DB_PASSWORD', 'luxury123');
define('DB_NAME', 'luxury_ecommerce');
```

4. Database Setup:
```sql
CREATE DATABASE luxury_ecommerce;
CREATE USER 'luxury_user'@'localhost' IDENTIFIED BY 'luxury123';
GRANT ALL PRIVILEGES ON luxury_ecommerce.* TO 'luxury_user'@'localhost';
FLUSH PRIVILEGES;
```

5. Database usage:

mysql -u luxury_user -p
password:luxury123

show databases;
use luxury_ecommerce;
show tables;

The application will automatically create all required tables on first access.

## Project Structure

```
ecommerce/
├── static/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── app.js
├── data/
│   └── products.json
├── admin.php          # Admin interface
├── auth.php          # Authentication handling
├── db_config.php     # Database configuration
├── config.php        # Application configuration
├── index.php         # Main frontend
├── login.php         # Login page
├── logout.php        # Logout handling
├── products.php      # Product management
├── register.php      # User registration
└── README.md
```

## Database Schema

### Users Table
- id (Primary Key)
- email (Unique)
- password (Hashed)
- is_admin
- created_at

### Categories Table
- id (Primary Key)
- name
- slug (Unique)
- description
- created_at

### Products Table
- id (Primary Key)
- name
- description
- price
- image_url
- category_id (Foreign Key)
- created_at

### Orders Table
- id (Primary Key)
- user_id (Foreign Key)
- total_amount
- status
- created_at

### Order_Items Table
- id (Primary Key)
- order_id (Foreign Key)
- product_id (Foreign Key)
- quantity
- price

## Usage

1. Start your web server and MySQL server
   php -S localhost:8000

2. Access the website:
   ```
   http://localhost:8000
   ```

3. Register a new account or login with existing credentials

4. Admin Access:
   - Login with admin credentials
   - Access admin panel through the dashboard
   - Manage products, categories, and users

## Features Implementation

1. User Authentication:
   - Secure password hashing
   - Session management
   - Role-based access control

2. Product Management:
   - CRUD operations for products
   - Category organization
   - Image upload support

3. Frontend Features:
   - Responsive design
   - Category navigation
   - Search functionality
   - Interactive chatbot
   - Shopping cart

4. Security Features:
   - SQL injection prevention
   - XSS protection
   - CSRF protection
   - Secure password storage

## Development Decisions

1. Database Choice:
   - MySQL for reliability and ACID compliance
   - Proper relationship management
   - Scalability support

2. Frontend Framework:
   - Bootstrap 5 for responsive design
   - jQuery for DOM manipulation
   - Custom CSS for luxury aesthetics

3. Security Implementation:
   - Prepared statements
   - Input validation
   - Session security
   - Password hashing

## License

This project is licensed under the MIT License - see the LICENSE file for details
