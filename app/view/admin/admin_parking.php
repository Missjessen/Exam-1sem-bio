<?php
// Inkluder init.php for at få adgang til databaseforbindelse og autoload
require_once dirname(__DIR__, 3) . '/init.php';
echo "<pre>dirname(__DIR__, 3): " . dirname(__DIR__, 3) . "</pre>";





// Simulate the database connection
$db = Database::getInstance()->getConnection();

// Instantiate the PageLoader
$pageLoader = new PageLoader($db);

// Test the `loadAdminPage` function
echo "<h1>Testing loadAdminPage</h1>";

$testViewName = 'admin_daily_showings'; // Replace with the view you want to test
$testData = [
    'movies' => [
        ['id' => '1', 'title' => 'Test Movie 1'],
        ['id' => '2', 'title' => 'Test Movie 2'],
    ],
    'showings' => [
        ['id' => '1', 'movie_title' => 'Test Movie 1', 'showing_time' => '2023-12-01 18:00:00'],
        ['id' => '2', 'movie_title' => 'Test Movie 2', 'showing_time' => '2023-12-02 20:00:00'],
    ],
];

try {
    // Debug the view path
    echo "<pre>View Name: $testViewName</pre>";
    $viewPath = __DIR__ . "/app/view/admin/$testViewName.php";
    echo "<pre>Calculated View Path: $viewPath</pre>";

    if (file_exists($viewPath)) {
        echo "<pre>View path exists: $viewPath</pre>";
    } else {
        echo "<pre>View path does NOT exist: $viewPath</pre>";
    }

    // Test loading the page
    echo "<h2>Attempting to Load Admin Page</h2>";
    $pageLoader->loadAdminPage($testViewName, $testData);

} catch (Exception $e) {
    echo "<pre>Error: " . $e->getMessage() . "</pre>";
}
$initPath = dirname(__DIR__, 3) . '/init.php';
echo "<pre>Init Path: $initPath</pre>";
if (!file_exists($initPath)) {
    echo "<pre>Init.php findes ikke på den angivne sti.</pre>";
    exit;
}
require_once $initPath;