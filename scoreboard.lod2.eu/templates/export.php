<div id="page">
<div>
    <h1 style="float:left">Exploring the data on your own</h1>
        <a href="http://ec.europa.eu/digital-agenda/en/scoreboard" style="">
            <img src="images/hp-eda-logo.jpg" style="width:15em;float:right;padding-top:1.775em;border-style: none" />
        </a>
<div style="clear:both" />
      </div>

        <div class="maincontent">
            <div class="section">
                <div class="section-content" style= "margin-right:2em; padding:1em; background-color:#E1F0F7">
                    In addition to browsing the data with the help of the <a href="index.php">four visualisation tools</a> 
                    (where you can download the data used to create the chart in CSV and RDF), you are able to download the 
                    whole database in XLS, CSV and SQL. In the near future the complete database will be provided as a graph in 
                    RDF for download. Furthermore a Linked Data -/ SPARQL endpoint to explore the data directly will be created.
                    <br><br>
                    The database with the selected indicators of the Digital Agenda Scoreboard is made of three tables:
                    <ul>
                      <li> a <b>data table</b>, with codes for the indicators, countries, years and values</li>
                      <li> an <b>indicators table</b>, with labels for indicators' codes, definition and scope, and a source code</li>
                      <li> a <b>sources table</b>, with details about sources and links to more methodological information</li>
                    </ul>
                    The codes allow the creation of relations between the tables.                
                </div>
                <h3>XLS and CSV download</h3>
                <p>
                    The following xls file contains all the three tables:
                    <ul>
                        <li><a href="data/digital_scoreboard_04_june_2012.xls">digital_scoreboard_04_june_2012.xls</a></li>
                    </ul>

                    There is a csv file for each one of the basic tables of the database:
                    <ul>
                        <li><a href="data/indicators.csv">indicators_table.csv</a></li>
                        <li><a href="data/data.csv">data_table.csv</a></li>
                        <li><a href="data/sources.csv">sources_table.csv</a></li>
                    </ul>
                </p>
                <h3>SQL download</h3>
                <p>
                    There is a sql file for each one of the basic tables of the database: 
                    <ul>
                        <li><a href="data/indicators.sql">indicators_table.sql</a></li>
                        <li><a href="data/data.sql">data_table.sql</a></li>
                        <li><a href="data/sources.sql">sources_table.sql</a></li>
                    </ul>
                </p>
                <h3>RDF model</h3>
                <p>
                    The digital scoreboard data is accessible as linked open data in RDF following the <a href="http://publishing-statistical-data.googlecode.com/svn/trunk/specs/src/main/html/cube.html"> DataCube vocabulary</a>.
                    DataCube standarizes the publishing of statistical information on the semantic web. It bases itself on 
                    other standards like SDMX.
                </p>
                <p>
                    In the DataCube vocabulary, an observation is a valuepoint in the datacube. Here an observation represents 
                    the value for one indicator for a country and a year. The indicator is the measure while the country and 
                    the year are the dimensions in the datacube.
                    If an observation is not present for a combination the data has not been collected. 
                    Each observation comes with additional information such as the unit.
                </p>
                <p>
                    There are rdf files in different notations containing the full DataCube:
                    <ul>
                        <li><a href="data/scoreboardDataCube.rdf">scoreboardDataCube.rdf</a> RDF Notation RDF/XML (approx. 27 MB)</li>
                        <li><a href="data/scoreboardDataCube.ttl">scoreboardDataCube.ttl</a> RDF Notation Turtle (approx. 8 MB)</li>
                        <li><a href="data/scoreboardDataCube.nt">scoreboardDataCube.nt</a> RDF Notation NT (approx. 23 MB)</li>
                    </ul>

                </p>
                <h3>SPARQL Endpoint</h3>
                <p>
                    The published digital scoreboard RDF data can be queried online via the 
                    <a href="http://digital-agenda-data.eu/ontowiki/index.php/queries/editor?m=http%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2F">SPARQL editor</a> (human readable) or 
                    <a href="http://digital-agenda-data.eu/sparql/">SPARQL endpoint </a> (for machines regarding the <a href="http://www.w3.org/TR/rdf-sparql-protocol/">SPARQL protocol for RDF</a>).
                    This SPARQL endpoint offers a public service to the statistical data allowing anyone to build applications based on the most recent
                    data.
                </p>
                <p>
                    Some sample queries are:
                    <ul>
                        <li>Select 20 observations for the indicator "Total number of fixed broadband lines" 
                            [<a href="http://digital-agenda-data.eu/ontowiki/index.php/queries/editor?m=http%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2F&immediate=true&query=SELECT%20DISTINCT%20%3Fobservation%20%3Fvalue%0AFROM%20%3Chttp%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2F%3E%20%0AWHERE%20%7B%20%0A%20%20%3Fobservation%20a%20%3Chttp%3A%2F%2Fpurl.org%2Flinked-data%2Fcube%23Observation%3E%20.%0A%20%20%3Fobservation%20prop%3Aindicator%20ind%3Abb_lines_TOTAL_FBB_nbr_lines%20.%0A%20%20%3Fobservation%20prop%3Avalue%20%3Fvalue%0A%7D%0ALIMIT%2020">try it</a>] </li>
                        <li>Select all observations for the indicator "Total number of fixed broadband lines" for the EU27 average 
                            [<a href="http://digital-agenda-data.eu/ontowiki/index.php/queries/editor?m=http%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2F&immediate=true&query=SELECT%20DISTINCT%20%3Fobservation%20%3Fvalue%0AFROM%20%3Chttp%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2F%3E%20%0AWHERE%20%7B%20%0A%20%20%3Fobservation%20a%20%3Chttp%3A%2F%2Fpurl.org%2Flinked-data%2Fcube%23Observation%3E%20.%0A%20%20%3Fobservation%20prop%3Aindicator%20ind%3Abb_lines_TOTAL_FBB_nbr_lines%20.%0A%20%20%3Fobservation%20prop%3Avalue%20%3Fvalue%20.%0A%20%20%3Fobservation%20prop%3Acountry%20%3Chttp%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2Fcountry%2FEuropean%2BUnion%2B-%2B27%2Bcountries%3E%20.%20%20%0A%7D%0ALIMIT%2020%E2%80%8B">try it</a>] </li>
                        <li>Select all observations for indicator "Total number of fixed broadband lines" and 
                            indicator "New entrants' share in fixed broadband lines" for the year 2010 
                            [<a href="http://digital-agenda-data.eu/ontowiki/index.php/queries/editor?m=http%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2F&immediate=true&query=SELECT%20DISTINCT%20%3Fobservation%20%3Fvalue%0AFROM%20%3Chttp%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2F%3E%20%0AWHERE%20%7B%20%0A%20%20%7B%0A%20%20%20%20%3Fobservation%20a%20%3Chttp%3A%2F%2Fpurl.org%2Flinked-data%2Fcube%23Observation%3E%20.%0A%20%20%20%20%3Fobservation%20prop%3Aindicator%20ind%3Abb_lines_TOTAL_FBB_nbr_lines%20.%0A%20%20%20%20%3Fobservation%20prop%3Avalue%20%3Fvalue%20.%0A%20%20%20%20%3Fobservation%20prop%3Ayear%20%3Chttp%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2Fyear%2F2010%3E%20.%0A%20%20%7D%0A%20%20UNION%20%0A%20%20%7B%0A%20%20%20%20%3Fobservation%20a%20%3Chttp%3A%2F%2Fpurl.org%2Flinked-data%2Fcube%23Observation%3E%20.%0A%20%20%20%20%3Fobservation%20prop%3Aindicator%20ind%3Abb_ne_TOTAL_FBB__lines%20.%0A%20%20%20%20%3Fobservation%20prop%3Avalue%20%3Fvalue%20.%0A%20%20%20%20%3Fobservation%20prop%3Ayear%20%3Chttp%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2Fyear%2F2010%3E%20.%0A%20%20%7D%0A%7D%0ALIMIT%20100%0A%E2%80%8B">try it</a>] </li>
                        <li>Select all observations for the country Belgium for the year 2010 
                            [<a href="http://digital-agenda-data.eu/ontowiki/index.php/queries/editor?m=http%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2F&immediate=true&query=SELECT%20%3Fobservation%20%3Fvalue%0AFROM%20%3Chttp%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2F%3E%20%0AWHERE%20%7B%20%0A%20%20%3Fobservation%20a%20%3Chttp%3A%2F%2Fpurl.org%2Flinked-data%2Fcube%23Observation%3E%20.%0A%20%20%3Fobservation%20prop%3Ayear%20%3Chttp%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2Fyear%2F2010%3E%20.%20%0A%20%20%3Fobservation%20prop%3Avalue%20%3Fvalue%20.%20%0A%20%20%3Fobservation%20prop%3Acountry%20%3Chttp%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2Fcountry%2FBelgium%3E%20.%20%20%0A%7D%0ALIMIT%20100%E2%80%8B">try it</a>] </li>
                    </ul>
                    The data can also be browsed via the <a href="http://digital-agenda-data.eu/ontowiki/index.php?m=http%3A%2F%2Fdata.lod2.eu%2Fscoreboard%2F">OntoWiki tool</a>.
                </p>
            </div>
        </div>
    </div>
      <div style="margin:1em 1em 2em 0em;" id="scenarios">
        <h4>To explore the data in scoreboard, click one of the icons:</h4>
        <p>
        <a href="index.php" alt="Back Home" style="float:top"><img src = "images/home.png" style="width:7em; border:0px;"/></a>
        <a href="index.php?scenario=1" alt="Barchart Scenario"><img src = "images/barchart.png" style="width:7em; border:0px;"/></a>
        <a href="index.php?scenario=2" alt="TimeLine-chart Scenario"><img src = "images/timeline.png" style="width:7em; border:0px;"/></a>
        <a href="index.php?scenario=3" alt="Scatter plot"><img src = "images/scatterplott.png" style="width:7em; border:0px;"/></a>
        <a href="index.php?scenario=4" alt="Country profile"><img src = "images/countryprofile.png" style="width:7em; border:0px;"/></a>
        <a href="http://ec.europa.eu/digital-agenda/en/scoreboard"><img src="images/hp-eda-logo.jpg" style="float:right;border-style: none"/></a>
        </p>
      </div>
</div>

