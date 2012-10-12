<?php
#if(isset($content['name']) && isset($content['head']) && isset($content['data'])) {
    
#    header('Content-Type: application/rdf-xml');
#    header('Content-disposition: attachment; filename='.$content['name'].'.csv');

#var_dump($content);

# print headers
print "
@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .
@prefix owl: <http://www.w3.org/2002/07/owl#> .
@prefix scovo: <http://purl.org/NET/scovo#> .
@prefix doap: <http://usefulinc.com/ns/doap#> .
@prefix dcterms: <http://purl.org/dc/terms/> .
@prefix sdmx-metadata: <http://purl.org/linked-data/sdmx/2009/metadata#> .
@prefix sdmx-dimension: <http://purl.org/linked-data/sdmx/2009/dimension#> .
@prefix sdmx: <http://purl.org/linked-data/sdmx#> .
@prefix skos: <http://www.w3.org/2004/02/skos/core#> .
@prefix dcterms: <http://purl.org/dc/terms/> .
@prefix qb: <http://purl.org/linked-data/cube#> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .
@prefix data: <http://data.lod2.eu/scoreboard/items/> .
@prefix prop: <http://data.lod2.eu/scoreboard/properties/> .
@prefix ind: <http://data.lod2.eu/scoreboard/indicators/> .
@prefix classes: <http://data.lod2.eu/scoreboard/classes/> .
@prefix country: <http://data.lod2.eu/scoreboard/country/> .
@prefix year: <http://data.lod2.eu/scoreboard/year/> .
";

$indicatorNS = "http://data.lod2.eu/scoreboard/indicators/"; 
$propertyNS = "http://data.lod2.eu/scoreboard/properties/";
$propertyPrefix = "prop:";
$indicatorPrefix = "ind:";

$countryPrefix = "country:";
$countryNS = "http://data.lod2.eu/scoreboard/country/";

$classesPrefix = "classes:";
$classesNS = "http://data.lod2.eu/scoreboard/classes/";


$yearPrefix = "year:";
$yearNS = "http://data.lod2.eu/scoreboard/year/";

$itemNS =  "http://data.lod2.eu/scoreboard/items/";

$brkdown = "brkdown";
print '
<'.$propertyNS.$brkdown.'> ' ."\n\t\t".
    'a qb:AttributeProperty ; '."\n\t\t".
    'rdfs:label "break down" .'."\n";

$variable = "variable";
print '
<'.$propertyNS.$variable.'> ' ."\n\t\t".
    'a qb:AttributeProperty ; '."\n\t\t".
    'rdfs:label "variable" .'."\n";


$unit = "unit";
print '
<'.$propertyNS.$unit.'> '."\n\t\t".
    'a qb:AttributeProperty ; '."\n\t\t".
    'rdfs:label "unit" .' ."\n";

$country = "country";
print '
<'.$propertyNS.$country.'> '."\n\t\t".
    'a qb:DimensionProperty ;' ."\n\t\t".
    'rdfs:label "country" .' ."\n" ;

$year = "year";
print '
<'.$propertyNS.$year.'> '."\n\t\t". 
    'a qb:DimensionProperty ;'."\n\t\t".
    'rdfs:label "year" .' ."\n";

print '
<'.$propertyNS.'indicator> '."\n\t\t". 
    'a qb:DimensionProperty ;'."\n\t\t".
    'rdfs:label "indicator" .' ."\n";

$value = "value";
print '
<'.$propertyNS.$value.'> '."\n\t\t". 
    'rdfs:range xsd:float'.' ;'."\n\t\t".
    'a qb:MeasureProperty ;'."\n\t\t".
    'rdfs:label "value" .' ."\n";

#var_dump($content['indicators']);

foreach ($content['indicators'] as $indicatorKey => $indicator) {
    $property=  urlencode(str_replace(array(" ", "%", "(" , ")"), array("","","",""), $indicator['variable']))
                ."_".
                urlencode(str_replace(array(" ", "%", "(" , ")"), array("","","",""), $indicator['brkdown']))
                ."_".
                urlencode(str_replace(array(" ", "%", "(" , ")","/"), array("","","","",""), $indicator['unit']));

    $metaData = array();
    foreach($content['metadata'][$indicator['igroup']] as $metaSet) {
        if  (   $metaSet['variable'] == $indicator['variable'] &&
                $metaSet['brkdown'] == $indicator['brkdown'] &&
                $metaSet['unit'] == $indicator['unit'] 
            ) {
            $metaData = $metaSet;
            break;
        }
    }

    print '
<'.$indicatorNS.$property.'> ' ."\n\t\t".
    'rdf:type <'.$classesNS.'Indicator> ;'."\n\t\t".
    'rdfs:label "'.$metaData['longLabel'].'" ;'."\n\t\t".
    'rdfs:comment "'.$metaData['notes'].'" ;'."\n\t\t".
    'prop:brkdown "'.$metaData['brkdown'].'" ;'."\n\t\t".
    'prop:unit "'.$metaData['unit'].'" ;'."\n\t\t".
    'prop:variable "'.$metaData['variable'].'" ;'."\n\t\t".
    'dcterms:source <'.$metaData['url'].'> ;'."\n\t\t".
    'dcterms:publisher "'.$metaData['sourceLabel'].'" .'."\n\n";
    $properties[] = $property;
    $propertiesNamed[$indicator['variable'].$indicator['brkdown'].$indicator['unit']]['localName'] = $property;
    $propertiesNamed[$indicator['variable'].$indicator['brkdown'].$indicator['unit']]['label'] = $metaData['shortLabel'];
}
?>
