<?php
namespace App\Model\Settings;
use App\Model\BaseModel;

class PurokModel extends BaseModel {
    private static $table = 'puroks';
    private static $order_by = [ "puroks.name", "asc"];
    private static $fillable = [];

    public $module = 7;
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