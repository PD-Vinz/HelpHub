<?php
// Get the page from the query string, default to 'home' if none is provided
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Build the path to the requested page
$pagePath = __DIR__ . $page;

// Check if the file exists, if not, show a 404 page
if (file_exists($pagePath)) {
    include($pagePath);
} else {
    include(__DIR__ . $pagePath);
}
?>
