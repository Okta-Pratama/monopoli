<?php
/**
 * PHP Development Router
 * Handles URL rewriting for local development to match Vercel configuration
 */

$requested_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Handle static files (CSS, JS, images, etc)
if (preg_match('/\.(css|js|jpg|jpeg|png|gif|svg|webp|woff|woff2|ttf|eot|mp3|wav|ogg|webm|ico|json)$/i', $requested_path)) {
    $file = __DIR__ . $requested_path;
    if (file_exists($file)) {
        // Set proper content type
        $mime_types = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'ogg' => 'audio/ogg',
            'webm' => 'video/webm',
            'ico' => 'image/x-icon',
            'json' => 'application/json',
        ];
        
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $content_type = $mime_types[$ext] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $content_type);
        readfile($file);
        return true;
    }
}

// Handle directories that exist
if (is_dir(__DIR__ . $requested_path) && file_exists(__DIR__ . $requested_path . '/index.php')) {
    $_SERVER['REQUEST_URI'] = $requested_path . '/index.php';
}

// Route everything else to app/index.php
if (!file_exists(__DIR__ . $requested_path) || is_dir(__DIR__ . $requested_path)) {
    require __DIR__ . '/app/index.php';
    return true;
}

return false;
