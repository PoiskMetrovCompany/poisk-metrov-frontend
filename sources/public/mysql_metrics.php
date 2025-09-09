<?php
header('Content-Type: text/plain; charset=utf-8');

// MySQL connection parameters
$host = getenv('MYSQL_HOST') ?: 'poisk-metrov_mysql';
$user = getenv('MYSQL_USER') ?: 'raptor';
$pass = getenv('MYSQL_PASSWORD') ?: 'lama22';
$db = getenv('MYSQL_DATABASE') ?: 'poiskmetrov';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Get basic MySQL metrics
    $metrics = [];

    // Global status
    $stmt = $pdo->query("SHOW GLOBAL STATUS");
    while ($row = $stmt->fetch()) {
        $metrics[$row['Variable_name']] = $row['Value'];
    }

    // Output in Prometheus format
    echo "# HELP mysql_up MySQL is up and responding\n";
    echo "# TYPE mysql_up gauge\n";
    echo "mysql_up 1\n\n";

    echo "# HELP mysql_threads_connected The number of currently open connections\n";
    echo "# TYPE mysql_threads_connected gauge\n";
    echo "mysql_threads_connected " . ($metrics['Threads_connected'] ?? 0) . "\n\n";

    echo "# HELP mysql_threads_running The number of threads that are not sleeping\n";
    echo "# TYPE mysql_threads_running gauge\n";
    echo "mysql_threads_running " . ($metrics['Threads_running'] ?? 0) . "\n\n";

    echo "# HELP mysql_queries_total The number of statements executed by the server\n";
    echo "# TYPE mysql_queries_total counter\n";
    echo "mysql_queries_total " . ($metrics['Queries'] ?? 0) . "\n\n";

    echo "# HELP mysql_connections_total The number of connection attempts\n";
    echo "# TYPE mysql_connections_total counter\n";
    echo "mysql_connections_total " . ($metrics['Connections'] ?? 0) . "\n\n";

    echo "# HELP mysql_uptime_seconds The number of seconds the MySQL server has been running\n";
    echo "# TYPE mysql_uptime_seconds counter\n";
    echo "mysql_uptime_seconds " . ($metrics['Uptime'] ?? 0) . "\n\n";

    echo "# HELP mysql_max_used_connections The maximum number of connections that have been in use simultaneously\n";
    echo "# TYPE mysql_max_used_connections gauge\n";
    echo "mysql_max_used_connections " . ($metrics['Max_used_connections'] ?? 0) . "\n\n";

} catch (Exception $e) {
    echo "# HELP mysql_up MySQL is up and responding\n";
    echo "# TYPE mysql_up gauge\n";
    echo "mysql_up 0\n\n";
    echo "# MySQL connection failed: " . $e->getMessage() . "\n";
}
