<?php
require_once ("Scenario.php");

class BarChart extends Scenario
{


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

                $indicatorsResult = $this->selectIndicators(1, 1);
                $indicators = $indicatorsResult['indicators'];

                $yearResult = $this->selectYear($indicators);
                $yearSelector = $yearResult['yearSelector'];
                $selectedYear = $yearResult['year'];

                $countriesResult = $this->selectCountries(-1, false, true, NULL,true);
                $countries = $countriesResult['countries'];

                $export['indicators'] = $indicators;
                $export['year'] = $selectedYear;
                $export['countries'] = $countries;

            break;
        }


        return $export;
    }

    //ToDo getContent auftrennen
    public function getContent() {
        $returnValue = array();
        $export = array();
        $indicatorsResult = $this->selectIndicators(1, 1);
        $indicatorsSelector = $indicatorsResult['indicatorsSelector'];
        $indicators = $indicatorsResult['indicators'];
        $export['indicators'] = $indicators;
        $yearResult = $this->selectYear($indicators);
        $yearSelector = $yearResult['yearSelector'];
        $selectedYear = $yearResult['year'];

        $metaData = $this->getMetaDataforIndicators($indicators);
        $export['metadata'] = $metaData['data'];
        if(empty($this->parameters['countries'])){
            $countriesResult = $this->selectCountries(1, true, true, NULL, true, true, true);
        } else {
            $countriesResult = $this->selectCountries(-1, false, true, NULL,false, false, true);
        }

        $countries = $countriesResult['countries'];
        $countrySelector = $countriesResult['countrySelector'];

        $form = '
          <form action="index.php#chart">
            <input type="hidden" name="scenario" value="1" />
            <table>
              <tr><td>&nbsp;Select one Indicator:&nbsp;</td><td>' .
            $indicatorsSelector . 
            '</td></tr>
            <tr><td>&nbsp;Select the Year:&nbsp;</td><td>' .
            $yearSelector .
            "</td></tr>".
            "<tr><td style=\"vertical-align:top\">&nbsp;Select the Country:&nbsp;</td><td>".
            "<a href=\"javascript:toggle('countrySelectionContainer')\"  > toggle country selector</a>".
            "<div id=\"countrySelectionContainer\" style=\"display:none\">"
             . $countrySelector .
             '<input type=submit value="update country selection" style="background-color:#fefefe; border:1px soldid #ababab; padding-left:2em;padding-right:2em;float:right"/>'.
          ' </div></td></tr></table></form>';

        $countrystr = '("'.implode('","', array_keys($countries)).'")';

        $indicator = current($indicators);
        $variable = $indicator['variable'];
        $brkdown  = $indicator['brkdown'];
        $unit = $indicator['unit'];
        $label = $indicator['longLabel'];
        $label = str_replace("'" , "\'" , $label);

        $export['parameters']['indicators[]'] = $indicator['variable'] ." ". $indicator['brkdown'] ." ". $indicator['unit'];
        $export['parameters']['year'] = $selectedYear;

        $query = 'SELECT DISTINCT country, value, note, flags
		  FROM data 
 		  WHERE variable = "'.$variable.'" AND 
			brkdown  = "'.$brkdown.'"  AND 
			unit = "'.$unit.'" AND 
			year = '.$selectedYear.' AND 
			value IS NOT NULL AND 
			country IN '.$countrystr.' 
		  ORDER BY value DESC';
        $dataresult = $this->db->query($query);

        if ($dataresult->rowCount() != 0) {
            //set of needed variables for processing the data
            $series = "{name: '".$label."', color: '#7FB2F0', data: [";
            $euseries = "{name: '".$label."', color: '#35478C', data: [";
            $percentage = ((strpos($unit, '%') === false) && (strpos($unit, '/') === false) ? false : true);
            $eu27data = "";
            $finaldata = "";
            $categories = "";
            
            //csv export
            $export['name'] = "Barchart-".urlencode($label)."-in-".$this->parameters['year'];
            $export['head'] = array("Country",$label,"Year");
            $export['data'] = "";

            //catch up all datasets
            foreach ($dataresult as $row) {
                //leave out n.a.-statements
                $roundedValue = "";
                if (is_numeric(str_replace(",",".",$row[1]))) {
                    $row[1] = (double) str_replace(",",".",$row[1]);
                    $roundedValue = ($percentage ? $row[1]*100 : $row[1]);
                } else {
                    $row[1] = NULL;
                    $roundedValue = "";
                }
		   // an empty value row with a note.
		$emptyvaluerow = ($row[1] == NULL && $row[2] !="");
                if($row[2] != "N.a" && $row[3] == "" && !$emptyvaluerow) {
                    if ($row[0] == "EU27") {

                        $euseries .= $roundedValue.",";
                        $series .= "0,";
                    } else {
                        $series .= $roundedValue.",";
                        $euseries .= "0,";
                    }

                    $indexhelper[$row[0]] = $countries[$row[0]];
                    //csv export data
                    $export['data'][] = array( $countries[$row[0]],
                                           $row[1],
                                           $this->parameters['year']);
                    
                } else {
                    $export['data'][] = array( $countries[$row[0]],
                                           "N.a.",
                                           $this->parameters['year']);
		}
            }
            
            $finaldata = substr($series, 0, strlen($finaldata)-1)."]}";
            $eu27data = substr($euseries, 0, strlen($eu27data)-1)."]}";
            $finaldata = "[".$finaldata .",".$eu27data ."]";
            
            //labels for xAxis: show full country names only for 11 countries and less due to missing space (note: save only for 9 countries and less)
            $categories = implode("','", (count(array_keys($indexhelper))>=40 ? array_keys($indexhelper) : array_values($indexhelper)));
            $returnValue['chart'] = "<script type=\"text/javascript\">
                            var chart;
                            var printchart;
                            var printOptions;
                            var chartOptions = {
                                colors: ['#1C3FFD', '#FF5400', '#21FF00', '#6A07B0', '#FF1D23','#1B76FF', '#FD8245', '#19BC01', '#9A24ED', '#D40D12','#15A9FA', '#94090D', '#7DC30F', '#D59AFE','#0EEAFF', '#FFC600', '#648E23', '#D000C4','#ADF0F6', '#FFFC00', '#436B06', '#FF40F4','#35478C', '#7FB2F0', '#044C29', '#45BF55', '#F70A9B', '#D50356', '#9A033F'],
                                chart: {
                                    renderTo: 'container',
                                    defaultSeriesType: 'column',
                                    marginBottom: 150
                                },
                                credits: {
                                    href: 'http://scoreboard.lod2.eu/',
                                    text: 'European Commission, Digital Agenda Scoreboard',
                                    position: {
                                        align: 'right',
                                        x: -10,
                                        verticalAlign: 'bottom',
                                        y: -2
                                    }
                                },
                                title: {
                                    text: '".substr($label,0,strpos($label," ", 50))."<br>".substr($label,strpos($label," ", 50))."',
                                    style: {
                                        color: '#000000',
                                        fontWeight: 'bold', 
                                        fontSize:'1.2em'
                                    }
                                },
                                subtitle: {
                                    text: 'Year ".$selectedYear."',
                                    align: 'left'

                                },
                                xAxis: {
                                    categories: ['".$categories."'],
                                    labels: {
                                        rotation: -45,
                                        align: 'right',
                                        style: {
                                            color: '#000000'
                                        }
                                     }
                                },
                                yAxis: {
                                    min: 0,
                                    title: {
                                            text: '".$unit."',
                                            style: {
                                                color: '#000000',
                                                fontWeight: 'bold'
                                            }
                                    }
                                },
                                legend: { 
                                    enabled:false
                                },
                                tooltip: {
                                    formatter: function() {
                                            return '<b>'+
                                                    this.x +'</b><br>".$this->parameters['year'].": '+ Math.round(this.y*10)/10 + ' ".$unit."';
                                    }
                                },
                                plotOptions: {
                                    column: {
                                        stacking: 'normal'
                                    }
                                },
                                series: ".$finaldata."
                            };

                        $(document).ready(function() {
                            chart = new Highcharts.Chart(chartOptions);
            			});
                    </script>";
            $returnValue['chart'] .= '<div id="container" style="width: 100%; height: 650px"></div>';
            $returnValue['facets'] = $form;
            $returnValue['export'] = $export;
            $returnValue['metadata'] = $metaData;
        } else {
            $returnValue['error'] = "No Data Found for that selection";
        }
        return $returnValue;
    }
}
/*
        						exporting: {
                                    buttons: {
                                        printButton: {
                                            onclick: function() {
                                                this.style.display='none';
                                                chart.print();
                                                //var printoptions = myclone1(chart.options);
                                                //var printnav = myclone1(printoptions.navigation);
                                                //var printButton2 = myclone1(printnav.buttonOptions);
                                                //printButton2.enabled = false;
                                                //printnav.buttonOptions = printButton2;
                                                //printoptions.navigation = printnav;
					                            //printoptions.exporting.buttons.printButton.onclick = null;
					                            //printoptions.plotOptions.column.animation = false;
                                                //printoptions.chart.renderTo= 'printcontainer';
                                                //printchart = new Highcharts.Chart(printoptions);
                                                //printchart.print();
                                            }
                                        }
                                    }
                                },
                                navigation: {
                                    buttonOptions: {
                                        enabled: true
                                    }
                                }

*/
?>
