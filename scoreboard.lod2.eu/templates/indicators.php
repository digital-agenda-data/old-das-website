<div id="page">
    <div>
        <h1 style="float:left">LIST OF the SELECTED indicators</h1>
        <a href="http://ec.europa.eu/digital-agenda/en/scoreboard" style="">
            <img src="images/hp-eda-logo.jpg" style="width:15em;float:right;padding-top:1.775em;border-style: none" />
        </a>
        <div style="clear:both" />
    </div>
        <div class="maincontent">
            <p> 
		European Commission services have selected about a hundred of indicators, divided into thematic groups, which illustrate some key dimensions of the European information society. These indicators allow a comparison of progress across countries as well as over time.<br><br>
                The following table provides methodological information about the source, the scope and the definition of each indicator. For more details, click on the links in the table or explore the whole database.
            </p>
            <div class="section">
                <div class="section-content" style= "margin-right:2em; padding:1em; background-color:#E1F0F7">
                    <?php foreach (array_keys($content["data"]) as $group) { ?>
                        <a href="#<?php echo md5($group)?>"><?php echo $group ?></a><br>
                    <?php } ?>
                </div>
                <div style="padding-right:2em;margin-top:2em;">
                    <?php echo $content['table'] ?>
                </div>
            </div>
        </div>
    </div>
      <div style="margin:1em 1em 2em 0em;" id="scenarios">
        <h4>To explore the data in scoreboard, click one of the following icons:</h4>
        <p>
        <a href="index.php" alt="Back Home" style="float:top"><img src = "images/home.png" style="width:7em; border:0px;"/></a>
        <a href="index.php?scenario=1" alt="Barchart Scenario"><img src = "images/barchart.png" style="width:7em; border:0px;"/></a>
        <a href="index.php?scenario=2" alt="TimeLine-chart Scenario"><img src = "images/timeline.png" style="width:7em; border:0px;"/></a>
        <a href="index.php?scenario=3" alt="Scatter plot"><img src = "images/scatterplott.png" style="width:7em; border:0px;"/></a>
        <a href="index.php?scenario=4" alt="Country profile"><img src = "images/countryprofile.png" style="width:7em; border:0px;"/></a>
        <a href="http://ec.europa.eu/digital-agenda/en/scoreboard"><img src="images/hp-eda-logo.jpg" style="float:right;"/></a>
        </p>
      </div>

</div>
