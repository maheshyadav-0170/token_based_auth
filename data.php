<?php
// Check if the cookie is set
if (isset($_COOKIE['my_sso_cookie'])) {
    // Decode the JSON data stored in the cookie
    $cookieData = json_decode($_COOKIE['my_sso_cookie'], true);

    // Print the decoded cookie data
    echo "Cookie Data:<br>";
    print_r($cookieData);
} else {
    echo "Cookie 'my_cookie' is not set.";
}
?>