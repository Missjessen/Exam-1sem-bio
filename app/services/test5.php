<?php
// test_loader.php
// Testfil til at debugge og finde den korrekte sti til loadPages.php

// Definer de stier, vi ønsker at teste
function testCurrentPageURL() {
    $testCases = [
        ['page' => 'homePage', 'additionalParams' => []],
        ['page' => 'movie_details', 'additionalParams' => ['slug' => 'the-dark-knight']],
        ['page' => 'admin_movie', 'additionalParams' => []],
    ];

    foreach ($testCases as $testCase) {
        $url = currentPageURL($testCase['page'], $testCase['additionalParams']);
        echo "Genereret URL for {$testCase['page']}: $url\n";
    }
}

testCurrentPageURL();

