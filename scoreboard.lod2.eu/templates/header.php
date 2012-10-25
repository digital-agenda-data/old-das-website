<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="pragma" content="no-cache" />
    <title>Visualization of Digital Agenda Scoreboard Indicators</title>

    <script type="text/javascript" src="./js/jquery.min.js"></script>
    <script type="text/javascript" src="./js/highcharts.js"></script>
    <script type="text/javascript" src="./js/modules/exporting.js"></script>

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

		<link rel="stylesheet" type="text/css" href="https://ec.europa.eu/digital-agenda/sites/digital-agenda/themes/dg_connect/wel/template-2012/stylesheets/ec.css" media="all" />
		<link rel="stylesheet" type="text/css" href="https://ec.europa.eu/digital-agenda/sites/digital-agenda/files/less/sites/digital-agenda/themes/dg_connect/css/less/ec_default.css" media="all" />
		<link rel="stylesheet" type="text/css" href="https://ec.europa.eu/digital-agenda/sites/digital-agenda/files/less/sites/digital-agenda/themes/dg_connect/css/less/hack-ec.css" media="all" />
    <link rel="stylesheet" type="text/css" href="./css/layout.css" media="all" title="Standard" />
    <link rel="stylesheet" type="text/css" href="./css/content.css" media="all" title="Standard" />
    <link rel="stylesheet" type="text/css" href="./css/scoreboard.css" />
    <!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="http://lod2.eu/extensions/site/sites/lod2/css/patches/ie6.css" media="all" /><![endif]-->
  </head>

	<body>
		<div id="layout" class="layout">

      <!-- header -->
			<div id="header">
				<img alt="European Commission logo" id="banner-flag" src="https://ec.europa.eu/digital-agenda/sites/digital-agenda/themes/dg_connect/wel/template-2012/images/logo/logo_en.gif" />
				<p id="banner-title-text">Digital Agenda for Europe</p>
				<span class="title-en" id="banner-image-title"></span> 
				<span id="banner-image-right"></span>
				<p class="off-screen">Accessibility tools</p>
				<ul class="reset-list" id="accessibility-menu">
					<li><a accesskey="1" href="#content">Go to content</a></li>
				</ul>
				<p class="off-screen">Service tools</p>
				<ul class="reset-list" id="services">
					<li><a class="first" href="https://ec.europa.eu/digital-agenda/welcome-digital-agenda">About</a></li>
					<li><a accesskey="3" href="https://ec.europa.eu/digital-agenda/contact">Contact</a></li>
					<li><a accesskey="2" href="http://ec.europa.eu/geninfo/legal_notices_en.htm">Legal notice</a></li>
					<li><a accesskey="4" href="https://ec.europa.eu/digital-agenda/search">Search</a></li>
					<li><a href="https://ec.europa.eu/digital-agenda/en/ecas" class="">Login</a></li>
				</ul>
			</div>

			<!-- breadcrumbs -->
			<div id="path">
				<p class="off-screen">Navigation path</p>
				<ul class="reset-list">
					<li class="first"><a href="http://ec.europa.eu/index_en.htm">European Commission</a></li>
					<li><a href="https://ec.europa.eu/digital-agenda/en">Digital Agenda for Europe</a></li>
					<li><a href="https://ec.europa.eu/digital-agenda/en/our-targets" title="Our Targets" class="">Our Targets</a></li>    
				</ul>
			</div>

			<!-- global navigation menu -->
			<div class="region region-featured">
				<div id="block-menu-block-dga-level-0" class="block block-menu-block">
					<div class="content">
						<div class="menu-block-wrapper menu-block-dga-level-0 menu-name-main-menu parent-mlid-0 menu-level-1">
							<ul class="nav nav-pills">
								<li class="first leaf menu-mlid-745"><a href="https://ec.europa.eu/digital-agenda/en" title="" class="">Home</a></li>
								<li class="expanded active-trail menu-mlid-1640"><a href="https://ec.europa.eu/digital-agenda/en/our-targets" title="Our Targets" class="active-trail">Our Targets</a>
									<ul class="dropdown-menu">
										<li class="first leaf has-children active-trail active menu-mlid-953"><a href="https://ec.europa.eu/digital-agenda/en/scoreboard" class="active-trail active">Scoreboard</a></li>
										<li class="leaf has-children menu-mlid-1641"><a href="https://ec.europa.eu/digital-agenda/en/our-targets/pillar-i-digital-single-market" title="Pillar I: Digital Single Market" class="">Pillar I: Digital Single Market</a></li>
										<li class="leaf has-children menu-mlid-1663"><a href="https://ec.europa.eu/digital-agenda/en/our-targets/pillar-ii-interoperability-standards" title="Pillar II: Interoperability &amp; Standards" class="">Pillar II: Interoperability &amp; Standards</a></li>
										<li class="leaf has-children menu-mlid-1671"><a href="https://ec.europa.eu/digital-agenda/en/our-targets/pillar-iii-trust-security" title="Pillar III: Trust &amp; Security" class="">Pillar III: Trust &amp; Security</a></li>
										<li class="leaf has-children menu-mlid-1686"><a href="https://ec.europa.eu/digital-agenda/en/our-targets/pillar-iv-fast-and-ultra-fast-internet-access" title="Pillar IV: Fast and ultra-fast Internet access" class="">Pillar IV: Fast and ultra-fast Internet access</a></li>
										<li class="leaf has-children menu-mlid-1695"><a href="https://ec.europa.eu/digital-agenda/en/our-targets/pillar-v-research-and-innovation" title="Pillar V: Research and innovation" class="">Pillar V: Research and innovation</a></li>
										<li class="leaf has-children menu-mlid-1703"><a href="https://ec.europa.eu/digital-agenda/en/our-targets/pillar-vi-enhancing-digital-literacy-skills-and-inclusion" title="Pillar VI: Enhancing digital literacy, skills and inclusion" class="">Pillar VI: Enhancing digital literacy, skills and inclusion</a></li>
										<li class="leaf has-children menu-mlid-1716"><a href="https://ec.europa.eu/digital-agenda/en/our-targets/pillar-vii-ict-enabled-benefits-eu-society" title="Pillar VII: ICT-enabled benefits for EU society" class="">Pillar VII: ICT-enabled benefits for EU society</a></li>
										<li class="last leaf has-children menu-mlid-1745"><a href="https://ec.europa.eu/digital-agenda/en/our-targets/international" title="International" class="">International</a></li>
									</ul>
							</li>
							<li class="expanded menu-mlid-697"><a href="https://ec.europa.eu/digital-agenda/en/digital-life" title="Digital Life" class="">Digital Life</a>
								<ul class="dropdown-menu">
									<li class="first leaf has-children menu-mlid-704"><a href="https://ec.europa.eu/digital-agenda/en/digital-life/get-involved" title="Get involved" class="">Get involved</a></li>
									<li class="leaf has-children menu-mlid-700"><a href="https://ec.europa.eu/digital-agenda/en/digital-life/environment" title="Environment" class="">Environment</a></li>
									<li class="leaf has-children menu-mlid-701"><a href="https://ec.europa.eu/digital-agenda/en/digital-life/mobility" title="Mobility" class="">Mobility</a></li>
									<li class="leaf has-children menu-mlid-702"><a href="https://ec.europa.eu/digital-agenda/en/digital-life/health" title="Health" class="">Health</a></li>
									<li class="leaf has-children menu-mlid-699"><a href="https://ec.europa.eu/digital-agenda/en/digital-life/government" title="Government" class="">Government</a></li>
									<li class="leaf has-children menu-mlid-1305"><a href="https://ec.europa.eu/digital-agenda/en/digital-life/education" title="Education" class="">Education</a></li>
									<li class="last leaf has-children menu-mlid-703"><a href="https://ec.europa.eu/digital-agenda/en/digital-life/living-line" title="Living on line" class="">Living on line</a></li>
								</ul>
							</li>
							<li class="expanded menu-mlid-715"><a href="https://ec.europa.eu/digital-agenda/en/business-funding" title="Business &amp; Funding" class="">Business &amp; Funding</a>
								<ul class="dropdown-menu">
									<li class="first leaf has-children menu-mlid-698"><a href="https://ec.europa.eu/digital-agenda/en/business-funding/funding-opportunities" title="Funding Opportunities" class="">Funding Opportunities</a></li>
									<li class="last leaf has-children menu-mlid-719"><a href="https://ec.europa.eu/digital-agenda/en/business-funding/web-business" title="The Web &amp; Business" class="">The Web &amp; Business</a></li>
								</ul>
							</li>
							<li class="expanded menu-mlid-720"><a href="https://ec.europa.eu/digital-agenda/en/science-and-technology" title="Science and Technology" class="">Science and Technology</a>
								<ul class="dropdown-menu">
									<li class="first leaf has-children menu-mlid-721"><a href="https://ec.europa.eu/digital-agenda/en/science-and-technology/robotics" title="Robotics" class="">Robotics</a></li>
									<li class="leaf has-children menu-mlid-722"><a href="https://ec.europa.eu/digital-agenda/en/science-and-technology/components-systems" title="Components &amp; Systems" class="">Components &amp; Systems</a></li>
									<li class="leaf has-children menu-mlid-723"><a href="https://ec.europa.eu/digital-agenda/en/science-and-technology/emerging-technologies" title="Emerging Technologies" class="">Emerging Technologies</a></li>
									<li class="last leaf has-children menu-mlid-737"><a href="https://ec.europa.eu/digital-agenda/en/science-and-technology/language-technologies" title="Language Technologies" class="">Language Technologies</a></li>
								</ul>
							</li>
							<li class="expanded menu-mlid-725"><a href="https://ec.europa.eu/digital-agenda/en/telecoms-internet" title="Telecoms &amp; the Internet" class="">Telecoms &amp; the Internet</a>
								<ul class="dropdown-menu">
									<li class="first leaf has-children menu-mlid-716"><a href="https://ec.europa.eu/digital-agenda/en/telecoms-internet/telecoms" title="Telecoms" class="">Telecoms</a></li>
									<li class="leaf has-children menu-mlid-955"><a href="https://ec.europa.eu/digital-agenda/en/telecoms-internet/cloud" title="Cloud" class="">Cloud</a></li>
									<li class="leaf has-children menu-mlid-730"><a href="https://ec.europa.eu/digital-agenda/en/telecoms-internet/software-services" title="Software &amp; Services" class="">Software &amp; Services</a></li>
									<li class="leaf has-children menu-mlid-731"><a href="https://ec.europa.eu/digital-agenda/en/telecoms-internet/internet" title="Internet" class="">Internet</a></li>
									<li class="leaf has-children menu-mlid-726"><a href="https://ec.europa.eu/digital-agenda/en/telecoms-internet/cyber-security" title="Cyber-Security" class="">Cyber-Security</a></li>
									<li class="last leaf has-children menu-mlid-728"><a href="https://ec.europa.eu/digital-agenda/en/telecoms-internet/roaming" title="Roaming" class="">Roaming</a></li>
								</ul>
							</li>
							<li class="expanded menu-mlid-733"><a href="https://ec.europa.eu/digital-agenda/en/creativity-media" title="Creativity &amp; media" class="">Creativity &amp; media</a>
								<ul class="dropdown-menu">
									<li class="first leaf has-children menu-mlid-736"><a href="https://ec.europa.eu/digital-agenda/en/creativity-media/cultural-heritage" title="Cultural Heritage" class="">Cultural Heritage</a></li>
									<li class="last leaf has-children menu-mlid-734"><a href="https://ec.europa.eu/digital-agenda/en/creativity-media/media-policies" title="Media Policies" class="">Media Policies</a></li>
								</ul>
							</li>
							<li class="last expanded menu-mlid-739"><a href="https://ec.europa.eu/digital-agenda/en/around-world" title="Around the World" class="">Around the World</a>
								<ul class="dropdown-menu">
									<li class="first leaf has-children menu-mlid-740"><a href="https://ec.europa.eu/digital-agenda/en/around-world/european-union" title="European Union" class="">European Union</a></li>
									<li class="last leaf has-children menu-mlid-742"><a href="https://ec.europa.eu/digital-agenda/en/around-world/world" title="World" class="">World</a></li>
								</ul>
							</li>
							</ul>
						</div>
					</div>
				</div>  
			</div>
			<!--  end global navigation menu -->

			<div class="layout-wrapper">
		  	<div class="page" about="http://lod2.eu/Welcome">

		
