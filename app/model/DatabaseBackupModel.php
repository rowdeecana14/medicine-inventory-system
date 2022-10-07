<?php
namespace App\Model;
use App\Model\BaseModel;
use mysqli;
use Exception;
use ZipArchive;

class DatabaseBackupModel extends BaseModel {
    private static $table = 'database_backups';
    private static $order_by =  [ "database_backups.id", "desc"];
    private static $fillable = [];

    public $module = 19;
    public $action_add = 1;
    public $action_update = 2;
    public $action_delete = 3;
    public $action_read = 4;
    public $action_download = 5;

    public function getFillable() {
       return self::$fillable;
   }

    public function getTable() {
        return self::$table;
    }

    public function getOrderBy() {
        return self::$order_by;
    }

    public function backup($file_name, $path) {
        try {
            $hostname = $this->getHostName();
            $username = $this->getUserName();
            $password = $this->getPassword();
            $database = $this->getDatabaseName();

            $conn = new mysqli($hostname, $username, $password, $database);

            if($conn->connect_error){
                die("Connection Failed: ". $conn->connect_error());
            }
            $conn->set_charset("utf8");
            $sqlScript = "";
            $sqlScript  = "# ".app_code()." : MySQL DATABASE BACKUP\n";
            $sqlScript .= "#\n";
            $sqlScript .= "# GENEREATED: " . date( 'l j. F Y' ) . "\n";
            $sqlScript .= "# HOSTNAME: " . $hostname. "\n";
            $sqlScript .= "# DATABASE: " . $database . "\n";
            $sqlScript .= "# --------------------------------------------------------\n";
            $sqlScript .= "-- --------------------------------------------------\n";
			$sqlScript .= "-- ---------------------------------------------------\n";
			$sqlScript .= 'SET AUTOCOMMIT = 0 ;' ."\n" ;
			$sqlScript .= 'SET FOREIGN_KEY_CHECKS=0 ;' ."\n" ;

            // Get All Table Names From the Database
            $tables = array();
            $result = $conn->query('SHOW TABLES' );

            while ($row = $result->fetch_row()) {
                $tables[] = $row[0];
            }

            foreach ($tables as $table) {
                
                // Add SQL statement to drop existing table
                $sqlScript .= "#\n";
                $sqlScript .= "DROP TABLE IF EXISTS `" . $table. "`;\n";

                /* Table Structure */

                // Comment in SQL-file
                $sqlScript .= "# Table STRUCTURE OF TABLE `" . $table . "`\n";

                // Prepare SQLscript for creating table structure
                $query = "SHOW CREATE TABLE $table";
                $result = $conn->query($query);
                $row = $result->fetch_row();
                
                $sqlScript .= "\n\n" . $row[1] . ";\n\n";
                
                
                $query = "SELECT * FROM $table";
                $result = $conn->query($query);
                
                $columnCount = mysqli_num_fields($result);
                
                // Prepare SQLscript for dumping data for each table
                for ($i = 0; $i < $columnCount; $i ++) {

                    while ($row = $result->fetch_row()) {
                        $sqlScript .= "INSERT INTO $table VALUES(";
                        for ($j = 0; $j < $columnCount; $j ++) {
                            $row[$j] = $row[$j];
                            
                            if (isset($row[$j])) {
                                $sqlScript .= '"' . $row[$j] . '"';
                            } else {
                                $sqlScript .= '""';
                            }
                            if ($j < ($columnCount - 1)) {
                                $sqlScript .= ',';
                            }
                        }
                        $sqlScript .= ");\n";
                    }
                }
                
                $sqlScript .= "\n\n"; 
            }

            $sqlScript .= 'SET FOREIGN_KEY_CHECKS = 1 ; '  . "\n" ; 
			$sqlScript .= 'COMMIT ; '  . "\n" ;
			$sqlScript .= 'SET AUTOCOMMIT = 1 ; ' . "\n"  ; 

			$zip = new ZipArchive() ;
			$resOpen = $zip->open($path . '/' .$file_name.".zip" , ZIPARCHIVE::CREATE) ;

			if( $resOpen ){
				$zip->addFromString( $file_name , "$sqlScript" ) ;
			}
			$zip->close() ;

            // $fileHandler = fopen($path, 'w');
            // fwrite($fileHandler, $sqlScript);
            // fclose($fileHandler); 
            
            return [
                "message" => "Successfully backup.",
                "data" => [],
                "success" => true
            ];
        }
        catch (Exception $ex) {
            return [
                "message" => "Successfully backup.",
                "error" => $ex,
                "success" => true
            ];
        }
    }

    public function getFileSize($file_name) {
        $abs_path = app_backup_root().$file_name.'.zip';
        $file_size = filesize($abs_path);
        
        switch (true) {
            case ($file_size/1024 < 1) :
                return intval($file_size ) ." Bytes" ;
                break;
            case ($file_size/1024 >= 1 && $file_size/(1024*1024) < 1)  :
                return intval($file_size/1024) ." KB" ;
                break;
            default:
            return intval($file_size/(1024*1024)) ." MB" ;
        }
    }
}

?>