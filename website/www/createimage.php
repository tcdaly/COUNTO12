<?php
/**
 * This is a modified version of the Daladi Framework script 'createimage.php'
 * 
 * This script is run by Apache when the user requests an image that is not available in one of the
 * image cache directories.  It creates a scaled copy of a master image
 * and saves it in the requested location, so that next time it is served directly instead of
 * coming back here.  Finally the scaled image is returned to the client.
 *
 * The required size is specified as an integer included in the image filename. e.g. 'image_500.jpg'
 * would request master image 'image.jpg' scaled to 500 pixels
 *
 * Requirements:
 *
 * In the directory that is the parent to the one containing this file, a file called 'config.php'
 * that defines the following constants:
 *
 * IMAGE_CACHE_PATH     Full path to a directory to contain cached images.  Directory must be
 *                          writable by webserver
 * MASTER_IMAGE_PATH    Full path to a directory containing the site's master images
 *
 * @author Thomas Daly
 * @copyright 2020
 * @access public
 */


// Find the system config script - path relative to this file
    require '../config.php';
    require '../lib/Image.php';

/*
    $uri is passed from the web server.  It is a URI fragment (path) to file requested, relative to site root.
    e.g. '/cache0/01hs00_1050.jpg'
    Apache is configured to direct all requests in or under the 'imagecache' directory to this script
*/
    $uri = '';
    if (isset($_SERVER['REQUEST_URI']))
        $uri = (string)$_SERVER['REQUEST_URI'];

// $imgpath should be the same as $uri
    $imgpath = parse_url($uri, PHP_URL_PATH);

/*
    example $pathinfo:
    Array
    (
        [dirname] => /cache0
        [basename] => 01hs00_1050.jpg
        [extension] => jpg
        [filename] => 01hs00_1050
    )
*/
    $pathinfo = pathinfo($imgpath);

    $cachedir = '';
    if (isset($pathinfo['dirname']))
        $cachedir = $pathinfo['dirname'];

    $filename = '';
    if (isset($pathinfo['filename']))
        $filename = $pathinfo['filename'];

    $extension = '';
    if (isset($pathinfo['extension']))
        $extension = $pathinfo['extension'];

// The full server path to the cache directory
    $cachedirpath = IMAGE_CACHE_PATH . $cachedir;

// Determine required size of scaled image from requested filename
    preg_match('/(.+)_(\d+)$/', $filename, $matches);

    $response_code = 500;
    try
    {
        if (!isset($matches[1]) || !isset($matches[2]))
        {
            $response_code = 500;
            throw new Exception("Couldn't parse requested URI '$uri' into form image_S.jpg where S is required image size");
        }

    // $masterfilename is filename of master image (e.g. '01hs00.jpg')
        $masterfilename = $matches[1] . ".$extension";
    // $destsize is the desired size of the destination image
        $destsize = $matches[2];

        $image = new Image($cachedirpath);

    // Read master image from disc
        list($success, $err) = $image->read(MASTER_IMAGE_PATH . DIRECTORY_SEPARATOR . $masterfilename);
        if (!$success)
        {
            $response_code = 404;
            throw new Exception('Source image file not found');
        }

    // Scale master image to required size and write to disc cache
        list($success, $err) = $image->scale($destsize);
        if (!$success)
        {
            $response_code = 500;
            throw new Exception($err);
        }

    // Write image from memory to PHP output stream
        list($success, $err) = $image->write('direct', '', JPEG_QUALITY, '', true);
        if (!$success)
        {
            $response_code = 500;
            throw new Exception($err);
        }
    }
    catch (Exception $e)
    {
        error_log($_SERVER['SCRIPT_FILENAME'] . ': ' . $e->getMessage() . ' at line ' . $e->getLine());

        $err = '';

        if ($response_code == 404)
        {
            $err = $e->getMessage();
            require '../views/notfound.php';
        }

        if ($response_code == 500)
        {
            require '../views/servererror.php';
        }
    }

?>