<?php
require_once ("Scenario.php");

class CountryProfile extends Scenario
{
    
    //containerId
    private $containerid = "container";
    private $graphclass  = "";
    private $printtable  = "showtable"; 
    private $exporting   = "";

    //ToDo getContent auftrennen
    public function getFacets() {
    }

    //ToDo getContent auftrennen
    public function getChart() {
    }

    //ToDo getContent auftrennen
    public function getExport($exportFormat) {

        $export = array();
        switch ($exportFormat){
            case "csv":
                $hack = $this->getContent();
                $export = $hack['export'];
            break;
            case "rdf":
                $hack = $this->getContent();
                $export = $hack['export'];
            break;
        }


        return $export;

    }

    //ToDo getContent auftrennen
    public function getContent() {
        $returnValue = array();
        $export = array();

        $excludeIndicatorsList  = array (
                array (  
                    'variable'=> "bb_lines" ,
    				'brkdown' => "TOTAL_FBB" ,
    				'unit'    => "nbr_lines"),
                array (  
                    'variable'=> "tel_rev" ,
    				'brkdown' => "TOTAL_TELECOM" ,
    				'unit'    => "million_euro"),
                array (  
                    'variable'=> "tel_inv" ,
    				'brkdown' => "TOTAL_TELECOM" ,
    				'unit'    => "million_euro"),
                array (  
                    'variable'=> "mob_subs" ,
    				'brkdown' => "TOTAL_MOB" ,
    				'unit'    => "nbr_subs"),
                array (  
                    'variable'=> "FP7ICT_EC_funding" ,
    				'brkdown' => "All_partners" ,
    				'unit'    => "euro"),
                array (  
                    'variable'=> "FP7ICT_TOTcost" ,
    				'brkdown' => "All_partners" ,
    				'unit'    => "euro"),
                array (  
                    'variable'=> "FP7ICT_particip" ,
    				'brkdown' => "All_partners" ,
    				'unit'    => "nb_of_participations"),
                array (  
                    'variable'=> "FP7ICT_newENTRY" ,
    				'brkdown' => "All_partners" ,
    				'unit'    => "nb_of_participations"),
                array (  
                    'variable'=> "FP7ICT_newENTRY" ,
    				'brkdown' => "All_partners" ,
    				'unit'    => "nb_of_organisations"),

                );

        //Group exclusion for a group if all indicators page is requested
        if (!empty($_GET['print'])) {
            $falseList[] = array('igroup' => 'Take up of internet services (% of population)');
        }

        $indicatorsResult = $this->selectIndicators(1, 0, true, 'indicatorgroup','',$excludeIndicatorsList);
        $indicators = $indicatorsResult['indicators'];
        $export['indicators'] = $indicators;
        $indicatorsSelector = $indicatorsResult['indicatorsSelector'];
        $metaData = $this->getMetaDataforIndicators($indicators);
        $export['metadata'] = $metaData['data'];

        if (empty($_GET['print'])) {
            $yearResult = $this->selectYear($indicators, 'merge');
        } else {
            $yearResult = $this->selectYear($indicators,'merge');

        }
        $yearSelector = $yearResult['yearSelector'];
        $selectedYear = $yearResult['year'];
        if ($selectedYear == null) {

        }

        $export['parameters']['year'] = $selectedYear;
        $export['parameters']['indicatorgroup[]'] = $this->parameters['indicatorgroup'][0];
        $countriesResult = $this->selectCountries(1, true, false, array("EU27", "EA", "EU15", "EU16", "EU25"), false);

        $countries = $countriesResult['countries'];
        $selectedCountry = array_keys($countriesResult['countries']);
        $selectedCountry = $selectedCountry[0];
        $countrySelector = $countriesResult['countrySelector'];       
        $export['parameters']['countries[]'] = implode("&countries[]=", array_keys($countries));

        $falseList = '("'.implode('","', array("EU27", "EA", "EU15", "EU16", "EU25")).'")';
        
        //general variables
        $dataresult = array();
        $dataset = array();
        $i = 0;
        
        //chart variables
        $EUValues = array();
        $categories = array();
        $series = array('left'=>array(), 'right'=>array());
        
        //table variables
        $quartile = array();
        
        //csv export
        $countryLabel = $countries[current($this->parameters['countries'])];
        $export['name'] = "CountryProfile-for-".urlencode($countryLabel)."-about-".$this->parameters['indicators'][0]."-in-".$this->parameters['year'];
        $export['head']['1'] = "Country";
        $export['head']['2'] = "Year";
        $export['head']['3'] = "Indicator";
        $export['head']['4'] = "Value";
        $export['head']['5'] = "Under EU27 average";
        $export['head']['6'] = "Above EU27 average";
        $export['data'] = array();

        foreach($indicators as $index=>$iset) {
                $query = '	SELECT DISTINCT variable, brkdown, unit, country, value 
				FROM data 
				WHERE 	
                    variable = "'.$iset['variable'].'" AND 
					brkdown = "'.$iset['brkdown'].'" AND 
					unit = "'.$iset['unit'].'" AND 
					year = '.$selectedYear.' AND 
					NOT (country IN '.$falseList.') AND 
					note NOT LIKE "N.a" 
				ORDER BY variable, unit ASC';
                $dataresult[$i] = $this->db->query($query);
                $query = '	SELECT DISTINCT value 
				FROM data 
				WHERE 	
                    variable = "'.$iset['variable'].'" AND 
					brkdown = "'.$iset['brkdown'].'" AND 
					unit = "'.$iset['unit'].'" AND 
					year = '.$selectedYear.' AND 
					country = "EU27" AND 
					note != "N.a" 
				ORDER BY variable, unit ASC';
                $EUAverageTemp = $this->db->query($query);
                if ($EUAverageTemp) {
                    $EUAverageRow = $EUAverageTemp->fetch();
                   $EUValues[$iset['variable']][$iset['brkdown']][$iset['unit']]['avg'] = (double) str_replace(",",".",$EUAverageRow['value']);
                }
                $i++;
        }
        $countryDataAvailable = FALSE;
        foreach($dataresult as $key => $result) {
            foreach($result as $row) {
                $dataset[$row[0]][$row[1]][$row[2]][$row[3]] = $row[4];
                if ($row[3] == $selectedCountry) {
                    $countryDataAvailable = TRUE;
                }
            }
        }
        if ($countryDataAvailable) {
            $allIndicatorsLink = '<a href="index.php?print=all&scenario=4&year='.$export['parameters']['year'].'&countries[]='.$export['parameters']['countries[]'].'">All Indicators</a>';
        } else {
            $allIndicatorsLink = "";
        }

        $form = '
          <form action="index.php#chart">
            <input type="hidden" name="scenario" value="4" />
            <table>
            <tr><td>&nbsp;Select one group of indicators:&nbsp;</td><td>' .
            $indicatorsSelector . 
            '</td>
            <td width=20%> 
                '.$allIndicatorsLink.'
            </td></tr>
            <tr>
                <td>&nbsp;Select the Year:&nbsp;</td>
                <td colspan=2>' . $yearSelector . '</td>
            </tr>
            <tr><td>&nbsp;Select the Country:&nbsp;</td><td>' .
            $countrySelector .
            ' </td></tr></table></form>';

        if (!$countryDataAvailable) {
    		$returnValue['error'] = "No data available for that selection.";    
        } else {
        
        	$output = '<div ' . $this->graphclass . '></div>';
            $output.= '<h3>Country profile for '.$countries[current($this->parameters['countries'])].' in the year '.$this->parameters['year'].'</h3>';
            $output.= "<p>The following table presents the original values of all the indicators for the year and country you have selected. In order to show if it is a high or low value compared with the other countries, values are organized in four columns depending on the number of other countries presenting higher/lower scores. For example, if a country value is in the first column, it means that at least 3 quarters of the other countries show higher values; if it is in the second column, it means that half of the countries have highest values but a quarter lower ones, etc. For some indicators lower values can have a positive meaning, for example the percentage of individuals that have never used the internet.</p>";

            $output .= '<table style="border:1px solid grey; width:100%"><tr>
                    <th width=30%>The Indicator values belongs to</th>
                    <th width=12.5%>EU27 value</th>
                    <th width=12.5%>First quarter of countries with lowest values</th>
                    <th width=12.5%>Second quarter of countries with medium-low values</th>
                    <th width=12.5%>Third quarter of countries with medium high values</th>
                    <th width=12.5%>Fourth quarter of countries with highest values</th></tr>';

            $i=$y=0;
            $addedGroups = array();
            $groupElement = null;
            foreach($indicators as $index=>$iset) {
                $i++;

                if(!in_array($iset['igroup'], $addedGroups)) {
                    $addedGroups[] = $iset["igroup"];
                    $groupElement = $iset["igroup"];
                } else {
                    $groupElement = null;
                }

                $variable = $iset['variable'];
                $brkdown = $iset['brkdown'];
                $unit = $iset['unit'];
                $label = $iset['shortLabel'];
                $longLabel = $iset['longLabel'];
                $label = str_replace("'" , "\'" , $label);
                $export['data'][$i] = array(    1 => $countryLabel, 
                                                2 => $selectedYear, 
                                                3 => $label);

                $countryset = array();
                //if data for this indicator is available in this year for this indicator, then calculate the values

                if (!empty($dataset[$variable][$brkdown][$unit])) {
                    $countryset = $dataset[$variable][$brkdown][$unit];
                    //sort values and set them
	                asort($countryset);

                    $dataset[$variable][$brkdown][$unit] = $countryset;

                    //set country value and catch unavailable values
                    $value = (isset($countryset[current($this->parameters['countries'])]) ? $countryset[current($this->parameters['countries'])] : NULL);
                    #$dataset[$row[0]][$row[1]][$row[2]][$row[3]] = $value;

                    $export['data'][$i]['4'] = $value;
                    //graph values calculation
                    $EUValues[$variable][$brkdown][$unit]['min'] = min($countryset);
                    $EUValues[$variable][$brkdown][$unit]['max'] = max($countryset);
#                    $EUValues[$variable][$brkdown][$unit]['avg'] = array_sum($countryset) / count($countryset);
                    $avg = $EUValues[$variable][$brkdown][$unit]['avg'];
                    if($value != NULL) {
                        $categories[] = "".$label."";
                        if($avg > $value ) {
                            $underEU = ((($value - $avg) / ($avg - $EUValues[$variable][$brkdown][$unit]['min']))!='' ? (($value - $avg) / ($avg - $EUValues[$variable][$brkdown][$unit]['min'])) : 0);
                            $series['left'][] = $underEU ;
                            $series['right'][] = 0;

                            $export['data'][$i]['5'] = $underEU;
                            $export['data'][$i]['6'] = "";
                        } else {
                            $aboveEU = (($value - $avg) / ($EUValues[$variable][$brkdown][$unit]['max'] - $avg)!='' ? ($value - $avg) / ($EUValues[$variable][$brkdown][$unit]['max'] - $avg) : 0);
                            $series['left'][] = 0;
                            $series['right'][] = $aboveEU;
                            $export['data'][$i]['5'] = "";
                            $export['data'][$i]['6'] = $aboveEU;
                        }

		                //table values calculation
		                if($value != NULL) {
		                    $roundedValue = round(((strpos($unit, '%') === false) && (strpos($unit, '/') === false) ? $value : $value*100),1);
		                } else {
		                    $roundedValue = "N.a";
		                }
		                $n = count($countryset);
		                $set = false;
		                $temp = array_values($countryset);
		                $output .= '<tr><td>'.$longLabel.'</td><td><center>'.(round(((strpos($unit, '%') === false) && (strpos($unit, '/') === false) ? $avg : $avg*100),1)).'</center></td>';

		                if($value != NULL) {
		                    for($q = 1; $q<4; $q++) { //calculate quartile
		                        if ($q == 1 || $q == 3) {
		                            $style = 'style="background-color:#dfdfdf"';
		                        } else {
		                            $style = 'style="background-color:#efefef"';
		                        }
	                        
		                        if($n % 2 != 0) {
		                            $quartile[$variable][$brkdown][$unit][$q] = $temp[ceil($n * ($q * 0.25))];
		                        } else {
		                            $quartile[$variable][$brkdown][$unit][$q] = ($temp[($n * ($q * 0.25))] + $temp[($n * ($q * 0.25))+1]) / 2;
	                            }

	                            if($value < $quartile[$variable][$brkdown][$unit][$q] && !$set) {
		                            $output .= '<td '.$style.'><center><b>'.$roundedValue.'</b></center></td>';
        	                        $set = true;
        	                    } else {
                                    $output .= '<td '.$style.'></td>';
                                }
	                        }
		                    if($value >= $quartile[$variable][$brkdown][$unit][3] && !$set) {
	                            $output .= '<td style="background-color:#efefef"><center><b>'.$roundedValue.'</b></center></td>';
	                            $set = true;
		                    } else {
	                            $output .= '<td style="background-color:#efefef"></td>';
	                        }
		                } else {
	                        $output .= '<td style="background-color:#efefef;" colspan=4><center>'.$roundedValue.'<c/enter></td>';
	                    }
                    }
                    $output .= '</tr>';
                }
            }
            $output .= '<tr style="background-color:lightgrey"><td colspan=6 style="text-align:right"><a href="http://ec.europa.eu/digital-agenda/en/graphs">European Commision, Digital Agenda Scoreboard</a></td></tr>';
            $output .= '</table>';

            //create chart --> reverse array orders due to the chart logic
            $chartCategories = "['".implode("','", (array_reverse($categories)))."']";
            $chartSeries = "[{  name: 'Under EU27 average', 
                                color: '#7DC30F',
                                data: [".implode(',',array_reverse($series['left']))."]},
                             {  name: 'Above EU27 average', 
                                color: '#436B06',
                                data: [".implode(',',array_reverse($series['right']))."]}
                            ]";

