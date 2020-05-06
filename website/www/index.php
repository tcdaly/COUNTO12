<?php
/**
 * Counto12 main server script
 *
 * @package Counto12
 * @author Thomas Daly
 * @copyright 2012
 * @access public
 */

    require '../config.php';

// Short site description
    define('DESCRIPTION', 'Photographic clock of people living, working and studying in the London 2012 Olympic boroughs.');

// Messages for site log
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

// Request for user agent details from javascript.  Parse user agent string and return to client as JSON
    $ua = false;
    if (isset($_GET['ua']))
        $ua = (boolean)$_GET['ua'];

    if ($ua)
    {
        require "../uaparser/uaparser.php";

        //$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; CPU OS 7_0_2 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A501 Safari/9537.53';

        $parser = new UAParser;
        $result = $parser->parse($_SERVER['HTTP_USER_AGENT']);

        //$result = $parser->parse('');

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

        // Site log entry header.  Date followed by full UA string
        $logmsg = "\n" . date('j/m/Y H:i:s') . "\n";
        $logmsg .= $_SERVER['REMOTE_ADDR'] . ": " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        //$logmsg .= print_r($pua, true);
        file_put_contents(SITE_LOG_DIR . '/client.log', $logmsg, FILE_APPEND | LOCK_EX);

        header('Content-type: application/json');
        echo json_encode($pua);
        exit;
    }

