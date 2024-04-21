<?php
class SSOHandler
{
    private $authorizationCode;
    private $cookieName;

    public function __construct($cookieName)
    {
        $this->cookieName = $cookieName;
        $this->handleSSO();
    }

    // public function handleSSO()
    // {
    //     if ($this->isCookieSet()) {
    //         echo "Cookie is already set. Redirecting to home.php...<br>";
    //         header('Location: ' . $_SERVER['PHP_SELF']);
    //         exit;
    //     } else {
    //         if (isset($_GET['code'])) {
    //             // Authorization code is present in the URL
    //             $codeFromUrl = $_GET['code'];
    //             $this->createCookie($codeFromUrl);
    //             echo "Authorization code found in URL. Creating cookie and redirecting to step 1 for cookie set check...<br>";
    //             header('Location: ' . $_SERVER['PHP_SELF']);
    //             exit;
    //         } else {
    //             // Generate a random state value
    //             $state = bin2hex(random_bytes(16)); // Generate a random 128-bit hexadecimal string

    //             // Store the state value in the session
    //             $_SESSION['state'] = $state;

    //             // Redirect to generateCode.php with the state value included
    //             header('Location: generateCode.php?return_url=' . urlencode($_SERVER['PHP_SELF']) . '&state=' . $state);
    //             exit;
    //         }
    //     }
    // }


    public function handleSSO()
    {
        if ($this->isCookieSet()) {
            echo "Cookie is already set. Redirecting to home.php...<br>";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            if (isset($_GET['code'])) {
                // Check if state value is sent in the URL
                if (isset($_GET['state'])) {
                    // Get the state value from the URL
                    $stateFromUrl = $_GET['state'];

                    // Check if state value exists in the session
                    if (isset($_SESSION['state'])) {
                        // Get the state value from the session
                        $stateFromSession = $_SESSION['state'];

                        // Compare the state values
                        if ($stateFromUrl === $stateFromSession) {
                            // Authorization code is present in the URL
                            $codeFromUrl = $_GET['code'];
                            $response = file_get_contents('http://localhost/SSO/validateCode.php?code=' . $_SESSION['authorization_code']);

                            $this->createCookie($codeFromUrl, $response);
                            echo "Authorization code found in URL. Creating cookie and redirecting to step 1 for cookie set check...<br>";
                            header('Location: ' . $_SERVER['PHP_SELF']);
                            exit; // Exit after redirect
                        } else {
                            echo "State value sent to generateCode.php does not match the one stored in the session.";
                        }
                    } else {
                        echo "State value is not set in the session.";
                    }
                } else {
                    echo "State value is not sent in the URL.";
                }
            } else {
                // Generate a random state value
                $state = bin2hex(random_bytes(16)); // Generate a random 128-bit hexadecimal string

                // Store the state value in the session
                $_SESSION['state'] = $state;

                // Redirect to generateCode.php with the state value included
                header('Location: generateCode.php?return_url=' . urlencode($_SERVER['PHP_SELF']) . '&state=' . $state);
                exit;
            }
        }
    }


    private function isCookieSet()
    {
        // Implement your own logic to check if the cookie is set
        // For example:
        return isset($_COOKIE[$this->cookieName]);
    }

    private function generateAuthorizationCode()
    {
        return bin2hex(random_bytes(16));
    }

    // private function createCookie($code)
    // {
    //     // Set the cookie with the provided authorization code
    //     setcookie('my_sso_cookie', $code, time() + 3600, '/');
    // }
    private function createCookie($code, $userInfo)
    {
        // Combine the information into a single array
        $userInfo = array(
            'code' => $code,
            'userinfo' => $userInfo
        );

        // Convert the array into a string representation with a delimiter
        $cookieValue = json_encode($userInfo);

        // Set the cookie with the combined information
        setcookie($this->cookieName, $cookieValue, time() + 3600, '/');
    }

}