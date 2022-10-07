<?php
namespace App\Model\Settings;
use App\Model\BaseModel;

class ChecklistModel extends BaseModel {
    private static $table = 'checklists';
    private static $order_by = [ "description", "asc"];
    private static $fillable = [];

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