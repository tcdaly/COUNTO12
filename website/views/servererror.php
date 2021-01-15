<?php
/**
 * Output a '500 Internal Server Error' page together with a 500 HTTP header.
 * On entry, $err is set to an error message to display
 *
 * @author T. C. Daly
 * @copyright 2013 T. C. Daly
 * @license MIT
 */

    http_response_code(500);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>500 Internal Server Error</title>
    <link rel="stylesheet" type="text/css" href="/packages/normalize-dist/normalize.css">
</head>
<body>
    <div id="page" style="padding-left: 40px">

    <h1>500 Internal Server Error</h1>
    <p><?php echo $err; ?></p>
    <p>For more information, please contact the <a href="mailto:<?php echo $_SERVER['SERVER_ADMIN']; ?>">webmaster</a>.</p>

    <h2>Error 500</h2>

    <a href="/"><?php echo $_SERVER['SERVER_NAME']; ?></a><br />
    <?php echo date("D, d M H:i:s Y"); ?><br />
    <?php echo $_SERVER["SERVER_SIGNATURE"]; ?>
    <p><small><em>Counto12</em></small></p>
    </div>
</body>
</html>
