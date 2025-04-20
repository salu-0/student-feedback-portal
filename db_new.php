<?php
// Include the MongoDB library
require 'vendor/autoload.php';

// Database connection settings
$databaseName = 'student';

try {
    // Create a MongoDB client
    $client = new MongoDB\Client("mongodb://localhost:27017");

// Select the database
$database = $client->$databaseName;
$db = $database; // Add this line to define $db for admin.php

// Function to get collection
function getCollection($collectionName) {
    global $database;
    return $database->$collectionName;
}
} catch (MongoDB\Driver\Exception\Exception $e) {
    die("Failed to connect to MongoDB: " . $e->getMessage());
}
?>
