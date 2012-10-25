<?php

class Scenario {

    var $parameters;
    var $db;
    function __construct ($dbConnection) {
        $this->parameters = array();
        $this->db = $dbConnection;
    }

    function __destruct () {

    }

    public function setConfigurationElement ($key = "" , $value = false) {
        $this->parameters[$key] = $value;
    }

    public function execquery($query) {
	return $this->db->query($query);
    }

	/**
	 *	SelectIndicator
     * @param 
     * @return
	 */   

    public function getMetadataForIndicators($indicators = array(), $withGroups = false) {

        $metadata = array("data" =>array(), "table" => "");
        $wheres = array(0 => "1");
        $i=0;
        foreach ($indicators as $indicator) {
            $wheres[$i++] = "variable = \"" .$indicator['variable']."\" AND unit = \"".$indicator['unit']."\" AND brkdown = \"".$indicator['brkdown']."\"";
#            $metadata["data"][$indicator['igroup']][$indicator['variable']]['brkdown'] = $indicator['brkdown'];
#            $metadata["data"][$indicator['igroup']][$indicator['variable']]['unit'] = $indicator['unit'];
#            $metadata["data"][$indicator['igroup']][$indicator['variable']]['shortLabel'] = $indicator['shortLabel'];
        }
        $where = implode(" OR ", $wheres);


        $query = '  SELECT DISTINCT brkdown, 
                                    unit, 
                                    shortLabel,
                                    longLabel,
                                    variable,
                                    igroup, 
                                    scopenotes,
                                    source_code,
                                    source_label,
                                    url,
                                    `order`
                    FROM indicators as i JOIN sources as s ON i.source=s.source_code 
                    WHERE '.$where.'
                    ORDER BY `order` ASC';
        $queryResult = $this->db->query($query);
        if ($queryResult->rowCount() > 0) {
            $i=0;
            foreach( $queryResult as $row ) {
                $metadata["data"][$row['igroup']][$i]['variable'] = $row['variable'];
                $metadata["data"][$row['igroup']][$i]['brkdown'] = $row['brkdown'];
                $metadata["data"][$row['igroup']][$i]['unit'] = $row['unit'];
                $metadata["data"][$row['igroup']][$i]['shortLabel'] = $row['shortLabel'];
                $metadata["data"][$row['igroup']][$i]['longLabel'] = $row['longLabel'];
                $metadata["data"][$row['igroup']][$i]['notes'] = $row['scopenotes'];
                $metadata["data"][$row['igroup']][$i]['code'] = $row['source_code'];
                $metadata["data"][$row['igroup']][$i]['sourceLabel'] = $row['source_label'];
                $metadata["data"][$row['igroup']][$i]['url'] = $row['url'];
                $i++;
            }
            $list = "";
            $table = '<table  style="border:1px solid grey; width:100%;padding:0em">';
            $table .= "<thead><tr>";
            $table .= '<th style="width:20%">Indicator short label</th>';
            $table .= '<th style="width:20%">Indicator long label</th>';
            $table .= '<th style="width:40%">Definition and scope</th>';
            $table .= '<th style="width:20%">Source</th>';
            $table .= "</tr></thead><tbody>";
            foreach ($metadata["data"] as $group => $variables) {
                if ($withGroups == true) {
                    $list .= "\n<h5 style=\"font-size:1.4em;\">Group ".$group."</h5>";
                    $table .= '<tr><th colspan=4 style="background-color:lightgrey;" id="'.md5($group).'">'.$group.'</th></tr>';                
                }
               foreach ($variables as $variable => $infos) {
                    $list .= "\n<p><b>".$infos['longLabel']."</b>";
                    $list .= "\n<ul>";
                    $list .= "\n<li><b>Definition and scope: </b>".$infos['notes']."</li>";
#                    $list .= "\n<li><b>Unit: </b>".$infos['unit']."</li>";
#                    $list .= "\n<li><b>Break Down: </b>".$infos['brkdown']."</li>";
                    $list .= "\n<li><b>Source: </b>".$infos['sourceLabel']." [<a href=\"".$infos['url']."\">More Information</a>]</li>";
                    $list .= "\n</ul>";
                    $list .="\n</p>";
                    $table .= "<tr>";
                    $table .= '<td style="background-color:#cfcfcf;padding:0.2em;">'.$infos['shortLabel'].'</td>';
                    $table .= '<td style="background-color:#efefef;padding:0.2em;">'.$infos['longLabel'].'</td>';
                    $table .= '<td style="background-color:#cfcfcf;padding:0.2em;">'.$infos['notes'].'</td>';
                    $table .= '<td style="background-color:#efefef;padding:0.2em;"><a href="'.$infos['url'].'">'.$infos['sourceLabel'].'</a></td>';
                    $table .= '</tr>';                
                }
    
            }
            $table .= '</tbody></table>';                
            $metadata['list'] = $list;
            $metadata['table'] = $table;
        }
        return $metadata;
    }

