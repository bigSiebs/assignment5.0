<?php
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// PATH SETUP
        // 
        // $domain = "https://www.uvm.edu" or "http://www.uvm.edu";
        $domain = 'http://';
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS']) {
                $domain = 'https://';
            }
        }
        
        $server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8');
        
        $domain .= $server;
        
        if ($debug) {
            print '<p>Domain: ' . $domain . '</p>';
            print '<p>php Self: ' . $phpSelf . '</p>';
            print '<p>Path Parts<pre>';
            print_r($path_parts) . '</pre></p>';
        }
        
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // Include all libraries
        //
        require_once('../lib/security.php');
        
        if ($path_parts['filename'] == 'contact') {
            include "../lib/validation-functions.php";
            include "../lib/mail-message.php";
        }
?>
