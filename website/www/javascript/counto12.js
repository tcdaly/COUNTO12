/**
 * Counto12 main javascript
 *
 * @author Thomas Daly
 * @copyright Counto12/code (c) 2015 Thomas Daly
 * @version 2.0.3
 */

// clock aspect ratio
    var caspect = 1.6;
// gap between images as a percentage of image height
    var igap = 30/105;
// Number of seconds images to preload before clock starts running.  Must be less than or equal to 60
    var prl = 60;

/* Clock is displayed in either pillarbox or letterbox mode.  Value of cdisplay is
    either 'pillarbox' or 'letterbox'.  infoopen defines if info screen is visible or not */
    var cdisplay = '', infoopen = false;

/*
    cwidth & cheight:     clock dimensions.  Clock height = displayed image height
    vpwidth & vpheight:   layout viewport dimensions
    swidth & sheight:     reported client screen dimensions in device pixels
    piheight:             physical height of images downloaded from server in pixels.
                          Different from height of image actually displayed, which is
                          equal to clock height (cheight)
*/
    var cwidth, cheight, vpwidth, vpheight, swidth, sheight, piheight = 0;

// global array indices to next position in image arrays to load image
    var sindex = 0, mindex = 0, hindex = 0;

// current and previous time
    var ct, ch, cm, cs, ph = -1, pm = -1, ps = -1;

    // Read current time
    ct = new Date();
    ch = ct.getHours();
    cm = ct.getMinutes();
    cs = ct.getSeconds();

// arrays of image objects for hours, minutes and seconds
    var h = new Array();
    var m = new Array();
    var s = new Array();

    var hlib = new Object;
    var mlib = new Object;
    var slib = new Object;

    var init1run = false;

    $(document).ready(init1);

/* Application init stage 1.

        Determine image size, preload initial batch of images
*/
    function init1()
    {
        var data;

    // Determine details of client device, storing in object agent.  Data returned as JSON
        $.getJSON('index.php', 'ua=1', function(data) {
            window.agent = data;
        // Determine physical height of images in pixels to load from server
            pimageheight();
        // Load library of image filenames
            $.getJSON('json/hours.json', function(data) {
                hlib = data;
                $.getJSON("json/minutes.json", function(data) {
                    mlib = data;
                    $.getJSON("json/seconds.json", function(data) {
                        slib = data;
                        // preload depends on value of piheight set by pimageheight()
                        preload();
                    });
                });
            });

        // draw clock outline and display 'loading' indicator
            sizeclock();

        /* temp code: display info page on startup
                    $('#info').show();
                    infoopen = true;
                    $('#textcontainer').perfectScrollbar();
        /* end temp code */
        });
    }

/* Application init stage 2.  Run when first batch of images have preloaded.

    Fade out loading indicator and put initial time on clock */
    function init2()
    {
        $('#loading').fadeOut(700, function() {
            // remove the loading <div> container
            $('#loading').remove();

            // put initial time on bottom frames
            ct = new Date();
            ch = ct.getHours();
            cm = ct.getMinutes();
            cs = ct.getSeconds();

            $('#h1').append(h[ch]);
            $('#m1').append(m[cm]);
            $('#s1').append(s[cs]);

            // empty placeholders in top frames
            $('#h2').append('<img src="" alt="">');
            $('#m2').append('<img src="" alt="">');
            $('#s2').append('<img src="" alt="">');

            if (window.agent.type == 'Desktop')
            {
                // Dynamically resize clock according to browser window size (desktop only)
                $(window).resize(sizeclock);
            }
            else
            {
                // orientation change handler (mobile only)
                window.addEventListener('orientationchange', orientationChange);
            }

            // update the time every 83ms (equals frame rate of 12fps)
            window.setInterval(update, 83);
            //update();

            // Fade up control bar
            $('#controlbg').fadeIn(400);
            $('#controltext').fadeIn(400);

            // Add onclick event handler to full screen button
            if (window.agent.type == 'Desktop' && window.agent.uafamily != 'IE')
            {
                $('#fullscreen').click(function() {
                    var e = document.getElementById("container");
                    if (RunPrefixMethod(document, "FullScreen") || RunPrefixMethod(document, "IsFullScreen"))

                    {
                        RunPrefixMethod(document, "CancelFullScreen");
                    }
                    else

                    {
                        RunPrefixMethod(e, "RequestFullScreen");
                    }
                });
            }

            // Add onclick event handler to full screen button
            $('#infoicon').click(function() {
                if (infoopen)
                {
                    $('#textcontainer').perfectScrollbar('destroy');
                    $('#info').hide();
                    infoopen = false;
                }
                else
                {
                    $('#textcontainer').height(vpheight - 120 - 70);
                    $('#info').show();
                    infoopen = true;
                    $('#textcontainer').perfectScrollbar();
                }
            });

            // Add onclick handler to close icon
            $('#close').click(function() {
                $('#textcontainer').perfectScrollbar('destroy');
                $('#info').hide();
                infoopen = false;
            });
        });

        // finish preloading seconds images
        preloadsec(60-prl);
    }

