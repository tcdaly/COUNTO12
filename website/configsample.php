<?php
/**
 * Application configuration
 *
 * This is included by the application entry point scripts index.php and createimage.php.
 * It defines application-wide constants
 *
 * @author Thomas Daly
 * @copyright 2013 Thomas Daly
 * @license MIT
 */

    define('APP_ROOT', dirname(__FILE__));

// Short site description
    define('DESCRIPTION', 'Photographic clock of people living, working and studying in the London 2012 Olympic boroughs.');

    // Server environment may be either 'dev' or 'live'
    define('SERVER', 'dev');

    // Quality of cached JPEG images that are created (min 1, max 100)
    define('JPEG_QUALITY', 100);

    // Path to the webserver root directory, containing publicly accessible resources
    define('WEB_ROOT_PATH', APP_ROOT . DIRECTORY_SEPARATOR . 'www');

    // Google search console verification code
    define('GOOGLE_VERIFICATION', '');

    // If true, verbose debugging information is output to the PHP error log
    define('DFDEBUG', false);

    // Folder containing the library of high-resolution clock number photos
    define('MASTER_IMAGE_PATH', APP_ROOT . DIRECTORY_SEPARATOR . 'mimages');

    // Path to site log directory
    define('SITE_LOG_DIR', APP_ROOT . DIRECTORY_SEPARATOR . 'logs');

    ini_set('default_mimetype', 'text/html');
    ini_set('error_log', '/var/log/php/counto.log');
    ini_set('default_charset', 'utf-8');
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', false);
    ini_set('log_errors', true);
?>