            $indicatorLabel = $indicators[0]['igroup'];

            $returnValue['chart'] =  $returnValue['chart'] = "<script type=\"text/javascript\">
		
			    $(document).ready(function() {
				    chart = new Highcharts.Chart({
                        colors: ['#1C3FFD', '#FF5400', '#21FF00', '#6A07B0', '#FF1D23','#1B76FF', '#FD8245', '#19BC01', '#9A24ED', '#D40D12','#15A9FA', '#94090D', '#7DC30F', '#D59AFE','#0EEAFF', '#FFC600', '#648E23', '#D000C4','#ADF0F6', '#FFFC00', '#436B06', '#FF40F4','#35478C', '#7FB2F0', '#044C29', '#45BF55', '#F70A9B', '#D50356', '#9A033F'],
					    chart: {
						    renderTo: '" . $this->containerid ."',
						    defaultSeriesType: 'bar'
					    },
					    title: {
						    text: 'Country profile for ".$countries[current($this->parameters['countries'])].", ".$indicatorLabel."',
                            style: {
                                color: '#000000',
                                fontWeight: 'bold'
                            }

					    },
                        credits: {
                                href: 'http://ec.europa.eu/digital-agenda/en/graphs/',
                                text: 'European Commission, Digital Agenda Scoreboard',
                                position: {
                                        align: 'right',
                                        x: -10,
                                        verticalAlign: 'bottom',
                                        y: -2
                                }
                        },
					    subtitle: {
						    text: '".$this->parameters['year']."'
					    },
					    xAxis: [{
						    categories: ".$chartCategories.",
						    reversed: false
					    } , {
						    categories: ".$chartCategories.",
						    reversed: false,
                            opposite: true
                            }
                        ],
					    yAxis:[
                            {
        						title: {text: null},
        						reversed: false,
                                min: -1,
                                max: 1,
                                opposite:true
                            }, {
        						title: {text: null},
                                min: -1,
                                max: 1 ,
                                linkedTo: 0
                            }
                            
					    ],
					
					    plotOptions: {
						    bar: { pointWidth: 30 }, 
						    series: {
							    stacking: 'normal',
                                events: {
                                    legendItemClick: function(event) {
                                    return false;
                                     }
                                }
						    }
					    }, ";

            if ($this->parameters['mode'] == "printCharts") {
                $returnValue['chart'] .= "
                        legend: {
                            layout: 'vertical',
                            align: 'right',
			                floating: true,
                            verticalAlign: 'top',
                            x: -10,
                            borderWidth: 0
                        },";
            }
            $returnValue['chart'] .= "
                        tooltip: {
						    formatter: function(){
					            if (this.point.y < 0) {
							        return '<b>'+ this.point.category +'</b><br/>'+
							        	Math.round(this.point.y*100) + '% of the gap between EU27 and the MIN observed values';
						        } else {
							        return '<b>'+ this.point.category +'</b><br/>'+
							        	Math.round(this.point.y*100) + '% of the gap between EU27 and the MAX observed values';
						        }
						    }

					    }," .  $this->exporting . "
					
					    series: ".$chartSeries."
				    });
				
			    });</script>";
            
