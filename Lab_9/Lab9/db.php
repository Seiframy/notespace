
<?php
$host = '120';
$dbname = 'simple_crud';
$username = 'root';
$password = '1166';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die(json_encode(["success" => false, "message" => "Database Connection Failed: " . $e->getMessage()]));
}
?>