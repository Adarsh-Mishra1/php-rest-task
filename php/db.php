<?php
$host = 'db';
$user = 'root';
$pass = 'rootpassword';
$db = 'recipe_db';

$connection = new mysqli($host, $user, $pass, $db);

if ($connection->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed: " . $connection->connect_error]));
}
?>
