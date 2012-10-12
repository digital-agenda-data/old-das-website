<?php

foreach ($content['data'] as $dKey => $dataItem) {
#var_dump($dataItem);
#var_dump($propertiesNamed);

    if(isset($dataItem[4])) 
    {

        $indicatorUri = "";
        foreach ($propertiesNamed as $key => $value) {
            if ($value['label'] == $dataItem['3']) {
                $indicatorUri = $indicatorPrefix.$value['localName'];
            }
        }


        $itemUri = $itemNS . 
            urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $indicator['variable']))."/".
            urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $indicator['brkdown']))."/".
            urlencode(str_replace(array(" " , "%" , "(" , ")","/"), array("","","","",""), $indicator['unit']))."/".
            urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $dataItem[1]))."/".
            $dataItem[2];

        $years["" . $dataItem[2]] =  $yearNS.$dataItem[2];
        $countries["" . $dataItem[1]] = $countryNS.urlencode($dataItem[1]);



        print '<'.$itemUri.'> '. "\n\t\t" . 
                    'a qb:Observation ;'."\n\t\t" . 
                    $propertyPrefix.'value "'.$dataItem[4].'"^^xsd:float ;'."\n\t\t".
                    $propertyPrefix.'indicator '. $indicatorUri . ' ;'."\n\t\t".
                    $propertyPrefix.$year. ' <' .$yearNS.$dataItem[2].'> ;'."\n\t\t".
                    $propertyPrefix.$country. ' <' .$countryNS.urlencode($dataItem[1]).'> ;'."\n\t\t" .
                    'prop:unit "'.$indicator['unit'].'" .'."\n\t\t";

        print "\n\n";
    }    
}


foreach ($years as $label => $uri) {
    print '<'.$uri.'>'. "\n\t\t" . 
                'rdf:type <'.$classesNS.'Year> ;'."\n\t\t".
                'rdfs:label "'.$label.'" .'."\n\r\n";
}

foreach ($countries as $label => $uri) {
    if($uri != "http://data.lod2.eu/scoreboard/country/European+Union+-+27+countries") {
        $type = 'http://ns.aksw.org/spatialHierarchy/Country';
        $relation = "http://ns.aksw.org/spatialHierarchy/isLocatedIn" ;
        $target = "http://data.lod2.eu/scoreboard/country/European+Union+-+27+countries" ;
        $statement = "<".$relation."> <".$target."> ; \n\t\t";
    } else {
        $type = 'http://ns.aksw.org/spatialHierarchy/SpatialArea';
        $statement = "";
    }

    print '<'.$uri.'>'. "\n\t\t" . 
                'a <'.$type.'> ;'."\n\t\t" . 
                $statement .
                'rdfs:label "'.$label.'" .'."\n\r\n";
}

?>
