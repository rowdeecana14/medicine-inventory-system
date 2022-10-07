<?php
namespace App\Model\Settings;
use App\Model\BaseModel;

class StockLevelModel extends BaseModel {
    private static $table = 'stock_levels';
    private static $order_by = [ "stock_levels.id", "asc"];
    private static $fillable = [
        'name', 'quantity', 'created_by', 'updated_by', 'deleted_by', 'updated_at', 'deleted_at'
   ];

    public $module = 20;
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