// Determine physical height of images to download from server in pixels
    function pimageheight()
    {
        // Width, height and aspect ratio of physical display device when in landscape mode
        var nswidth, nsheight, nsaspect, msg = '';

        msg = "UA family: " + window.agent.uafamily + " CR";
        msg += "OS family: " + window.agent.osfamily + " CR";
        msg += "Device family: " + window.agent.devicefamily + " CR";

        // Some iOS and Android mobile devices have high-res retina displays
        if (window.agent.type == 'Mobile' && window.devicePixelRatio > 1)
        {
            swidth = screen.width * window.devicePixelRatio;
            sheight = screen.height * window.devicePixelRatio;
        }
        else
        {
        // Desktop monitor
            swidth = screen.width;
            sheight = screen.height;
        }

        /*  Compare aspect ratio of clock with that of screen. Make comparison independent of device orientation.
            Normalise orientation to landscape
        */
        if (swidth > sheight)
        {
            // display is in landscape mode
            nswidth = swidth;
            nsheight = sheight;
        }
        else
        {
            // display is in portrait mode
            nswidth = sheight;
            nsheight = swidth;
        }
        nsaspect = nswidth / nsheight;

        if (nsaspect > caspect)
        {
            /* screen is wider than clock: pillarbox display.  So height of clock must be
                the height of the screen */
            piheight = Math.round(nsheight);
        }
        else
        {
            /* screen is narrower than clock: letterbox display.  Width of clock must be
                the width of the screen */
            cwidth = nswidth;
            cheight = cwidth / caspect;
            piheight = Math.round(cheight);
        }

    // Maximum available resolution of photos is 1050
        if (piheight > 1050)
        {
            piheight = 1050;
        }

    // Log device details on server

        if (window.agent.osfamily == 'iOS')
        {
            msg += "Guess at iOS device model: " + guess_device(nswidth, nsheight) + " CR";
        }
        msg    += 'Reported device pixels: ' + screen.width + ' x ' + screen.height + 'CR'
            + 'Calculated device pixels: ' + swidth + ' x ' + sheight + 'CR'
            + 'Reported layout viewport: ' + document.documentElement.clientWidth + ' x ' + document.documentElement.clientHeight + 'CR'
            + 'Device pixel ratio: ' + window.devicePixelRatio + 'CR'
            + 'Physical image height: ' + piheight + 'CR';

        log(msg);
    }

    function log(msg)
    {
        $.get('index.php', 'logmsg=' + msg);
    }

// Reset physical device dimensions and clock size on device orientation change
    function orientationChange()
    {
        switch(window.orientation)

        {
            // landscape
            case -90:
            case 90:
                log('CROrientation change to landscape (' + window.orientation + ') CR');
                break;

            // portrait
            default:
                log('CROrientation change to portrait CR');
                break;

        }
        pimageheight();
        sizeclock();
    }

