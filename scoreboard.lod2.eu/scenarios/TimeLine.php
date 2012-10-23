<?php
require_once ("Scenario.php");

class TimeLine extends Scenario
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

        $indicatorsResult = $this->selectIndicators(1, 1);
        $indicatorsSelector = $indicatorsResult['indicatorsSelector'];
        $indicators = $indicatorsResult['indicators'];
        $export['indicators'] = $indicators;
        $metaData = $this->getMetaDataforIndicators($indicators);
        $export['metadata'] = $metaData['data'];
        $countriesResult = $this->selectCountries(1, true, true, NULL, false, true, true);
        $countries = $countriesResult['countries'];
        $countrySelector = $countriesResult['countrySelector'];

        $form = "
          <form action=\"index.php#chart\">
            <input type=\"hidden\" name=\"scenario\" value=\"2\" />
            <table style=\"\">
              <tr><td>&nbsp;Select one Indicator:&nbsp;</td><td>" .
            $indicatorsSelector . 
            "</td></tr>".
            "<tr><td style=\"vertical-align:top\">&nbsp;Select the Country:&nbsp;</td><td>".
            "<a href=\"javascript:toggle('countrySelectionContainer')\"  > toggle country selector</a>".
            "<div id=\"countrySelectionContainer\" style=\"display:none\">"
             . $countrySelector .
            '<input type=submit value="update country selection" style="background-color:#fefefe; border:1px soldid #ababab; padding-left:2em;padding-right:2em;float:right"/>'.
          " </td></tr></table></form>";
        
        $indicator = current($indicators);
        $variable = $indicator['variable'];
        $brkdown  = $indicator['brkdown'];
        $unit = $indicator['unit'];
        $label = $indicator['longLabel'];
        $label = str_replace("'" , "\'" , $label);

        $countrystr = '("'.implode('","', array_keys($countries)).'")';

        $export['parameters']['indicators[]'] = $indicator['variable'] ." ". $indicator['brkdown'] ." ". $indicator['unit'];
        $export['parameters']['countries[]'] = implode("&countries[]=", array_keys($countries));
        
        $dataset = array();
        $years = array(0 => "2002",1 => "2003", 2=>"2004",3=>"2005",4=>"2006",5=>"2007",6=>"2008",7=>"2009",8=>"2010", 9=>"2011");
        $series = array();
        
        //csv export
        $export['name'] = "TimeLineChart-".substr($label,0,20)."-".implode('-', array_keys($countries));
