<?php
// Required for GD library
header('Content-Type: image/png');

// Function to create a placeholder screenshot
function createScreenshot($width, $height, $title, $filename) {
    // Create image
    $image = imagecreatetruecolor($width, $height);
    
    // Colors
    $bg = imagecolorallocate($image, 245, 245, 245);
    $text_color = imagecolorallocate($image, 33, 37, 41);
    $accent = imagecolorallocate($image, 0, 123, 255);
    
    // Fill background
    imagefilledrectangle($image, 0, 0, $width, $height, $bg);
    
    // Add header bar
    imagefilledrectangle($image, 0, 0, $width, 60, $accent);
    
    // Add title text
    $font = 5; // Built-in font
    $title = "AZU Luxury - " . $title;
    $title_width = imagefontwidth($font) * strlen($title);
    $title_x = ($width - $title_width) / 2;
    imagestring($image, $font, $title_x, 20, $title, $bg);
    
    // Add some placeholder content
    $content_text = "Screenshot placeholder for: " . $filename;
    $content_width = imagefontwidth($font) * strlen($content_text);
    $content_x = ($width - $content_width) / 2;
    imagestring($image, $font, $content_x, $height/2, $content_text, $text_color);
    
    // Save image
    imagepng($image, __DIR__ . '/screenshots/' . $filename);
    imagedestroy($image);
}

// Create screenshots directory if it doesn't exist
if (!file_exists(__DIR__ . '/screenshots')) {
    mkdir(__DIR__ . '/screenshots', 0777, true);
}

// Generate screenshots
$screenshots = [
    ['Homepage', 'homepage.png'],
    ['Product Listing', 'products.png'],
    ['Admin Dashboard', 'admin.png'],
    ['Authentication', 'auth.png'],
    ['Shopping Cart', 'cart.png']
];

foreach ($screenshots as $screenshot) {
    createScreenshot(1200, 800, $screenshot[0], $screenshot[1]);
}

echo "Screenshots generated successfully!\n";
?>