/* Set initial clock size (mobile and desktop) or resize clock according to window size change (desktop only).
    Clock is sized to sit within a rectangular layout viewport with width vpwidth and height vpheight */
    function sizeclock()
    {
        if (window.agent.type == 'Desktop')
        {
            // The size of the user's browser window gives us the layout viewport
            vpwidth = document.documentElement.clientWidth;
            vpheight = document.documentElement.clientHeight;

            // Determine whether to display right info column
            if (vpwidth > 700)
            {
                $('#col_right').show();
            }
            else
            {
                $('#col_right').hide();
            }

        }
        else
        {
            /* For mobile devices base the clock size on the reported physical screen size, which should in turn
                set the layout viewport to be the same */
            vpwidth = screen.width;
            vpheight = screen.height;
        }

        // viewport aspect ratio
        var vpaspect = vpwidth / vpheight;

        if (vpaspect > caspect)
        {
            // viewport AR > clock AR. Pillarbox display
            cheight = vpheight;
            cwidth = vpheight * caspect;
            $('#clock').height(vpheight);
            $('#clock').width(cwidth);
            cdisplay = 'pillarbox';
        }
        else
        {
            // viewport AR < clock AR. Letterbox display
            cheight = vpwidth / caspect;
            cwidth = vpwidth;
            $('#clock').width(cwidth);
            $('#clock').height(cheight);
            cdisplay = 'letterbox';
        }

        // vertically centre clock
        $('#clock').css('margin-top', (vpheight-cheight) / 2);

        var gap = (igap/100) * cheight;

        // Displayed image width = (clock width - (gap between images * 2)) / 3
        var iwidth = ((cwidth - (gap * 2)) / 3);

        $('.frame').css({    'width': iwidth,
                            'height': cheight,
                            'top': 0

                            });
        $('.h').css('left', 0);
        $('.m').css('left', iwidth+gap + 'px');
        $('.s').css('left', ((iwidth+gap)*2) + 'px');
        //$('#s1').css('left', ((iwidth+gap)*2) + 200 + 'px');

        // Set position of control bar
        if (cdisplay == 'letterbox')
        {
            // Control bar at bottom
            $('#controlbg,#controltext').css({  'left':'0',
                                                'top':'auto',
                                                'bottom':'0',
                                                'width':'100%',
                                                'height':'32px',
                                                'padding-left':'14px',
                                                'padding-top':'0px'
                                                });
        }
        else
        {
            // Control bar at left
            $('#controlbg,#controltext').css({  'left':'0',
                                                'top':'0',
                                                'bottom':'auto',
                                                'width':'32px',
                                                'height':'100%',
                                                'padding-left':'0px',
                                                'padding-top':'14px'
                                                });
        }

        // Display full screen mode icon in all browsers except IE
        if (window.agent.type == "Desktop" && window.agent.uafamily != 'IE')
        {
            $('#fullscreen').show();
        }

        // scrollbar update
        if (infoopen)
        {
            $('#textcontainer').height(vpheight - 120 - 70);
            $('#textcontainer').perfectScrollbar('update');
        }
    }

/* Create new image object and load an image into it
    cat (string):     either h, m or s
    n (integer):     number displayed on image

    returns: pointer to new image object
*/
    function loadimage(cat, n)
    {
        var filename, obj = new Image;
        //console.log(cat, n, slib);
        switch(cat)
        {
            case 'h':
                filename = hlib[n][Math.floor(Math.random()*hlib[n].length)] + '_' + piheight + '.jpg';
                break;
            case 'm':
                filename = mlib[n][Math.floor(Math.random()*mlib[n].length)] + '_' + piheight + '.jpg';
                break;
            case 's':
                filename = slib[n][Math.floor(Math.random()*slib[n].length)] + '_' + piheight + '.jpg';
                break;

        }
        // 0 <= n <= 59.  There are 6 cache folders numbered 0 - 5 with images for 10 digits in each
        obj.src = 'cache' + Math.floor(n / 10) + '/' + filename;
        obj.alt = n + cat;
        //console.log('Requested image', obj.src);
        return obj;
    }

// Preload seconds images.  x = number of images to preload
    function preloadsec(x)
    {
        for (var j=0; j<x; j++)
        {
            // sindex is global array index to next position in seconds array
            s[sindex] = null;
            s[sindex] = loadimage('s', sindex);
            $('#preload').append(s[sindex]);
            sindex = (sindex + 1) % 60;
        }
    }

