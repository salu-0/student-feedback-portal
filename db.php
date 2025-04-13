<?php
// Include the MongoDB library
require 'vendor/autoload.php'; // Make sure to adjust the path if necessary

// Database connection settings
$databaseName = 'student';
$collectionName = 'register';

try {
    // Create a MongoDB client
    $client = new MongoDB\Client("mongodb://localhost:27017");

    // Select the database and collection
    $database = $client->$databaseName;
    $collection = $database->$collectionName;

    // Connection successful
    // echo "Connected to MongoDB database: $databaseName, collection: $collectionName";
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Failed to connect to MongoDB: " . $e->getMessage();
}
?>