    protected function selectIndicators(    $preselect, 
                                            $maxselect, 
                                            $groupsOnly = false, 
                                            $formName = 'indicators', 
                                            $preventDoubleSelection = 'indicators2', 
                                            $falseList = array()) {

        $indicators = array();
        $indicatorsSelector = '';
        $indicatorGuiElements = array();

        $indicatorsAll = array();
        $indicatorsRandom = array();

#        $falseList[] = array (  'variable'=> "tel_rev" ,
#				'brkdown' => "TOTAL_TELECOM" ,
#				'unit'    => "million euro");
	    if (!empty($falseList)) {
		    foreach ($falseList as $entry) {
                if(isset($entry['variable']) && isset($entry['brkdown']) && isset($entry['unit']) ) {
			        $constraints[] ='(variable = "' . $entry['variable'] . '" AND
					          brkdown  = "' . $entry['brkdown'] . '" AND
					          unit     = "' . $entry['unit'] . '")';
                }

                if (isset($entry['igroup'])) {
                    $constraints[] = '(igroup = "'.$entry['igroup'].'")';
                }

		    }
		    $constraint = 'NOT (' . implode(" OR ", $constraints) . ')';

	    } else {
		    $constraint = "1" ;
	    }

        $query = 'SELECT DISTINCT igroup, variable, brkdown, unit, shortlabel, longLabel, `order` 
		  FROM indicators 
		  WHERE '.$constraint.'
		  ORDER BY `order` ASC';

#        foreach( $this->db->query($query) as $row ) {
#            $indicatorsAll[$row[0]][$row[1]][$row[2]] = $row[3];
#            $indicatorsRandom[$row[0].'%'.$row[1].'%'.$row[2]] = $row[3];
#        }
        $toBePreventedKey = "";
        if (isset($this->parameters[$preventDoubleSelection][0])) {
            $toBePreventedKey = $this->parameters[$preventDoubleSelection][0];
        }

        $indicatorCount = 1;
        $queryResult = $this->db->query($query);
        foreach($queryResult as $row ) {
            //prevent double selection
            if (($row[1].' '.$row[2].' '.$row[3]) != $toBePreventedKey) {
                $igroup = $row[0];
                $indicatorsAll[$igroup][$indicatorCount]['igroup']     = $row[0];
                $indicatorsAll[$igroup][$indicatorCount]['variable']   = $row[1];
                $indicatorsAll[$igroup][$indicatorCount]['brkdown']    = $row[2];
                $indicatorsAll[$igroup][$indicatorCount]['unit']       = $row[3];
                $indicatorsAll[$igroup][$indicatorCount]['shortLabel'] = $row[4];
                $indicatorsAll[$igroup][$indicatorCount]['longLabel']  = $row[5];
                $indicatorsRandom[$row[1].' '.$row[2].' '.$row[3]] = $row[4];
                $indicatorCount++;
            }
        }

