<?php
$years = $content['head'];
unset($years[0]);
unset($years[1]);
foreach ($years as $key => $yearItem) {
    foreach ($content['data'] as $dataGroup) {
        $value = $dataGroup[$key];
        if ($value != "") {

            $itemUri = $itemNS . 
                urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $content['indicators'][0]['variable']))."/".
                urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $content['indicators'][0]['brkdown']))."/".
                urlencode(str_replace(array(" " , "%" , "(" , ")","/"), array("","","","",""), $content['indicators'][0]['unit']))."/".
                urlencode(str_replace(array(" " , "%" , "(" , ")"), array("","","",""), $dataGroup[1]))."/".
                $yearItem;

            $yearsRDF["" . $yearItem] =  $yearNS.$yearItem;
            $countries["" . $dataGroup[1]] = $countryNS.urlencode($dataGroup[1]);

            print '<'.$itemUri.'> '. "\n\t\t" . 
                        'a qb:Observation ;'."\n\t\t" . 
                        $propertyPrefix.'value "'.$value.'"^^xsd:float ;'."\n\t\t".
                        $propertyPrefix.'indicator '. $indicatorPrefix.$properties[0]. ' ;'."\n\t\t".
                        $propertyPrefix.$year. ' <' .$yearNS.$yearItem.'> ;'."\n\t\t".
                        $propertyPrefix.$country. ' <' .$countryNS.urlencode($dataGroup[1]).'> ;'."\n\t\t" .
                        'prop:unit "'.$content['indicators'][0]['unit'].'" .'."\n\t\t";

            print "\n\n";

        }
    }
}
foreach ($yearsRDF as $label => $uri) {
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
