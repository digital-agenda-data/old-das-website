<?php
require "Connector.php";
/*
 *	@author			Tom-Michael Hesse <tommichael.hesse@googlemail.com>
 *	@author			Michael Martin <martin@informatik.uni-leipzig.de>
 *	@copyright		University of Leipzig AKSW
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@since			05.05.2011
*/
class Scoreboard {
    
    private $db;

	/**
	 *	Constructor of Class
     *  - Creating the connector
	 */        
    function __construct() {        
        $this->db = new Connector();
    }
    
    public function getIndicators() {
        require_once ("scenarios/Scenario.php");
        $scenario = new Scenario($this->db);
        return $scenario->getMetadataForIndicators(array(),true);
    }
    
    public function getContent($scenarioSelection, $outputFormat = "html") {

        $content = array();

        switch($scenarioSelection) {
            case 1: //bar graph for one indicator over countries/time (one year)
                    //preselected: the first row for each option, all countries
                require_once ("scenarios/BarChart.php");
                $scenario = new BarChart($this->db); 
                $title = "1. Analyse one indicator and compare countries";
                $description = "This bar-chart allows you to select one indicator, one year, and see which countries are leading the league. You can also check if the ranking of countries has changed over the last few years. Countries are always ordered according to their score. You can also select or de-select countries in order to visualise only those you are interested in.";

                //selectIndicator
                $indicators = array();
                if( empty( $_GET['indicators'] ) ) {
                    $indicators = array("preselect"=>"preselect");
                } else {
                    $indicators = $_GET['indicators'];
                }
                $scenario->setConfigurationElement("indicators", $indicators);

                if (!empty($_GET['countries'])) {
                    $scenario->setConfigurationElement("countries", $_GET['countries']);
                }

                //select Year
                if (!empty($_GET['year'])) {
                    $scenario->setConfigurationElement("year", $_GET['year']);
                }


            break;

            case 2: //line graph for one indicator over countries/time (2004 - 2010)
                    //preselected: the first row for each option, six countries
                require_once ("scenarios/TimeLine.php");
                $scenario = new TimeLine($this->db); 
                $title = "2. See the evolution of an indicator";
                $description = "Time-line charts allow you to visualise trends for one country and to compare it with some others, as well as with the European average trend of the selected indicator.";
                //select Indicator
                $indicators = array();
                if( empty( $_GET['indicators'] ) ) {
                    $indicators = array("preselect"=>"preselect");
                } else {
                    $indicators = $_GET['indicators'];
                }
                $scenario->setConfigurationElement("indicators", $indicators);

                //select Year
                if (!empty($_GET['year'])) {
                    $scenario->setConfigurationElement("year", $_GET['year']);
                }

                //select Country
                if (!empty($_GET['countries'])) {
                    $scenario->setConfigurationElement("countries", $_GET['countries']);
                }


            break;

            case 3: //two indicators for the selected countries in one year
                    //preselected: two indicators and three countries, the first year
                require_once ("scenarios/ScatterPlot.php");
                $scenario = new ScatterPlot($this->db); 
                $title = "3. Compare two indicators";
                $description = "Scatter-plot is a kind of chart that compares two indicators, represented on the x (horizontal) and y (vertical) axis. Each country appears as a point whose coordinates are its values on the two selected indicators.";
                //Select Indicator
                $indicators = array();
                if( empty( $_GET['indicators'] ) ) {
                    $indicators = array("preselect"=>"preselect");
                } else {
                    $indicators = $_GET['indicators'];
                }
                $scenario->setConfigurationElement("indicators", $indicators);

                //Select SecondIndicator

                if( empty( $_GET['indicators2'] ) ) {
                    $indicators2 = array("preselect"=>"preselect");
                } else {
                    $indicators2 = $_GET['indicators2'];
                }
                $scenario->setConfigurationElement("indicators2", $indicators2);

                //Select Year
                if (!empty($_GET['year'])) {
                    $scenario->setConfigurationElement("year", $_GET['year']);
                }


            break;

            case 4: //the country profile for one country over the indicators in one year
                    //preselected: the row for each option, six indicators
                require_once ("scenarios/CountryProfile.php");
                $scenario = new CountryProfile($this->db); 
                $title = "4. See a country profile";
                $description = "This chart presents the values of a country for a group of indicators (or all of them), and compares them with the European average. It allows to see if a country is performing above/under EU average and more or less near to the maximum/minimum observed values (corresponding respectively to +1/-1 standardised values).  To visualise the original values (not standardised) of the country on the selected group of indicators, look at the table under the chart, presenting these values into four different columns according to the quartile they belong to.";

  
                //Select indicator group
                $indicatorsGroup = array();
                if( empty( $_GET['indicatorgroup'] ) ) {
                    $indicatorGroup = array("preselect"=>"preselect");
                } else {
                    $indicatorGroup = $_GET['indicatorgroup'];
                }
                $scenario->setConfigurationElement("indicatorgroup", $indicatorGroup);
                
                //Select Year
                if (!empty($_GET['year'])) {
                    $scenario->setConfigurationElement("year", $_GET['year']);
                }
                //select Country
                if (!empty($_GET['countries'])) {
                    $scenario->setConfigurationElement("countries", $_GET['countries']);
                }
            break;

            case 5: //Full DataCube Export
                require_once ("scenarios/FullDataCubeExporter.php");
                $scenario = new FullDataCubeExporter($this->db); 

                #$indicatorsGroup = array("preselect"=>"preselect");
                #$scenario->setConfigurationElement("indicators", $indicatorsGroup);

                $title = "Exporting the DataCube";
                $description = "";
                $outputFormat = "rdf";
            break;
            default: //StartPage
            break;
        }

        //$facets = $scenario->getFacets();
        //$chart = $scenario->getChart();

        //$content['facets'] = $facets;
        //$content['chart'] = $chart;

 #       try {
    if ($outputFormat == "html") {
        $content = $scenario->getContent();
        $content['exportLinkParameter'] = "";
        $elements=array();
        if(!empty($content['export']['parameters'])) {
            foreach ($content['export']['parameters'] as $key => $value) {
                #BugFIX with doubled disposition headers
                if($key != "countries[]") {
                    $value = urlencode($value);
                }
                    $elements[] = $key."=".$value;
            }
            $content['exportLinkParameter'] = implode ("&", $elements);
        }
    } else {
        $content = $scenario->getExport($outputFormat);
    }
#        } catch (Exception $e) {
#	    var_dump($e);
#            if (!empty($_GET['countries'])) {
#                unset($_GET['countries']);
#            }
#            if (!empty($_GET['indicators'])) {
#                unset($_GET['indicators']);
#            }
#            if (!empty($_GET['indicators2'])) {
#                unset($_GET['indicators2']);
#            }
#            if (!empty($_GET['year'])) {
#                unset($_GET['year']);
#            }
#	    var_dump($content);
#            #$content = $this->getContent($scenarioSelection, $outputFormat);
#            $content['error'] = $e->getMessage();
#        }
        $content['title'] = $title;
        $content['description'] = $description;
        $content['scenario'] = $scenarioSelection;

        return $content;
    }

   public function getPrintCharts () { 
        require_once ("scenarios/CountryProfile.php");
        $scenario = new CountryProfile($this->db); 

                $title = "4. See a country profile";
                //Select Year
                if (!empty($_GET['year'])) {
                    $scenario->setConfigurationElement("year", $_GET['year']);
                }
                //select Country
                if (!empty($_GET['countries'])) {
                    $scenario->setConfigurationElement("countries", $_GET['countries']);
                }

        $scenario->setConfigurationElement("mode", "printCharts");

        return $scenario->getPrintCharts();
   }

}
?>