// Preload second, minute and hour images before clock runs
    function preload()
    {
        /* the width of the loading progress bar, as a percentage of the total width, to increment
            by every time an image is preloaded */
        var lb_inc_width = 100 / (prl + 3 + 1);
        var lb_width = 0;

        ct = new Date();
        ch = ct.getHours();
        cm = ct.getMinutes();
        cs = ct.getSeconds();

        var i, j;

        // preload second images starting from current time
        sindex = cs;
        preloadsec(prl);

        /* preloading 3 minutes means clock can run for at least 3 minutes
            in event of network failure */
        mindex = cm;
        for (j=0; j<3; j++)
        {
            m[mindex] = loadimage('m', mindex);
            $('#preload').append(m[mindex]);
            mindex = (mindex + 1) % 60;
        }

        // preload current hour.  Next hour will be loaded immediately when clock is displayed
        hindex = ch;

        h[hindex] = loadimage('h', hindex);
        $('#preload').append(h[hindex]);
        hindex = (hindex + 1) % 24;

        // Add 'onload' event handler to preload container
        $('#preload').imagesLoaded()
        .always(function(instance) {
            //console.log('all images loaded or confirmed as broken');
            // proceed to initialisation step 2
            init2();
        })
        .done(function(instance) {
            //console.log('all images successfully loaded');
        })
        .fail(function() {
            //console.log('all images loaded, at least one is broken');
        })
        .progress(function(instance, image) {
            var result = image.isLoaded ? 'loaded' : 'broken';
            /*
            if (result == 'broken')
            {
                console.error('image is broken for ' + image.img.src);
            }
            else
            {
                console.log('image is ' + result + ' for ' + image.img.src);
            }
            */
            // Update loading progress bar
            lb_width += lb_inc_width;
            $('#loading_progress_bar').width(lb_width.toString() + '%');
        });
    }

// Update clock display
    function update()
    {
        var i;

        // read current local time
        ct = new Date();
        ch = ct.getHours();
        cm = ct.getMinutes();
        cs = ct.getSeconds();

        // compare with previous time
        if (ch != ph)
        {
            // load a new hour image from server for later use
            h[hindex] = null;
            h[hindex] = loadimage('h', hindex);
            hindex = (hindex + 1) % 24;

            // swap z-index of two hour frames
            if ($('#h1').css('z-index') == '1')
            {
                // s1 now visible
                $('#h1').css('z-index', '2');
                $('#h2').css('z-index', '1');
                // put next frame (current hour + 1) in h2
                $(h[(ch + 1) % 24]).replaceAll('#h2 img');
            }
            else
            {
                // s2 now visible
                $('#h1').css('z-index', '1');
                $('#h2').css('z-index', '2');
                // put next frame (current hour + 1) in h1
                $(h[(ch + 1) % 24]).replaceAll('#h1 img');
            }
        }

        if (cm != pm)
        {
            // load a new minute image from server for later use
            m[mindex] = null;
            m[mindex] = loadimage('m', mindex);
            mindex = (mindex + 1) % 60;

            // swap z-index of two hour frames
            if ($('#m1').css('z-index') == '1')
            {
                // s1 now visible
                $('#m1').css('z-index', '2');
                $('#m2').css('z-index', '1');
                // put next frame (current minute + 1) in m2
                $(m[(cm + 1) % 60]).replaceAll('#m2 img');
            }
            else
            {
                // s2 now visible
                $('#m1').css('z-index', '1');
                $('#m2').css('z-index', '2');
                // put next frame (current minute + 1) in m1
                $(m[(cm + 1) % 60]).replaceAll('#m1 img');
            }
        }

        if (cs != ps)
        {

            /* load new second image 30s ahead.  Don't use the global sindex here because it could conflict with
                second images that are still preloading */
            i = (cs + 30) % 60;
            s[i] = null;
            s[i] = loadimage('s', i);

            // swap z-index of two second frames
            if ($('#s1').css('z-index') == '1')
            {
                // s1 now visible
                $('#s1').css('z-index', '2');
                $('#s2').css('z-index', '1');
                // put next frame (current second + 1) in s2
                $(s[(cs + 1) % 60]).replaceAll('#s2 img');
            }
            else
            {
                // s2 now visible
                $('#s1').css('z-index', '1');
                $('#s2').css('z-index', '2');
                // put next frame (current second + 1) in s1
                $(s[(cs + 1) % 60]).replaceAll('#s1 img');
            }
        }

        // store time for later comparison
        ph = ch;
        pm = cm;
        ps = cs;
    }

