<?php
    session_start();

    class CSRF
    {
        public static function createTokenOnly(int $expireSecs): string
        {
            // Generate token
            $token = bin2hex(random_bytes(32));

            // Save in session
            $_SESSION['csrf-token'] = $token;

            // Generate and save token expiry
            $_SESSION['csrf-token-expire'] = time() + $expireSecs; // 1hr

            return $token;
        }

        public static function createTokenWithField()
        {
            // Generate token
            $token = bin2hex(random_bytes(32));

            // Save in session
            $_SESSION['csrf-token'] = $token;

            // Generate and save token expiry
            $_SESSION['csrf-token-expire'] = time() + 60; // 1hr

            // Create hidden field - NOTE: token is generated on SUBMIT not form creation
            echo "<input type='hidden' name='csrf-token' value='$token' />";
        }

        public static function validateToken($token, $expire): bool
        {
            // Check all required params exist
            if (!isset($_POST['csrf-token']) || !isset($_SESSION['csrf-token']) || !isset($_SESSION['csrf-token-expire']))
            {
                return false;
            }

            // SESSION & POST token match
            if ($_SESSION['csrf-token'] == $token) {

                // Check if expired
                if (time() >= $expire) {
                    // exit('expired time');
                    return false;
                } else {

                    // CSRF IS VALID
                    unset($_SESSION['token']);
                    unset($_SESSION['token-expire']);

                    return true;
                }
            } else {
                return false;
            }
        }
    }