        if(in_array('preselect', $this->parameters[$formName]) && $preselect > 0) {
            
            if(!$groupsOnly) {
                $target = array_rand($indicatorsRandom, $preselect);
                if( count($target) == 1 ) {
                    $target = array(0 => $target);
                    $this->parameters[$formName] = array();
                }
                $this->parameters[$formName] = array_merge($this->parameters[$formName], $target);
            }
            else {
                $groups = array_keys($indicatorsAll);
                $target = array_rand($groups);
                $this->parameters[$formName] = array(0 => $groups[$target]);
            }
        } else {
            foreach ($this->parameters[$formName] as $key => $value) {
                $this->parameters[$formName][$key] = urldecode($value);
            }

        }
        $select = 0;
        if($maxselect == 0) { 
            $maxselect =  $indicatorCount;
        }
        $selectedIndicators = array();
        $indicatorList = array();
#        $firstGroup = true; //relevance for groups only
        foreach($indicatorsAll as $indicatorGroup => $indicators) {
            $indicatorList = array_merge($indicatorList, $indicators);
            if(!$groupsOnly) { //single indicator selection
            
                $indicatorGuiElements[] = '<optgroup label="'.$indicatorGroup.'">';
                foreach ($indicators as $indicator) {
                    $check = false;
                    if( in_array($indicator['variable'].' '.$indicator['brkdown'].' '.$indicator['unit'], $this->parameters[$formName]) && $select < $maxselect) {
                        $check = true;
                        $select++;
                        $selectedIndicators[] = $indicator;
                    }
                    $guiElement = '
                        <option  
                        value="'.$indicator['variable'].' '.$indicator['brkdown'].' '.$indicator['unit'].'"'. 
                        ($check ? ' selected="selected"' : '').'>
                        &nbsp;'.$indicator['shortLabel'].'</option>';
                    $indicatorGuiElements[] = $guiElement;
                }
                $indicatorGuiElements[] = '</optgroup>';
            } else { //group selection of indicators
                $check = false;
                if(( in_array($indicatorGroup, $this->parameters[$formName])) && $select < $maxselect) {
                        $check = true;
                        $select++;
                    $selectedIndicators = array_merge($selectedIndicators, $indicators);
                }
                $indicatorGuiElements[] = '
                        <option  
                        value="'.urlencode($indicatorGroup).'"'.
                        ($check ? ' selected' : '').'>
                        &nbsp;'.$indicatorGroup.'</option>';          

                
            }
        }
        $indicatorsSelector = '<select onChange="this.form.submit()" name='.$formName.'[] size="1" style="width:auto;min-width:33em; background-color:#fff; border:1px solid #ababab;">';
        $indicatorsSelector .= implode("\n", $indicatorGuiElements);
        $indicatorsSelector .= '</select>';
	if (empty($selectedIndicators)) {
		$selectedIndicators = $indicatorList;
	}
        return array (  'indicators'            => $selectedIndicators, 
                        'indicatorsSelector'    => $indicatorsSelector);
    }

	/**
	 * SelectYear
     * @param 
     * @return
	 */  
    protected function selectYear($indicators = array(), $mode = 'intersect') {
        $years = array();
        $yearSelector = '';
  
        //catch up all years where data for all indicators is available
        if(!empty($indicators)) {
            foreach ($indicators as $indicator) {

                $variable = $indicator['variable'];
                $brkdown  = $indicator['brkdown'];
                $unit = $indicator['unit'];
                $label = $indicator['shortLabel'];
                $tempyears = array();
                $query = '  SELECT DISTINCT year FROM data 
                            WHERE   year >= 2003 AND 
                                    variable = "'.$variable.'" AND 
                                    brkdown = "'.$brkdown.'" AND 
                                    unit = "'.$unit.'" ORDER BY year DESC';

                foreach ($this->db->query($query) as $row) {
                    $tempyears[$row[0]] = $row[0];
                }
                if( count( $years ) == 0 ) {
                    $years = $tempyears;
                } else {
                    if ($mode == 'intersect') {
                        $years = array_intersect($years, $tempyears);
                    } else {
                        foreach ($tempyears as $key => $value) {
                            $years[$key] = $value;
                        }
                    }
                }

            }
        } else {    
            $query =  'SELECT DISTINCT year FROM data WHERE year >= 2003 ORDER BY year DESC';              
            foreach ($this->db->query($query) as $row) {
                $years[$row[0]] = $row[0];
            }
        }
        //remove the year set in the $this->parameters if it is not in the current list
        if(isset($this->parameters['year'])) {
            $year = $this->parameters['year'];
            if(!in_array($this->parameters['year'], $years)) {
                $year = null;
            }
        }

        rsort($years);
        if (!empty($years)) {
            foreach($years as $row) {
                if(!isset($year)) {
                    $year = $row;
                }
                $yearSelector .= '<input type="radio" name="year" value="'.$row.'" '.($year==$row ? 'checked="checked"':'').' onclick="this.form.submit()" />&nbsp;'.$row.'&nbsp;&nbsp; ';
            }
            return array("yearSelector" => $yearSelector, "year" => $year);
        } #else {
          #  throw new Exception("No Data Found for the selection"); 
        #}
    }

    protected function selectCountries($preselect, $noyear, $multiple, $falselist, $allSelectable = false, $addAllCountries = false, $withoutEventHandling = false) {
        $countriesall = array();
        $countries = array();
        $countryGuiElements = array();
        $countrySelector = "";

        $falselist[] = "EU15";
        $falselist[] = "EU25";
        $falselist[] = "EA";

        $notin = "";

        if(isset($falselist)) {
            $notin = 'NOT (code IN ("'.implode('","', $falselist).'"))';
        }

        //hack: $preselect-value is overwritten with change in form
        if(!isset($this->parameters['countries'])) {
            $this->parameters['countries'] = array('preselect'=>'preselect');
        }
        
        $where = ' 1 ';
        $whereClauses = array();

        if ($noyear == false && !empty($this->parameters['year'])) {
            $whereClauses[] = 'year = '.$this->parameters['year'];
        }

        if ($notin) {
            $whereClauses[] = $notin;
        }

        if (count($whereClauses) > 0) {
            $where = implode (' AND ', $whereClauses);
        }

        $query = '  SELECT DISTINCT code, caption 
                    FROM countries WHERE ' . $where . ' ORDER BY caption ASC';

        foreach ($this->db->query($query) as $row) {
            $countriesall[$row[0]] = $row[1];
        }

        //randomly select countries if preselect > 0 
        if(in_array('preselect', $this->parameters['countries']) && $preselect > 0) {
            $target = "EU27";
            if (!$multiple) {
                $target = array_rand($countriesall, $preselect);
            }
            if(count($target)==1) {
                $target = array(0=>$target);
                $this->parameters['countries'] = array();
            }
            $this->parameters['countries'] = array_merge($this->parameters['countries'], $target);
        }

        if ($allSelectable) {
            $countries = $countriesall;
        }

#        if ($addAllCountries) {
#            if(in_array("allCountries", $this->parameters['countries'])) {
#
#                $countryGuiElements[] = '<option value="allCountries" selected="selected" >&nbsp;Select All Countries</option>';
#                $countries = $countriesall;
#                $this->parameters['countries'] = $countriesall;
#            } 
#            else {
#                $countryGuiElements[] = '<option value="allCountries" >&nbsp;Select All Countries</option>';
#           }
#        }

        foreach($countriesall as $index=>$value) {
            $check = false;
            if(in_array($index, $this->parameters['countries'])) {
                $check = true;
                $countries[$index] = $value;
            }
            if(in_array('preselect',$this->parameters['countries'])) {
                if($preselect == -1) $check = true;
            }
            if($allSelectable) {
                $check = true;
            }

            #$countrySelector .= ' <input type="'.($multiple == true ? 'checkbox' : 'radio').'" name="countries[]" value="'.$index.'" '.($check == true ? 'checked="checked"' : '').' onchange="this.form.submit()" /> '.$value.' ('.$index.')&nbsp;&nbsp;  ';

            if (!$multiple) {
                $countryGuiElements[] = '
                 <option  
                 value="'.$index.'"'. 
                 ($check ? ' selected="selected"' : '').'>
                 &nbsp;'.$value.' ('.$index.')</option>';
            } else {
                $eventHandler = 'onclick="this.form.submit()"';
                if ($withoutEventHandling == true) {
                    $eventHandler = "";
                }                

                $countryGuiElements[] = '
                    <input type="checkbox" name="countries[]" '.$eventHandler.'
                        value="'.$index.'" '.
                        ($check ? ' checked="checked"' : '').' /> &nbsp;'.$value.' ('.$index.')<br />';
            }
        }
        $cElements = implode("\n", $countryGuiElements);
        if (!$multiple) {
            $countrySelector = '<select onchange="this.form.submit()" name=countries[] size="1" style="background-color:#fff; border:1px solid #ababab;">';
            $countrySelector .= $cElements;
            $countrySelector .= '</select>';
        } else {
            $countrySelector = '<div style="height:5.8em; overflow:auto;background-color:#fff; border:1px solid grey">';
            $countrySelector .= $cElements;
            $countrySelector .= '</div>';
        }
        return array('countries' => $countries, 'countrySelector' => $countrySelector);
    }
}

?>
