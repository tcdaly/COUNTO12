<?php
/**
 * Counto12 application server-side entry point
 *
 * This script either responds to a request from the client-side Javascript to discover
 * details of the device being used to access the site, or returns to the client
 * the application HTML
 *
 * @author Thomas Daly
 * @copyright 2013 Thomas Daly
 * @license MIT
 */

    require '../config.php';

    // The client-side Javascript may send a message to be stored in the site log
    if (isset($_GET['logmsg']))
        $logmsg = (string)$_GET['logmsg'];
    else
        $logmsg = '';

    if ($logmsg)
    {
        $logmsg = str_replace('CR', "\n", $logmsg);
        $logmsg = $_SERVER['REMOTE_ADDR'] . ': ' . $logmsg;
        file_put_contents(SITE_LOG_DIR . '/client.log', $logmsg, FILE_APPEND | LOCK_EX);
        exit;
    }

    /* The client-side Javascript may request user agent details by setting a flag ('ua') in the URI to 1.
    This enables discovery of what sort of device the user is using to access the application.
    In this case, parse the user agent string provided by the webserver, and return to client as JSON */
    $ua = false;
    if (isset($_GET['ua']))
        $ua = (boolean)$_GET['ua'];

    if ($ua)
    {
        require "../uaparser/uaparser.php";

        $parser = new UAParser;
        $result = $parser->parse($_SERVER['HTTP_USER_AGENT']);

        $pua = array();
        $pua['uafamily']    = $result->ua->family;                // Safari
        $pua['uamajor']     = $result->ua->major;                 // 6
        $pua['uaminor']     = $result->ua->minor;                 // 0
        $pua['uapatch']     = $result->ua->patch;                 // 2
        $pua['ua']          = $result->ua->toString;              // Safari 6.0.2
        $pua['uaversion']   = $result->ua->toVersionString;       // 6.0.2
        $pua['osfamily']    = $result->os->family;                // Mac OS X
        $pua['osmajor']     = $result->os->major;                 // 10
        $pua['osminor']     = $result->os->minor;                 // 7
        $pua['ospatch']     = $result->os->patch;                 // 5
        $pua['ospatchminor'] = $result->os->patch_minor;          // [null]
        $pua['os']          = $result->os->toString;              // Mac OS X 10.7.5
        $pua['osversion']   = $result->os->toVersionString;       // 10.7.5
        $pua['devicefamily'] = $result->device->family;           // Other

        switch($pua['osfamily'])
        {
            case 'Windows':
            case 'Windows XP':
            case 'Windows 7':
            case 'Windows Vista':
            case 'Windows Me':
            case 'Windows RT':
            case 'Windows 8':
            case 'Windows 10':
            case 'Windows 2000':
            case 'Windows NT 4.0':
            case 'Windows 98':
            case 'Mac OS':
            case 'Samsung':
            case 'FireHbbTV':
            case 'ATV OS X':
            case 'Linux':
            case 'Debian':
            case 'Solaris':
            case 'Ubuntu':
            case 'Kubuntu':
            case 'Arch Linux':
            case 'CentOS':
            case 'Slackware':
            case 'Gentoo':
            case 'openSUSE':
            case 'SUSE':
            case 'Red Hat':
            case 'Fedora':
            case 'PCLinuxOS':
            case 'Gentoo':
            case 'Mageia':
            // Assume Chrome OS is for desktop devices only for now
            case 'Chrome OS':
                $pua['type'] = 'Desktop';
                break;

            default:
                $pua['type'] = 'Mobile';
                break;
        }

        // Store details of the client in the site log
        $logmsg = "\n" . date('j/m/Y H:i:s') . "\n";
        $logmsg .= $_SERVER['REMOTE_ADDR'] . ": " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        file_put_contents(SITE_LOG_DIR . '/client.log', $logmsg, FILE_APPEND | LOCK_EX);

        // Return details of the client to front-end Javascript as JSON
        header('Content-type: application/json');
        echo json_encode($pua);
        exit;
    }

/*
    For mobile devices it doesn't make sense to attempt to set layout viewport size at this (HTML) stage,
        since the page is built by Javascript which has yet to run, and this will determine the layout viewport size */
    require '../views/home.php';
?>