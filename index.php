<?php
// // index.php

// // Include the SSOHandler class
// require_once 'SSOHandler.php';

// // Start the session
// session_start();

// // Check if the cookie is set
// if (isset($_COOKIE['my_sso_cookie'])) {
//     // Cookie is set, show the content of home.php
//     echo "Welcome to the home page!";
//     if(isset($_SESSION['authorization_code'])) {
//         print_r($_SESSION['authorization_code']);
//     } else {
//         echo "Authorization code is not set.";
//     }
//     // Add your other content here...
// } else {
//     // Cookie is not set, display an alternative message or redirect
//     echo "Please log in to access the home page.";

//     // Create an instance of SSOHandler
//     $ssoHandler = new SSOHandler();

// }

// index.php

// Include the SSOHandler class
require_once 'SSOHandler.php';

// Start the session
session_start();

// Check if the cookie is set
if (isset($_COOKIE['my_sso_cookie'])) {
    // Cookie is set, show the content of home.php
    echo "Welcome to the home page!";
    if (isset($_SESSION['authorization_code'])) {
        echo "</br> Authorization Code : " . $_SESSION['authorization_code'];
    } else {
        echo "Authorization code is not set.";
    }

    // Check if state value is set in session and print it
    if (isset($_SESSION['state'])) {
        echo "<br>State value: " . htmlspecialchars($_SESSION['state']);
    } else {
        echo "<br>State value is not set.";
    }

} else {
    // Cookie is not set, display an alternative message or redirect
    echo "Please log in to access the home page.";

    // Create an instance of SSOHandler
    $ssoHandler = new SSOHandler('my_sso_cookie');
}