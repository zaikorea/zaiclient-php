/**
* To supress deprecation warning
* https://github.com/sebastianbergmann/phpunit/issues/4228
*/
<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
require_once 'vendor/autoload.php';
