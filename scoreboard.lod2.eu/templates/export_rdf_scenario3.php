<?php
foreach ($content['data'] as $itemLine) {
    #indicator-variable , country, year

    $years["" . $itemLine[1]] =  $yearNS.$itemLine[1];
    $countries["" . $itemLine[0]] = $countryNS.urlencode($itemLine[0]);

    //firstObservation
    $itemUri = $itemNS . 
        urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $content['indicators'][0]['variable']))."/".
        urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $content['indicators'][0]['brkdown']))."/".
        urlencode(str_replace(array(" " , "%" , "(" , ")","/"), array("","","","",""), $content['indicators'][0]['unit']))."/".
        urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $itemLine[0]))."/".
        $itemLine[1];

    print '<'.$itemUri.'> '. "\n\t\t" . 
                'a qb:Observation ;'."\n\t\t" . 
                $propertyPrefix.'value "'.$itemLine[2].'"^^xsd:float ;'."\n\t\t".
                $propertyPrefix.'indicator '. $indicatorPrefix.$properties[0]. ' ;'."\n\t\t".
                $propertyPrefix.$year. ' <' .$yearNS.$itemLine[1].'> ;'."\n\t\t".
                $propertyPrefix.$country. ' <' .$countryNS.urlencode($itemLine[0]).'> ;'."\n\t\t" .
                'prop:unit "'.$content['indicators'][0]['unit'].'" .'."\n\t\t";

    print "\n\n";

    //SecondObservation
    $itemUri = $itemNS . 
        urlencode(str_replace(" ", "", $content['indicators'][1]['igroup']))."/".
        urlencode(str_replace(" ", "", $content['indicators'][1]['variable']))."/".
        urlencode(str_replace(" ", "", $itemLine[0]))."/".
        $itemLine[1];


    print '<'.$itemUri.'> '. "\n\t\t" . 
                'a qb:Observation ;'."\n\t\t" . 
                $propertyPrefix.'value "'.$itemLine[3].'"^^xsd:float ;'."\n\t\t".
                $propertyPrefix.'indicator '. $indicatorPrefix.$properties[1]. ' ;'."\n\t\t".
                $propertyPrefix.$year. ' <' .$yearNS.$itemLine[1].'> ;'."\n\t\t".
                $propertyPrefix.$country. ' ' .$countryPrefix.urlencode($itemLine[0]).' ;'."\n\t\t" .
                'prop:unit "'.$content['indicators'][1]['unit'].'" .'."\n\t\t";

    print "\n\n";
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