            $returnValue['chart'] .= '<div id="' . $this->containerid . '" style="width: 100%; height: '.max(((count($indicators, 1) / 5) * 30), 650).'px"></div>';

            if ( !empty($this->parameters['mode']) && $this->parameters['mode'] == "printCharts") {
                $returnValue['chart'] .= "";
            } else {
                $returnValue['chart'] .= $output;
            }
        }
        $returnValue['facets'] = $form;
        $returnValue['export'] = $export;
        $returnValue['metadata'] = $metaData;

        return $returnValue;
#        } // end no data check
    }

    //ToDo getContent auftrennen
    public function getFacets2() {
    }

    //ToDo getContent auftrennen
    public function getPrintCharts() {

	$this->printtable = "hidetable";
	$this->exporting  = " exporting: { enabled : false }, ";

	$allindicatorsgroup = array();
        $query = 'SELECT DISTINCT igroup FROM indicators WHERE NOT (igroup = "Take up of internet services (% of population)")';
	
        $queryResult = $this->db->query($query);
        $indicatorCount = 0;
        foreach ($queryResult as $row ) {
                $igroup = $row[0];
                $allindicatorsgroup[$indicatorCount] = $igroup;
                $indicatorCount++;
        }

	$allcontents = array();
        $indicatorCount = 0;

	$this->graphclass='class="Page"';

	foreach ($allindicatorsgroup as $ind) {

		$indarray = array();
		$indarray[0] = $ind;
		$this->containerid = "container_" . $ind;
        $this->setConfigurationElement("indicatorgroup", $indarray);
		try {
            $hack = $this->getContent();
			$hack['chart'] = "<div class=\"Page\"></div><div><h3>The country profile for indicator group " . $ind . "</h3></div>" . $hack['chart'];
			if ($hack['error'] == "No data available for that selection.") {
				$hack['chart'] = "<div class=\"Page\"></div><div><h3>No data available for indicator group " . $ind . "</h3></div>";
			};
		} catch (Exception $e) {
			$hack['chart'] = "<div class=\"Page\"></div><div><h3>No data available for indicator group " . $ind . "</h3></div>";
		};
			
                $allcontents[$indicatorCount]= $hack['chart'];
                $indicatorCount++;
	}


	$this->printtable = "showtable";
	$this->graphclass="";
	$this->exporting  = "";

	return $allcontents;
	
    }

}

?>
