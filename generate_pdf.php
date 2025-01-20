<?php
require 'vendor/autoload.php';

use Mpdf\Mpdf;

// Create directory for screenshots if it doesn't exist
if (!file_exists('screenshots')) {
    mkdir('screenshots', 0777, true);
}

// Function to capture screenshot
function captureScreenshot($url, $filename) {
    // In a real environment, you would use a proper screenshot tool
    // For now, we'll create placeholder images
    $image = imagecreatetruecolor(800, 600);
    $bg = imagecolorallocate($image, 255, 255, 255);
    $text = imagecolorallocate($image, 0, 0, 0);
    imagefilledrectangle($image, 0, 0, 800, 600, $bg);
    imagestring($image, 5, 10, 10, "Screenshot: " . $filename, $text);
    imagepng($image, 'screenshots/' . $filename . '.png');
    imagedestroy($image);
}

// Capture screenshots
$screenshots = ['homepage', 'products', 'admin', 'auth', 'cart'];
foreach ($screenshots as $screen) {
    captureScreenshot('http://localhost:8000/' . $screen, $screen);
}

// Read markdown content
$markdown = file_get_contents('project_report.md');

// Initialize Mpdf
$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_header' => 5,
    'margin_top' => 25,
    'margin_bottom' => 25
]);

// Add title page
$mpdf->WriteHTML('
    <style>
        .title-page {
            text-align: center;
            padding-top: 50%;
        }
        .title-page h1 {
            font-size: 24pt;
            margin-bottom: 20pt;
        }
        .title-page p {
            font-size: 12pt;
        }
    </style>
    <div class="title-page">
        <h1>AZU Luxury E-commerce Platform</h1>
        <p>Project Documentation</p>
        <p>' . date('F Y') . '</p>
    </div>
');

// Add new page for content
$mpdf->AddPage();

// Convert markdown to HTML and write to PDF
require_once 'vendor/parsedown/Parsedown.php';
$parsedown = new Parsedown();
$html = $parsedown->text($markdown);

// Add custom styles
$stylesheet = '
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { font-size: 20pt; color: #333; }
        h2 { font-size: 16pt; color: #444; }
        h3 { font-size: 14pt; color: #555; }
        p { font-size: 11pt; line-height: 1.6; }
        img { max-width: 100%; height: auto; }
    </style>
';

$mpdf->WriteHTML($stylesheet . $html);

// Save PDF
$mpdf->Output('AZU_Project_Report.pdf', 'F');

echo "PDF report has been generated successfully!\n";
?>
