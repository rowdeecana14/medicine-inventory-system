<?php
namespace App\Model;
use App\Model\BaseModel;

class LogModel extends BaseModel {
    private static $table = 'logs';
    private static $order_by = [];
    private static $fillable = [];

    public function logs() {
        $this->sql = "
            SELECT logs.requests, logs.ip, logs.datetime, logs.module_id, logs.action_id, logs.user_id, logs.id as log_id,
                modules.name as module, actions.name as action, occupations.name as position, logs.record_id,
                health_officials.first_name as h_first_name, health_officials.middle_name as h_middle_name,
                health_officials.last_name as h_last_name,
                health_officials.image as image 
            FROM logs 
            LEFT JOIN modules ON modules.id=logs.module_id 
            LEFT JOIN actions ON actions.id=logs.action_id 
            LEFT JOIN users ON users.id=logs.user_id 
            LEFT JOIN health_officials ON health_officials.id=users.health_official_id
            LEFT JOIN occupations ON occupations.id=health_officials.position_id
            WHERE logs.deleted_at IS NULL AND YEAR(logs.datetime)= YEAR(CURDATE())
            ORDER BY logs.datetime DESC
            ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement ->execute();
  
        return $this->statement ->fetchAll();
    }
   
    public function getFillable() {
       return self::$fillable;
   }

    public function getTable() {
        return self::$table;
    }

    public function getOrderBy() {
        return self::$order_by;
    }
}

?>