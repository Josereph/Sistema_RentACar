<?php
// config/google.php
defined('PROJECT_ROOT_FS') or define('PROJECT_ROOT_FS', dirname(__DIR__));
require_once PROJECT_ROOT_FS . '/config/db.php';

define('GOOGLE_CLIENT_ID', '464202329490-jt8q049a5gmc7kdnog95nprmf1morgbp.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost/Sistema_RentACar/index.php?controller=GoogleAuth&action=callback');
?>