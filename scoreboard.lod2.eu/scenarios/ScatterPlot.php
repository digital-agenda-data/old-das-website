<?php
require_once ("Scenario.php");

class ScatterPlot extends Scenario
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
            break;
        }
        return $export;
    }

    //ToDo getContent auftrennen
    public function getContent() {
        $returnValue = array();
        $export = array();

        $indicatorsResult = $this->selectIndicators(1, 1, false, 'indicators','indicators2');
        $indicators = $indicatorsResult['indicators'];
        $indicatorsSelector = $indicatorsResult['indicatorsSelector'];

        $indicatorsResult2 = $this->selectIndicators(1, 1, false, 'indicators2', 'indicators');
        $indicators = array_merge($indicators, $indicatorsResult2['indicators']);

        $export['indicators'] = $indicators;
        $indicatorsSelector2 = $indicatorsResult2['indicatorsSelector'];
        $metaData = $this->getMetaDataforIndicators($indicators);
        $export['metadata'] = $metaData['data'];
        $yearResult = $this->selectYear($indicators);
        $yearSelector = $yearResult['yearSelector'];
        $selectedYear = $yearResult['year'];
        $export['parameters']['year'] = $selectedYear;

        $countriesResult = $this->selectCountries(20, true, true, array("EA", "EU15", "EU16", "EU25"), true);
        $countries = $countriesResult['countries'];
        $countrySelector = $countriesResult['countrySelector'];
        
        $form = '
          <form action="index.php#chart">
            <input type="hidden" name="scenario" value="3" />
            <table style="">
              <tr><td>&nbsp;Select one Indicator (X-Axis):&nbsp;</td><td>' .
            $indicatorsSelector . 
            '</td></tr>
              <tr><td>&nbsp;Select one Indicator (Y-Axis):&nbsp;</td><td>' .
            $indicatorsSelector2 . 
            '</td></tr>
            <tr><td>&nbsp;Select the Year:&nbsp;</td><td>' .
            $yearSelector .
          ' </td></tr></table></form>';

        $countrycodes = '("'.implode('","', array_keys($countries)).'")';
        $dataresult = array();
        $i=0;
        
        //csv export
        $export['name'] = "Scatterplot-";
        $export['head'][] = "Country";
        $export['head'][] = "Year";
        $export['data'] = "";
        $exportseries = array();

        $indicatorLabels = array();
        //maximum queries 2 due to indicator selection process
        
        foreach($indicators as $indicator) {

            $variable = $indicator['variable'];
            $brkdown  = $indicator['brkdown'];
            $unit = $indicator['unit'];
            $export['parameters'][(isset($export['parameters']['indicators[]']) ? 'indicators2[]' : 'indicators[]')] = $indicator['variable'] ." ". $indicator['brkdown'] ." ". $indicator['unit'];
            $export['name'] .= $variable ."-";


            $indicatorLabels[$variable.$brkdown.$unit] = $indicator['longLabel'];

            $query = 'SELECT DISTINCT variable, brkdown, unit, country, value 
		      FROM data 
		      WHERE variable = "'.$variable.'" AND 
			    brkdown  = "'.$brkdown.'" AND 
			    unit = "'.$unit.'" AND 
			    country IN '.$countrycodes.' AND 
			    year = '.$selectedYear.' 
		      ORDER BY variable, unit ASC';
            $dataresult[$i] = $this->db->query($query);
            $i++;
        }
        $export['name'] .= "in-".$this->parameters['year'];
        $dataset = array();
        $points = array();
        $labels = array();
        $delinquents = array();
        $valuableCountries = array();
        $varCount=0;
        foreach($dataresult as $result) {
            $varCount++;
            foreach($result as $row) {
                $valuableCountries[$varCount][] = $row[3];
                if (is_numeric(str_replace(",",".",$row[4]))) {
                    $row[4] = (double) str_replace(",",".",$row[4]);
                } else {
                    $row[4] = '';
                    $roundedValue = '';
                    $delinquents[] = $row[3];

                }
                $dataset[$row[0]][$row[1]][$row[2]][$row[3]] = $row[4];
            }
        }
        //cleaning the array;
        $delinquents = array_merge($delinquents, array_diff($valuableCountries[1], $valuableCountries[2]));
        foreach ($delinquents as $delinquent) {
            foreach ($dataset as $var => $entrySet) {
                foreach ($entrySet as $breakdown => $units) {
                    foreach ($units as $unit => $countryset) {
                        unset($dataset[$var][$breakdown][$unit][$delinquent]);
                    }
                }
            }
        }
        $indicatorSets = 0;
        foreach($dataset as $variable => $brkdownset) {
            foreach ($brkdownset as $unitset) {
                foreach ($unitset as $unit) {
                    $indicatorSets ++;
                }
            }
        }
        if ($indicatorSets != 2) {
            $returnValue['error'] = "No Data available for that selection.";
        }

        foreach($dataset as $variable=>$varset) {
            foreach($varset as $brkdown=>$bset) {
                foreach($bset as $unit=>$countryset) {
                    //sort values and set them
                    asort($countryset);
                    $dataset[$variable][$brkdown][$unit] = $countryset;
                    $label = $indicatorLabels[$variable.$brkdown.$unit];
                    $label = str_replace("'" , "\'" , $label);
                    $labels[(!isset($labels['x']) ? 'x' : 'y')] = $label;
                    $labels[(!isset($labels['xind']) ? 'xind' : 'yind')] = $label;
                    $labels[(!isset($labels['xunit']) ? 'xunit' : 'yunit')] = $unit;

                    foreach($countryset as $country=>$value) {
                        $roundedValue = round(((strpos($unit, '%') === false) && (strpos($unit, '/') === false) ? $value : $value*100),1);
                        if(!isset($points[$country])) {
                            $points[$country] = "x:".$roundedValue;
                            //csv export
                            $export['data'][$country][] = $countries[$country];
                            $export['data'][$country][] = $selectedYear;  
                            $export['data'][$country][] = $value;
                        }
                        else {
                            $points[$country] .= ",y:".$roundedValue;
                            //csv export
                           $export['data'][$country][] = $value;
                        }
                    }
                    //csv export
                    $export['head'][] = $indicatorLabels[$variable.$brkdown.$unit];
                }
            }
        }

        $series ="[";
        foreach($points as $country=>$point) {
            if(count(explode(",",$point)) == 2 ) {
                $markerMode = "radius: 5, symbol: 'circle'";
                $labelPosition = "x:16,y:4";
                if ($country == "EU27") {
                    $markerMode = "radius: 10, symbol: 'diamond'";
                    $labelPosition = "x:25,y:4";
                }
                $series .= "{name: '".$countries[$country]." (".$country.")"."', 
                             color: countrycolor('".$country."'),
                             data: [{name: '".$country."', ".$point."}],
                             marker: {".$markerMode.", states: { hover: {enabled: true, lineColor: 'rgb(100,100,100)'}}},
                             dataLabels: { enabled: true,".$labelPosition.", formatter: function() {return this.point.name;}}},";
            }
        }

        $series = substr($series, 0, strlen($series)-1)."]";
        $returnValue['chart'] = "
        <script type=\"text/javascript\">

                        var chart;
                        $(document).ready(function() {
                                chart = new Highcharts.Chart({
                                        colors: ['#1C3FFD', '#FF5400', '#21FF00', '#6A07B0', '#FF1D23','#1B76FF', '#FD8245', '#19BC01', '#9A24ED', '#D40D12','#15A9FA', '#94090D', '#7DC30F', '#D59AFE','#0EEAFF', '#FFC600', '#648E23', '#D000C4','#ADF0F6', '#FFFC00', '#436B06', '#FF40F4','#35478C', '#7FB2F0', '#044C29', '#45BF55', '#F70A9B', '#D50356', '#9A033F'],
                                        chart: {
                                                renderTo: 'container', 
                                                defaultSeriesType: 'scatter',
                                                zoomType: 'xy',
                                                marginRight: 25,
                                                marginBottom: 150,
                                                marginTop: 100

                                        },
                                        credits: {
                                                href: 'http://ec.europa.eu/digital-agenda/en/graphs',
                                                text: 'European Commission, Digital Agenda Scoreboard',
                                                position: {
                                                        align: 'right',
                                                        x: -10,
                                                        verticalAlign: 'bottom',
                                                        y: -2
                                                }
                                        },
                                        title: {
                                                text: '<b>".$labels['xind']."</b><br/> and <b>".$labels['yind']."</b>',
                                                style: {
                                                    color: '#000000',
                                                    fontWeight: 'bold', 
                                                    fontSize:'1.2em'
                                                }

                                        },
                                        xAxis: [{
                                                title: {
                                                        enabled: true,
                                                        text: '".$labels['x']."',
                                                        style: {
                                                            color: '#000000',
                                                            fontWeight: 'bold'
                                                        }
                                                },
                                                startOnTick: true,
                                                endOnTick: true,
                                                showLastLabel: true,
                                                labels: {
                                                    style: {
                                                        color: '#000000'
                                                    }
                                                 }

                                        },{
                                            opposite:true,
                    						title: {
                                                text: 'Year ".$selectedYear."',
                                                style: {
                                                    color: '#000000',
                                                    fontWeight: 'bold'
                                                }
                                            }

                                        }],
                                        yAxis: {
                                                title: {
                                                        text: '".$labels['y']."',
                                                        style: {
                                                            color: '#000000',
                                                            fontWeight: 'bold'
                                                        }
                                                },
                                                labels: {
                                                    style: {
                                                        color: '#000000'
                                                    }
                                                 }

                                        },
                                        tooltip: {
                                                formatter: function() {
                                                return '<b>'+ this.series.name +'</b><br/>x: '+
                                                                this.x +' ".$labels['xunit'].",&lt;br /&gt;y: '+ this.y +' ".$labels['yunit']."';
                                                }
                                        },
                                        legend: {
                                                layout: 'horizontal',
                                                align: 'center',
                                                verticalAlign: 'bottom',
                                                x: 0,
                                                y: -20,
                                                borderWidth: 0
                                        },
                                        plotOptions: {
                                                scatter: {
                                                        states: {
                                                                hover: {
                                                                        marker: {
                                                                                enabled: false
                                                                        }
                                                                }
                                                        }
                                                }
                                        },
                                        series: ".$series."
                                });


                        });

                </script>";

        $returnValue['chart'] .= '<div id="container" style="width: 100%; height: 650px"></div>';
        $returnValue['facets'] = $form;
        $returnValue['export'] = $export;
        $returnValue['metadata'] = $metaData;
        return $returnValue;
    }
}

?>
