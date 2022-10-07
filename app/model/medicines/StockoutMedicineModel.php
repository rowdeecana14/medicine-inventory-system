<?php
namespace App\Model\Medicines;

use App\Helper\Helper;
use App\Model\BaseModel;

class StockoutMedicineModel extends BaseModel {
    private static $table = 'stockout_medicines';
    private static $order_by = [ "stockout_medicines.id", "asc"];
    private static $fillable = [];

    public $module = 16;
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