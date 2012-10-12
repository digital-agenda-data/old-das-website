<?php
//Written by Tom-Michael Hesse, 2011, for LOD2-DGINFSO project

#var_dump($content);

if(isset($content['name']) && isset($content['head']) && isset($content['data'])) {

    $filename = "da_scoreboard_export_".str_replace(".","",microtime(TRUE)).".csv";
    header('Content-Type: application/octet-stream');
    header('Content-disposition: attachment; filename="'.$filename.'"');
    print ("Data Items" . "\n\n" );  
    print ('"'.implode('";"',$content['head']) . '"'."\n" );
    foreach ($content['data'] as $elements) {
        print ('"'. implode('";"',$elements) . '"'."\n");
    } 

    print ("\n\n"."Indicator - Information" . "\n\n" );  
    $metaHeader = false;

    foreach ($content['indicators'] as $indicatorKey => $indicator) {
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
        if($metaHeader == false) {
            print ('"'.implode('";"',(array_keys($metaData))) . '"'."\n" );
            $metaHeader = true;
        }
        print ('"'.implode('";"',$metaData) . '"'."\n" );
    }

    print ("\n\n"."Version - Information" . "\n\n" );  
    print ("Extraction-Date:;". '"'. strftime("%B,%d,%Y", time()) .'"'. "\n");

    $link = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    print ("Link to the Document:;". '"'.$link.'"');                    

}
?>
