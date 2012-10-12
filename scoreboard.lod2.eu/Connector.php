<?php
/*
 *	@author			Tom-Michael Hesse <tommichael.hesse@googlemail.com>
 *	@author			Michael Martin <martin@informatik.uni-leipzig.de>
 *	@copyright		University of Leipzig AKSW
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@since			05.05.2011
*/
class Connector {

    private $logfile;
    private $db;

	/**
	 *	Constructor of Class
     *  - Containing the Configuration to the Databese and the configuration for the logifles
     *  - Opens the DB-Connection, the LogFile and held them as class attibute 
	 */    
    function __construct() {

#        $host   = "localhost";
#        $dbname = "scoreboard";
#        $username = "infso";
#        $password = "xCcJMCrZGbFyV6mh";

        $host   = "localhost";
        $dbname = "dginfso";
        $username = "root";
        $password = "root";

        $logfile = "logs/scoreboard.log";
        $dsn = "mysql:dbname=".$dbname.";host=".$host;
       
        if(!empty ($dsn) && !empty ($username) && !empty ($password)) {
            $this->db=new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
        }
        
        if(!empty($logfile)) {
            $this->logfile = fopen($logfile, 'a');
        }
    }

	/**
	 *	Queries the Database with configured credentials and write execution time to configured log file
	 *	@access		public
	 *	@param		string	query		SQL Query
	 *	@return		array   $result     Result of the query
	 */    
    public function query($query = "") {
        if(!empty ($query) && isset($this->db)) {

            $start = microtime(true);            
            $result = $this->db->query($query);
            $executionTime = microtime(true) - $start;
            
            if($this->logfile) {
                $curTime = getdate();
                fwrite( $this->logfile, 
                    $curTime['year'].
                    $curTime['mon'].
                    $curTime['mday']." ".
                    $curTime['hours'].":".
                    $curTime['minutes'].":".
                    $curTime['seconds']." ET(s): ".
                    $executionTime . " Query: ".
                    $query.
                    "\r\n"
                );
            }
            return $result;
        }
    }
    
    function __destruct() {
        if($this->logfile) {
            fclose($this->logfile);
        }
    } 
}
?>
