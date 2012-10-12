<?php
require_once ("Scenario.php");

class FullDataCubeExporter extends Scenario
{
    //ToDo getContent auftrennen
    public function getExport($exportFormat = "rdf") {
        $export = array();

        $indicatorsResult = $this->selectIndicators(0, "All", false, 'indicators');
        $indicators = $indicatorsResult['indicators'];
        $export['indicators'] = $indicators;
        $metaData = $this->getMetaDataforIndicators($indicators);
        $export['metadata'] = $metaData['data'];
        $export['data'] = array();
        $dataresult = array();
        $falseList = '("'.implode('","', array("EA", "EU15", "EU16", "EU25")).'")';

        $query = '  SELECT DISTINCT variable, brkdown, unit, caption as country, data.year, value 
                    FROM data 
                        JOIN countries on data.country=countries.code 
                    WHERE NOT (country IN '.$falseList.') AND 
                                    note NOT LIKE "N.a" 
                    ORDER BY variable, unit ASC';

        $result = $this->db->query($query);
        $i = 0;
        foreach ($result as $key=>$entry) {
            $export['data'][$i]['variable'] = $entry['variable'];
            $export['data'][$i]['brkdown'] = $entry['brkdown'];
            $export['data'][$i]['unit'] = $entry['unit'];
            $export['data'][$i]['country'] = $entry['country'];
            $export['data'][$i]['year'] = $entry['year'];
            $export['data'][$i]['value'] = str_replace(",",".",$entry['value']);
            $i ++;
        }

        return $export;

    }
}
