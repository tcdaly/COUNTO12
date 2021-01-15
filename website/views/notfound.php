<?php
/**
 * Output a '404 not found' page together with a 404 HTTP header.
 * On entry, $err is set to an error message string to display
 *
 * @author T. C. Daly
 * @copyright 2013 T. C. Daly
 * @license MIT
 */

    if (isset($_GET['error']))
        $err = $_GET['error'];

    http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>404 Not Found</title>
    <link rel="stylesheet" type="text/css" href="/packages/normalize-dist/normalize.css">
</head>
<body>
    <div id="page" style="padding-left: 40px">

    <h1>404 Not Found</h1>
    <p><?php echo $err; ?></p>
    <p>If you entered the URL manually please check your spelling and try again.</p>
    <p>
    If you think this is a server error, please contact
    the <a href="mailto:<?php echo $_SERVER['SERVER_ADMIN']; ?>">webmaster</a>.
    </p>
    
    <h2>Error 404</h2>

    <a href="/"><?php echo $_SERVER['SERVER_NAME']; ?></a><br />
    <?php echo date("D, d M H:i:s Y"); ?><br />
    <?php echo $_SERVER["SERVER_SIGNATURE"]; ?>
    <p><small><em>Counto12</em></small></p>
    </div>
</body>
</html>
