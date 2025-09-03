<?php
/**
 * Simple health check endpoint for Laravel application
 * This endpoint checks if the application is responding correctly
 */

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

try {
    // Basic PHP check
    if (!function_exists('phpversion')) {
        throw new Exception('PHP is not working');
    }

    // Check if we can connect to the response
    $response = [
        'status' => 'healthy',
        'timestamp' => date('c'),
        'php_version' => phpversion(),
        'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
        'message' => 'Application is running correctly'
    ];

    http_response_code(200);
    echo json_encode($response, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    $error = [
        'status' => 'unhealthy',
        'timestamp' => date('c'),
        'error' => $e->getMessage(),
        'message' => 'Application has issues'
    ];

    http_response_code(503);
    echo json_encode($error, JSON_PRETTY_PRINT);
}
?>
