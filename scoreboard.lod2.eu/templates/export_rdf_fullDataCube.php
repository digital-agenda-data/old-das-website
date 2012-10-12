<?php


foreach ($content['data'] as $key => $item) {

    if(isset($item['value'])) {
        $itemUri = $itemNS . 
            urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $item['variable']))."/".
            urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $item['brkdown']))."/".
            urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $item['unit']))."/".
            urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $item['country']))."/".
            $item['year'];

        $property = $propertiesNamed[$item['variable'].$item['brkdown'].$item['unit']]['localName'];
        $observationLabel = $propertiesNamed[$item['variable'].$item['brkdown'].$item['unit']]['label'] . " for " .
                            $item['country'] . " in " .
                            $item['year'];

        $years["" . $item['year']] =  $yearNS.$item['year'];
        $countries["" . $item['country']] = $countryNS.urlencode($item['country']);


        print '<'.$itemUri.'> '. "\n\t\t" . 
                    'a qb:Observation ;'."\n\t\t" .
                    'rdfs:label "' . $observationLabel . '" ;'. "\n\t\t".
                    $propertyPrefix.'value "'.$item['value'].'"^^xsd:float ;'."\n\t\t".
                    $propertyPrefix.'indicator '. $indicatorPrefix.$property. ' ;'."\n\t\t".
                    $propertyPrefix.$year. ' <' .$yearNS.$item['year'].'> ;'."\n\t\t".
                    $propertyPrefix.$country. ' <' .$countryNS.urlencode($item['country']).'> ;'."\n\t\t" .
                    'prop:unit "'.$item['unit'].'" .'."\n\t\t";
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
