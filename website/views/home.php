<?php
/**
 * Counto12 application HTML
 *
 * This HTML view is included by the main application script 'index.php'
 *
 * @author T. C. Daly
 * @copyright 2013 T. C. Daly
 * @license MIT
 */
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo ini_get('default_charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=9">
    <meta name="google-site-verification" content="<?php echo GOOGLE_VERIFICATION; ?>">
    <meta name="author" content="T. C. Daly">
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

                        <h3>PC</h3>
                        <p><strong><a href="/downloads/InstallCounto12.exe">Download COUNTO12 PC Standard</a></strong> for monitors of at least 1280 x 800 pixels<br>

                        <strong><a href="/downloads/InstallCounto12hi.exe">Download COUNTO12 PC High Res</a></strong> for monitors of at least 1680 x 1050 pixels<br>

                        <a target="_blank" href="/downloads/counto12_screensaver_readme_pc.pdf">PC installation instructions</a></p>

                        <h3>MAC</h3>
                        <p><strong><a href="/downloads/counto12_screensaver_mac.zip">Download COUNTO12 Mac Standard</a></strong> for monitors of at least 1280 x 800 pixels<br>

                        <strong><a href="/downloads/counto12_screensaver_mac_hi.zip">Download COUNTO12 Mac High Res</a></strong> for monitors of at least 1680 x 1050 pixels<br>

                        <a target="_blank" href="/downloads/counto12_screensaver_readme_mac.pdf">Mac installation instructions</a>
                        </p>

                        <h3>CREDITS</h3>
                        <small>Producers: <strong>Yujin&nbsp;Yun</strong>, <a target="_blank" href="https://app.yunojuno.com/p/christina-katsantoni"><strong>Christina&nbsp;Katsantoni</strong></a> and <strong>Ewa&nbsp;Balazinska</strong>. Photographers: <a target="_blank" href="http://www.londonsartistquarter.org/artist-hub/users/memento8012/profile"><strong>SangDuck&nbsp;Bae</strong></a>, <strong>Paul&nbsp;Yeung</strong>, <strong>Amanda&nbsp;Shiu</strong> and <strong>Tomoko&nbsp;Kinoshita</strong>. Programmers: <a href="http://daly.live/">T. C. Daly</a></strong> and <strong>Stephen&nbsp;Pho</strong></small>
                    </div>
                    <p>COUNTO12 was a digital clock and countdown timer to the opening ceremony of the <a target="_blank" href="http://www.olympic.org/london-2012">London 2012 Olympic Games</a>.  It comprised over 500 photographic portraits of people living, working and studying in the five Olympic host boroughs of Greenwich, Tower Hamlets, Hackney, Newham and Waltham Forest. COUNTO12 was a contribution to the UK National Portrait Gallery/BT’s <a target="_blank" href="https://www.npg.org.uk/whatson/road-to-2012/">Road&nbsp;to&nbsp;2012</a> photography exhibition and was designed as a screensaver that can be installed on Mac and PC&nbsp;computers.</p>

                    <p>In 2011, <cite>Road to 2012</cite> photographer Brian Griffin, sound artist Martyn Ware and film academic Julian Henriques challenged the students of <a target="_blank" href="http://www.gold.ac.uk/">Goldsmith’s University of London</a> to create and contribute digital media works.  The students were asked to explore, experiment and reflect on what the London Olympic and Paralympic Games meant to them, to a neighbourhood, to a community, to East London – and to tell that story. COUNTO12 is one of the resulting&nbsp;works.</p>

                    <p>“We felt eager to portray our local environment of people living, working and studying in east London and represent them in their natural surroundings and everyday occupations,” said creators Yujin, Christina and Ewa. “For COUNTO12 we photographed over 500 people living, working and studying in the Olympic-host boroughs. Different faces, different places, same feeling of&nbsp;anticipation...”</p>

                    <p>The National Portrait Gallery’s <cite>Road to 2012</cite> was a three-year photography project that recorded the inspirational men and women who worked and trained to make the London Olympic Games happen.  Between 2009 and 2012, numerous photographic portraits celebrated the people, both high-profile and behind the scenes, who collectively prepared the event. At the heart of the project was the exploration of inspirational, personal narratives.  The photographs were displayed at a series of summer exhibitions at the National Portrait Gallery and&nbsp;online.</p>

                    <p>COUNTO12 was screened at the gallery on 5 August 2011 as part of <cite>ReAnimate Late Shift Extra</cite>. Late Shift Extra took over the gallery with a night of sensory stimulation exploring the body, movement and the senses with artistic collaborations and interventions of live music, sound art, film, performance and&nbsp;philosophy.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- never visible, used to attach preloaded images -->
    <div id="preload"></div>
</body>
</html>