/*
    For mobile devices it doesn't make sense to attempt to set layout viewport size at this (HTML) stage,
        since the page is built by Javascript which has yet to run, and this will determine the layout viewport size */
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo ini_get('default_charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=9">
    <meta name="google-site-verification" content="<?php echo GOOGLE_VERIFICATION; ?>">
    <meta name="author" content="Thomas Daly">
    <title>Counto12 | London 2012 Olympic Clock</title>
    <meta name="description" content="<?echo DESCRIPTION; ?>">
    <meta name="keywords" content="photo, photograph, portrait, exhibition, clock, london, 2012, olympic, olympics, games, Greenwich, Tower Hamlets, Hackney, Newham, Waltham Forest, digital, media, Goldsmiths, National Portrait Gallery, community, neighbourhood">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
    <link rel="icon" href="/siteimages/favicon.ico" type="image/x-icon">
    <meta property="og:site_name" content="Counto12">
    <meta property="og:url" content="http://counto12.daladi.org">
    <meta property="og:type" content="product">
    <meta property="og:title" content="Counto12 | London 2012 Olympic Clock">
    <meta property="og:description" content="<?echo DESCRIPTION; ?>">
    <meta property="og:image" content="http://coun.to/siteimages/counto12_logo.png">
    <meta property="og:locale" content="en_GB">
    <link rel="stylesheet" type="text/css" href="/packages/normalize-dist/normalize.css">
    <link rel="stylesheet" type="text/css" href="/packages/perfect-scrollbar-dist/perfect-scrollbar-0.4.9.min.css">
    <link rel="stylesheet" type="text/css" href="/style.css">
    <script src="/packages/jquery-dist/jquery.min.js" type="text/javascript"></script>
    <script src="/packages/jquery-mousewheel-dist/jquery.mousewheel.js" type="text/javascript"></script>
    <script src="/packages/perfect-scrollbar-dist/perfect-scrollbar-0.4.9.min.js" type="text/javascript"></script>
    <script src="/packages/imagesloaded-dist/imagesloaded.pkgd.min.js" type="text/javascript"></script>
<?php
    // server-dependent code includes
    switch (SERVER)
    {
        case 'dev':
            ?>
            <script src="/javascript/counto12.js" type="text/javascript"></script>
            <?
            break;

        case 'live':
            ?>
            <script src="/javascript/counto12.min.js" type="text/javascript"></script>
            <?
            break;
    }
?>

</head>

<body>
    <div id="container">
        <!-- clock -->
        <div id="clock">
            <div class="frame h" id="h1"></div>
            <div class="frame h" id="h2"></div>
            <div class="frame m" id="m1"></div>
            <div class="frame m" id="m2">
                <div id="loading"><img src="siteimages/loading.gif" alt="Counto12 logo">
                <div id="loading_progress"><div id="loading_progress_bar"></div></div>
                </div>
            </div>
            <div class="frame s" id="s1"></div>
            <div class="frame s" id="s2"></div>
        </div>

        <!-- control bar -->
        <div id="controlbg"></div>
        <div id="controltext">
            <img class="icon" id="fullscreen" src="siteimages/full_screen.png" title="Full screen" alt="Full screen">
            <img class="icon" id="infoicon" src="siteimages/info.png" title="About Counto12" alt="About Counto12">

            <a target="_blank" href="http://www.facebook.com/sharer.php?s=100&amp;p%5Burl%5D=<? echo urlencode('http://coun.to/12'); ?>&amp;p%5Bimages%5D%5B0%5D=<? echo urlencode('http://coun.to/siteimages/counto12_logo_black_100.png'); ?>&amp;p%5Btitle%5D=Counto12&amp;p%5Bsummary%5D=<? echo urlencode(DESCRIPTION); ?>"><img class="icon" id="facebook" src="siteimages/facebook_logo_48.png" title="Post to Facebook" alt="Facebook icon"></a>

            <a target="_blank" href="http://twitter.com/share?url=<?php echo urlencode('http://coun.to/12'); ?>&amp;text=<? echo urlencode(DESCRIPTION . " #london2012"); ?>"><img class="icon" id="twitter" src="siteimages/twitter_logo_48.png" title="Tweet this" alt="Twitter icon"></a>
        </div>

        <!-- project info page -->
        <div id="info">
            <img id="close" src="siteimages/close.png" alt="Close icon">
            <img id="logo" src="siteimages/counto12_logo_white.png" alt="Counto12 logo">

            <div id="textcontainer">
                <div id="textframe">
                    <div id="col_right">
                        <h2>DOWNLOAD AND INSTALL COUNTO12 SCREENSAVER</h2>

                        <p><strong><a href="http://roadto2012.npg.org.uk/_store/Standard%20res%20COUNTO12%20%20PC/installcounto12.exe">Download COUNTO12 PC Standard</a></strong> for monitors of at least 1280 x 800 pixels<br>

                        <strong><a href="http://roadto2012.npg.org.uk/_store/High%20res%20COUNTO12%20%20PC/installcounto12hi.exe">Download COUNTO12 PC High Res</a></strong> for monitors of at least 1680 x 1050 pixels<br>

                        <a target="_blank" href="http://roadto2012.npg.org.uk/_store/counto12_readme_pc.pdf">PC installation instructions</a></p>

                        <p><strong><a href="http://roadto2012.npg.org.uk/_store/counto12_mac_standard.zip">Download COUNTO12 Mac Standard</a></strong> for monitors of at least 1280 x 800 pixels<br>

                        <strong><a href="http://roadto2012.npg.org.uk/_store/counto12_mac_highres.zip">Download COUNTO12 Mac High Res</a></strong> for monitors of at least 1680 x 1050 pixels<br>

                        <a target="_blank" href="http://roadto2012.npg.org.uk/_store/counto12_readme_mac.pdf">Mac installation instructions</a>
                        </p>

                        <h3>CREDITS</h3><small>Producers: <strong>Yujin&nbsp;Yun</strong>, <a href="https://app.yunojuno.com/p/christina-katsantoni"><strong>Christina&nbsp;Katsantoni</strong></a> and <strong>Ewa&nbsp;Balazinska</strong>. Photographers: <a href="http://www.londonsartistquarter.org/artist-hub/users/memento8012/profile"><strong>SangDuck&nbsp;Bae</strong></a>, <strong><a href="http://www.paulyeungvision.com/">Paul&nbsp;Yeung</a></strong>, <strong>Amanda&nbsp;Shiu</strong> and <strong>Tomoko&nbsp;Kinoshita</strong>. Programmers: <strong><a href="http://thomasdaly.co.uk/">Thomas&nbsp;Daly</a></strong> and <strong>Stephen&nbsp;Pho</strong></small>
                    </div>
                    <p>Seen in action here, COUNTO12 was a digital clock and countdown timer to the opening ceremony of the <a target="_blank" href="http://www.olympic.org/london-2012">London 2012 Olympic Games</a>.  It comprised over 500 photographic portraits of people living, working and studying in the five Olympic host boroughs of Greenwich, Tower Hamlets, Hackney, Newham and Waltham Forest. COUNTO12 was a contribution to the UK National Portrait Gallery/BT’s <a target="_blank" href="http://roadto2012.npg.org.uk/"><cite>Road&nbsp;to&nbsp;2012</cite></a> photography exhibition and was designed as a screensaver that can be installed on Mac and PC&nbsp;computers.</p>

                    <p>In 2011, <cite>Road to 2012</cite> photographer Brian Griffin, sound artist Martyn Ware and film academic Julian Henriques challenged the students of <a target="_blank" href="http://www.gold.ac.uk/">Goldsmith’s University of London</a> to create and contribute digital media works.  The students were asked to explore, experiment and reflect on what the London Olympic and Paralympic Games meant to them, to a neighbourhood, to a community, to East London – and to tell that story. COUNTO12 is one of the resulting&nbsp;works.</p>

                    <p>“We felt eager to portray our local environment of people living, working and studying in east London and represent them in their natural surroundings and everyday occupations,” said creators Yujin, Christina and Ewa. “For COUNTO12 we photographed over 500 people living, working and studying in the Olympic-host boroughs. Different faces, different places, same feeling of&nbsp;anticipation...”</p>

                    <p>The National Portrait Gallery’s <cite>Road to 2012</cite> was a three-year photography project that recorded the inspirational men and women who worked and trained to make the London Olympic Games happen.  Between 2009 and 2012, numerous photographic portraits celebrated the people, both high-profile and behind the scenes, who collectively prepared the event. At the heart of the project was the exploration of inspirational, personal narratives.  The photographs were displayed at a series of summer exhibitions at the National Portrait Gallery and&nbsp;online.</p>

                    <p>COUNTO12 was screened at the gallery on 5 August 2011 as part of <a target="_blank" href="http://roadto2012.npg.org.uk/participation/reanimate-late-shift-extra"><cite>ReAnimate Late Shift Extra</cite></a>. Late Shift Extra took over the gallery with a night of sensory stimulation exploring the body, movement and the senses with artistic collaborations and interventions of live music, sound art, film, performance and&nbsp;philosophy.</p>
                </div>
            </div>

        </div>

    </div>

    <!-- never visible, used to attach preloaded images -->
    <div id="preload"></div>

</body>
</html>