<?php

//--------------------------------------------------------------------
//Caching via eAccelerator
//--------------------------------------------------------------------
#echo $_SERVER['PHP_SELF'].'?GET='.serialize($_POST);
#eaccelerator_cache_page($_SERVER['PHP_SELF'].'?GET='.serialize($_GET), 14400);
//--------------------------------------------------------------------



    $scenario = 0;
    if(!empty( $_GET['scenario'])) {
        $scenario = $_GET['scenario'];
    }

    if ( $scenario > 0 && $scenario < 6 ) {
        require_once "Scoreboard.php";
        $scoreboard = new Scoreboard();
        $content = array();
	$printcontent = array();

        if(!empty( $_GET['export'])) {
            $outputFormat = $_GET['export'];
            $content = $scoreboard->getContent($scenario, $outputFormat);
            switch ($outputFormat) {
                case "csv":
                    require_once "templates/export_csv.php";    
                    break;
                case "rdf":
                    require_once "templates/export_rdf.php";
                    switch ($scenario) {
                        case 1:
                            #var_dump($content);
                            require_once "templates/export_rdf_scenario1.php";
                        break;
                        case 2:
                            #var_dump($content);
                            require_once "templates/export_rdf_scenario2.php";
                        break;
                        case 3:
                            require_once "templates/export_rdf_scenario3.php";
                        break;
                        case 4:
                            require_once "templates/export_rdf_scenario4.php";
                        break;
                        case 5:
                            require_once "templates/export_rdf_fullDataCube.php";
                        break;
                    }

                break;
            }
        } else if ( !empty ( $_GET['print'])) {
            require_once "templates/header.php";
            $printcontent = $scoreboard->getPrintCharts($scenario);
            require_once "templates/print.php";
        } else if ( $scenario > 0 && $scenario < 5 ){
            require_once "templates/header.php";
            $content = $scoreboard->getContent($scenario);
	    #var_dump($content);
            require_once "templates/scenario.php";
            require_once "templates/footer.php";
        }
    } else if (!empty( $_GET['page'])){
        $page = $_GET['page'];
        if ($page=="indicators") {
            require_once "Scoreboard.php";
            $scoreboard = new Scoreboard();
            $content = array();
            require_once "templates/header.php";
            $content = $scoreboard->getIndicators();

            require_once "templates/indicators.php";
            require_once "templates/footer.php";
        } else if ($page=="export") {
            require_once "templates/header.php";
            require_once "templates/export.php";
            require_once "templates/footer.php";

        } else {

        require_once "templates/header.php";
        require_once "templates/startpage.php";
        require_once "templates/footer.php";
        }
    } else {
        require_once "templates/header.php";
        require_once "templates/startpage.php";
        require_once "templates/footer.php";
    }

    
?>



      






