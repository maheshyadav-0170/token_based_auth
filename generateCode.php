<?php
// Start the session
session_start();

// Generate a random authorization code
$authorizationCode = bin2hex(random_bytes(16)); // Generate a random 128-bit hexadecimal string

// Store the authorization code in the session
$_SESSION['authorization_code'] = $authorizationCode;

// Check if state value is provided
if (isset($_GET['state'])) {
    // Store the state value in the session
    $_SESSION['state'] = $_GET['state'];
}

// Check if return URL is provided
if (isset($_GET['return_url'])) {
    // Append the authorization code and state to the return URL as query parameters
    $returnURL = $_GET['return_url'] . "?code=" . $authorizationCode;
    // If state value is set, append it to the return URL
    if (isset($_SESSION['state'])) {
        $returnURL .= "&state=" . $_SESSION['state'];
    }
    // Redirect to the return URL with the authorization code and state
    header("Location: " . $returnURL);
    exit();
} else {
    // If return URL is not provided, just echo the authorization code
    echo $authorizationCode;
}