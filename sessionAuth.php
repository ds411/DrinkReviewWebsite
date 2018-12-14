<?php
if(!isset($_SESSION)) session_start();

//Key for hash function
define('SERVER_KEY', 'static_server_key');

class SessionAuth
{

    //Start session
    static function initSession($username, $permissions)
    {
        session_unset();
        $_SESSION['username'] = $username;
        $_SESSION['permissions'] = $permissions;
        $_SESSION['hmac'] = hash_hmac('sha256', $username . $permissions, SERVER_KEY);
    }

    //Check if session is valid
    static function isValid()
    {
        if (isset($_SESSION['hmac']) && isset($_SESSION['username']) && isset($_SESSION['permissions'])) {
            return hash_equals($_SESSION['hmac'], hash_hmac('sha256', $_SESSION['username'] . $_SESSION['permissions'], SERVER_KEY));
        }
        return false;
    }
}

?>