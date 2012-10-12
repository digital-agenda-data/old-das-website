   <div class="maincontent">
        <div> 
           <h2 style="float:left">
                <?php echo $content['title']; ?>
            </h2>
            <a href="http://ec.europa.eu/information_society/digital-agenda/scoreboard/index_en.htm" style="">
                <img src="images/hp-eda-logo.jpg" style="width:15em;float:right" />
            </a>
            <div style="clear:both" />
        </div>

     <div class="section">   
        <p style= "padding-right:2em"><?php echo $content['description']; ?></p>

        <div class="section-content" style= "margin-right:2em;height: 1" id="chart">
            <table style="width:100%;">
              <tr>
                <td><?php echo $content['facets']; ?></td>
                <td style="vertical-align:top">
<?php if(empty($content['error'])){ ?>
                    <a href="index.php?export=csv&scenario=<?php echo $content['scenario']; ?><?php echo '&'.$content['exportLinkParameter']; ?>">Export CSV</a>
                    <a href="index.php?export=rdf&scenario=<?php echo $content['scenario']; ?><?php echo '&'.$content['exportLinkParameter']; ?>">Export RDF</a><br>
                    <a href="#indicators">Definitions and scopes</a><br>
                    <!-- <a href="#scenarios">Further Exploration</a><br> -->
<?php } ?>
			    <!-- AddThis Button BEGIN -->
			    <div style="background-color:white;margin-top:0.6em;margin-bottom:0.6em;padding:0.3em;padding-bottom:0.1em;border:1px dotted #fefefe">
			    
			     <div class="addthis_toolbox_menu addthis_default_style">
			       <b style="float:left;margin-right:1em;margin-left:1em">Share:</b>
			       <a class="addthis_button_facebook" style="margin-right:1em"></a>
			       <a class="addthis_button_twitter" style="margin-right:1em"></a>
			       <a href="http://www.addthis.com/bookmark.php?v=250&username=xa-4cb32783294785a7" class="addthis_button_compact" 
				    style="margin-right:1em"></a>
			     </div>
			     <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4cb32783294785a7"></script>
			<div style="clear:both"></div>
			</div>
			     <!-- AddThis Button END --> 
                </td>
              </tr>
            </table>              
        </div>

        <div id="colX">
          <div id="col1_content" class="clearfix">
            <!-- add your content here -->
            <?php if(empty($content['error'])){ ?>
                <div id="swstack" class="section">
                    <?php echo $content['chart']; ?>
                </div>

              <div style="clear:both"></div>

                <div class="section-content" id="indicators">
                  <h2>Definitions and scopes:</h2>
                    <?php if($scenario != 4) echo $content['metadata']['list']; ?>
                    <p>
                        <a href="index.php?page=indicators">Consult the list of available indicators, their definition and sources.</a>            
                    </p>
                <div>
            <?php } else {?>
                <div id="swstack" class="section">
                </div>
                <div class="message">
                    <?php echo $content['error']; ?>
                <div>
            <?php } ?>

          </div> <!-- /#col1_content -->
        </div> <!-- /#col1 -->
      </div>
    </div>
  </div>
<?php $indSelection = ($content['export']['parameters']['indicators[]']) ? "&indicators[]=".$content['export']['parameters']['indicators[]'] : ""; ?>
  <div style="margin:1em 1em 2em 0em;" id="scenarios">
    <h4>To swap charts , click one of the icons:</h4>
    <p>
        <a href="index.php" alt="Back Home" style="float:top">
            <img src = "images/home.png" style="width:7em; border:0px;"/>
        </a>
        <a href="index.php?scenario=1<?php echo $indSelection ?>" alt="Barchart Scenario">
            <img src = "images/barchart.png" style="width:7em; border:0px;"/>
        </a>
        <a href="index.php?scenario=2<?php echo $indSelection ?>" alt="TimeLine-chart Scenario">
            <img src = "images/timeline.png" style="width:7em; border:0px;"/>
        </a>
        <a href="index.php?scenario=3<?php echo $indSelection ?>" alt="Scatter plot">
            <img src = "images/scatterplott.png" style="width:7em; border:0px;"/>
        </a>
        <a href="index.php?scenario=4" alt="Country profile">
            <img src = "images/countryprofile.png" style="width:7em; border:0px;"/>
        </a>
        <a href="http://ec.europa.eu/information_society/digital-agenda/scoreboard/index_en.htm">
            <img src="images/hp-eda-logo.jpg" style="float:right;"/>
        </a>
    </p>
  </div>
  <div style="clear:both"></div>
  <div id="printcontainer" style="width: 1000px; height: 600px; display:none"/>
<?php //
      //  <div id="col3">
      //    <div id="col3_content" class="clearfix sidecontent">
      //      <div class="section">
      //        <?php echo $content['facets']; 
      //      </div>
      //    </div> <!-- /#col3_content -->
      //    <!-- IE Column Clearing -->
      //    <div id="ie_clearing"> &#160; 
      //    </div>
      //  </div> <!-- /#col3 --> ?>

