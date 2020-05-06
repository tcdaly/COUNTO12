<?php
/**
 * Application configuration
 * This is included by the application entry point scripts such index.php and createimage.php.
 * It defines system-wide constants and global variables.  Some of these are server-specific.
 *
 * @package DaladiFramework
 * @author Thomas Daly
 * @copyright 2013
 * @access public
 */

    define('APP_ROOT', dirname(__FILE__));

    define('SERVER', 'dev');

    // Quality of cached JPEG images that are created
    define('JPEG_QUALITY', 100);

    /* Path to image cache directories without trailing slash.
         Appended to this is the name of one of the 6 image cache directories */
    define('IMAGE_CACHE_PATH', APP_ROOT . DIRECTORY_SEPARATOR . 'www');

    // Google webmaster tools verification code
    define('GOOGLE_VERIFICATION', '');

    // Controls whether to output debugging messages to the PHP error log
    define('DFDEBUG', false);

    define('MASTER_IMAGE_PATH', APP_ROOT . DIRECTORY_SEPARATOR . 'mimages');
    define('SITE_LOG_DIR',      APP_ROOT . DIRECTORY_SEPARATOR . 'logs');

    ini_set('default_mimetype', 'text/html');

    // Site-specfic error log
    ini_set('error_log', '/var/log/php/counto.log');

    // This controls what is set in the HTTP headers and in the HTML head section
    ini_set('default_charset', 'utf-8');

    ini_set('error_reporting', E_ALL);

    ini_set('display_errors', false);

    ini_set('log_errors', true);



?>