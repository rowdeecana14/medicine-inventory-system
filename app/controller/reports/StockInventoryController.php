<?php

namespace App\Controller\Reports;
use App\Model\Medicines\MedicineModel;
use App\Model\Settings\StockLevelModel;
use App\Controller\BaseController;
use App\Helper\Helper;
use ModuleModel;

class StockInventoryController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new StockLevelModel;
        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 1]];
        $low_level = $model->select(['quantity'], [],  $wheres);
        $low_level = isset($low_level['quantity']) ? $low_level['quantity'] : 0;

        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 2]];
        $moderate_level = $model->select(['quantity'], [],  $wheres);
        $moderate_level = isset($moderate_level['quantity']) ? $moderate_level['quantity'] : 0;

        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 3]];
        $high_level = $model->select(['quantity'], [],  $wheres);
        $high_level = isset($high_level['quantity']) ? $high_level['quantity'] : 0;

        $model = new MedicineModel;
        $show_fields = [ 
            'image', 'name', 'description', 'status', 'category_id', 'type_id', 
        ];
        $show_fields = Helper::appendTable('medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            'medicines.id as medicine_id', 'categories.name as category', 'types.name as type',
            "(SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=medicines.id ) as stockin",
            "(SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=medicines.id ) as stockout",
            "(SELECT SUM(ss.quantity) FROM stock_expiries AS ss WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 AND ss.medicine_id=medicines.id AND ss.deleted_at IS NULL) as expired"
        ]);
        $join_tables = [
            [ "LEFT", "categories", "medicines.category_id", "categories.id"],
            [ "LEFT", "types", "medicines.type_id", "types.id"],
        ];
        $where_fields = [];
        $order_fields = [[ 'table' => 'medicines', 'key' => 'name', 'value' => 'asc']];

        $medicines = $model->selectsAdvanced($show_fields, $join_tables, $where_fields, $order_fields);
        $result = [];

        foreach($medicines as $index => $medicine) {
            $level = "";
            $available = ((int) $medicine['stockin'] - (int)  $medicine['stockout']) - (int) $medicine['expired'];

            if($available <= $low_level ) {
                $level = 'LOW';
            }
            else if($available > $low_level && $available <= $moderate_level) {
                $level = 'MODERATE';
            }
            else if($available <= $high_level  && $available > $moderate_level) {
                $level = 'HIGH';
            }
            else if($available > $high_level) {
                $level = 'EXCELENT';
            }

            array_push($result, [
                'index' => $index + 1,
                'name' => $medicine['name'],
                'description' => $medicine['description'],
                'category_id' => $medicine['category'],
                'type_id' => $medicine['type'],
                'stockin' => (int)  $medicine['stockin'],
                'stockout' => (int) $medicine['stockout'],
                'expired' => (int) $medicine['expired'],
                'available' => $available,
                'level' => $level
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }

    public function filter($request) {
        $model = new StockLevelModel;
        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 1]];
        $low_level = $model->select(['quantity'], [],  $wheres);
        $low_level = isset($low_level['quantity']) ? $low_level['quantity'] : 0;

        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 2]];
        $moderate_level = $model->select(['quantity'], [],  $wheres);
        $moderate_level = isset($moderate_level['quantity']) ? $moderate_level['quantity'] : 0;

        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 3]];
        $high_level = $model->select(['quantity'], [],  $wheres);
        $high_level = isset($high_level['quantity']) ? $high_level['quantity'] : 0;

        $model = new MedicineModel;
        $medicines = $model->filterStockLevel($request, [
            'low_level' => $low_level,
            'moderate_level' => $moderate_level,
            'high_level' => $high_level,
        ]);
        $result = [];


        foreach($medicines as $index => $medicine) {
            $level = "";

            if($medicine['available'] <= $low_level ) {
                $level = 'LOW';
            }
            else if($medicine['available'] > $low_level && $medicine['available'] <= $moderate_level) {
                $level = 'MODERATE';
            }
            else if($medicine['available'] <= $high_level  && $medicine['available'] > $moderate_level) {
                $level = 'HIGH';
            }
            else if($medicine['available'] > $high_level) {
                $level = 'EXCELENT';
            }
            
            array_push($result, [
                'index' => $index + 1,
                'name' => $medicine['name'],
                'description' => $medicine['description'],
                'category_id' => $medicine['category'],
                'type_id' => $medicine['type'],
                'stockin' => (int)  $medicine['stockin'],
                'stockout' => (int) $medicine['stockout'],
                'expired' => (int) $medicine['expired'],
                'available' =>(int)  $medicine['available'],
                'level' => $level
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }
}
?>