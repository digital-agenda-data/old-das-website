<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <title>Visualization of Digital Agenda Scoreboard Indicators</title>
    <script src="./js/jquery.min.js" type="text/javascript"></script>
    <script src="./js/highcharts.js" type="text/javascript"></script>

    <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-23678295-1']);
    _gaq.push(['_trackPageview']);

    (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

    function toggle(obj) {
	    var el = document.getElementById(obj);
	    if ( el.style.display != 'none' ) {
		    el.style.display = 'none';
	    }
	    else {
		    el.style.display = '';
	    }
    }
	countrycolortable = {
        'CZ':'#1C3FFD',
        'CY':'#FF5400',
        'BG':'#21FF00',
        'EL':'#6A07B0',
        'PL':'#FF1D23',
        'RO':'#1B76FF',
        'BE':'#FD8245',
        'IT':'#19BC01',
        'LT':'#9A24ED',
        'HU':'#D40D12',
        'FR':'#15A9FA',
        'EU27':'#94090D',
        'SK':'#7DC30F',
        'LV':'#D59AFE',
        'EE':'#0EEAFF',
        'DE':'#FFC600',
        'SI':'#648E23',
        'UK':'#D000C4',
        'IE':'#ADF0F6',
        'PT':'#FFFC00',
        'SE':'#436B06',
        'NL':'#FF40F4',
        'MT':'#35478C',
        'FI':'#7FB2F0',
        'ES':'#044C29',
        'DK':'#45BF55',
        'LU':'#D50356',
        'NO':'#F70A9B',
        'AT':'#AABC66',
        'TR':'#9900AB',
        'IS':'#662293',
        'HR':'#33EED2'};



    function countrycolor(n) {
        if (countrycolortable[n] == null) {
	        return '#1C3FFD'
        } else { 
	        return countrycolortable[n];
        };
    };

    function myclone1(o) {
	    return eval('(' + JSON.stringify(o) + ')');
    };
    function myclone(o) {
	    return eval(uneval(o));
    };



    </script>

    <script src="./js/modules/exporting.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="./css/layout.css" media="all" title="Standard" />
    <link rel="stylesheet" type="text/css" href="./css/content.css" media="all" title="Standard" />
    <link rel="stylesheet" type="text/css" href="./css/scoreboard.css" />
    <!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="http://lod2.eu/extensions/site/sites/lod2/css/patches/ie6.css" media="all" /><![endif]-->
    </head>
<body>
  <div class="page_margins">
    <div class="page" about="http://lod2.eu/Welcome">
      <div id="header" style= "float:right; padding-right:2em">
      </div>
      <div id="main">
