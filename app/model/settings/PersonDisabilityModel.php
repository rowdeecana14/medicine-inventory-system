<?php
namespace App\Model\Settings;
use App\Model\BaseModel;

class PersonDisabilityModel extends BaseModel {
    private static $table = 'person_disabilities';
    private static $order_by = [ "name", "asc"];
    private static $fillable = [];
   
    public $module = 5;
    public $action_add = 1;
    public $action_update = 2;
    public $action_delete = 3;
    public $action_read = 4;

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