/*
    Enter/exit full screen mode
    Code (c) Craig Buckler, Director of OptimalWorks
    http://www.sitepoint.com/html5-full-screen-api/
*/
    function RunPrefixMethod(obj, method) {
        var pfx = ["webkit", "moz", "ms", "o", ""];

        var p = 0, m, t;
        while (p < pfx.length && !obj[m]) {
            m = method;
            if (pfx[p] == "") {
                m = m.substr(0,1).toLowerCase() + m.substr(1);
            }
            m = pfx[p] + m;
            t = typeof obj[m];
            if (t != "undefined") {
                pfx = [pfx[p]];
                return (t == "function" ? obj[m]() : obj[m]);
            }
            p++;
        }
    }

/*
    Make a guess at device model based on reported data.  Currently only supports iOS devices
    dw = device width, dh = device height
*/
    function guess_device(dw, dh)
    {
        var possdevs = '';
        var osver = parseFloat(window.agent.osmajor + '.' + window.agent.osminor + window.agent.ospatch);

        // Lookup table to enable physical screen dimension corrections.  All dimensions given in landscape mode
        var devfamilies = { "iPhone" : {
                                    "iphone1"     : { "los":1.0, "hos":3.13, "w":480,  "h":320  },
                                    "iphone3G"     : { "los":2.0, "hos":4.21, "w":480,  "h":320  },
                                    "iphone3GS" : { "los":3.0, "hos":6.13, "w":480,  "h":320  },
                                    "iphone4"     : { "los":4.0, "hos":7.02, "w":960,  "h":640  },
                                    "iphone4S"     : { "los":5.0, "hos":7.02, "w":960,  "h":640  },
                                    "iphone5"     : { "los":6.0, "hos":7.02, "w":1136, "h":640  },
                                    "iphone5C"     : { "los":7.0, "hos":7.02, "w":1136, "h":640  },
                                    "iphone5S"     : { "los":7.0, "hos":7.02, "w":1136, "h":640  }
                                    },
                        "iPad" :    {
                                    "ipad1"        : { "los":3.2, "hos":5.11, "w":1024, "h":768  },
                                    "ipad2"        : { "los":4.3, "hos":7.02, "w":1024, "h":768  },
                                    "ipad3"        : { "los":5.1, "hos":7.02, "w":2048, "h":1536 },
                                    "ipad4"        : { "los":6.0, "hos":7.02, "w":2048, "h":1536 },
                                    "ipadm"        : { "los":6.0, "hos":7.02, "w":1024, "h":768  }
                                    },
                        "iPod" :    {
                                    "ipod1"        : { "los":1.1,  "hos":3.13, "w":1024, "h":768  },
                                    "ipod2"        : { "los":2.11, "hos":4.21, "w":1024, "h":768  },
                                    "ipod3"        : { "los":3.12, "hos":5.11, "w":2048, "h":1536 },
                                    "ipod4"        : { "los":4.1,  "hos":6.13, "w":2048, "h":1536 },
                                    "ipod5"        : { "los":6.0,  "hos":7.02, "w":1024, "h":768  }
                                    }
                        };

        // devfamily is the set of members of a device family (e.g. iPhones)

        var devfamily = devfamilies[window.agent.devicefamily];

        // Consider each device in the family, looking for ones that match the detected OS version
        for (var dev in devfamily)
        {
            if ((osver >= devfamily[dev]['los'] && osver <= devfamily[dev]['hos'])
                && (devfamily[dev]['w'] == dw && devfamily[dev]['h'] == dh))
            {
                // A possible device.  Add to list of possibilities
                possdevs += dev + " ";
            }
        }
        return possdevs;
    }