#        $export['head'] = $label."\r\nCountry;2004;2005;2006;2007;2008;2009;2010\r\n";
        $export['head'] = array("Indicator","Country","2002","2003","2004","2005","2006","2007","2008","2009","2010","2011");
        $export['data'] = "";
        $dataset = array();
        
        $percentage = ((strpos($unit, '%') === false)  && (strpos($unit, '/') === false) ? false : true); //find out whether values are in % --> cap of scale at 100 and multiply values with 100

        $query = 'SELECT DISTINCT year, country, value, note, flags 
		  FROM data 
		  WHERE variable = "'.$variable.'" AND 
		  	brkdown = "'.$brkdown.'" AND 
		  	unit = "'.$unit.'" AND 
		  	country IN '.$countrystr.' 
		  ORDER BY year ASC' ;
        $dataresult = $this->db->query($query);
        if ($dataresult->rowCount() == 0) {
            $returnValue['error'] = "No Data available for that selection.";
        }

        //create series for timeline and categories (years)
        //data style is now: [x, y]
        foreach($dataresult as $row) {
            //$years[$row[0]] = $row[0];
            if(!isset($series[$row[1]])){
                $series[$row[1]] = "{name: '".addslashes($countries[$row[1]])."', color: countrycolor('".$row[1]."'), data: [";
            }
            //leave out n.a.-statements
	    $emptyvaluerow = ($row[2] == NULL && $row[3] !="");
            if(!($row[3]=="N.a") && !($row[4]=="u") && !($row[4]=="c") && !$emptyvaluerow) {
                $row[2] = (double) str_replace(",",".",$row[2]);
                $roundedValue = ($percentage == true ? $row[2]*100 : $row[2]);
                $roundedValue = round($roundedValue,1);
                $series[$row[1]] = $series[$row[1]]."[".$row[0].", ".$roundedValue."]";
                //csv export
                $dataset[$row[1]][$row[0]] = $row[2];
            } else {
                //csv export
                $dataset[$row[1]][$row[0]] = "N.a";
            }
        }
        //csv export
        $i=0;
        foreach($dataset as $country=>$cset) {
            $i++;
            $export['data'][$i][] = $label;
            $export['data'][$i][] = $countries[$country];
            foreach($years as $index=>$year) {
                if(isset($cset[$year])) {
                    $export['data'][$i][] = $cset[$year];
                } else {
                    $export['data'][$i][] = "";
                }
            }
        }

        $finaldata = "[";
        foreach($series as $index=>$value) {
            $finaldata = $finaldata.str_replace("][","],[",$value)."]},";
        }

        $finaldata = substr($finaldata,0, strlen($finaldata)-1)."]";
        //$categories = "['".implode("','", $years)."']";
        $categories = "['2002','2003','2004','2005','2006','2007','2008','2009','2010','2011','2012']";

        $returnValue['chart'] = "<script type=\"text/javascript\">

                        var chart;
                        $(document).ready(function() {
                                chart = new Highcharts.Chart({
                                        colors: ['#1C3FFD', '#FF5400', '#21FF00', '#6A07B0', '#FF1D23','#1B76FF', '#FD8245', '#19BC01', '#9A24ED', '#D40D12','#15A9FA', '#94090D', '#7DC30F', '#D59AFE','#0EEAFF', '#FFC600', '#648E23', '#D000C4','#ADF0F6', '#FFFC00', '#436B06', '#FF40F4','#35478C', '#7FB2F0', '#044C29', '#45BF55', '#F70A9B', '#D50356', '#9A033F'],
                                        chart: {
                                                renderTo: 'container',
                                                type: 'spline',
                                                "./*defaultSeriesType: 'line',*/"
                                                marginRight: 50,
                                                marginBottom: 100
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
                                        title: {
                                                text: '".substr($label,0,strpos($label," ", 50))."<br>".substr($label,strpos($label," ", 50))."',
                                                x: -20,
                                                margin: 30,
                                                style: {
                                                    color: '#000000',
                                                    fontWeight: 'bold', 
                                                    fontSize:'1.2em'
                                                }

                                        },
                                        xAxis: {
                                                categories: ".$categories.",
                                                min: 2004,
                                                max: 2012,
                                                labels: {
                                                    style: {
                                                        color: '#000000'
                                                    }
                                                 }
                                        },
                                        yAxis: {
                                                title: {
                                                        text: '".$unit."',
                                                        style: {
                                                            color: '#000000',
                                                            fontWeight: 'bold'
                                                        }
                                                },
                                                labels: {
                                                    style: {
                                                        color: '#000000'
                                                    }
                                                },
                                                min: 0
                                        },
                                        tooltip: {
                                                formatter: function() {
                                                return '<b>'+ this.series.name +'</b><br/>'+
                                                                this.x +': '+ Math.round(this.y*10)/10 +' ".$unit."';
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
                                            series: {
                                                dataLabels: {
                                                    enabled: true,
                                                    formatter: function() {
                                                    var ex = this.series.xAxis.getExtremes();
                                                    if (this.x == ex.dataMax) {
                                                        this.series.options.dataLabels.y = -8;
                                                        this.series.options.dataLabels.x = 25;
                                                        return this.series.name;
                                                        } else {
                                                            return \"\";
                                                        }                                                        
                                                    }
                                                },
                                                marker: {
                                                    fillColor: null,
                                                    lineWidth: 4,
                                                    lineColor: null // inherit from series
                                                }
                                            }
                                        },
                                        series: ".$finaldata."
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
